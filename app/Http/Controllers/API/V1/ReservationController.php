<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Repositories\ReservationRepository;
use App\Http\Requests\API\V1\CheckAvailabilityRequest;
use App\Http\Resources\API\V1\TableResource;
use Illuminate\Http\Request;

class ReservationController extends Controller
{

    private ReservationRepository $repository;

    public function __construct(ReservationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function listTables(Request $request)
    {
        $reservations = $this->repository->listTables($request);

        return $this->paginateResponse(
            data: TableResource::collection($reservations),
            collection: $reservations,
        );
    }

    public function checkTableAvailability(CheckAvailabilityRequest $request)
    {
        $data = $request->validated();
        $available = $this->repository->checkAvailability(
            $data['datetime'],
            $data['guests_count'],
            $data['table_id']
        );

        return $this->apiResource(
            data: [
                'is_available' => $available,
            ],
            message: $available ? 'Table is available' : 'Table is not available',
        );
    }
}
