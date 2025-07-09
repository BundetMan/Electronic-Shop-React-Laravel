<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return  response()->json(Product::with('images')->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //no need to create a form in API context
        //API typically uses JSON for data exchange, not HTML forms
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'discount' => 'nullable|numeric|min:0|max:100',
            'rating' => 'nullable|numeric|min:0|max:5',
            'status' => 'required|boolean'
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
        $product = Product::findOrFail($product->id);
        return response()->json($product->load('images'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // No need to create a form in API context
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $product = Product::findOrFail($product->id);
        $validator = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
        $product = Product::findOrFail($product->id);
        $product->images()->delete(); // Delete associated images
        $product->delete(); // Delete the product
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
