<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GatewayLossController;

// Gateway Loss Routes
Route::get('gateway-loss', [GatewayLossController::class, 'index']);
Route::get('gateway-loss/{id}', [GatewayLossController::class, 'show']);
Route::post('gateway-loss', [GatewayLossController::class, 'store']);
Route::put('gateway-loss/{id}', [GatewayLossController::class, 'update']);
Route::patch('gateway-loss/{id}', [GatewayLossController::class, 'partialUpdate']);  // ← BAG-O!
Route::delete('gateway-loss/{id}', [GatewayLossController::class, 'destroy']);

// Combined Dashboard Stats (Service 1 + Service 2)
Route::get('/dashboard/stats', [App\Http\Controllers\DashboardController::class, 'getStats']);