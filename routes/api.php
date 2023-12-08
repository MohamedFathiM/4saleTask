<?php

use App\Http\Controllers\API\V1\LoginController;
use App\Http\Controllers\API\V1\ReservationController;
use Illuminate\Support\Facades\Route;



Route::prefix('v1')->group(function () {
    Route::post('/login', [LoginController::class, 'login']);

    Route::middleware('auth:sanctum')->controller(ReservationController::class)
        ->group(function () {
            Route::get('list-tables',  'listTables');
            Route::get('check-table-availability',  'checkTableAvailability');
            Route::post('reserve-table',  'reserveTable');
        });
});
