<?php

use App\Http\Controllers\GatewayLossController;
use Illuminate\Support\Facades\Route;

Route::get('/gateway-loss', [GatewayLossController::class, 'index']);
Route::post('/gateway-loss', [GatewayLossController::class, 'store']);
Route::get('/gateway-loss/{id}', [GatewayLossController::class, 'show']);
Route::put('/gateway-loss/{id}', [GatewayLossController::class, 'update']);
Route::delete('/gateway-loss/{id}', [GatewayLossController::class, 'destroy']);
Route::get('/gateway-loss-statistics', [GatewayLossController::class, 'statistics']);
