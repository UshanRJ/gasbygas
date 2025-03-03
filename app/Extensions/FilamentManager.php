<?php

namespace App\Extensions;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Filament\FilamentManager as BaseFilamentManager;

class FilamentManager extends BaseFilamentManager
{
    public function getUserName(Authenticatable|Model $user): string
    {
        // Return a non-null value no matter what
        return $user->first_name ?? 'Admin';
    }
}