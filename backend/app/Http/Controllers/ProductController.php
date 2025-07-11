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
        return  response()->json([
            'message' => 'Product list retrieved successfully',
            'products' => Product::with('images')->where('status', 'available')->paginate(8)
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // $product = Product::findOrFail($product->id);
        return response()->json($product->load('images'));
    }

}
