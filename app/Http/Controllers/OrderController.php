<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin') || $user->hasRole('staff')) {
            $orders = Order::with(['user', 'items.medicine'])->latest()->paginate(15);
        } else {
            $orders = Order::with('items.medicine')->where('user_id', $user->id)->latest()->paginate(10);
        }

        return view('orders.index', compact('orders'));
    }

    public function checkout()
    {
        $user = Auth::user();
        $cart = Cart::with('items.medicine')->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Check if any medicine in cart requires prescription
        $prescriptionRequired = $cart->items->contains(function ($item) {
            return $item->medicine->prescription_required;
        });

        return view('orders.checkout', compact('cart', 'prescriptionRequired'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::with('items.medicine')->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $prescriptionRequired = $cart->items->contains(function ($item) {
            return $item->medicine->prescription_required;
        });

        // Validation rules
        $rules = [
            'shipping_address' => 'required|string|max:1000',
            'phone' => 'required|string|max:15',
            'payment_method' => 'required|in:cod,online',
            'coupon_code' => 'nullable|string|max:50',
        ];

        if ($prescriptionRequired) {
            $rules['prescription'] = 'required|file|mimes:jpeg,png,jpg,pdf|max:2048';
        }

        $request->validate($rules);

        // Verify stock for all items
        foreach ($cart->items as $item) {
            if ($item->medicine->stock_quantity < $item->quantity) {
                return redirect()->route('cart.index')->with('error', "Sorry, {$item->medicine->name} is out of stock or does not have enough stock available.");
            }
        }

        // Handle prescription file upload
        $prescriptionPath = null;
        if ($prescriptionRequired && $request->hasFile('prescription')) {
            $file = $request->file('prescription');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/prescriptions'), $filename);
            $prescriptionPath = 'uploads/prescriptions/' . $filename;
        }

        // Calculate discount and shipping
        $subtotal = $cart->subtotal;
        $discount = 0;
        $couponCode = $request->input('coupon_code');
        if ($couponCode === 'HEALTH20') {
            $discount = $subtotal * 0.20;
        } elseif ($couponCode === 'MEDICARE10' || $couponCode === '123') {
            $discount = $subtotal * 0.10;
        } elseif ($couponCode === 'WELCOME50') {
            $discount = min(50, $subtotal);
        }

        $discountedSubtotal = $subtotal - $discount;
        $shipping = $discountedSubtotal >= 500 ? 0 : 50;
        $totalAmount = $discountedSubtotal + $shipping;

        // Create Order
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total_amount' => $totalAmount,
            'shipping_address' => $request->shipping_address,
            'phone' => $request->phone,
            'prescription_path' => $prescriptionPath,
            'payment_method' => $request->payment_method,
            'coupon_code' => $couponCode ?: null,
            'discount_amount' => $discount,
        ]);

        // Create Order Items and decrease stock
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'medicine_id' => $item->medicine_id,
                'quantity' => $item->quantity,
                'price' => $item->medicine->selling_price,
            ]);

            // Deduct stock
            $item->medicine->decrement('stock_quantity', $item->quantity);
        }

        // Clear cart
        $cart->items()->delete();

        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $user = Auth::user();
        if (!$user->hasRole('admin') && !$user->hasRole('staff')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        // If cancelling, restock the medicines
        if ($request->status === 'cancelled' && $order->status !== 'cancelled') {
            foreach ($order->items as $item) {
                $item->medicine->increment('stock_quantity', $item->quantity);
            }
        }

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }
}
