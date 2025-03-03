<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Branch Manager permissions
        $branchManager = Role::where('slug', 'branch-manager')->first();
        $branchManager->permissions()->attach(Permission::all());

        // Outlet Manager permissions
        $outletManager = Role::where('slug', 'outlet-manager')->first();
        $outletManager->permissions()->attach(
            Permission::whereIn('slug', [
                'manage-products',
                'manage-orders',
            ])->get()
        );

        // Personal Customer permissions
        $personalCustomer = Role::where('slug', 'personal-customer')->first();
        $personalCustomer->permissions()->attach(
            Permission::whereIn('slug', [
                'place-orders',
                'view-own-orders',
                'update-profile',
            ])->get()
        );

        // Business Customer permissions
        $businessCustomer = Role::where('slug', 'business-customer')->first();
        $businessCustomer->permissions()->attach(
            Permission::whereIn('slug', [
                'place-orders',
                'view-own-orders',
                'update-profile',
            ])->get()
        );
    }
}


