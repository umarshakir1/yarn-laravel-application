<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'client_id',
        'purchase_date',
        'invoice_no',
        'total_amount',
        'paid_amount',
        'notes',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
