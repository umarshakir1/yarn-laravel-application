<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'client_id',
        'sale_date',
        'invoice_no',
        'total_amount',
        'paid_amount',
        'discount',
        'total_profit',
        'notes',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'sale_services')
                    ->withPivot('price', 'unit', 'quantity_used', 'service_cost', 'service_profit')
                    ->withTimestamps();
    }

    public function servicesTotal(): float
    {
        return $this->services->sum('pivot.price');
    }

    public function serviceProfitsTotal(): float
    {
        return $this->services->sum('pivot.service_profit');
    }
}
