<?php

use App\Http\Controllers\ShopiTestController;
use Illuminate\Support\Facades\Route;


Route::get('/shopify/test-products', [ShopiTestController::class, 'apirest_products']);

Route::get('/', function () {
    return view('welcome');
});
