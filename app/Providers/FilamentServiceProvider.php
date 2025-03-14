<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Panel;

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
        // Configure panel to hide documentation link
        Panel::configureUsing(function (Panel $panel): void {
            $panel->showDocumentationLink(false);
        });

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