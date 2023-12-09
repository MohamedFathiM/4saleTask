<?php

namespace App\Http\Resources\API\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'table_id' => $this->table_id,
            'customer' => [
                'id' => $this->customer_id,
                'name' => $this->customer->name,
            ],
            'from_time' => Carbon::parse($this->from_time)->format('H:i'),
            'date' => Carbon::parse($this->date)->format('Y-m-d'),
            'to_time' => Carbon::parse($this->to_time)->format('H:i'),
            'guests_count' => $this->table?->guests_count,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
