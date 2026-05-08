<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GatewayLossRecordController;

// Routes for Gateway Loss Records
Route::get('/gateway-loss-records', [GatewayLossRecordController::class, 'index']);
Route::post('/gateway-loss-records', [GatewayLossRecordController::class, 'store']);
Route::get('/gateway-loss-records/{id}', [GatewayLossRecordController::class, 'show']);
Route::put('/gateway-loss-records/{id}', [GatewayLossRecordController::class, 'update']);
Route::delete('/gateway-loss-records/{id}', [GatewayLossRecordController::class, 'destroy']);
