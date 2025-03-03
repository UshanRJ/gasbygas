<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Admin permissions
        Permission::create([
            'name' => 'Manage Users',
            'slug' => 'manage-users',
            'description' => 'Can create, update, and delete users'
        ]);

        Permission::create([
            'name' => 'Manage Products',
            'slug' => 'manage-products',
            'description' => 'Can create, update, and delete products'
        ]);

        Permission::create([
            'name' => 'Manage Orders',
            'slug' => 'manage-orders',
            'description' => 'Can view and update order status'
        ]);

        Permission::create([
            'name' => 'Manage Settings',
            'slug' => 'manage-settings',
            'description' => 'Can manage application settings'
        ]);

        // Customer permissions
        Permission::create([
            'name' => 'Place Orders',
            'slug' => 'place-orders',
            'description' => 'Can place new orders'
        ]);

        Permission::create([
            'name' => 'View Own Orders',
            'slug' => 'view-own-orders',
            'description' => 'Can view their own orders'
        ]);

        Permission::create([
            'name' => 'Update Profile',
            'slug' => 'update-profile',
            'description' => 'Can update their own profile'
        ]);
    }
}

