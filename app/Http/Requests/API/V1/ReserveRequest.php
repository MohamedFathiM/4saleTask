<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class ReserveRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'table_id' => 'required|exists:tables,id',
            'date' => 'required|date_format:Y-m-d',
            'from_time' => 'required|date_format:H:i',
            'to_time' => 'required|date_format:H:i|after:from_time',
            'guests_count' => 'required|integer|min:1',
            'customer_id' => 'required|exists:customers,id',
        ];
    }
}
