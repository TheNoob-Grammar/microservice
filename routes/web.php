<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GatewayLossRecordController;

// Web routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/gateway-loss', function () {
    return view('gateway-loss.index');
});

// API routes (temporarily in web.php for testing)
Route::get('/api/gateway-loss-records', [GatewayLossRecordController::class, 'index']);
Route::post('/api/gateway-loss-records', [GatewayLossRecordController::class, 'store']);
Route::get('/api/gateway-loss-records/{id}', [GatewayLossRecordController::class, 'show']);
Route::put('/api/gateway-loss-records/{id}', [GatewayLossRecordController::class, 'update']);
Route::delete('/api/gateway-loss-records/{id}', [GatewayLossRecordController::class, 'destroy']);