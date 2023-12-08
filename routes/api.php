<?php

use App\Http\Controllers\API\V1\LoginController;
use Illuminate\Support\Facades\Route;



Route::prefix('v1')->group(function () {
    Route::post('/login', [LoginController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
    });
});
