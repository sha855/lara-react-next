<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\OrderController;

Route::get('/restaurants', [RestaurantController::class, 'index']);
Route::get('/restaurants/top', [RestaurantController::class, 'topRestaurants']);
Route::get('/orders/metrics', [OrderController::class, 'metrics']);