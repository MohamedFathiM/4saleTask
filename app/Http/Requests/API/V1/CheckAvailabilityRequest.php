<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class CheckAvailabilityRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'table_id' => 'required|exists:tables,id',
            'datetime' => 'required|date_format:Y-m-d H:i',
            'guests_count' => 'required|integer|min:1',
        ];
    }
}
