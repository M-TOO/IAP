<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;      
use App\Models\Vendor;    

class LoginController extends Controller
{
    // Show the generic login form (for Customer/Admin)
    public function create()
    {
        return view('auth.login')->layout('layouts.app'); 
    }

    // 🔑 NEW METHOD: Show the Vendor-specific login form
    public function createVendor()
    {
        return view('auth.vendor_login')->layout('layouts.app');
    }

    // Handle authentication attempt for all user types
    public function store(Request $request)
    {
        // 🔑 Validate using unique 'email' address
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // ----------------------------------------
        // 1. ATTEMPT CUSTOMER/ADMIN LOGIN (Users Table)
        // ----------------------------------------
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role === 'customer') {
                $request->session()->regenerate();
                return redirect()->route('customer.dashboard');
            }
            
            if ($user->role === 'admin') {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard'); // Will be implemented next
            }
        }
        
        // ----------------------------------------
        // 2. ATTEMPT VENDOR LOGIN (Vendors Table)
        // ----------------------------------------
        $vendor = Vendor::where('email', $credentials['email'])->first();

        if ($vendor) {
            
            // 2a. Check Vendor Status (Must be Approved)
            if ($vendor->status !== 'approved') {
                $status_message = str_replace('_', ' ', $vendor->status);
                
                return back()->withErrors([
                    'email' => "Your vendor account status is: **{$status_message}**. Please await admin approval.",
                ])->onlyInput('email');
            }
            
            // 2b. Check Vendor Password and log them in
            if (Hash::check($credentials['password'], $vendor->password)) {
                
                // Log in the Vendor (using the default 'web' guard)
                Auth::login($vendor); 
                $request->session()->regenerate();
                
                return redirect()->route('vendor.dashboard'); // Placeholder route
            }
        }

        // ----------------------------------------
        // 3. FAILED LOGIN
        // ----------------------------------------
        return back()->withErrors([
            'email' => 'The provided credentials do not match any active account.',
        ])->onlyInput('email');
    }

    // Handle user logout
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
    
    public function createAdmin()
    {
        // We will create this specific view file next
        return view('auth.admin_login')->layout('layouts.app'); 
    }
}