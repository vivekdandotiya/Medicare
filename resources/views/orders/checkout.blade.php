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
                    
                    <!-- Hidden coupon code input -->
                    <input type="hidden" name="coupon_code" id="coupon_code_hidden" value="">

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
                            id="place_order_btn"
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

                <!-- Offer Code Section -->
                <div class="border-t border-gray-50 pt-4 mb-4">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Have a Coupon / Offer Code?</label>
                    <div class="flex gap-2">
                        <input type="text" 
                               id="coupon_code_input" 
                               placeholder="Enter code (e.g. 123)" 
                               class="flex-1 border border-gray-200 rounded-lg px-3 py-1.5 text-xs focus:border-teal-500 focus:ring-teal-500 uppercase">
                        <button type="button" 
                                id="apply_coupon_btn" 
                                class="bg-teal-600 hover:bg-teal-700 text-white font-semibold text-xs px-4 py-2 rounded-lg transition shadow-sm whitespace-nowrap">
                            Apply
                        </button>
                    </div>
                    <div id="coupon_message" class="text-xs mt-2 hidden"></div>
                </div>

                <div class="border-t border-gray-50 pt-4 flex flex-col gap-3">
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Items Price</span>
                        <span>₹{{ number_format($cart->subtotal, 2) }}</span>
                    </div>

                    <div id="discount_row" class="justify-between text-xs text-green-600 font-semibold hidden">
                        <span>Discount (10% Off)</span>
                        <span id="discount_amount">-₹0.00</span>
                    </div>

                    @php
                        $shipping = $cart->subtotal >= 500 ? 0 : 50;
                        $grandTotal = $cart->subtotal + $shipping;
                    @endphp

                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Delivery</span>
                        <span id="delivery_amount">
                            @if($shipping == 0)
                                <span class="text-green-600 font-semibold">FREE</span>
                            @else
                                ₹{{ number_format($shipping, 2) }}
                            @endif
                        </span>
                    </div>

                    <div class="border-t border-gray-100 pt-4 mt-2 flex justify-between items-baseline">
                        <span class="font-bold text-gray-900">Total Payable</span>
                        <span class="font-extrabold text-teal-700 text-xl" id="total_payable">₹{{ number_format($grandTotal, 2) }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Coupon Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const couponInput = document.getElementById('coupon_code_input');
            const applyBtn = document.getElementById('apply_coupon_btn');
            const couponMessage = document.getElementById('coupon_message');
            const hiddenCouponInput = document.getElementById('coupon_code_hidden');
            
            const subtotal = {{ $cart->subtotal }};
            
            // Elements to update
            const discountRow = document.getElementById('discount_row');
            const discountAmountEl = document.getElementById('discount_amount');
            const deliveryAmountEl = document.getElementById('delivery_amount');
            const totalPayableEl = document.getElementById('total_payable');
            const submitBtn = document.getElementById('place_order_btn');
            
            let appliedCode = '';
            
            function updatePrices() {
                let discount = 0;
                if (appliedCode === '123' || appliedCode === '1234') {
                    discount = subtotal * 0.10;
                }
                
                const discountedSubtotal = subtotal - discount;
                const shipping = discountedSubtotal >= 500 ? 0 : 50;
                const total = discountedSubtotal + shipping;
                
                // Update UI
                if (discount > 0) {
                    discountRow.classList.remove('hidden');
                    discountRow.classList.add('flex');
                    discountAmountEl.textContent = '-₹' + discount.toFixed(2);
                } else {
                    discountRow.classList.remove('flex');
                    discountRow.classList.add('hidden');
                }
                
                if (shipping === 0) {
                    deliveryAmountEl.innerHTML = '<span class="text-green-600 font-semibold">FREE</span>';
                } else {
                    deliveryAmountEl.textContent = '₹' + shipping.toFixed(2);
                }
                
                totalPayableEl.textContent = '₹' + total.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                if (submitBtn) {
                    submitBtn.textContent = 'Place Order (₹' + total.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ')';
                }
            }
            
            applyBtn.addEventListener('click', function () {
                if (appliedCode) {
                    // Remove code
                    appliedCode = '';
                    hiddenCouponInput.value = '';
                    couponInput.value = '';
                    couponInput.disabled = false;
                    applyBtn.textContent = 'Apply';
                    applyBtn.className = 'bg-teal-600 hover:bg-teal-700 text-white font-semibold text-xs px-4 py-2 rounded-lg transition shadow-sm';
                    couponMessage.classList.add('hidden');
                    updatePrices();
                    return;
                }
                
                const enteredCode = couponInput.value.trim();
                if (!enteredCode) {
                    couponMessage.textContent = 'Please enter a code.';
                    couponMessage.className = 'text-xs mt-2 text-red-600';
                    couponMessage.classList.remove('hidden');
                    return;
                }
                
                if (enteredCode === '123' || enteredCode === '1234') {
                    appliedCode = enteredCode;
                    hiddenCouponInput.value = enteredCode;
                    couponInput.disabled = true;
                    applyBtn.textContent = 'Remove';
                    applyBtn.className = 'bg-red-600 hover:bg-red-700 text-white font-semibold text-xs px-4 py-2 rounded-lg transition shadow-sm';
                    couponMessage.textContent = 'Coupon applied successfully! 10% discount applied.';
                    couponMessage.className = 'text-xs mt-2 text-green-600 font-semibold';
                    couponMessage.classList.remove('hidden');
                    updatePrices();
                } else {
                    couponMessage.textContent = 'Invalid offer code.';
                    couponMessage.className = 'text-xs mt-2 text-red-600';
                    couponMessage.classList.remove('hidden');
                }
            });
        });
    </script>
</x-app-layout>
