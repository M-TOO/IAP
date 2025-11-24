<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

use App\Http\Controllers\Auth\CustomerRegistrationController; 
use App\Http\Controllers\Auth\VendorRegistrationController;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Customer\CustomerProfileController;
use App\Http\Controllers\Customer\CustomerDashboardController;

use App\Http\Controllers\Vendor\ProductController;

// ------------------------------------------------------
// 1. LANDING PAGE
// ------------------------------------------------------
Route::get('/', function () {
    return view('welcome');
});

// ------------------------------------------------------
// 2. AUTHENTICATION (UNPROTECTED)
// ------------------------------------------------------

// LOGIN ROUTES
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::get('/vendor/login', [LoginController::class, 'createVendor'])->name('vendor.login');
Route::get('/admin/login', [LoginController::class, 'createAdmin'])->name('admin.login');

// LOGIN SUBMISSION
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

// CUSTOMER REGISTRATION ROUTES
Route::prefix('register/customer')->name('customer.')->group(function () {
    Route::get('/', [CustomerRegistrationController::class, 'create'])->name('register');
    Route::post('/', [CustomerRegistrationController::class, 'store'])->name('store');
});

// VENDOR REGISTRATION ROUTES
Route::prefix('register/vendor')->name('vendor.')->group(function () {
    Route::get('/', [VendorRegistrationController::class, 'create'])->name('register');
    Route::post('/', [VendorRegistrationController::class, 'store'])->name('store');

    Route::get('/wait', function () {
        return view('vendor.wait');
    })->name('wait');
});

// ------------------------------------------------------
// 3. PROTECTED ROUTES (AUTH REQUIRED)
// ------------------------------------------------------
Route::middleware('auth')->group(function () {

    // LOGOUT
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // CUSTOMER DASHBOARD
    Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])
        ->name('customer.dashboard');

    // CUSTOMER PROFILE
    Route::get('/customer/profile', [CustomerProfileController::class, 'show'])
        ->name('customer.profile');
    Route::patch('/customer/profile', [CustomerProfileController::class, 'update'])
        ->name('customer.profile.update');
    Route::delete('/customer/profile', [CustomerProfileController::class, 'destroy'])
        ->name('customer.profile.destroy');
    Route::put('/customer/profile/password', [CustomerProfileController::class, 'updatePassword'])
        ->name('customer.profile.password');

    // ADMIN DASHBOARD
    Route::get('/admin/dashboard', function () {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->withErrors(['error' => 'Unauthorized access.']);
        }
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // --------------------------------------------------
    // ADMIN MANAGEMENT ROUTES
    // --------------------------------------------------

    // Vendor Approval Page
    Route::get('/admin/vendors', function () {
        if (Auth::user()->role !== 'admin') abort(403);

        return Blade::render('
            @extends("layouts.app")
            @section("title", "Vendor Management")
            @section("content")
                <h2 style="color:var(--color-vendor); text-align:center; margin-bottom:20px;">Vendor Management</h2>
                @livewire("admin.vendor-approval")
            @endsection
        ');
    })->name('admin.vendors');

    // User Moderation Page
    Route::get('/admin/users', function () {
        if (Auth::user()->role !== 'admin') abort(403);

        return Blade::render('
            @extends("layouts.app")
            @section("title", "User Moderation")
            @section("content")
                <h2 style="color:var(--color-accent); text-align:center; margin-bottom:20px;">User Moderation</h2>
                @livewire("admin.user-moderation")
            @endsection
        ');
    })->name('admin.users');

});

// ------------------------------------------------------
// 4. VENDOR ROUTES (UNPROTECTED IN YOUR VERSION)
// ------------------------------------------------------
Route::prefix('vendor')->name('vendor.')->group(function () {

    // Vendor Dashboard (ProductController index)
    Route::get('/dashboard', [ProductController::class, 'index'])->name('dashboard');
    Route::post('/vendor/dashboard', [ProductController::class, 'store']);

    // Product CRUD
    Route::resource('products', ProductController::class);
});
