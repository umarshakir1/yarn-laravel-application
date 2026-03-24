<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'name',
        'type',
        'account_number',
        'bank_name',
        'opening_balance',
        'current_balance',
        'is_active',
        'notes',
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

    public function scopeBank($query)
    {
        return $query->where('type', 'bank');
    }

    public function scopeCash($query)
    {
        return $query->where('type', 'cash');
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * All ledger entries posted to this account.
     */
    public function ledgerEntries()
    {
        return $this->hasMany(LedgerEntry::class, 'account_id')
                    ->where('account_type', $this->type); // bank or cash
    }

    /**
     * Transfers where this account is the source (credit side).
     */
    public function outgoingTransfers()
    {
        return $this->hasMany(Transfer::class, 'from_account_id')
                    ->where('from_account_type', $this->type);
    }

    /**
     * Transfers where this account is the destination (debit side).
     */
    public function incomingTransfers()
    {
        return $this->hasMany(Transfer::class, 'to_account_id')
                    ->where('to_account_type', $this->type);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'bank' => 'Bank Account',
            'cash' => 'Cash Account',
            default => ucfirst($this->type),
        };
    }
}
