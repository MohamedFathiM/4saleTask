<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Repositories\MenuRepository;
use App\Http\Resources\API\V1\MealResource;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    private MenuRepository $menuRepository;

    public function __construct(
        MenuRepository $menuRepository,
    ) {
        $this->menuRepository = $menuRepository;
    }

    public function index(Request $request)
    {
        $items = $this->menuRepository->index($request);

        return $this->paginateResponse(
            data: MealResource::collection($items),
            collection: $items,
        );
    }
}
