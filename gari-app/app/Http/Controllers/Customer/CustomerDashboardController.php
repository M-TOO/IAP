<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product; // 🔑 FIX: Use Product, NOT SparePart

class CustomerDashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Query the 'products' table
        $query = Product::with('vendor'); 

        // 2. Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 3. Brand Filter
        if ($request->filled('brand') && $request->brand !== 'All Brands') {
            $query->where('car_brand', $request->brand);
        }
        
        // 4. Only show available products
        $query->where('is_available', true);

        // 5. Paginate Results
        $parts = $query->latest()->paginate(12)->withQueryString();

        return view('customer.dashboard', [
            'user' => Auth::user(),
            'parts' => $parts, // Variable name 'parts' is kept for view compatibility
            'search' => $request->query('search'),
            'brand' => $request->query('brand'),
        ]);
    }
}
