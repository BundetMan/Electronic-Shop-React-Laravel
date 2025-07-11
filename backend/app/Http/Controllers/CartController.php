<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        
        $cartItems = CartItem::where('user_id', Auth::id())->with('product.images')->get();
        return response()->json($cartItems);
    }
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItem::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();
        if ($item) {
            $item->quantity += $request->quantity;
            $item->save();
        }
        else {
            $item = CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => Product::find($request->product_id)->price,
            ]);
        }

        return response()->json([
            'message' => 'Item added to cart successfully',
            'cart_item' => $item
        ]);
    }

    public function show($id)
    {
        $item = CartItem::where('user_id', Auth::id())
            ->where('id', $id)
            ->with('product')
            ->firstOrFail();

        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItem::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $item->quantity = $request->quantity;
        $item->save();

        return response()->json([
            'message' => 'Cart item updated successfully',
            'cart_item' => $item
        ]);
    }

    public function destroy($id)
    {
        $item = CartItem::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $item->delete();

        return response()->json([
            'message' => 'Cart item removed successfully'
        ]);
    }
}

