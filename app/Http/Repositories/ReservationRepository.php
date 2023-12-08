<?php

namespace App\Http\Repositories;

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
}
