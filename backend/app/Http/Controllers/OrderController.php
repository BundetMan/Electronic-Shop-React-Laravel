<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)
        ->with('items.product')
        ->orderBy('created_at', 'desc')
        ->get();

        if ($order->user_id !== Auth::id()) {
        abort(403);
    }
        return response()->json($order);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();
        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        //use transaction to advoid partial insert
        DB::beginTransaction();
        try {
            $totalAmount = 0;
            foreach ($cartItems as $item) {
                $price = $items->price ?? $item->product->price;
                $discount = $items->discount ?? $item->product->discount ?? 0;
                $itemTotal = $item->quantity * $price * (1 - $discount / 100);
                $totalAmount += $itemTotal;
            }

            // Create the order
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price ?? $item->product->price,
                    'discount' => $item->discount ?? $item->product->discount ?? 0,
                ]);
            }

            // Clear the cart
            CartItem::where('user_id', $user->id)->delete();
            DB::commit();
            return response()->json([
                'message' => 'Order placed successfully',
                'order' => $order->load('items.product')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to place order', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)
            ->where('id', $order->id)
            ->with('items.product')
            ->firstOrFail();
        return response()->json($order);
    }

    
}
