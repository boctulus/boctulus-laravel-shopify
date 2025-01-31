<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::resource('addresses', App\Http\Controllers\AddressController::class);
Route::resource('cart_items', App\Http\Controllers\CartItemController::class);
Route::resource('carts', App\Http\Controllers\CartController::class);
Route::resource('favorites', App\Http\Controllers\FavoriteController::class);
Route::resource('inventory', App\Http\Controllers\InventoryController::class);
Route::resource('order_items', App\Http\Controllers\OrderItemController::class);
Route::resource('orders', App\Http\Controllers\OrderController::class);
Route::resource('price_rules', App\Http\Controllers\PriceRuleController::class);
Route::resource('products', App\Http\Controllers\ProductController::class);

