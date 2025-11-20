<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;

class VendorRegistrationController extends Controller
{
    // Show the Vendor Registration form
    public function create()
    {
        return view('auth.vendor_register')->layout('layouts.app');
    }

    // Handle form submission and save the Vendor with PENDING status
    public function store(Request $request)
    {
        $request->validate([
            'owner_name' => ['required', 'string', 'max:255'],
            'shop_description' => ['required', 'string', 'max:500'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:vendors'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'national_id' => ['required', 'string', 'max:20', 'unique:vendors'],
            'phone_number' => ['required', 'string', 'max:15'],
            'location_description' => ['required', 'string', 'max:255'],
        ]);

        Vendor::create([
            'owner_name' => $request->owner_name,
            'shop_description' => $request->shop_description,
            'email' => $request->email,
            'password' => $request->password,
            'national_id' => $request->national_id,
            'phone_number' => $request->phone_number,
            'location_description' => $request->location_description,
            'status' => 'pending_approval', 
        ]);

        // Redirect to a waiting page, as the vendor cannot log in yet
        return redirect()->route('vendor.wait')->with('success_message', 'Registration successful! Your account is pending admin approval.');
    }
}