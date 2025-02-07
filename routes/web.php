<?php

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
use Illuminate\Support\Facades\Route;

Route::get("/", HomePage::class );
Route::get("/ordergas", OrderGasPage::class );
Route::get("/categories", CategoriesPage::class );
Route::get("/products", ProductsPage::class );

Route::get("/myorders", MyOrdersPage::class );
Route::get("/myorders/{order}", MyOrderDetailPage::class );

Route::get("/login", LoginPage::class );
Route::get("/signup", RegisterPage::class );
Route::get("/forgot", ForgotPasswordPage::class );
Route::get("/reset", ResetPasswordPage::class );

Route::get("/success", SuccessPage::class );
Route::get("/cancel", CancelPage::class );