<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-8">Shopping Cart</h1>

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-r-2xl mb-8 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-r-2xl mb-8 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="font-medium text-sm">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if(!$cart || $cart->items->isEmpty())
            <div class="bg-white rounded-3xl border border-slate-200/50 p-16 text-center shadow-sm max-w-2xl mx-auto">
                <div class="w-24 h-24 bg-teal-50 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-inner text-teal-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-extrabold text-slate-900 mb-2">Your Cart is Empty</h2>
                <p class="text-slate-500 mb-8 max-w-sm mx-auto font-medium">Explore our wide selection of prescription medicines and healthcare products to fill up your cart.</p>
                <a href="{{ route('medicines.index') }}" class="bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white font-bold px-8 py-3.5 rounded-xl shadow-lg shadow-teal-500/20 transition">
                    Browse Medicines
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Cart Items List -->
                <div class="lg:col-span-2 flex flex-col gap-6">
                    @foreach($cart->items as $item)
                        <div class="bg-white border border-slate-200/50 rounded-3xl p-6 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 group hover:border-teal-500/20 transition duration-300">
                            
                            <!-- Product Details -->
                            <div class="flex gap-4">
                                <div class="w-20 h-20 bg-slate-50 rounded-2xl overflow-hidden flex items-center justify-center shrink-0 border border-slate-100 p-2">
                                    @if($item->medicine->image)
                                        <img src="{{ asset($item->medicine->image) }}" alt="{{ $item->medicine->name }}" class="object-contain h-full w-full">
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-teal-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $item->medicine->brand->name }}</span>
                                    <h3 class="font-bold text-slate-900 group-hover:text-teal-700 transition mt-0.5">{{ $item->medicine->name }}</h3>
                                    <p class="text-xs text-teal-600 font-semibold mt-0.5">{{ $item->medicine->category->name }}</p>
                                    
                                    @if($item->medicine->prescription_required)
                                        <span class="mt-2 inline-block bg-red-500/10 border border-red-500/20 text-red-650 text-[9px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">Rx Required</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Quantity controls & pricing -->
                            <div class="flex items-center justify-between sm:justify-end gap-8 w-full sm:w-auto">
                                
                                <!-- Quantity controls -->
                                <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center border border-slate-200 rounded-xl overflow-hidden h-10 bg-slate-50/50">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}" 
                                            class="w-9 h-full hover:bg-slate-100 flex items-center justify-center font-extrabold text-slate-500 transition"
                                            {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                        -
                                    </button>
                                    <input type="text" readonly value="{{ $item->quantity }}" 
                                           class="w-10 h-full border-0 text-center text-sm font-bold text-slate-700 bg-transparent pointer-events-none focus:ring-0 focus:outline-none">
                                    <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" 
                                            class="w-9 h-full hover:bg-slate-100 flex items-center justify-center font-extrabold text-slate-500 transition"
                                            {{ $item->quantity >= $item->medicine->stock_quantity ? 'disabled' : '' }}>
                                        +
                                    </button>
                                </form>

                                <!-- Price -->
                                <div class="text-right min-w-[80px]">
                                    <span class="block font-black text-teal-750 text-lg">₹{{ number_format($item->medicine->selling_price * $item->quantity, 2) }}</span>
                                    @if($item->medicine->mrp > $item->medicine->selling_price)
                                        <span class="text-xs text-slate-400 line-through">₹{{ number_format($item->medicine->mrp * $item->quantity, 2) }}</span>
                                    @endif
                                </div>

                                <!-- Remove button -->
                                <form action="{{ route('cart.remove', $item) }}" method="POST" onsubmit="return confirm('Remove this item?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-650 hover:bg-red-50/50 transition p-2.5 rounded-xl border border-transparent hover:border-red-500/10">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Price Summary Panel -->
                <div class="bg-white border border-slate-200/50 rounded-3xl p-6 shadow-sm h-fit hover:border-teal-500/10 transition duration-300">
                    <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-4">Payment Details</h3>
                    
                    <div class="flex flex-col gap-3">
                        <div class="flex justify-between text-sm text-slate-500 font-medium">
                            <span>MRP Total</span>
                            <span>₹{{ number_format($cart->mrp_subtotal, 2) }}</span>
                        </div>
                        @if($cart->savings > 0)
                            <div class="flex justify-between text-sm text-orange-600 font-bold bg-orange-50/50 px-3 py-1 rounded-lg border border-orange-500/5">
                                <span>Medicare Discount</span>
                                <span>- ₹{{ number_format($cart->savings, 2) }}</span>
                            </div>
                        @endif

                        @php
                            $shipping = $cart->subtotal >= 500 ? 0 : 50;
                            $grandTotal = $cart->subtotal + $shipping;
                        @endphp

                        <div class="flex justify-between text-sm text-slate-500 font-medium">
                            <span>Delivery Charges</span>
                            <span>
                                @if($shipping == 0)
                                    <span class="text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded border border-emerald-500/10">FREE</span>
                                @else
                                    ₹{{ number_format($shipping, 2) }}
                                @endif
                            </span>
                        </div>

                        @if($shipping > 0)
                            <div class="bg-slate-550/5 border border-slate-200/50 rounded-xl p-3 mt-1">
                                <p class="text-[10px] text-slate-450 font-medium leading-normal">Add <span class="font-bold text-teal-650">₹{{ number_format(500 - $cart->subtotal, 2) }}</span> more of medicines to unlock <span class="font-bold text-emerald-600">FREE delivery</span>!</p>
                            </div>
                        @endif

                        <div class="border-t border-slate-100 pt-4 mt-2 flex justify-between items-baseline">
                            <span class="font-bold text-slate-900 text-base">Total Amount</span>
                            <span class="font-black text-teal-750 text-2xl">₹{{ number_format($grandTotal, 2) }}</span>
                        </div>
                    </div>

                    @if($cart->savings > 0)
                        <div class="bg-gradient-to-r from-teal-500/10 to-emerald-500/10 border border-teal-500/10 text-teal-800 font-bold text-center text-xs p-3 rounded-2xl mt-6">
                            🎉 You are saving ₹{{ number_format($cart->savings, 2) }} on this order!
                        </div>
                    @endif

                    <a href="{{ route('checkout') }}" class="mt-6 w-full bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white font-bold text-center py-3.5 rounded-xl transition block shadow-lg shadow-teal-500/20 hover:scale-[1.02]">
                        Proceed to Checkout
                    </a>
                </div>

            </div>
        @endif
    </div>
</x-app-layout>