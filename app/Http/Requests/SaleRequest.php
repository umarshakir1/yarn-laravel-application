<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'sale_date' => 'required|date',
            'invoice_no' => 'required|string|unique:sales,invoice_no,' . ($this->sale ? $this->sale->id : ''),
            'items' => 'required|array|min:1',
            'items.*.lot_id' => 'required|exists:lots,id',
            'items.*.bags' => 'required|numeric|min:0.01',
            'items.*.unit_price_per_bundle' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }
}
