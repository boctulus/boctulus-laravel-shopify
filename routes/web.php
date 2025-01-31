<?php

use App\Http\Controllers\AdminTasks;
use App\Http\Controllers\ShopiTestController;
use App\Http\Controllers\TestFallback;
use Illuminate\Support\Facades\Route;



Route::get('/test-fallback', [TestFallback::class, 'test_apiclientfallback_package']);

Route::get('/shopify/test-products', [ShopiTestController::class, 'apirest_products']);

Route::get('/admin/list', [AdminTasks::class, 'list']);

Route::get('/', function () {
    return view('welcome');
});
