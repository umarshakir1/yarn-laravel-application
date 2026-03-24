<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:expense_categories,name,' . ($this->expense_category ? $this->expense_category->id : ''),
            'description' => 'nullable|string',
        ];
    }
}
