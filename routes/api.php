<?php

use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::get('/weather', [WeatherController::class, 'getWeather']);
Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
Route::get('/confirm/{token}', [SubscriptionController::class, 'confirm']);
Route::get('/unsubscribe/{token}', [SubscriptionController::class, 'unsubscribe']);
