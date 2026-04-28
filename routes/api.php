<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AppointmentApiController;

Route::get('/appointments',      [AppointmentApiController::class, 'index']);
Route::get('/appointments/{id}', [AppointmentApiController::class, 'show']);
Route::post('/appointments',     [AppointmentApiController::class, 'store']);