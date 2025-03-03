<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\FilamentManager;
use App\Extensions\FilamentManager as CustomFilamentManager;

class FilamentBindingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(FilamentManager::class, function ($app) {
            return new CustomFilamentManager();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}