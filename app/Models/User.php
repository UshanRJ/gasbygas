<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;


class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'address',
        'mobile',
        'user_type',
        'nic',
        'business_id',
        'certificate',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's name for Filament.
     */
    public function getFilamentName(): string
    {
        return $this->first_name ?: 'Admin';
    }

    /**
     * Ensure name is never null for Filament.
     * This is a crucial method!
     */
    public function name(): string
    {
        return $this->first_name ?: 'Admin';
    }

    /**
     * Ensure we have a fallback for the name attribute.
     */

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Relationship with roles
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    // Check if user has a specific role
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('slug', $role);
        }

        return $role->intersect($this->roles)->count() > 0;
    }

    // Check if user has any of the given roles
    public function hasAnyRole($roles)
    {
        if (is_string($roles)) {
            return $this->hasRole($roles);
        }

        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    // Check if user has all of the given roles
    public function hasAllRoles($roles)
    {
        if (is_string($roles)) {
            return $this->hasRole($roles);
        }

        foreach ($roles as $role) {
            if (!$this->hasRole($role)) {
                return false;
            }
        }

        return true;
    }

    // Check if user has permission through their roles
    public function hasPermission($permission)
    {
        return $this->roles->flatMap(function ($role) {
            return $role->permissions;
        })->contains('slug', $permission);
    }

    // Get full name
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . ($this->last_name ?? '');
    }

    // Check if user is admin
    public function isAdmin()
    {
        return $this->user_type === 'admin';
    }

    // Check if user is a personal customer
    public function isPersonalCustomer()
    {
        return $this->user_type === 'personal';
    }

    // Check if user is a business customer
    public function isBusinessCustomer()
    {
        return $this->user_type === 'business';
    }

    // Determine if the user can access the Filament admin panel
    public function canAccessPanel(Panel $panel): bool
    {
        // Just check if the user is an admin, don't require email verification
        return $this->isAdmin();
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }
}