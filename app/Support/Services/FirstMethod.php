<?php

namespace App\Support\Services;

use App\Models\Order;

class FirstMethod implements IPayeable
{
    public function checkout(Order $order): void
    {
        //   first one add 14 % taxes and 20 % service
        $total = $order->total;
        $services = $total * 0.20;
        $taxes = $total * 0.14;

        $endTotal = $total - ($services + $taxes);

        $order->update([
            'services' => $services,
            'taxes' => $taxes,
            'final_total' => $endTotal
        ]);
    }
}
