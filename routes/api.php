<?php

use App\Http\Controllers\RentalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OfficeController;

Route::apiResource('berlesek', RentalController::class);
Route::apiResource('irodak', OfficeController::class);


/*
Route::get('/berlesek',[RentalController::class, 'index']);
Route::get('/berlesek/{id}', [RentalController::class, 'show']);
Route::post('/berlesek', [RentalController::class, 'store']);
Route::put('/berlesek/{id}', [RentalController::class, 'update']);
*/

