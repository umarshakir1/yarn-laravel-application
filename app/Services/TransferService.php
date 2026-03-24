<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Client;
use App\Models\LedgerEntry;
use App\Models\Transfer;
use Illuminate\Support\Facades\DB;

class TransferService
{
    /**
     * Execute a double-entry transfer between two accounts.
     * Always posts two ledger_entry rows and one transfer row inside a transaction.
     * Also updates current_balance on Account and Client models where applicable.
     */
    public function createTransfer(array $data): Transfer
    {
        return DB::transaction(function () use ($data) {

            // 1) Persist the transfer record
            $transfer = Transfer::create([
                'date'              => $data['date'],
                'from_account_type' => $data['from_account_type'],
                'from_account_id'   => $data['from_account_id'],
                'to_account_type'   => $data['to_account_type'],
                'to_account_id'     => $data['to_account_id'],
                'amount'            => $data['amount'],
                'description'       => $data['description'] ?? null,
                'reference_no'      => $data['reference_no'] ?? null,
            ]);

            $amount      = $data['amount'];
            $description = $data['description'] ?? "Transfer {$transfer->reference_no}";
            $date        = $data['date'];

            // ── 2) CREDIT the source (money goes OUT) ──────────────────────────
            LedgerEntry::create([
                'account_type'   => $data['from_account_type'],
                'account_id'     => $data['from_account_id'],
                'entry_type'     => 'credit',
                'amount'         => $amount,
                'date'           => $date,
                'description'    => $description,
                'reference_type' => 'Transfer',
                'reference_id'   => $transfer->id,
            ]);

            // ── 3) DEBIT the destination (money comes IN) ──────────────────────
            LedgerEntry::create([
                'account_type'   => $data['to_account_type'],
                'account_id'     => $data['to_account_id'],
                'entry_type'     => 'debit',
                'amount'         => $amount,
                'date'           => $date,
                'description'    => $description,
                'reference_type' => 'Transfer',
                'reference_id'   => $transfer->id,
            ]);

            // ── 4) Update running balances ──────────────────────────────────────
            $this->adjustBalance($data['from_account_type'], $data['from_account_id'], -$amount);
            $this->adjustBalance($data['to_account_type'],   $data['to_account_id'],   +$amount);

            return $transfer;
        });
    }

    /**
     * Reverse a transfer: delete ledger entries and restore balances.
     */
    public function deleteTransfer(Transfer $transfer): void
    {
        DB::transaction(function () use ($transfer) {
            // Restore balances in reverse
            $this->adjustBalance($transfer->from_account_type, $transfer->from_account_id, +$transfer->amount);
            $this->adjustBalance($transfer->to_account_type,   $transfer->to_account_id,   -$transfer->amount);

            // Delete the double-entry rows
            LedgerEntry::where('reference_type', 'Transfer')
                       ->where('reference_id', $transfer->id)
                       ->delete();

            $transfer->delete();
        });
    }

    /**
     * Add $delta to the current_balance of a Client or Account.
     * For suppliers: a positive delta means we owe MORE (balance goes more negative).
     * For bank/cash: a positive delta means more money in the account.
     */
    private function adjustBalance(string $type, int $id, float $delta): void
    {
        if (in_array($type, ['customer', 'supplier'])) {
            Client::where('id', $id)->increment('current_balance', $delta);
        } else {
            Account::where('id', $id)->increment('current_balance', $delta);
        }
    }
}
