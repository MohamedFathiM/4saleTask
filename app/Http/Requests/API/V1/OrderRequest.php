<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'meals' => 'required|array',
            'meals.*.meal_id' => 'required|exists:meals,id',
            'meals.*.amount_to_pay' => 'required|integer',
        ];
    }
}
