<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable; // Treat Vendor as Authenticatable for easy login

class Vendor extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'owner_name',
        'shop_description',
        'email',
        'password',
        'national_id',
        'phone_number',
        'location_description',
        'status', // Initial status is 'pending_approval'
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'approved_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Define the relationship: A Vendor has many Spare Parts.
     */
    public function spareParts()
    {
        return $this->hasMany(SparePart::class);
    }
}