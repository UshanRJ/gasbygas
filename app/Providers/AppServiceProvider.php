<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Schedule;
use App\Models\Order;
use App\Models\OrderItem;
use App\Observers\ScheduleObserver;
use App\Observers\OrderObserver;
use App\Observers\OrderItemObserver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
        /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];
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

        // Role blade directive
        Blade::directive('role', function ($role) {
            return "<?php if(auth()->check() && auth()->user()->hasRole({$role})): ?>";
        });
        
        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });
        
        // Multiple roles blade directive
        Blade::directive('hasanyrole', function ($roles) {
            return "<?php if(auth()->check() && auth()->user()->hasAnyRole({$roles})): ?>";
        });
        
        Blade::directive('endhasanyrole', function () {
            return "<?php endif; ?>";
        });
        
        // Permission blade directive
        Blade::directive('permission', function ($permission) {
            return "<?php if(auth()->check() && auth()->user()->hasPermission({$permission})): ?>";
        });
        
        Blade::directive('endpermission', function () {
            return "<?php endif; ?>";
        });
        
        // User type blade directive
        Blade::directive('usertype', function ($type) {
            return "<?php if(auth()->check() && auth()->user()->user_type == {$type}): ?>";
        });
        
        Blade::directive('endusertype', function () {
            return "<?php endif; ?>";
        });

        $this->registerPolicies();

        // Define gates for roles
        Gate::define('branch-manager', function (User $user) {
            return $user->hasRole('branch-manager');
        });

        Gate::define('outlet-manager', function (User $user) {
            return $user->hasRole('outlet-manager');
        });

        Gate::define('personal-customer', function (User $user) {
            return $user->hasRole('personal-customer');
        });

        Gate::define('business-customer', function (User $user) {
            return $user->hasRole('business-customer');
        });

        // Define gates for permissions
        Gate::define('manage-users', function (User $user) {
            return $user->hasPermission('manage-users');
        });

        Gate::define('manage-products', function (User $user) {
            return $user->hasPermission('manage-products');
        });

        Gate::define('manage-orders', function (User $user) {
            return $user->hasPermission('manage-orders');
        });

        Gate::define('manage-settings', function (User $user) {
            return $user->hasPermission('manage-settings');
        });

        Gate::define('place-orders', function (User $user) {
            return $user->hasPermission('place-orders');
        });

        Gate::define('view-own-orders', function (User $user) {
            return $user->hasPermission('view-own-orders');
        });

        Gate::define('update-profile', function (User $user) {
            return $user->hasPermission('update-profile');
        });



    }
}
