<?php

use App\Http\Controllers\API\V1\LoginController;
use App\Http\Controllers\API\V1\MenuController;
use App\Http\Controllers\API\V1\ReservationController;
use Illuminate\Support\Facades\Route;



Route::prefix('v1')->group(function () {
    Route::post('/login', [LoginController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::controller(ReservationController::class)->group(function () {
            Route::get('list-tables',  'listTables');
            Route::get('check-table-availability',  'checkTableAvailability');
            Route::post('reserve-table',  'reserveTable');
        });

        Route::controller(MenuController::class)->prefix('menus')->group(function () {
            Route::get('',  'index');
        });
    });
});
