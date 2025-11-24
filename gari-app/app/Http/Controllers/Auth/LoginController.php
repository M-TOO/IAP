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
    public function create() { 
        return view('auth.login')->layout('layouts.app'); 
    }

    public function createVendor() { 
        return view('auth.vendor_login')->layout('layouts.app'); 
    }

    public function createAdmin() { 
        return view('auth.admin_login')->layout('layouts.app'); 
    }

    public function store(Request $request)
    {
        // 1. Validate input (including our new login_type)
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'login_type' => ['nullable', 'string'], // Allow generic login if missing
        ]);

        $type = $request->input('login_type', 'customer'); // Default to customer if not specified

        /**
         * -----------------------------------------------------
         * CASE A: VENDOR LOGIN
         * -----------------------------------------------------
         */
        if ($type === 'vendor') {
            $vendor = Vendor::where('email', $credentials['email'])->first();

            if ($vendor) {
                // Suspension Check
                if ($vendor->suspended_until && $vendor->suspended_until->isFuture()) {
                    return back()->withErrors([
                        'email' => 'Account suspended until ' . $vendor->suspended_until->format('M d, Y H:i'),
                    ])->onlyInput('email');
                }

                // Approval Check
                if ($vendor->status !== 'approved') {
                    return back()->withErrors([
                        'email' => "Account status: {$vendor->status}. Please await approval.",
                    ])->onlyInput('email');
                }

                // Password Check
                if (Hash::check($credentials['password'], $vendor->password)) {
                    Auth::login($vendor);
                    $request->session()->regenerate();
                    return redirect()->route('vendor.dashboard');
                }
            }
            
            // Failed Vendor Login
            return back()->withErrors(['email' => 'Invalid vendor credentials.'])->onlyInput('email');
        }

        /**
         * -----------------------------------------------------
         * CASE B: CUSTOMER & ADMIN LOGIN (Users Table)
         * -----------------------------------------------------
         */
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $user = Auth::user();

            // Suspension Check
            if ($user->suspended_until && $user->suspended_until->isFuture()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'email' => 'Account suspended until ' . $user->suspended_until->format('M d, Y H:i'),
                ])->onlyInput('email');
            }

            // Role Enforcement: Admin trying to login via Customer form?
            if ($type === 'customer' && $user->role === 'admin') {
                // Option A: Redirect them to admin dashboard anyway
                // Option B: Logout and show error. 
                // We will redirect them correctly to avoid confusion.
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }

            // Role Enforcement: Customer trying to login via Admin form?
            if ($type === 'admin' && $user->role !== 'admin') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors(['email' => 'Access denied. This area is for Administrators only.']);
            }

            // Successful login → regenerate session
            $request->session()->regenerate();

            // Final Redirect based on Role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('customer.dashboard');
        }

        /**
         * -----------------------------------------------------
         * FAILED LOGIN (Generic)
         * -----------------------------------------------------
         */
        return back()->withErrors([
            'email' => 'Wrong username or password input.',
        ])->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}