<?php

namespace App\Services;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Lot;
use App\Models\Client;
use App\Models\Ledger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseService
{
    /**
     * Create a purchase and associated items/lots.
     */
    public function createPurchase(array $data)
    {
        return DB::transaction(function () use ($data) {
            $totalAmount = 0;
            $itemsToCreate = [];

            // 1. Calculate totals and prepare items
            foreach ($data['items'] as $item) {
                $bundles = $item['bags'] * 5; // 1 Bag = 5 Bundles
                $subtotal = $bundles * $item['unit_price_per_bundle'];
                $totalAmount += $subtotal;

                $kgQty = isset($item['kg_quantity']) && $item['kg_quantity'] > 0
                    ? (float) $item['kg_quantity']
                    : null;

                $itemsToCreate[] = [
                    'product_id'            => $item['product_id'],
                    'bags'                  => $item['bags'],
                    'bundles'               => $bundles,
                    'unit_price_per_bundle' => $item['unit_price_per_bundle'],
                    'subtotal'              => $subtotal,
                    'kg_quantity'           => $kgQty,
                ];
            }

            // 2. Create Purchase Header
            $purchase = Purchase::create([
                'client_id' => $data['client_id'],
                'purchase_date' => $data['purchase_date'],
                'invoice_no' => $data['invoice_no'],
                'total_amount' => $totalAmount,
                'paid_amount' => $data['paid_amount'],
                'notes' => $data['notes'] ?? null,
            ]);

            // 3. Create Items and Lots
            foreach ($itemsToCreate as $itemData) {
                $purchaseItem = $purchase->items()->create($itemData);

                // Create Lot for this item
                Lot::create([
                    'lot_number'            => 'LOT-' . now()->format('Hi-Ymd'),
                    'purchase_item_id'      => $purchaseItem->id,
                    'product_id'            => $itemData['product_id'],
                    'initial_bags'          => $itemData['bags'],
                    'remaining_bags'        => $itemData['bags'],
                    'kg_quantity'           => $itemData['kg_quantity'],
                    'cost_price_per_bundle' => $itemData['unit_price_per_bundle'],
                ]);
            }

            // 4. Update Client Balance (Subtract total, add paid)
            $client = Client::find($data['client_id']);
            $netEffect = $data['paid_amount'] - $totalAmount;
            $client->increment('current_balance', $netEffect);

            // Create Ledger Entry
            Ledger::create([
                'client_id' => $client->id,
                'date' => $data['purchase_date'],
                'description' => "Purchase Invoice #{$data['invoice_no']}",
                'transaction_type' => 'purchase',
                'reference_type' => 'Purchase',
                'reference_id' => $purchase->id,
                'debit' => $data['paid_amount'], // Decreases Payable
                'credit' => $totalAmount, // Increases Payable
                'balance' => $client->current_balance,
            ]);

            return $purchase;
        });
    }

    /**
     * Delete a purchase and reverse its effects.
     */
    public function deletePurchase(Purchase $purchase)
    {
        return DB::transaction(function () use ($purchase) {
            // Reverse Client Balance
            $client = $purchase->client;
            $netEffect = $purchase->paid_amount - $purchase->total_amount;
            $client->decrement('current_balance', $netEffect);

            Ledger::where('reference_type', 'Purchase')->where('reference_id', $purchase->id)->delete();

            $purchase->delete();

            return true;
        });
    }
}
