<?php

namespace App\Models;

// NOTE: Use App\Models\User; if you are on an older Laravel/Livewire setup, or App\Livewire; if using a fresh L11/Livewire 3 setup.

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * We explicitly include 'role' to ensure customers are saved as 'customer'.
     * 'name', 'email', 'password' are the form fields.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Added for Admin/Customer distinction
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel automatically hashes the password
    ];
}