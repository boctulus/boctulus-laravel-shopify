<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PriceRuleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\AddressController;
use App\Http\Middleware\Authenticate;

// Rutas públicas de autenticación
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Rutas protegidas
Route::middleware([
    'auth:sanctum',
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
])->group(function () {
    // Ruta de usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rutas solo para admin
    Route::middleware('role:admin')->group(function () {
        Route::apiResources([
            'products' => ProductController::class,            
            'price_rules' => PriceRuleController::class,
            'users' => UserController::class,
        ]);

        // Rutas de escritura para Product
        Route::post('products', [ProductController::class, 'store']);
        Route::put('products/{id}', [ProductController::class, 'update']);
        Route::delete('products/{id}', [ProductController::class, 'destroy']);
    });

    // Rutas para customers 
    Route::middleware('role:customer')->group(function () {
        // Rutas de solo lectura para productos
        Route::get('products', [ProductController::class, 'index']);
        Route::get('products/{id}', [ProductController::class, 'show']);
        
        // Rutas para operaciones propias del cliente
        Route::apiResources([
            'cart_items' => CartItemController::class,
            'carts' => CartController::class,
            'favorites' => FavoriteController::class,
            'orders' => OrderController::class,
            'order_items' => OrderItemController::class,
            'addresses' => AddressController::class
        ]);
    });
});