<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles
        $adminRole = Role::create(['name' => 'admin']);
        $customerRole = Role::create(['name' => 'customer']);

        // Crear permisos
        Permission::create(['name' => 'manage products']);
        Permission::create(['name' => 'view products']);
        Permission::create(['name' => 'manage orders']);
        Permission::create(['name' => 'manage own orders']);

        // Asignar permisos a roles
        $adminRole->givePermissionTo(['manage products', 'view products', 'manage orders']);
        $customerRole->givePermissionTo(['view products', 'manage own orders']);
    }
}