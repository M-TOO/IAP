<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class CustomerRegistrationController extends Controller
{
    // Show the Customer Registration form (replaces the Livewire render method)
    public function create()
    {
        // This view uses the base layout for styling consistency
        return view('auth.customer_register')->layout('layouts.app'); 
    }

    // Handle form submission and save the Customer
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // Handled by User model casting
            'role' => 'customer', 
        ]);

        // Log the user in
        Auth::login($user);

        // Redirect to the dashboard (we'll implement this route later)
        return redirect()->route('customer.dashboard')->with('success', 'Welcome to GARI! Your account is ready.');
    }
}