<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomerRegistrationController; 
use App\Http\Controllers\Auth\VendorRegistrationController;
use App\Http\Controllers\Auth\LoginController;

// --- 1. LANDING PAGE ---
Route::get('/', function () {
    return view('welcome');
});

// --- 2. AUTHENTICATION (UNPROTECTED) ---

// LOGIN ROUTES
// Generic Entry Point (for Customer)
Route::get('/login', [LoginController::class, 'create'])->name('login');      
// Dedicated Vendor Entry Point
Route::get('/vendor/login', [LoginController::class, 'createVendor'])->name('vendor.login'); 
// 🔑 NEW: Dedicated Admin Entry Point
Route::get('/admin/login', [LoginController::class, 'createAdmin'])->name('admin.login'); 

// Login Submission Handler (Shared by all)
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
    
    // Redirect target after registration
    Route::get('/wait', function () {
        return view('vendor.wait');
    })->name('wait');
});


// --- 3. PROTECTED ROUTES (Requires user to be logged in) ---
Route::middleware('auth')->group(function () {

    // LOGOUT ROUTE
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    
    // CUSTOMER DASHBOARD
    Route::get('/customer/dashboard', function () {
        return view('customer.dashboard');
    })->name('customer.dashboard');

    // VENDOR DASHBOARD (Must be logged in, will add status check logic later)
    Route::get('/vendor/dashboard', function () {
        return view('vendor.dashboard'); 
    })->name('vendor.dashboard');

    // 🔑 NEW: ADMIN DASHBOARD 
    Route::get('/admin/dashboard', function () {
        // Basic check to ensure only admins can view this page
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->withErrors(['error' => 'Unauthorized access.']);
        }
        return view('admin.dashboard'); // Placeholder view
    })->name('admin.dashboard');

});