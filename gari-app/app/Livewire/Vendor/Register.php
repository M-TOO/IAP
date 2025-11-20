<?php

namespace App\Livewire\Vendor;

use Livewire\Component;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Register extends Component
{
    // Properties matching the form fields and Vendor model
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $national_id = '';
    public $phone_number = '';
    public $location_description = '';

    // Validation rules (National ID and Email must be unique)
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:vendors',
        'password' => 'required|string|min:8|confirmed',
        'national_id' => 'required|string|max:20|unique:vendors',
        'phone_number' => 'required|string|max:15',
        'location_description' => 'required|string|max:255',
    ];

    public function register()
    {
        $this->validate();

        Vendor::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password, // Hashing is handled by the Vendor Model cast
            'national_id' => $this->national_id,
            'phone_number' => $this->phone_number,
            'location_description' => $this->location_description,
            'status' => 'pending_approval', // 🔑 CRUCIAL MVP STEP: Default status
        ]);
        
        // 🔑 IMPORTANT: Vendor must wait for admin approval. We redirect to a waiting page.
        session()->flash('success_message', 'Registration successful! Your account is pending admin approval.');
        return redirect()->to('/vendor/wait'); // Route we will define later
    }

    public function render()
    {
        return view('livewire.vendor.register')->layout('layouts.app', ['title' => 'Vendor Registration']);
    }
}