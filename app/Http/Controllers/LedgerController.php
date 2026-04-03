<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Account;
use App\Models\Ledger;
use App\Models\LedgerEntry;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LedgerController extends Controller
{
    // ─── Customer Ledger ──────────────────────────────────────────────────────

    /**
     * List all customers with their outstanding balance.
     */
    public function customerIndex()
    {
        $customers = Client::customers()->active()->orderBy('name')->get();
        return view('ledger.customer_index', compact('customers'));
    }

    /**
     * Show the full ledger for one customer, with date-range filter.
     */
    public function customerShow(Request $request, Client $client)
    {
        abort_unless($client->isCustomer(), 404);

        $from = $request->input('from');
        $to   = $request->input('to');

        // Pull entries from legacy ledgers table (sales posted by SaleService)
        $legacyQuery = Ledger::where('client_id', $client->id);

        // Pull payment entries from the new ledger_entries table
        // Include both 'customer' entries AND 'supplier' entries if client type is 'both'
        $newQuery = LedgerEntry::where(function($q) use ($client) {
            $q->where(function($sq) {
                $sq->where('account_type', 'customer');
            });
            if ($client->type === 'both') {
                $q->orWhere(function($sq) {
                    $sq->where('account_type', 'supplier');
                });
            }
        })->where('account_id', $client->id);

        if ($from) {
            $legacyQuery->where('date', '>=', $from);
            $newQuery->where('date', '>=', $from);
        }
        if ($to) {
            $legacyQuery->where('date', '<=', $to);
            $newQuery->where('date', '<=', $to);
        }

        // Merge both sources into a unified collection sorted by date
        $legacyEntries = $legacyQuery->get()->map(fn($row) => [
            'date'        => $row->date,
            'description' => $row->description,
            'debit'       => $row->debit,
            'credit'      => $row->credit,
            'source'      => 'legacy',
        ]);

        $newEntries = $newQuery->orderBy('date')->get()->map(fn($row) => [
            'date'        => $row->date,
            'description' => $row->description,
            'debit'       => $row->entry_type === 'debit'  ? $row->amount : 0,
            'credit'      => $row->entry_type === 'credit' ? $row->amount : 0,
            'source'      => 'ledger_entry',
        ]);

        $entries = $legacyEntries->toBase()->merge($newEntries)
                                 ->sortBy('date')
                                 ->values();

        // Calculate running balance
        $runningBalance  = $client->opening_balance;
        $openingBalance  = $client->opening_balance;
        $rows = [];

        foreach ($entries as $entry) {
            $runningBalance += ($entry['debit'] - $entry['credit']);
            $rows[] = array_merge($entry, ['balance' => $runningBalance]);
        }

        $closingBalance = $runningBalance;

        return view('ledger.customer_show', compact(
            'client', 'rows', 'openingBalance', 'closingBalance', 'from', 'to'
        ));
    }

    /**
     * Export the customer ledger as a PDF.
     */
    public function customerPdf(Request $request, Client $client)
    {
        abort_unless($client->isCustomer(), 404);

        // Re-use the same data building logic
        $data = $this->buildCustomerLedgerData($client, $request->input('from'), $request->input('to'));

        $pdf = Pdf::loadView('ledger.customer_pdf', $data)->setPaper('a4');

        return $pdf->download("ledger-{$client->name}-" . now()->format('Ymd') . ".pdf");
    }

    // ─── Supplier Ledger ──────────────────────────────────────────────────────

    /**
     * List all suppliers with their outstanding balance.
     */
    public function supplierIndex()
    {
        $suppliers = Client::suppliers()->active()->orderBy('name')->get();
        return view('ledger.supplier_index', compact('suppliers'));
    }

    /**
     * Show the full ledger for one supplier, with date-range filter.
     */
    public function supplierShow(Request $request, Client $client)
    {
        abort_unless($client->isSupplier(), 404);

        $from = $request->input('from');
        $to   = $request->input('to');

        // Legacy ledger entries (purchases posted by PurchaseService)
        $legacyQuery = Ledger::where('client_id', $client->id);

        // Payment entries from the double-entry table
        // Include both 'supplier' entries AND 'customer' entries if client type is 'both'
        $newQuery = LedgerEntry::where(function($q) use ($client) {
            $q->where(function($sq) {
                $sq->where('account_type', 'supplier');
            });
            if ($client->type === 'both') {
                $q->orWhere(function($sq) {
                    $sq->where('account_type', 'customer');
                });
            }
        })->where('account_id', $client->id);

        if ($from) {
            $legacyQuery->where('date', '>=', $from);
            $newQuery->where('date', '>=', $from);
        }
        if ($to) {
            $legacyQuery->where('date', '<=', $to);
            $newQuery->where('date', '<=', $to);
        }

        $legacyEntries = $legacyQuery->get()->map(fn($row) => [
            'date'        => $row->date,
            'description' => $row->description,
            'debit'       => $row->debit,
            'credit'      => $row->credit,
            'source'      => 'legacy',
        ]);

        $newEntries = $newQuery->orderBy('date')->get()->map(fn($row) => [
            'date'        => $row->date,
            'description' => $row->description,
            'debit'       => $row->entry_type === 'debit'  ? $row->amount : 0,
            'credit'      => $row->entry_type === 'credit' ? $row->amount : 0,
            'source'      => 'ledger_entry',
        ]);

        $entries = $legacyEntries->toBase()->merge($newEntries)
                                 ->sortBy('date')
                                 ->values();

        $openingBalance  = $client->isCustomer() ? -$client->opening_balance : $client->opening_balance;
        $runningBalance  = $openingBalance;
        $rows = [];

        foreach ($entries as $entry) {
            // For suppliers: credit = money we owe grows; debit = payment reduces what we owe
            $runningBalance += ($entry['credit'] - $entry['debit']);
            $rows[] = array_merge($entry, ['balance' => $runningBalance]);
        }

        $closingBalance = $runningBalance;

        return view('ledger.supplier_show', compact(
            'client', 'rows', 'openingBalance', 'closingBalance', 'from', 'to'
        ));
    }

    /**
     * Export the supplier ledger as a PDF.
     */
    public function supplierPdf(Request $request, Client $client)
    {
        abort_unless($client->isSupplier(), 404);

        $data = $this->buildSupplierLedgerData($client, $request->input('from'), $request->input('to'));

        $pdf = Pdf::loadView('ledger.supplier_pdf', $data)->setPaper('a4');

        return $pdf->download("supplier-ledger-{$client->name}-" . now()->format('Ymd') . ".pdf");
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    private function buildCustomerLedgerData(Client $client, ?string $from, ?string $to): array
    {
        $legacyQuery = Ledger::where('client_id', $client->id);
        $newQuery    = LedgerEntry::where(function($q) use ($client) {
            $q->where(function($sq) {
                $sq->where('account_type', 'customer');
            });
            if ($client->type === 'both') {
                $q->orWhere(function($sq) {
                    $sq->where('account_type', 'supplier');
                });
            }
        })->where('account_id', $client->id);

        if ($from) { $legacyQuery->where('date', '>=', $from); $newQuery->where('date', '>=', $from); }
        if ($to)   { $legacyQuery->where('date', '<=', $to);   $newQuery->where('date', '<=', $to);   }

        $legacyEntries = $legacyQuery->get()->map(fn($r) => [
            'date' => $r->date, 'description' => $r->description,
            'debit' => $r->debit, 'credit' => $r->credit,
        ]);

        $newEntries = $newQuery->orderBy('date')->get()->map(fn($r) => [
            'date' => $r->date, 'description' => $r->description,
            'debit'  => $r->entry_type === 'debit'  ? $r->amount : 0,
            'credit' => $r->entry_type === 'credit' ? $r->amount : 0,
        ]);

        $entries        = $legacyEntries->toBase()->merge($newEntries)->sortBy('date')->values();
        $runningBalance = $client->opening_balance;
        $openingBalance = $client->opening_balance;
        $rows = [];

        foreach ($entries as $entry) {
            $runningBalance += ($entry['debit'] - $entry['credit']);
            $rows[] = array_merge($entry, ['balance' => $runningBalance]);
        }

        $closingBalance = $runningBalance;
        return compact('client', 'rows', 'openingBalance', 'closingBalance', 'from', 'to');
    }

    private function buildSupplierLedgerData(Client $client, ?string $from, ?string $to): array
    {
        $legacyQuery = Ledger::where('client_id', $client->id);
        $newQuery    = LedgerEntry::where(function($q) use ($client) {
            $q->where(function($sq) {
                $sq->where('account_type', 'supplier');
            });
            if ($client->type === 'both') {
                $q->orWhere(function($sq) {
                    $sq->where('account_type', 'customer');
                });
            }
        })->where('account_id', $client->id);

        if ($from) { $legacyQuery->where('date', '>=', $from); $newQuery->where('date', '>=', $from); }
        if ($to)   { $legacyQuery->where('date', '<=', $to);   $newQuery->where('date', '<=', $to);   }

        $legacyEntries = $legacyQuery->get()->map(fn($r) => [
            'date' => $r->date, 'description' => $r->description,
            'debit' => $r->debit, 'credit' => $r->credit,
        ]);

        $newEntries = $newQuery->orderBy('date')->get()->map(fn($r) => [
            'date' => $r->date, 'description' => $r->description,
            'debit'  => $r->entry_type === 'debit'  ? $r->amount : 0,
            'credit' => $r->entry_type === 'credit' ? $r->amount : 0,
        ]);

        $entries        = $legacyEntries->toBase()->merge($newEntries)->sortBy('date')->values();
        $openingBalance = $client->isCustomer() ? -$client->opening_balance : $client->opening_balance;
        $runningBalance = $openingBalance;
        $rows = [];

        foreach ($entries as $entry) {
            $runningBalance += ($entry['credit'] - $entry['debit']);
            $rows[] = array_merge($entry, ['balance' => $runningBalance]);
        }

        $closingBalance = $runningBalance;
        return compact('client', 'rows', 'openingBalance', 'closingBalance', 'from', 'to');
    }
}