<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Repositories\ReservationRepository;
use App\Http\Requests\API\V1\CheckAvailabilityRequest;
use App\Http\Requests\API\V1\ReserveRequest;
use App\Http\Resources\API\V1\TableResource;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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

    public function reserveTable(ReserveRequest $request)
    {
        if (!$this->repository->checkAvailability($request->date . ' ' . $request->from_time, $request->guests_count, $request->table_id)) {
            throw ValidationException::withMessages([
                'table_id' => 'Table is not available',
            ]);
        }

        if (!$this->repository->isTableGuestAvailable($request->guests_count, $request->table_id)) {
            throw ValidationException::withMessages([
                'guests_count' => 'Table is not suitable for the number of guests',
            ]);
        }

        $reservation = $this->repository->reserveTable($request);

        return $this->apiResource(
            message: $reservation ? 'Table reserved successfully' : 'Try Again',
        );
    }
}
