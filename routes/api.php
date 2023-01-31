<?php

use App\Http\Controllers\SensorController;
use Illuminate\Support\Facades\Route;

Route::post('store/data/{type}', [SensorController::class, 'store']);
Route::get('get/data/all', [SensorController::class, 'getList']);
Route::get('get/data/{type}', [SensorController::class, 'get']);

Route::get('test/store/data/{type}', [SensorController::class, 'store']);