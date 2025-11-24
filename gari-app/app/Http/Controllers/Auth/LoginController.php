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
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        /**
         * -----------------------------------------------------
         * 1. CUSTOMER / ADMIN LOGIN
         * -----------------------------------------------------
         */
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Suspension check
            if ($user->suspended_until && $user->suspended_until->isFuture()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Your account is suspended until ' . 
                               $user->suspended_until->format('M d, Y H:i'),
                ])->onlyInput('email');
            }

            // Successful login → regenerate session
            $request->session()->regenerate();

            // Role-based dashboard redirect
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('customer.dashboard');
        }

        /**
         * -----------------------------------------------------
         * 2. VENDOR LOGIN
         * -----------------------------------------------------
         */
        $vendor = Vendor::where('email', $credentials['email'])->first();

        if ($vendor) {

            // Vendor suspension
            if ($vendor->suspended_until && $vendor->suspended_until->isFuture()) {
                return back()->withErrors([
                    'email' => 'Your shop is suspended until ' . 
                               $vendor->suspended_until->format('M d, Y H:i'),
                ])->onlyInput('email');
            }

            // Vendor approval
            if ($vendor->status !== 'approved') {
                return back()->withErrors([
                    'email' => "Your vendor account status is: {$vendor->status}. Please await admin approval.",
                ])->onlyInput('email');
            }

            // Vendor password check
            if (Hash::check($credentials['password'], $vendor->password)) {
                Auth::login($vendor);
                $request->session()->regenerate();

                return redirect()->route('vendor.dashboard');
            }
        }

        /**
         * -----------------------------------------------------
         * 3. FAILED LOGIN
         * -----------------------------------------------------
         */
        return back()->withErrors([
            'email' => 'The provided credentials do not match any active account.',
        ])->onlyInput('email');
    }

    /**
     * -----------------------------------------------------
     * LOGOUT
     * -----------------------------------------------------
     */
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
