<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    protected $fillable = [
        'client_id',
        'date',
        'description',
        'transaction_type',
        'reference_type',
        'reference_id',
        'debit',
        'credit',
        'balance',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
