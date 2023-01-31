<?php

use App\Http\Controllers\SensorController;
use Illuminate\Support\Facades\Route;


// Route::prefix('store/data/')->controller(SensorController::class)->group(function () {
//     Route::post('suhu', 'store_suhu');
//     Route::post('kelembaban', 'store_kelembaban');
//     Route::post('ph', 'store_ph');
//     Route::post('tinggi_bak_air', 'store_tinggi_bak_air');
//     Route::post('tinggi_nutrisi_a', 'store_tinggi_nutrisi_a');
//     Route::post('tinggi_nutrisi_b', 'store_tinggi_nutrisi_b');
// });

Route::post('store/data/{type}', [SensorController::class, 'store']);
Route::get('get/data/all', [SensorController::class, 'getList']);
Route::get('get/data/{type}', [SensorController::class, 'get']);
