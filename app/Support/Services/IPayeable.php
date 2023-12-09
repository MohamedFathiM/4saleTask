<?php

namespace App\Support\Services;

use App\Models\Order;

interface IPayeable
{
    public function checkout(Order $order): void;
}
