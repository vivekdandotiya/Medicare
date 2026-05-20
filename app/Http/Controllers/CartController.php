<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cart = Cart::with(['items.medicine.category', 'items.medicine.brand'])
            ->firstOrCreate(['user_id' => $user->id]);

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, Medicine $medicine)
    {
        // Check if medicine is active/available
        if (!$medicine->status) {
            return redirect()->back()->with('error', 'This medicine is currently unavailable.');
        }

        // Validate stock
        if ($medicine->stock_quantity < 1) {
            return redirect()->back()->with('error', 'This medicine is out of stock.');
        }

        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('medicine_id', $medicine->id)
            ->first();

        $quantity = $request->input('quantity', 1);

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($newQuantity > $medicine->stock_quantity) {
                return redirect()->back()->with('error', 'Cannot add more items. Exceeds available stock.');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            if ($quantity > $medicine->stock_quantity) {
                return redirect()->back()->with('error', 'Cannot add items. Exceeds available stock.');
            }
            CartItem::create([
                'cart_id' => $cart->id,
                'medicine_id' => $medicine->id,
                'quantity' => $quantity,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Medicine added to cart.');
    }

    public function update(Request $request, CartItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $medicine = $item->medicine;
        $quantity = $request->quantity;

        if ($quantity > $medicine->stock_quantity) {
            return redirect()->back()->with('error', 'Requested quantity exceeds available stock.');
        }

        $item->update(['quantity' => $quantity]);

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    public function remove(CartItem $item)
    {
        // Ensure user owns this cart item
        if ($item->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $item->delete();

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
}
