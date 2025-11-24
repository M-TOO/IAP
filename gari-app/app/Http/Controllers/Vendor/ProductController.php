<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; 

class ProductController extends Controller
{
    public function index()
    {
        $vendorId = Auth::id(); 
        $products = Product::where('vendor_id', $vendorId)->get();
        
        return view('vendor.dashboard', compact('products'));
    }

    // 🟢 NEW: Returns the separate Add Product page
    public function create()
    {
        return view('vendor.products.create');
    }

    public function store(Request $request)
    {
        // ... (Keep existing validation & logic) ...
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'car_brand' => 'required|in:Ford,Toyota,Nissan,Mazda,Honda,Mercedes,BMW,Audi',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'vendor_id' => Auth::id(), 
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'car_brand' => $validated['car_brand'],
            'stock_quantity' => $validated['stock_quantity'],
            'image' => $imagePath,
            'is_available' => $request->has('is_available')
        ]);

        // Redirect back to dashboard to see the list
        return redirect()->route('vendor.dashboard')->with('success', "Product added successfully!");
    }

    public function destroy(string $id)
    {
        // ... (Keep existing logic) ...
        try {
            $product = Product::where('id', $id)->where('vendor_id', Auth::id())->firstOrFail();
            
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->delete();
            return redirect()->route('vendor.dashboard')->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('vendor.dashboard')->with('error', 'Failed to delete product.');
        }
    }
}