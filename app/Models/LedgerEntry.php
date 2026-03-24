<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerEntry extends Model
{
    protected $fillable = [
        'account_type',
        'account_id',
        'entry_type',
        'amount',
        'date',
        'description',
        'reference_type',
        'reference_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date'   => 'date',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * Resolve the owning account: returns a Client or Account instance.
     */
    public function account()
    {
        if (in_array($this->account_type, ['customer', 'supplier'])) {
            return $this->belongsTo(Client::class, 'account_id');
        }

        return $this->belongsTo(Account::class, 'account_id');
    }

    /**
     * The source transaction this entry was generated from.
     * Resolved via the reference_type class name stored in the column.
     */
    public function reference()
    {
        if (!$this->reference_type || !$this->reference_id) {
            return null;
        }

        $map = [
            'Sale'     => Sale::class,
            'Purchase' => Purchase::class,
            'Transfer' => Transfer::class,
        ];

        $modelClass = $map[$this->reference_type] ?? null;

        return $modelClass ? $modelClass::find($this->reference_id) : null;
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeForAccount($query, string $type, int $id)
    {
        return $query->where('account_type', $type)->where('account_id', $id);
    }

    public function scopeDebits($query)
    {
        return $query->where('entry_type', 'debit');
    }

    public function scopeCredits($query)
    {
        return $query->where('entry_type', 'credit');
    }

    public function scopeInDateRange($query, ?string $from, ?string $to)
    {
        if ($from) {
            $query->where('date', '>=', $from);
        }
        if ($to) {
            $query->where('date', '<=', $to);
        }

        return $query;
    }
}
