<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,NULL,id,user_id,'.$this->user()->id.',deleted_at,NULL'],
            'percentage' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
