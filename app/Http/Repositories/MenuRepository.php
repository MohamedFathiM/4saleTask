<?php

namespace App\Http\Repositories;

use App\Models\Meal;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class MenuRepository
{
    public function index(Request $request): LengthAwarePaginator
    {
        return Meal::query()->paginate($request->per_page);
    }
}
