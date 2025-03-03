<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin roles
        Role::create([
            'name' => 'Branch Manager',
            'slug' => 'branch-manager',
            'description' => 'Branch Manager can manage all aspects of a branch'
        ]);

        Role::create([
            'name' => 'Outlet Manager',
            'slug' => 'outlet-manager',
            'description' => 'Outlet Manager can manage specific outlet operations'
        ]);

        // Create customer roles
        Role::create([
            'name' => 'Personal Customer',
            'slug' => 'personal-customer',
            'description' => 'Individual customer account'
        ]);

        Role::create([
            'name' => 'Business Customer',
            'slug' => 'business-customer',
            'description' => 'Business customer account'
        ]);
    }
}




