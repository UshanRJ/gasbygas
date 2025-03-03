<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Filament::serving(function () {
            // Tell Filament to use first_name as the user's name
            if (auth()->check()) {
                Filament::registerUserMenuItems([
                    'account' => \Filament\Pages\Actions\ActionGroup::make([
                        \Filament\Pages\Actions\Action::make('account')
                            ->label(fn (): string => auth()->user()->first_name ?? 'Account')
                            ->url(route('filament.admin.pages.dashboard')),
                    ])->label(fn (): string => auth()->user()->first_name ?? 'Account'),
                ]);
            }
        });
    }
}