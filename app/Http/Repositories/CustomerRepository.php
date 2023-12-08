<?php

namespace App\Http\Repositories;

use App\Models\Customer;

class CustomerRepository
{
    public function findByPhone(string $phone): ?Customer
    {
        return Customer::query()->where('phone', $phone)->first();
    }
}
