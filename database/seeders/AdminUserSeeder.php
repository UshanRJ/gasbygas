<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create a Branch Manager admin user
        $branchManager = User::create([
            'first_name' => 'Branch',
            'last_name' => 'Manager',
            'email' => 'branch.manager@example.com',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
        ]);
        
        $branchManager->roles()->attach(Role::where('slug', 'branch-manager')->first());

        // Create an Outlet Manager admin user
        $outletManager = User::create([
            'first_name' => 'Outlet',
            'last_name' => 'Manager',
            'email' => 'outlet.manager@example.com',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
        ]);
        
        $outletManager->roles()->attach(Role::where('slug', 'outlet-manager')->first());
    }
}
