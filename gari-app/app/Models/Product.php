<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// 🟢 IMPORT THE VENDOR MODEL
use App\Models\Vendor; 

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'name',
        'description', 
        'price',
        'car_brand',
        'image',
        'is_available',
        'stock_quantity'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean'
    ];

    // 🟢 CORRECTED RELATIONSHIP
    public function vendor()
    {
        // Now it looks in the 'vendors' table
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}