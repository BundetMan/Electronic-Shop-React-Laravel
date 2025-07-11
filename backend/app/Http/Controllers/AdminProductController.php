<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return  response()->json([
            'message' => 'Product list retrieved successfully',
            'products' => Product::with('images')->where('status', 'available')->paginate(8)
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'discount' => 'nullable|numeric|min:0|max:100',
            'rating' => 'nullable|numeric|min:0|max:5',
            'status' => 'required|text|in:available,unavailable,deleted'
        ]);

        $product = Product::create($validator);
        if($request->hasFile('image')) {
            foreach($request->file('image') as $image) {
                $imagePath = $image->store('images', 'public');
                $product->images()->create(['url' => $imagePath]);
            }
        }
        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product->load('images')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // $product = Product::findOrFail($product->id);
        return response()->json($product->load('images'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validator = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'discount' => 'nullable|numeric|min:0|max:100',
            'rating' => 'nullable|numeric|min:0|max:5',
            'status' => 'required|boolean'
        ]);
        $product->update($validator);
        if($request->hasFile('image')) {
            foreach($request->file('image') as $image) {
                $imagePath = $image->store('images', 'public');
                $product->images()->create(['url' => $imagePath]);
            }
        }
        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product->load('images')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->update(['status' => 'deleted']);
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
