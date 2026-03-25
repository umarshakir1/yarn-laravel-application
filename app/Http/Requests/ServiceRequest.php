<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'cost_price'  => 'required|numeric|min:0',
            'unit'        => 'required|in:per_kg,per_bundle,per_bag',
            'description' => 'nullable|string|max:1000',
            'is_active'   => 'boolean',
        ];
    }
}
