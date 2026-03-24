<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'type',
        'opening_balance',
        'current_balance',
        'is_active',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active'       => 'boolean',
    ];

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCustomers($query)
    {
        return $query->whereIn('type', ['customer', 'both']);
    }

    public function scopeSuppliers($query)
    {
        return $query->whereIn('type', ['supplier', 'both']);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function ledgers()
    {
        return $this->hasMany(Ledger::class);
    }

    /**
     * Entries in the new universal ledger_entries table.
     * account_type is 'customer' or 'supplier' depending on context.
     */
    public function ledgerEntries()
    {
        return $this->hasMany(LedgerEntry::class, 'account_id')
                    ->whereIn('account_type', ['customer', 'supplier']);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isCustomer(): bool
    {
        return in_array($this->type, ['customer', 'both']);
    }

    public function isSupplier(): bool
    {
        return in_array($this->type, ['supplier', 'both']);
    }

    /**
     * Positive balance = client owes us (receivable).
     * Negative balance = we owe client (payable).
     */
    public function outstandingBalanceLabel(): string
    {
        $balance = abs($this->current_balance);
        $formatted = number_format($balance, 2);

        return $this->current_balance >= 0
            ? "Receivable: {$formatted}"
            : "Payable: {$formatted}";
    }
}
