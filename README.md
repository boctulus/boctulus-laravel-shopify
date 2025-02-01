# Laravel-Shopify: Documentación del Plan y Estructura de Tablas

Este documento describe el plan de desarrollo y la estructura de la base de datos para el proyecto **Laravel-Shopify**, una API REST que integra Shopify con Laravel para gestionar productos, carritos, pedidos, usuarios y más.

---

## **Plan de Desarrollo**

### **Objetivo Principal**
Desarrollar una API REST en Laravel que permita:
1. Sincronizar productos desde Shopify y almacenar información adicional en la base de datos local.
2. Gestionar usuarios, carritos, pedidos y favoritos.
3. Implementar lógica de descuentos basada en reglas de precios.
4. Sincronizar inventarios con Shopify mediante webhooks.
5. Proporcionar un sistema de roles y permisos para controlar el acceso a las funcionalidades.

---

### **Tecnologías Utilizadas**
- **Laravel**: Framework PHP para el backend.
- **MySQL**: Base de datos relacional.
- **Redis**: Caché para optimizar el rendimiento.
- **Shopify API**: Para obtener y actualizar datos de productos e inventarios.
- **Spatie Laravel-Permission**: Para gestionar roles y permisos.

---

### **Estructura de Tablas**

A continuación, se detalla la estructura de las tablas de la base de datos:

---

#### **1. Tabla `users`**
Almacena la información de los usuarios. Se utiliza la tabla por defecto de Laravel.

```sql
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();
});
```

---

#### **2. Tabla `products`**
Almacena los productos sincronizados desde Shopify.

```sql
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->bigInteger('shopify_product_id')->unique(); // ID del producto en Shopify
    $table->timestamps();
});
```

---

#### **3. Tabla `price_rules`**
Almacena las reglas de precios para los productos.

```sql
Schema::create('price_rules', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->onDelete('cascade');
    $table->decimal('wholesale_price', 10, 2); // Precio mayorista
    $table->integer('minimum_quantity'); // Cantidad mínima para aplicar el descuento
    $table->decimal('discount_percentage', 5, 2)->nullable(); // Porcentaje de descuento
    $table->boolean('active')->default(true); // Indica si la regla está activa
    $table->timestamps();
});
```

---

#### **4. Tabla `carts`**
Almacena los carritos de compra de los usuarios.

```sql
Schema::create('carts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->timestamps();
});
```

---

#### **5. Tabla `cart_items`**
Almacena los productos agregados a los carritos.

```sql
Schema::create('cart_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('cart_id')->constrained()->onDelete('cascade');
    $table->bigInteger('shopify_product_id'); // ID del producto en Shopify
    $table->integer('quantity'); // Cantidad del producto
    $table->decimal('price', 10, 2); // Precio unitario
    $table->timestamps();
});
```

---

#### **6. Tabla `addresses`**
Almacena las direcciones de los usuarios.

```sql
Schema::create('addresses', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('street');
    $table->string('city');
    $table->string('state');
    $table->string('zip_code');
    $table->boolean('is_default')->default(false); // Indica si es la dirección principal
    $table->timestamps();
});
```

---

#### **7. Tabla `orders`**
Almacena los pedidos realizados por los usuarios.

```sql
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->foreignId('address_id')->constrained();
    $table->string('status'); // Estado del pedido (ej: pendiente, completado, cancelado)
    $table->decimal('total', 10, 2); // Total del pedido
    $table->timestamps();
});
```

---

#### **8. Tabla `order_items`**
Almacena los productos asociados a cada pedido.

```sql
Schema::create('order_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')->constrained()->onDelete('cascade');
    $table->bigInteger('shopify_product_id'); // ID del producto en Shopify
    $table->integer('quantity'); // Cantidad del producto
    $table->decimal('price', 10, 2); // Precio unitario
    $table->timestamps();
});
```

---

#### **9. Tabla `favorites`**
Almacena los productos favoritos de los usuarios.

```sql
Schema::create('favorites', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->bigInteger('shopify_product_id'); // ID del producto en Shopify
    $table->timestamps();

    $table->unique(['user_id', 'shopify_product_id']); // Evita duplicados
});
```

---

#### **10. Tabla `inventory`**
Almacena el inventario de los productos sincronizado con Shopify.

```sql
Schema::create('inventory', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->onDelete('cascade');
    $table->integer('quantity')->default(0); // Cantidad disponible
    $table->timestamps();
});
```

---

### **Relaciones Entre Tablas**

1. **`users`** → **`carts`**: Un usuario puede tener un carrito.
2. **`carts`** → **`cart_items`**: Un carrito puede tener múltiples productos.
3. **`users`** → **`addresses`**: Un usuario puede tener múltiples direcciones.
4. **`users`** → **`orders`**: Un usuario puede tener múltiples pedidos.
5. **`orders`** → **`order_items`**: Un pedido puede tener múltiples productos.
6. **`products`** → **`price_rules`**: Un producto puede tener múltiples reglas de precios.
7. **`users`** → **`favorites`**: Un usuario puede tener múltiples productos favoritos.
8. **`products`** → **`inventory`**: Un producto tiene un registro de inventario.

---

### **Comandos Artisan para Crear Migraciones**

```bash
php artisan make:migration create_products_table
php artisan make:migration create_price_rules_table
php artisan make:migration create_carts_table
php artisan make:migration create_cart_items_table
php artisan make:migration create_addresses_table
php artisan make:migration create_orders_table
php artisan make:migration create_order_items_table
php artisan make:migration create_favorites_table
php artisan make:migration create_inventory_table
```

---

# Autenticacion JWT

Generar la secret key ejecutando:

```bash 
php artisan jwt:secret
```

# Roles y permisos

Sanctum maneja la autenticación (tokens)

[Sanctum](https://github.com/laravel/sanctum)

Para los roles se utiliza utiliza la librería [Laravel Permission](https://github.com/spatie/laravel-permission)

---





