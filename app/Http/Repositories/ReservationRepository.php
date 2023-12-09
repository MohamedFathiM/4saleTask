<?php

namespace App\Http\Repositories;

use App\Http\Requests\API\V1\ReserveRequest;
use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;

class ReservationRepository
{
    public function listTables(Request $request): LengthAwarePaginator
    {
        return Table::query()->paginate();
    }

    public function checkAvailability(string $datetime, int $guestsCount, int $tableId): bool
    {
        $date = Carbon::parse($datetime)->format('Y-m-d');
        $time = Carbon::parse($datetime)->format('H:i:s');

        return Reservation::whereDate('date', $date)
            ->whereTime('from_time', '<=', $time)
            ->whereTime('to_time', '>=', $time)
            ->join('tables', function (JoinClause $join) use ($tableId) {
                $join->on('reservations.table_id', '=', 'tables.id')
                    ->where('tables.id', $tableId);
            })
            ->where('tables.capacity', '>=', $guestsCount)
            ->doesntExist();
    }

    public function reserveTable(ReserveRequest $request): ?Reservation
    {
        $data = $request->validated();

        $reservation = Reservation::create($data + ['customer_id' => $request->customer_id]);

        return $reservation;
    }

    public function isTableGuestAvailable(int $guests_count, int $table_id): bool
    {
        return Table::query()->where('id', $table_id)->where('capacity', '>=', $guests_count)->exists();
    }
}
