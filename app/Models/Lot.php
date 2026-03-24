<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    protected $fillable = [
        'lot_number',
        'purchase_item_id',
        'product_id',
        'initial_bags',
        'remaining_bags',
        'cost_price_per_bundle',
        'is_exhausted',
    ];

    public function purchaseItem()
    {
        return $this->belongsTo(PurchaseItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope for available lots
     */
    public function scopeAvailable($query)
    {
        return $query->where('remaining_bags', '>', 0)->where('is_exhausted', false);
    }
}
