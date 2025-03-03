<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Schedule;
use App\Models\Order;
use App\Models\OrderItem;
use App\Observers\ScheduleObserver;
use App\Observers\OrderObserver;
use App\Observers\OrderItemObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schedule::observe(ScheduleObserver::class);
        Order::observe(OrderObserver::class);
        OrderItem::observe(OrderItemObserver::class);
    }
}
