<?php

namespace App\Http\Resources\API\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'table' => [
                'id' => $this->table_id,
                'capacity' => $this->table->capacity,
            ],
            'reservation_id' => $this->reservation_id,
            'customer' => [
                'id' => $this->customer_id,
                'name' => $this->customer->name,
            ],
            'waiter' => [
                'id' => $this->user_id,
                'name' => $this->waiter->name,
            ],
            'total' => (float) number_format((float) $this->total, 2, '.', ''),
            'is_paid' => (bool) $this->is_paid,
            'paid_at' => $this->paid_at ? Carbon::parse($this->paid_at)->format('Y-m-d H:i:s') : null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
