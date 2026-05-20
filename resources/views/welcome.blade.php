<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Medicare - Online Medical Store & Pharmacy</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
        <!-- Vite Styles & Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 text-gray-800 font-sans">
        
        <!-- Header -->
        <header class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex justify-between items-center">
                
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <span class="w-10 h-10 rounded-xl bg-teal-600 flex items-center justify-center text-white font-bold text-xl shadow-md">M</span>
                        <span class="text-2xl font-bold tracking-tight text-teal-700">Medi<span class="text-orange-500">care</span></span>
                    </a>
                </div>

                <!-- Navigation & Auth Links -->
                <div class="flex items-center gap-6">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-teal-600 font-medium text-sm transition">Dashboard</a>
                        
                        <!-- Cart Icon -->
                        <a href="{{ route('cart.index') }}" class="relative flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            @php
                                $cart = \App\Models\Cart::where('user_id', auth()->id())->first();
                                $cartCount = $cart ? $cart->items()->sum('quantity') : 0;
                            @endphp
                            @if($cartCount > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center font-bold">{{ $cartCount }}</span>
                            @endif
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-red-600 font-medium text-sm transition">Log Out</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-teal-600 font-medium text-sm transition">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-teal-600 hover:bg-teal-700 text-white font-medium text-sm px-5 py-2.5 rounded-lg transition shadow-md">Register</a>
                        @endif
                    @endauth
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="bg-gradient-to-r from-teal-700 to-teal-900 text-white py-16 px-4">
            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="bg-teal-500/30 text-teal-200 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider">Your Trusted Pharmacy</span>
                    <h1 class="text-4xl sm:text-5xl font-extrabold mt-4 leading-tight">Genuine Medicines, Delivered Safely To Your Door.</h1>
                    <p class="text-teal-100 mt-6 text-lg">Medicare is your ultimate online destination for healthcare, beauty, and wellness supplies. Explore original medicines with high discounts.</p>
                    
                    <!-- Search Form -->
                    <form action="{{ route('medicines.index') }}" method="GET" class="mt-8 bg-white p-2 rounded-xl flex shadow-xl max-w-lg">
                        <input type="text" name="search" placeholder="Search for medicines, wellness products..." class="flex-1 px-4 py-3 text-gray-800 rounded-lg focus:outline-none focus:ring-0 text-sm">
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold text-sm px-6 py-3 rounded-lg transition">Search</button>
                    </form>
                </div>
                <div class="hidden lg:flex justify-center relative">
                    <div class="w-80 h-80 bg-teal-500 rounded-full filter blur-3xl opacity-20 absolute -top-5"></div>
                    <div class="bg-teal-800/40 p-6 rounded-3xl border border-teal-600/30 shadow-2xl backdrop-blur-sm max-w-sm">
                        <div class="flex items-center gap-4 mb-4">
                            <span class="p-3 rounded-2xl bg-teal-600 text-white"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg></span>
                            <div>
                                <h3 class="font-bold text-sm">100% Genuine</h3>
                                <p class="text-xs text-teal-200">Sourced directly from verified manufacturers</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 mb-4">
                            <span class="p-3 rounded-2xl bg-teal-600 text-white"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></span>
                            <div>
                                <h3 class="font-bold text-sm">Super Fast Delivery</h3>
                                <p class="text-xs text-teal-200">Get your health essential needs on time</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="p-3 rounded-2xl bg-teal-600 text-white"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg></span>
                            <div>
                                <h3 class="font-bold text-sm">Extra Savings</h3>
                                <p class="text-xs text-teal-200">Save up to 25% off MRP on all prescriptions</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Categories Section -->
        <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900">Shop by Category</h2>
                    <p class="text-gray-500 mt-2">Explore wellness categories built for your specific health needs</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                @forelse($categories as $category)
                    <a href="{{ route('medicines.index', ['category' => $category->id]) }}" class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition text-center group">
                        <div class="w-16 h-16 rounded-full bg-teal-50 flex items-center justify-center mx-auto mb-4 group-hover:bg-teal-600 transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-teal-600 group-hover:text-white transition duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 text-sm group-hover:text-teal-700 transition">{{ $category->name }}</h3>
                    </a>
                @empty
                    <p class="text-gray-500 text-center col-span-full">No categories available.</p>
                @endforelse
            </div>
        </section>

        <!-- Featured Medicines Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-end mb-10">
                    <div>
                        <h2 class="text-3xl font-extrabold text-gray-900">Featured Medicines</h2>
                        <p class="text-gray-500 mt-2">Find popular treatments and over-the-counter essentials</p>
                    </div>
                    <a href="{{ route('medicines.index') }}" class="text-teal-600 hover:text-teal-700 font-semibold text-sm flex items-center gap-1 group">
                        View All Medicines
                        <span class="group-hover:translate-x-1 transition duration-200">&rarr;</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @forelse($medicines as $medicine)
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg transition duration-300 overflow-hidden flex flex-col justify-between group">
                            
                            <!-- Card Body -->
                            <div class="p-6">
                                <div class="relative w-full h-48 bg-gray-50 rounded-xl overflow-hidden mb-4 flex items-center justify-center">
                                    @if($medicine->image)
                                        <img src="{{ asset($medicine->image) }}" alt="{{ $medicine->name }}" class="object-cover h-full w-full group-hover:scale-105 transition duration-300">
                                    @else
                                        <!-- Fallback pill SVG -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-teal-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z" />
                                        </svg>
                                    @endif

                                    <!-- Prescription requirement badge -->
                                    @if($medicine->prescription_required)
                                        <span class="absolute top-3 right-3 bg-red-100 text-red-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Prescription Required</span>
                                    @endif
                                </div>

                                <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider">{{ $medicine->brand->name }}</span>
                                <h3 class="font-bold text-gray-900 text-lg mt-1 group-hover:text-teal-700 transition">{{ $medicine->name }}</h3>
                                <p class="text-xs text-teal-600 mt-0.5">{{ $medicine->category->name }}</p>
                                <p class="text-gray-500 text-xs mt-3 line-clamp-2">{{ $medicine->description ?? 'No description available for this medicine.' }}</p>
                            </div>

                            <!-- Card Footer -->
                            <div class="px-6 pb-6 pt-0 border-t border-gray-50 mt-4">
                                <div class="flex items-baseline gap-2 mt-4">
                                    <span class="text-2xl font-extrabold text-teal-700">₹{{ number_format($medicine->selling_price, 2) }}</span>
                                    @if($medicine->mrp > $medicine->selling_price)
                                        <span class="text-sm text-gray-400 line-through">₹{{ number_format($medicine->mrp, 2) }}</span>
                                        @php
                                            $discount = (($medicine->mrp - $medicine->selling_price) / $medicine->mrp) * 100;
                                        @endphp
                                        <span class="text-xs font-bold text-orange-500 bg-orange-50 px-2 py-0.5 rounded">{{ round($discount) }}% OFF</span>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-4 flex gap-2">
                                    @auth
                                        @if(auth()->user()->hasRole('customer'))
                                            @if($medicine->stock_quantity > 0)
                                                <form action="{{ route('cart.add', $medicine) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm py-2.5 rounded-xl transition flex items-center justify-center gap-1">
                                                        Add to Cart
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('cart.add', $medicine) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    <input type="hidden" name="buy_now" value="1">
                                                    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold text-sm py-2.5 rounded-xl transition flex items-center justify-center gap-1">
                                                        Buy Now
                                                    </button>
                                                </form>
                                            @else
                                                <button class="w-full bg-gray-100 text-gray-400 cursor-not-allowed font-semibold text-sm py-2.5 rounded-xl transition" disabled>
                                                    Out of Stock
                                                </button>
                                            @endif
                                        @else
                                            <a href="{{ route('medicines.edit', $medicine) }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm py-2.5 rounded-xl transition text-center block">
                                                Edit Pricing
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm py-2.5 rounded-xl transition text-center block">
                                            Log in to Buy
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center col-span-full py-8">No medicines found. Please contact admin to seed medicines.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-400 py-12 border-t border-gray-800 mt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:flex sm:justify-between sm:text-left">
                <p class="text-sm">&copy; {{ date('Y') }} Medicare Online Pharmacy. All rights reserved.</p>
                <div class="mt-4 sm:mt-0 flex justify-center gap-6">
                    <a href="#" class="hover:text-white text-sm transition">Privacy Policy</a>
                    <a href="#" class="hover:text-white text-sm transition">Terms of Service</a>
                    <a href="#" class="hover:text-white text-sm transition">Contact Us</a>
                </div>
            </div>
        </footer>
    </body>
</html>
