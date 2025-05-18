<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

Route::get('/confirm/{token}', function () {
    return view('app');
});

Route::get('/unsubscribe/{token}', function () {
    return view('app');
});
