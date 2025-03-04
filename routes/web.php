<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Original Livewire pages
use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\CancelPage;
use App\Livewire\CategoriesPage;
use App\Livewire\HomePage;
use App\Livewire\MyOrderDetailPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\OrderGasPage;
use App\Livewire\ProductsPage;
use App\Livewire\SuccessPage;
use App\Http\Controllers\OrderController;

// Role-based auth components
use App\Livewire\Customer\Personal\Dashboard as PersonalDashboard;
use App\Livewire\Customer\Business\Dashboard as BusinessDashboard;
use App\Livewire\Customer\Profile as CustomerProfile;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Original public routes
Route::get("/", HomePage::class)->name('home');
// In your routes/web.php file
Route::get("/ordergas", OrderGasPage::class)->middleware('auth');
Route::get("/categories", CategoriesPage::class);
Route::get("/products", ProductsPage::class);
Route::get("/success", SuccessPage::class);
Route::get("/cancel", CancelPage::class);

// Guest routes for authentication
Route::middleware('guest')->group(function () {
    // Original auth routes
    Route::get("/login", LoginPage::class)->name('login');
    Route::get("/signup", RegisterPage::class)->name('register');
    Route::get("/forgot", ForgotPasswordPage::class);
    Route::get("/reset", ResetPasswordPage::class)->name('password.request');
});

// Auth routes that require authentication
Route::middleware('auth')->group(function () {

    // Order routes
Route::middleware(['auth'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    // Add this to your web.php routes file
Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});

    
    // Customer dashboards without middleware for now
    Route::get('/personal/dashboard', PersonalDashboard::class)->name('personal.dashboard');
    Route::get('/business/dashboard', BusinessDashboard::class)->name('business.dashboard');
    Route::get('/customer/profile', CustomerProfile::class)->name('customer.profile');
    
    // Logout route
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});