<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MealResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'price' => (float) number_format($this->price, 2, '.', ''),
            'description' => $this->description,
            'available_quantity' => (int) $this->available_quantity,
            'discount' => (float) number_format($this->discount, 2, '.', ''),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
