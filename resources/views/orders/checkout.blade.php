<x-app-layout>
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('cart.index') }}" class="text-teal-600 hover:text-teal-700 font-semibold text-sm flex items-center gap-1">
                &larr; Return to Shopping Cart
            </a>
            <h1 class="text-3xl font-extrabold text-gray-900 mt-2">Secure Checkout</h1>
            <p class="text-gray-500 text-sm">Provide your delivery address and complete your purchase</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Checkout Form -->
            <div class="lg:col-span-2">
                <form action="{{ route('orders.store') }}" 
                      method="POST" 
                      enctype="multipart/form-data"
                      class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm flex flex-col gap-6">
                    @csrf

                    <!-- Phone Number -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Phone Number</label>
                        <input type="text" 
                               name="phone" 
                               value="{{ old('phone') }}" 
                               placeholder="e.g. +91 9876543210"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500"
                               required>
                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                    </div>

                    <!-- Shipping Address -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Delivery Shipping Address</label>
                        <textarea name="shipping_address" 
                                  rows="4" 
                                  placeholder="Enter complete address, landmark, state, pincode..."
                                  class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500"
                                  required>{{ old('shipping_address') }}</textarea>
                        <x-input-error :messages="$errors->get('shipping_address')" class="mt-1" />
                    </div>

                    <!-- Prescription upload if required -->
                    @if($prescriptionRequired)
                        <div class="bg-red-50 border border-red-100 rounded-xl p-5">
                            <div class="flex gap-3 mb-3">
                                <span class="p-2 bg-red-100 text-red-700 rounded-lg h-10 w-10 flex items-center justify-center shrink-0 font-bold text-sm">Rx</span>
                                <div>
                                    <h4 class="font-bold text-red-950 text-sm">Doctor's Prescription Required</h4>
                                    <p class="text-xs text-red-700 mt-0.5">One or more items in your cart require a valid prescription. Please upload a clear image or PDF copy (Max 2MB).</p>
                                </div>
                            </div>
                            
                            <input type="file" 
                                   name="prescription" 
                                   class="w-full bg-white border border-red-200 rounded-lg px-4 py-2.5 text-sm focus:border-red-500 focus:ring-red-500 file:bg-teal-50 file:border-none file:text-teal-700 file:text-xs file:font-semibold file:px-3 file:py-1 file:rounded-md cursor-pointer"
                                   required>
                            <x-input-error :messages="$errors->get('prescription')" class="mt-2" />
                        </div>
                    @endif

                    <!-- Payment Mode -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Payment Method</label>
                        <select name="payment_method" 
                                class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500"
                                required>
                            <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>Cash on Delivery (COD)</option>
                            <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>Online Card Payment (Mock Simulation)</option>
                        </select>
                        <x-input-error :messages="$errors->get('payment_method')" class="mt-1" />
                    </div>

                    <!-- Submit -->
                    <button type="submit" 
                            class="bg-teal-600 hover:bg-teal-700 text-white font-semibold text-center py-3.5 rounded-xl transition block shadow-md mt-4">
                        Place Order (₹{{ number_format($cart->subtotal + ($cart->subtotal >= 500 ? 0 : 50), 2) }})
                    </button>
                </form>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm h-fit">
                <h3 class="text-lg font-bold text-gray-900 border-b border-gray-50 pb-4 mb-4">Order Summary</h3>

                <!-- Items list -->
                <div class="flex flex-col gap-4 max-h-60 overflow-y-auto mb-6 pr-2">
                    @foreach($cart->items as $item)
                        <div class="flex justify-between items-center gap-3 text-sm">
                            <div class="truncate flex-1">
                                <span class="font-semibold text-gray-800">{{ $item->medicine->name }}</span>
                                <span class="text-xs text-gray-400 block">Qty: {{ $item->quantity }}</span>
                            </div>
                            <span class="font-bold text-gray-700 text-right">₹{{ number_format($item->medicine->selling_price * $item->quantity, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-50 pt-4 flex flex-col gap-3">
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Items Price</span>
                        <span>₹{{ number_format($cart->subtotal, 2) }}</span>
                    </div>

                    @php
                        $shipping = $cart->subtotal >= 500 ? 0 : 50;
                        $grandTotal = $cart->subtotal + $shipping;
                    @endphp

                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Delivery</span>
                        <span>
                            @if($shipping == 0)
                                <span class="text-green-600 font-semibold">FREE</span>
                            @else
                                ₹{{ number_format($shipping, 2) }}
                            @endif
                        </span>
                    </div>

                    <div class="border-t border-gray-100 pt-4 mt-2 flex justify-between items-baseline">
                        <span class="font-bold text-gray-900">Total Payable</span>
                        <span class="font-extrabold text-teal-700 text-xl">₹{{ number_format($grandTotal, 2) }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
