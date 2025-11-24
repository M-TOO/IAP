<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Vendor extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'owner_name',
        'shop_description',
        'email',
        'password',
        'national_id',
        'phone_number',
        'location_description',
        'status',
        'suspended_until', // Added for suspension logic
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'suspended_until' => 'datetime', // Cast as datetime
        'password' => 'hashed',
    ];

    public function spareParts()
    {
        return $this->hasMany(SparePart::class);
    }
}