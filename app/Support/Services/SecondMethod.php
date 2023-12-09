<?php

namespace App\Support\Services;

use App\Models\Order;

class SecondMethod implements IPayeable
{
    public function checkout(Order $order): void
    {
        // second one add 15 service only
        $total = $order->total;
        $services = $total * 0.15;

        $endTotal = $total - ($services);

        $order->update([
            'services' => $services,
            'taxes' => 0,
            'final_total' => $endTotal
        ]);
    }
}
