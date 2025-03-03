<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {

        // Modify existing users table or create it if it doesn't exist
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name')->nullable();
                $table->string('email')->unique();
                $table->string('password');
                $table->string('address')->nullable();
                $table->string('mobile')->nullable();
                $table->enum('user_type', ['admin', 'personal', 'business'])->default('personal');
                $table->string('nic')->nullable();
                $table->string('business_id')->nullable();
                $table->string('certificate')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'user_type')) {
                    $table->enum('user_type', ['admin', 'personal', 'business'])->default('personal');
                }
                if (!Schema::hasColumn('users', 'first_name')) {
                    $table->string('first_name')->after('id');
                }
                if (!Schema::hasColumn('users', 'last_name')) {
                    $table->string('last_name')->nullable()->after('first_name');
                }
                if (!Schema::hasColumn('users', 'address')) {
                    $table->string('address')->nullable();
                }
                if (!Schema::hasColumn('users', 'mobile')) {
                    $table->string('mobile')->nullable();
                }
                if (!Schema::hasColumn('users', 'nic')) {
                    $table->string('nic')->nullable();
                }
                if (!Schema::hasColumn('users', 'business_id')) {
                    $table->string('business_id')->nullable();
                }
                if (!Schema::hasColumn('users', 'certificate')) {
                    $table->string('certificate')->nullable();
                }
            });
        }

        // Create roles table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Create role_user pivot table
        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Create permissions table
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Create permission_role pivot table
        Schema::create('permission_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

    }


    public function down(): void
    {
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
        // We don't drop the users table in down() to prevent data loss
    }
};