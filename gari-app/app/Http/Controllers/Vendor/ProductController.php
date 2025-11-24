<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // 🔑 IMPORT AUTH

class ProductController extends Controller
{
    public function index()
    {
        // 🟢 FIX: Get products ONLY for the currently logged-in vendor
        $vendorId = Auth::id(); 
        $products = Product::where('vendor_id', $vendorId)->get();
        
        return view('vendor.dashboard', compact('products'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        Log::info('🎯 PRODUCT CONTROLLER STORE METHOD REACHED!');

        try {
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

            // 🟢 FIX: Use Auth::id() instead of hardcoded '1'
            $product = Product::create([
                'vendor_id' => Auth::id(), 
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'car_brand' => $validated['car_brand'],
                'stock_quantity' => $validated['stock_quantity'],
                'image' => $imagePath,
                'is_available' => $request->has('is_available')
            ]);

            Log::info('Product created with ID: ' . $product->id);

            return redirect()->route('vendor.dashboard')->with('success', "Product added successfully!");

        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            return back()->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            // 🟢 FIX: Ensure vendor can only delete THEIR OWN products
            $product = Product::where('id', $id)
                              ->where('vendor_id', Auth::id()) 
                              ->firstOrFail();
            
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();

            return redirect()->route('vendor.dashboard')->with('success', 'Product deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->route('vendor.dashboard')->with('error', 'Failed to delete product.');
        }
    }
    
    // Add empty methods for edit/update/show if needed to prevent errors
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
}
