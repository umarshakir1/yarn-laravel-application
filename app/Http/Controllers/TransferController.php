<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Client;
use App\Models\Transfer;
use App\Services\TransferService;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    protected TransferService $transferService;

    public function __construct(TransferService $transferService)
    {
        $this->transferService = $transferService;
    }

    /**
     * Transfer history with filters (date, account type, account name).
     */
    public function index(Request $request)
    {
        $query = Transfer::latest('date');

        if ($from = $request->input('from')) {
            $query->where('date', '>=', $from);
        }
        if ($to = $request->input('to')) {
            $query->where('date', '<=', $to);
        }
        if ($accountType = $request->input('account_type')) {
            $query->where(function ($q) use ($accountType) {
                $q->where('from_account_type', $accountType)
                  ->orWhere('to_account_type', $accountType);
            });
        }

        $transfers = $query->paginate(20)->withQueryString();

        // Eagerly resolve account names for display
        $transfers->getCollection()->transform(function (Transfer $transfer) {
            $transfer->from_label = $this->resolveLabel($transfer->from_account_type, $transfer->from_account_id);
            $transfer->to_label   = $this->resolveLabel($transfer->to_account_type,   $transfer->to_account_id);
            return $transfer;
        });

        return view('transfers.index', compact('transfers'));
    }

    /**
     * Show the form for creating a transfer.
     */
    public function create()
    {
        $accounts  = Account::active()->orderBy('name')->get();
        $customers = Client::customers()->active()->orderBy('name')->get();
        $suppliers = Client::suppliers()->active()->orderBy('name')->get();

        return view('transfers.create', compact('accounts', 'customers', 'suppliers'));
    }

    /**
     * Store a new transfer and post the double-entry ledger rows.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'date'              => 'required|date',
            'from_account_type' => 'required|in:customer,supplier,bank,cash',
            'from_account_id'   => 'required|integer|min:1',
            'to_account_type'   => 'required|in:customer,supplier,bank,cash',
            'to_account_id'     => 'required|integer|min:1',
            'amount'            => 'required|numeric|min:0.01',
            'description'       => 'nullable|string|max:500',
            'reference_no'      => 'nullable|string|max:100|unique:transfers,reference_no',
        ]);

        // Prevent transferring to the same account
        if ($data['from_account_type'] === $data['to_account_type'] &&
            $data['from_account_id']   === (int) $data['to_account_id']) {
            return back()->withErrors(['to_account_id' => 'Source and destination cannot be the same account.'])
                         ->withInput();
        }

        $this->transferService->createTransfer($data);

        return redirect()->route('transfers.index')
                         ->with('success', 'Transfer recorded and ledger entries posted successfully.');
    }

    /**
     * Show a single transfer's details.
     */
    public function show(Transfer $transfer)
    {
        $transfer->from_label = $this->resolveLabel($transfer->from_account_type, $transfer->from_account_id);
        $transfer->to_label   = $this->resolveLabel($transfer->to_account_type,   $transfer->to_account_id);
        $transfer->load('ledgerEntries');

        return view('transfers.show', compact('transfer'));
    }

    /**
     * Reverse (delete) a transfer and restore all balances.
     */
    public function destroy(Transfer $transfer)
    {
        $this->transferService->deleteTransfer($transfer);

        return redirect()->route('transfers.index')
                         ->with('success', 'Transfer reversed and balances restored.');
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    /**
     * Resolve a human-readable name for any account_type + account_id combo.
     */
    private function resolveLabel(string $type, int $id): string
    {
        if (in_array($type, ['customer', 'supplier'])) {
            $client = Client::find($id);
            return $client ? "{$client->name} (" . ucfirst($type) . ")" : "Unknown {$type}";
        }

        $account = Account::find($id);
        return $account ? "{$account->name} (" . $account->getTypeLabel() . ")" : "Unknown account";
    }
}
