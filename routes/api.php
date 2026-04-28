<?php

use App\Http\Controllers\Api\AppointmentApiController;
use App\Http\Controllers\Api\ServiceApiController;
use App\Http\Controllers\Api\UserApiController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->as('api.')->group(function () {
    Route::get('/appointments', [AppointmentApiController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/{id}', [AppointmentApiController::class, 'show'])->name('appointments.show');
    Route::post('/appointments', [AppointmentApiController::class, 'store'])->name('appointments.store');

    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', UserApiController::class);
        Route::apiResource('services', ServiceApiController::class);
    });
});
