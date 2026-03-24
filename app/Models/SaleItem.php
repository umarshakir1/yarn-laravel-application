<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id',
        'lot_id',
        'product_id',
        'bags',
        'bundles',
        'unit_price_per_bundle',
        'subtotal',
        'profit',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
