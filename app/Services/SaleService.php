<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Lot;
use App\Models\Client;
use App\Models\Ledger;
use Illuminate\Support\Facades\DB;

class SaleService
{
    public function createSale(array $data)
    {
        return DB::transaction(function () use ($data) {
            $totalAmount = 0;
            $totalProfit = 0;
            $itemsToCreate = [];

            foreach ($data['items'] as $itemData) {
                $lot = Lot::findOrFail($itemData['lot_id']);
                
                if ($lot->remaining_bags < $itemData['bags']) {
                    throw new \Exception("Insufficient stock in Lot: {$lot->lot_number}");
                }

                $bundles = $itemData['bags'] * 5;
                $subtotal = $bundles * $itemData['unit_price_per_bundle'];
                
                // Profit = (Sale Price - Purchase Price) * Bundles
                $profit = ($itemData['unit_price_per_bundle'] - $lot->cost_price_per_bundle) * $bundles;
                
                $totalAmount += $subtotal;
                $totalProfit += $profit;

                $itemsToCreate[] = [
                    'lot_id' => $lot->id,
                    'product_id' => $lot->product_id,
                    'bags' => $itemData['bags'],
                    'bundles' => $bundles,
                    'unit_price_per_bundle' => $itemData['unit_price_per_bundle'],
                    'subtotal' => $subtotal,
                    'profit' => $profit,
                ];

                // Deduct from Lot
                $lot->decrement('remaining_bags', $itemData['bags']);
                if ($lot->remaining_bags <= 0) {
                    $lot->update(['is_exhausted' => true]);
                }
            }

            $netTotal = $totalAmount - $data['discount'];

            // Create Sale
            $sale = Sale::create([
                'client_id' => $data['client_id'],
                'sale_date' => $data['sale_date'],
                'invoice_no' => $data['invoice_no'],
                'total_amount' => $netTotal,
                'paid_amount' => $data['paid_amount'],
                'discount' => $data['discount'],
                'total_profit' => $totalProfit,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($itemsToCreate as $item) {
                $sale->items()->create($item);
            }

            // Update Client Balance
            $client = Client::find($data['client_id']);
            // If they pay, balance goes down (they owe less). If they buy on credit, balance goes up (they owe more).
            // Logic: current_balance = current_balance + total_amount - paid_amount
            $netEffect = $netTotal - $data['paid_amount'];
            $client->increment('current_balance', $netEffect);

            // Create Ledger Entry
            Ledger::create([
                'client_id' => $client->id,
                'date' => $data['sale_date'],
                'description' => "Sale Invoice #{$data['invoice_no']}",
                'transaction_type' => 'sale',
                'reference_type' => 'Sale',
                'reference_id' => $sale->id,
                'debit' => $netTotal,
                'credit' => $data['paid_amount'],
                'balance' => $client->current_balance,
            ]);

            return $sale;
        });
    }

    public function deleteSale(Sale $sale)
    {
        return DB::transaction(function () use ($sale) {
            // Restore Lots
            foreach ($sale->items as $item) {
                $lot = $item->lot;
                $lot->increment('remaining_bags', $item->bags);
                if ($lot->remaining_bags > 0) {
                    $lot->update(['is_exhausted' => false]);
                }
            }

            // Reverse Client Balance
            $client = $sale->client;
            $netEffect = $sale->total_amount - $sale->paid_amount;
            $client->decrement('current_balance', $netEffect);

            // Ledger entries will be handled or deleted
            Ledger::where('reference_type', 'Sale')->where('reference_id', $sale->id)->delete();

            $sale->delete();
        });
    }
}
