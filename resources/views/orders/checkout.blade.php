<x-app-layout>
    <div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('cart.index') }}" class="text-teal-650 hover:text-teal-850 font-bold text-sm flex items-center gap-1.5 transition">
                &larr; Return to Shopping Cart
            </a>
            <h1 class="text-3xl font-extrabold text-slate-900 mt-3 tracking-tight">Secure Checkout</h1>
            <p class="text-slate-500 text-sm mt-1 font-medium">Provide your delivery address and complete your purchase securely.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Checkout Form -->
            <div class="lg:col-span-2">
                <form action="{{ route('orders.store') }}" 
                      method="POST" 
                      enctype="multipart/form-data"
                      class="bg-white border border-slate-200/50 rounded-3xl p-8 shadow-sm flex flex-col gap-6 hover:border-teal-500/10 transition duration-300">
                    @csrf
                    
                    <!-- Hidden coupon code input -->
                    <input type="hidden" name="coupon_code" id="coupon_code_hidden" value="">

                    <!-- Phone Number -->
                    <div>
                        <label class="block text-sm font-bold text-slate-800 mb-2">Contact Phone Number</label>
                        <input type="text" 
                               name="phone" 
                               value="{{ old('phone') }}" 
                               placeholder="e.g. +91 9876543210"
                               class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-teal-500 focus:ring-teal-500 focus:outline-none transition bg-slate-50/50 font-medium"
                               required>
                        <x-input-error :messages="$errors->get('phone')" class="mt-1.5" />
                    </div>

                    <!-- Shipping Address -->
                    <div>
                        <label class="block text-sm font-bold text-slate-800 mb-2">Delivery Address</label>
                        <textarea name="shipping_address" 
                                  rows="4" 
                                  placeholder="Enter complete address with flat number, building name, street name, area, landmark, city, state, pincode..."
                                  class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-teal-500 focus:ring-teal-500 focus:outline-none transition bg-slate-50/50 font-medium"
                                  required>{{ old('shipping_address') }}</textarea>
                        <x-input-error :messages="$errors->get('shipping_address')" class="mt-1.5" />
                    </div>

                    <!-- Prescription upload if required -->
                    @if($prescriptionRequired)
                        <div class="bg-red-500/5 border border-red-500/10 rounded-2xl p-5">
                            <div class="flex gap-3 mb-4">
                                <span class="p-2 bg-gradient-to-br from-red-500 to-red-650 text-white rounded-xl h-10 w-10 flex items-center justify-center shrink-0 font-extrabold text-xs shadow-md shadow-red-500/10">Rx</span>
                                <div>
                                    <h4 class="font-extrabold text-red-950 text-sm">Doctor's Prescription Required</h4>
                                    <p class="text-xs text-red-750 mt-1 leading-relaxed">One or more items in your cart require a valid prescription. Please upload a clear photo/scan or PDF copy (Max 2MB).</p>
                                </div>
                            </div>
                            
                            <input type="file" 
                                   name="prescription" 
                                   class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-teal-500 focus:ring-teal-500 focus:outline-none file:mr-4 file:py-1 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-teal-50 file:text-teal-700 cursor-pointer"
                                   required>
                            <x-input-error :messages="$errors->get('prescription')" class="mt-2" />
                        </div>
                    @endif

                    <!-- Payment Mode -->
                    <div>
                        <label class="block text-sm font-bold text-slate-800 mb-2">Payment Method</label>
                        <select name="payment_method" 
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-teal-500 focus:ring-teal-500 focus:outline-none transition bg-slate-50/50 font-bold text-slate-700"
                                required>
                            <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>Cash on Delivery (COD)</option>
                            <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>Online Card Payment (Mock Simulation)</option>
                        </select>
                        <x-input-error :messages="$errors->get('payment_method')" class="mt-1.5" />
                    </div>

                    <!-- Submit -->
                    <button type="submit" 
                            id="place_order_btn"
                            class="bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white font-bold text-center py-4 rounded-xl transition shadow-lg shadow-teal-500/20 active:scale-98 mt-4">
                        Place Order (₹{{ number_format($cart->subtotal + ($cart->subtotal >= 500 ? 0 : 50), 2) }})
                    </button>
                </form>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="bg-white border border-slate-200/50 rounded-3xl p-6 shadow-sm h-fit hover:border-teal-500/10 transition duration-300">
                <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-4">Order Summary</h3>

                <!-- Items list -->
                <div class="flex flex-col gap-4 max-h-60 overflow-y-auto mb-6 pr-2">
                    @foreach($cart->items as $item)
                        <div class="flex justify-between items-center gap-3 text-sm">
                            <div class="truncate flex-1">
                                <span class="font-bold text-slate-800 text-sm block truncate">{{ $item->medicine->name }}</span>
                                <span class="text-xs text-slate-400 font-bold block mt-0.5">Qty: {{ $item->quantity }}</span>
                            </div>
                            <span class="font-extrabold text-slate-750 text-right min-w-[70px]">₹{{ number_format($item->medicine->selling_price * $item->quantity, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Offer Code Section -->
                <div class="border-t border-slate-100 pt-5 mb-5">
                    <div class="flex justify-between items-center mb-2.5">
                        <label class="block text-[10px] font-bold text-slate-450 uppercase tracking-widest">Have a Coupon / Offer Code?</label>
                        <button type="button" onclick="toggleCouponsModal(true)" class="text-[10px] font-bold text-teal-605 hover:text-teal-800 hover:underline transition">View Available Offers</button>
                    </div>
                    <div class="flex gap-2">
                        <input type="text" 
                               id="coupon_code_input" 
                               placeholder="e.g. HEALTH20" 
                               class="flex-1 border border-slate-250 rounded-xl px-3.5 py-2 text-xs focus:border-teal-500 focus:ring-teal-500 focus:outline-none uppercase font-bold text-slate-800 placeholder:text-slate-400">
                        <button type="button" 
                                id="apply_coupon_btn" 
                                class="bg-teal-650 hover:bg-teal-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition shadow-md shadow-teal-500/10 whitespace-nowrap active:scale-95">
                            Apply
                        </button>
                    </div>
                    <div id="coupon_message" class="text-xs mt-2.5 hidden"></div>
                </div>

                <div class="border-t border-slate-100 pt-5 flex flex-col gap-3">
                    <div class="flex justify-between text-xs text-slate-500 font-medium">
                        <span>Items Price</span>
                        <span>₹{{ number_format($cart->subtotal, 2) }}</span>
                    </div>

                    <div id="discount_row" class="justify-between text-xs text-orange-600 font-bold bg-orange-50/50 px-2 py-1 rounded border border-orange-500/5 hidden">
                        <span>Discount (10% Off)</span>
                        <span id="discount_amount">-₹0.00</span>
                    </div>

                    @php
                        $shipping = $cart->subtotal >= 500 ? 0 : 50;
                        $grandTotal = $cart->subtotal + $shipping;
                    @endphp

                    <div class="flex justify-between text-xs text-slate-500 font-medium">
                        <span>Delivery</span>
                        <span id="delivery_amount">
                            @if($shipping == 0)
                                <span class="text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded border border-emerald-500/10">FREE</span>
                            @else
                                ₹{{ number_format($shipping, 2) }}
                            @endif
                        </span>
                    </div>

                    <div class="border-t border-slate-100 pt-4 mt-2 flex justify-between items-baseline">
                        <span class="font-bold text-slate-900 text-sm">Total Payable</span>
                        <span class="font-black text-teal-750 text-xl" id="total_payable">₹{{ number_format($grandTotal, 2) }}</span>
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
            
            window.toggleCouponsModal = function(show) {
                const modal = document.getElementById('coupons_modal');
                if (show) {
                    modal.classList.remove('hidden');
                } else {
                    modal.classList.add('hidden');
                }
            };

            window.applyCouponCode = function(code) {
                if (appliedCode) {
                    // Remove current code first
                    applyBtn.click();
                }
                couponInput.value = code;
                toggleCouponsModal(false);
                applyBtn.click();
            };

            function updatePrices() {
                let discount = 0;
                let discountText = 'Discount';
                if (appliedCode === 'HEALTH20') {
                    discount = subtotal * 0.20;
                    discountText = 'Discount (20% Off)';
                } else if (appliedCode === 'MEDICARE10' || appliedCode === '123') {
                    discount = subtotal * 0.10;
                    discountText = 'Discount (10% Off)';
                } else if (appliedCode === 'WELCOME50') {
                    discount = Math.min(50, subtotal);
                    discountText = 'Discount (Flat ₹50 Off)';
                }
                
                const discountedSubtotal = subtotal - discount;
                const shipping = discountedSubtotal >= 500 ? 0 : 50;
                const total = discountedSubtotal + shipping;
                
                // Update UI
                if (discount > 0) {
                    discountRow.classList.remove('hidden');
                    discountRow.classList.add('flex');
                    discountRow.querySelector('span:first-child').textContent = discountText;
                    discountAmountEl.textContent = '-₹' + discount.toFixed(2);
                } else {
                    discountRow.classList.remove('flex');
                    discountRow.classList.add('hidden');
                }
                
                if (shipping === 0) {
                    deliveryAmountEl.innerHTML = '<span class="text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded border border-emerald-500/10">FREE</span>';
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
                    applyBtn.className = 'bg-teal-650 hover:bg-teal-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition shadow-md shadow-teal-500/10 whitespace-nowrap active:scale-95';
                    couponMessage.classList.add('hidden');
                    updatePrices();
                    return;
                }
                
                const enteredCode = couponInput.value.trim().toUpperCase();
                if (!enteredCode) {
                    couponMessage.textContent = 'Please enter a code.';
                    couponMessage.className = 'text-xs mt-2 text-red-650 font-semibold';
                    couponMessage.classList.remove('hidden');
                    return;
                }
                
                const validCodes = ['HEALTH20', 'MEDICARE10', 'WELCOME50', '123'];
                if (validCodes.includes(enteredCode)) {
                    appliedCode = enteredCode;
                    hiddenCouponInput.value = enteredCode;
                    couponInput.disabled = true;
                    applyBtn.textContent = 'Remove';
                    applyBtn.className = 'bg-red-600 hover:bg-red-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition shadow-md shadow-red-500/10 whitespace-nowrap active:scale-95';
                    
                    let msg = 'Coupon applied successfully! ';
                    if (enteredCode === 'HEALTH20') msg += '20% discount applied.';
                    else if (enteredCode === 'WELCOME50') msg += 'Flat ₹50 discount applied.';
                    else msg += '10% discount applied.';

                    couponMessage.textContent = msg;
                    couponMessage.className = 'text-xs mt-2 text-emerald-650 font-bold';
                    couponMessage.classList.remove('hidden');
                    updatePrices();
                } else {
                    couponMessage.textContent = 'Invalid offer code.';
                    couponMessage.className = 'text-xs mt-2 text-red-650 font-semibold';
                    couponMessage.classList.remove('hidden');
                }
            });
        });
    </script>

    <!-- Available Coupons Modal -->
    <div id="coupons_modal" class="fixed inset-0 z-50 flex items-center justify-center hidden" style="display: none;">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="toggleCouponsModal(false)"></div>
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl relative z-10 border border-slate-200 m-4">
            <div class="flex justify-between items-center pb-4 border-b border-slate-100 mb-4">
                <h4 class="font-extrabold text-slate-800 text-sm">Available Pharmacy Coupons</h4>
                <button type="button" onclick="toggleCouponsModal(false)" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition active:scale-90 font-bold">&times;</button>
            </div>
            <div class="space-y-4 max-h-[350px] overflow-y-auto pr-1">
                <!-- Coupon 1 -->
                <div class="border border-teal-500/20 bg-teal-50/30 p-4 rounded-2xl flex flex-col gap-2 justify-between">
                    <div class="flex justify-between items-center">
                        <span class="bg-teal-100/80 text-teal-850 px-2.5 py-1 rounded text-xs font-black uppercase tracking-wider">HEALTH20</span>
                        <span class="text-xs font-bold text-teal-700">Save 20%</span>
                    </div>
                    <p class="text-[10px] text-slate-500 font-medium">Get flat 20% off on all medicine orders. No minimum spend.</p>
                    <button type="button" onclick="applyCouponCode('HEALTH20')" class="w-full mt-1 bg-teal-650 hover:bg-teal-700 text-white font-bold text-xs py-2 rounded-xl transition active:scale-95">Apply Code</button>
                </div>
                <!-- Coupon 2 -->
                <div class="border border-indigo-500/20 bg-indigo-50/30 p-4 rounded-2xl flex flex-col gap-2 justify-between">
                    <div class="flex justify-between items-center">
                        <span class="bg-indigo-100/80 text-indigo-850 px-2.5 py-1 rounded text-xs font-black uppercase tracking-wider">MEDICARE10</span>
                        <span class="text-xs font-bold text-indigo-705">Save 10%</span>
                    </div>
                    <p class="text-[10px] text-slate-500 font-medium">Save 10% on your pharmaceutical care orders.</p>
                    <button type="button" onclick="applyCouponCode('MEDICARE10')" class="w-full mt-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs py-2 rounded-xl transition active:scale-95">Apply Code</button>
                </div>
                <!-- Coupon 3 -->
                <div class="border border-orange-500/20 bg-orange-50/30 p-4 rounded-2xl flex flex-col gap-2 justify-between">
                    <div class="flex justify-between items-center">
                        <span class="bg-orange-100/80 text-orange-850 px-2.5 py-1 rounded text-xs font-black uppercase tracking-wider">WELCOME50</span>
                        <span class="text-xs font-bold text-orange-700">Flat ₹50 Off</span>
                    </div>
                    <p class="text-[10px] text-slate-500 font-medium">Flat ₹50 off on your first order over ₹200.</p>
                    <button type="button" onclick="applyCouponCode('WELCOME50')" class="w-full mt-1 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs py-2 rounded-xl transition active:scale-95">Apply Code</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
