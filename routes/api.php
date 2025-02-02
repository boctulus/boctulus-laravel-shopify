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

// Rutas públicas de autenticación
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Rutas públicas para productos (solo lectura)
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);

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
        // Rutas de gestión de productos (escritura)
        Route::post('products', [ProductController::class, 'store']);
        Route::put('products/{id}', [ProductController::class, 'update']);
        Route::delete('products/{id}', [ProductController::class, 'destroy']);
        
        // Gestión completa de price_rules y users
        Route::apiResources([
            'price_rules' => PriceRuleController::class,
            'users' => UserController::class,
        ]);

        // Solo lectura de órdenes para admin
        Route::get('orders', [OrderController::class, 'index']);
        Route::get('orders/{id}', [OrderController::class, 'show']);
        Route::get('order_items', [OrderItemController::class, 'index']);
        Route::get('order_items/{id}', [OrderItemController::class, 'show']);
    });

    // Rutas para customers y admin
    Route::middleware('role:customer,admin')->group(function () {
        // Acceso a price_rules (solo lectura)
        Route::get('price_rules', [PriceRuleController::class, 'index']);
        Route::get('price_rules/{id}', [PriceRuleController::class, 'show']);
    });

    // Rutas exclusivas para customers
    Route::middleware('role:customer')->group(function () {
        // Carrito y favoritos (CRUD completo)
        Route::apiResources([
            'cart_items' => CartItemController::class,
            'carts' => CartController::class,
            'favorites' => FavoriteController::class,
            'addresses' => AddressController::class
        ]);

        // Órdenes (solo crear y leer)
        Route::get('orders', [OrderController::class, 'index']);
        Route::get('orders/{id}', [OrderController::class, 'show']);
        Route::post('orders', [OrderController::class, 'store']);
        
        Route::get('order_items', [OrderItemController::class, 'index']);
        Route::get('order_items/{id}', [OrderItemController::class, 'show']);
        Route::post('order_items', [OrderItemController::class, 'store']);
    });
});