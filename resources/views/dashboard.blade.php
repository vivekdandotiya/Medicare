<x-app-layout>
    @php
        $user = auth()->user();
        
        if ($user->hasRole('admin') || $user->hasRole('staff')) {
            $totalMedicines = \App\Models\Medicine::count();
            $lowStock = \App\Models\Medicine::where('stock_quantity', '<=', 5)->count();
            $pendingOrders = \App\Models\Order::whereIn('status', ['pending', 'processing'])->count();
            $revenue = \App\Models\Order::where('status', 'delivered')->sum('total_amount');
        } else {
            $cart = \App\Models\Cart::where('user_id', $user->id)->first();
            $cartCount = $cart ? $cart->items()->sum('quantity') : 0;
            $orderCount = \App\Models\Order::where('user_id', $user->id)->count();
            $totalSpent = \App\Models\Order::where('user_id', $user->id)->where('status', 'delivered')->sum('total_amount');
            
            // Fetch featured products for the customer dashboard
            $featuredMedicines = \App\Models\Medicine::with(['category', 'brand'])
                ->where('status', true)
                ->inRandomOrder()
                ->take(4)
                ->get();
        }
    @endphp

    <!-- Custom Style Sheet -->
    <style>
        .truemeds-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }
        .truemeds-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.06);
            border-color: #cbd5e1;
        }
        .search-container {
            background: linear-gradient(180deg, #f0fdf4 0%, #ffffff 100%);
            border-radius: 32px;
            padding: 40px 24px;
        }
        .doctor-stripe {
            background-color: #e6f7f0;
            border: 1px solid #c2ebd9;
            border-radius: 20px;
        }
    </style>

    <div class="bg-slate-50 min-h-screen py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-10">
            
            @if($user->hasRole('admin') || $user->hasRole('staff'))
                <!-- ================= ADMIN / STAFF SYSTEM ================= -->
                <div class="bg-gradient-to-r from-teal-850 to-emerald-950 rounded-3xl p-8 md:p-10 text-white shadow-2xl relative overflow-hidden">
                    <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-teal-500 rounded-full filter blur-3xl opacity-20"></div>
                    <span class="inline-flex items-center gap-1.5 bg-teal-500/25 text-teal-200 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider border border-teal-500/20">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        {{ $user->hasRole('admin') ? 'Administrator Access' : 'Store Staff Access' }}
                    </span>
                    <h1 class="text-3xl md:text-4xl font-black tracking-tight mt-4">Welcome back, {{ $user->name }}!</h1>
                    <p class="text-teal-100/90 mt-2 text-sm max-w-xl">Medicare's centralized admin suite gives you instant access to stock levels, metrics, and billing operations.</p>
                </div>

                <!-- Admin Statistics Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-5">
                        <div class="p-4 bg-teal-500/10 text-teal-700 rounded-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-gray-405 uppercase tracking-widest block">Total Medicines</span>
                            <span class="text-3xl font-black text-slate-850 block mt-0.5">{{ $totalMedicines }}</span>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-5">
                        <div class="p-4 bg-red-500/10 text-red-650 rounded-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-gray-450 uppercase tracking-widest block">Low Stock Alerts</span>
                            <span class="text-3xl font-black text-slate-850 block mt-0.5">{{ $lowStock }}</span>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-5">
                        <div class="p-4 bg-blue-500/10 text-blue-650 rounded-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-gray-450 uppercase tracking-widest block">Active Orders</span>
                            <span class="text-3xl font-black text-slate-850 block mt-0.5">{{ $pendingOrders }}</span>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-5">
                        <div class="p-4 bg-emerald-500/10 text-emerald-650 rounded-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-gray-450 uppercase tracking-widest block">Total Revenue</span>
                            <span class="text-3xl font-black text-slate-850 block mt-0.5">₹{{ number_format($revenue, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Management Operations -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('medicines.index') }}" class="bg-white border border-slate-150 hover:border-teal-500 rounded-3xl p-6 shadow-sm hover:shadow-md transition duration-300 block text-left group">
                        <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mb-5 group-hover:bg-teal-650 group-hover:text-white transition duration-300 shadow-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <h3 class="font-extrabold text-gray-900 text-lg group-hover:text-teal-700 transition">Inventory & Pricing</h3>
                        <p class="text-xs text-gray-500 mt-2 font-medium leading-relaxed">Update catalogs, adjust markdowns, and monitor inventory warning triggers.</p>
                    </a>

                    <a href="{{ route('orders.index') }}" class="bg-white border border-slate-150 hover:border-teal-500 rounded-3xl p-6 shadow-sm hover:shadow-md transition duration-300 block text-left group">
                        <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mb-5 group-hover:bg-teal-650 group-hover:text-white transition duration-300 shadow-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" />
                            </svg>
                        </div>
                        <h3 class="font-extrabold text-gray-900 text-lg group-hover:text-teal-700 transition">Order Fulfillment</h3>
                        <p class="text-xs text-gray-500 mt-2 font-medium leading-relaxed">Review doctor prescriptions, assign shipping carriers, and change fulfillment status.</p>
                    </a>

                    <a href="{{ route('categories.index') }}" class="bg-white border border-slate-150 hover:border-teal-500 rounded-3xl p-6 shadow-sm hover:shadow-md transition duration-300 block text-left group">
                        <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mb-5 group-hover:bg-teal-650 group-hover:text-white transition duration-300 shadow-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </div>
                        <h3 class="font-extrabold text-gray-900 text-lg group-hover:text-teal-700 transition">Taxonomy Structure</h3>
                        <p class="text-xs text-gray-500 mt-2 font-medium leading-relaxed">Create brand labels, configure therapy categories, and structure web navigation tags.</p>
                    </a>
                </div>

            @else
                <!-- ================= CUSTOMER PORTAL (TRUEMEDS REFERENCE DESIGN) ================= -->
                
                <!-- 1. Search Header Section -->
                <div class="search-container text-center space-y-6 shadow-sm border border-slate-100">
                    <div class="space-y-2">
                        <h1 class="text-3xl md:text-4xl font-extrabold text-slate-800 tracking-tight">Say GoodBye to high medicine prices</h1>
                        <p class="text-slate-500 text-sm font-semibold">Compare prices and save up to 51% on your health essentials</p>
                    </div>

                    <!-- Truemeds Search Bar -->
                    <form action="{{ route('medicines.index') }}" method="GET" class="max-w-3xl mx-auto flex flex-col md:flex-row items-center bg-white p-2 rounded-2xl shadow-md border border-slate-150 gap-2">
                        <!-- Location Dropdown -->
                        <div class="flex items-center gap-2 px-4 py-2 border-r border-slate-150 shrink-0 w-full md:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-xs font-black text-slate-700">400079, Mumbai</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        <!-- Search input -->
                        <div class="flex-1 w-full relative">
                            <span class="absolute inset-y-0 left-3 flex items-center pl-1 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text" name="search" placeholder="Search for medicines..." class="w-full pl-11 pr-4 py-2.5 text-sm text-slate-800 bg-transparent border-0 focus:ring-0 focus:outline-none placeholder-slate-400">
                        </div>

                        <!-- Search button -->
                        <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-750 text-white font-bold text-sm px-8 py-3 rounded-xl transition shadow-md flex items-center justify-center gap-2">
                            <span>Search</span>
                        </button>
                    </form>
                </div>

                <!-- 2. Dual Side-by-Side Banners -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left: Lowest Price Guaranteed -->
                    <div class="truemeds-card overflow-hidden relative group cursor-pointer aspect-[16/9]">
                        <img src="{{ asset('uploads/medicines/lowest_price_banner.png') }}" class="w-full h-full object-cover group-hover:scale-103 transition duration-500" alt="Lowest Price Banner">
                        <!-- Content Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent flex flex-col justify-end p-6 text-white">
                            <span class="bg-blue-600 text-white text-[9px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider w-max mb-1">Found cheaper?</span>
                            <h3 class="text-lg sm:text-2xl font-black">We'll Pay the Difference</h3>
                            <p class="text-xs text-slate-100 font-medium">Found a cheaper alternative online? We'll credit the difference instantly.</p>
                        </div>
                    </div>

                    <!-- Right: Nutrition / Wellness -->
                    <div class="truemeds-card overflow-hidden relative group cursor-pointer aspect-[16/9]">
                        <img src="{{ asset('uploads/medicines/nutrition_banner.png') }}" class="w-full h-full object-cover group-hover:scale-103 transition duration-500" alt="Nutrition Banner">
                        <!-- Content Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent flex flex-col justify-end p-6 text-white">
                            <span class="bg-emerald-600 text-white text-[9px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider w-max mb-1">Supplements</span>
                            <h3 class="text-lg sm:text-2xl font-black">Choose Supreme Nutrition</h3>
                            <p class="text-xs text-slate-100 font-medium">Protein mixes, health capsule bars, and wellness syrups to fuel energy.</p>
                        </div>
                    </div>
                </div>

                <!-- 3. Doctor Call assistance Stripe -->
                <div class="doctor-stripe p-5 flex flex-col md:flex-row items-center justify-between gap-4 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center shadow-sm text-teal-600 overflow-hidden border border-emerald-100 shrink-0">
                            <!-- Friendly Doctor SVG Avatar -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-teal-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-emerald-950 text-base">Call us and Order medicines</h4>
                            <p class="text-xs text-emerald-700 font-medium">Working Hours: 8:00 AM to 10:00 PM</p>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <a href="tel:09240250346" class="text-xl md:text-2xl font-black text-emerald-800 hover:text-emerald-950 transition block">
                            09240250346
                        </a>
                    </div>
                </div>

                <!-- 4. Recommended Medicines Shelf -->
                <div class="space-y-6">
                    <div class="flex justify-between items-baseline">
                        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Recommended Medicines</h2>
                        <a href="{{ route('medicines.index') }}" class="text-sm font-bold text-teal-650 hover:text-teal-800 transition">View All &rarr;</a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($featuredMedicines as $medicine)
                            <div class="truemeds-card p-5 flex flex-col justify-between group overflow-hidden bg-white">
                                <div>
                                    <!-- Image container -->
                                    <div class="relative w-full h-40 bg-slate-50 rounded-2xl overflow-hidden mb-4 flex items-center justify-center border border-slate-100">
                                        @if($medicine->image)
                                            <img src="{{ asset($medicine->image) }}" alt="{{ $medicine->name }}" class="object-cover h-full w-full group-hover:scale-104 transition duration-500">
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-teal-350" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z" />
                                            </svg>
                                        @endif
                                        
                                        @if($medicine->prescription_required)
                                            <span class="absolute top-2.5 right-2.5 bg-red-100 text-red-750 text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider border border-red-200">Rx</span>
                                        @endif
                                    </div>

                                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest block">{{ $medicine->brand->name }}</span>
                                    <h3 class="font-extrabold text-slate-800 text-sm mt-1 line-clamp-1 group-hover:text-teal-700 transition">{{ $medicine->name }}</h3>
                                    <p class="text-[10px] text-teal-650 font-bold mt-0.5">{{ $medicine->category->name }}</p>
                                </div>

                                <div class="mt-4 border-t border-slate-100 pt-3">
                                    <div class="flex items-baseline gap-1.5 mb-3">
                                        <span class="text-lg font-black text-teal-750">₹{{ number_format($medicine->selling_price, 2) }}</span>
                                        @if($medicine->mrp > $medicine->selling_price)
                                            <span class="text-xs text-gray-400 line-through">₹{{ number_format($medicine->mrp, 2) }}</span>
                                        @endif
                                    </div>

                                    <form action="{{ route('cart.add', $medicine) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full bg-teal-650 hover:bg-teal-750 text-white font-bold text-xs py-2.5 rounded-xl transition shadow-sm">
                                            Add to Cart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 5. Testimonials (What our customers have to say) -->
                <div class="space-y-6">
                    <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight text-center">What our customers have to say</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Review 1 -->
                        <div class="bg-white p-6 rounded-3xl border border-slate-150 shadow-sm flex flex-col justify-between">
                            <div class="space-y-3">
                                <div class="flex text-orange-400">
                                    &#9733; &#9733; &#9733; &#9733; &#9733;
                                </div>
                                <h4 class="font-black text-slate-800 text-sm">Provides doorstep delivery</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">"Can order from anywhere and any time since Medicare provides doorstep delivery. Extremely helpful."</p>
                            </div>
                            <span class="text-[10px] text-slate-400 font-bold block mt-4">- Subhash Sehgal</span>
                        </div>

                        <!-- Review 2 -->
                        <div class="bg-white p-6 rounded-3xl border border-slate-150 shadow-sm flex flex-col justify-between">
                            <div class="space-y-3">
                                <div class="flex text-orange-400">
                                    &#9733; &#9733; &#9733; &#9733; &#9733;
                                </div>
                                <h4 class="font-black text-slate-800 text-sm">Used the app and found it easy</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">"Excellent interface. All info about substitutes is readily available, and checkout response was prompt."</p>
                            </div>
                            <span class="text-[10px] text-slate-400 font-bold block mt-4">- Deepali Sharma</span>
                        </div>

                        <!-- Review 3 -->
                        <div class="bg-white p-6 rounded-3xl border border-slate-150 shadow-sm flex flex-col justify-between">
                            <div class="space-y-3">
                                <div class="flex text-orange-400">
                                    &#9733; &#9733; &#9733; &#9733; &#9733;
                                </div>
                                <h4 class="font-black text-slate-800 text-sm">Very customer friendly portal</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">"Medicare is the best. The team did not reduce the discounts, which shows their patient-friendly values. Thank you."</p>
                            </div>
                            <span class="text-[10px] text-slate-400 font-bold block mt-4">- Rajesh K.</span>
                        </div>
                    </div>
                </div>

                <!-- 6. Accordion FAQs -->
                <div x-data="{ active: null }" class="space-y-6 max-w-4xl mx-auto">
                    <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight text-center">FAQs</h2>
                    
                    <div class="bg-white rounded-3xl border border-slate-150 divide-y divide-slate-100 overflow-hidden shadow-sm">
                        <!-- FAQ 1 -->
                        <div class="w-full text-left">
                            <button @click="active = (active === 1 ? null : 1)" class="w-full px-6 py-5 flex items-center justify-between text-slate-850 hover:bg-slate-50 transition focus:outline-none">
                                <span class="text-sm font-extrabold">Is opting for substitutes safe?</span>
                                <span class="text-slate-400 transition" :class="active === 1 ? 'rotate-180' : ''">&#9662;</span>
                            </button>
                            <div x-show="active === 1" x-collapse class="px-6 pb-5 text-xs text-slate-500 leading-relaxed">
                                Yes. Substitutes contain the exact same chemical salts and active therapeutic molecules as the branded medications, formulated in equivalent strengths under strict clinical protocols.
                            </div>
                        </div>

                        <!-- FAQ 2 -->
                        <div class="w-full text-left">
                            <button @click="active = (active === 2 ? null : 2)" class="w-full px-6 py-5 flex items-center justify-between text-slate-850 hover:bg-slate-50 transition focus:outline-none">
                                <span class="text-sm font-extrabold">Is there a guarantee on the quality of substitutes?</span>
                                <span class="text-slate-400 transition" :class="active === 2 ? 'rotate-180' : ''">&#9662;</span>
                            </button>
                            <div x-show="active === 2" x-collapse class="px-6 pb-5 text-xs text-slate-500 leading-relaxed">
                                Absolutely. All substitutes are sourced from certified pharmaceutical manufacturing labs complying with international medical regulations and quality standards.
                            </div>
                        </div>

                        <!-- FAQ 3 -->
                        <div class="w-full text-left">
                            <button @click="active = (active === 3 ? null : 3)" class="w-full px-6 py-5 flex items-center justify-between text-slate-850 hover:bg-slate-50 transition focus:outline-none">
                                <span class="text-sm font-extrabold">How can I avail free delivery?</span>
                                <span class="text-slate-400 transition" :class="active === 3 ? 'rotate-180' : ''">&#9662;</span>
                            </button>
                            <div x-show="active === 3" x-collapse class="px-6 pb-5 text-xs text-slate-500 leading-relaxed">
                                All orders with values above ₹500 receive automatic free shipping at checkout. Standard delivery fees apply to orders below this threshold.
                            </div>
                        </div>

                        <!-- FAQ 4 -->
                        <div class="w-full text-left">
                            <button @click="active = (active === 4 ? null : 4)" class="w-full px-6 py-5 flex items-center justify-between text-slate-850 hover:bg-slate-50 transition focus:outline-none">
                                <span class="text-sm font-extrabold">What is Medicare Online Store?</span>
                                <span class="text-slate-400 transition" :class="active === 4 ? 'rotate-180' : ''">&#9662;</span>
                            </button>
                            <div x-show="active === 4" x-collapse class="px-6 pb-5 text-xs text-slate-500 leading-relaxed">
                                Medicare is a premier healthcare delivery platform that enables users to purchase authentic prescription medicines and supplements at competitive discounts.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 7. Patient Statistics Grid -->
                <div class="bg-white rounded-3xl p-6 border border-slate-150 shadow-sm space-y-6">
                    <h3 class="font-extrabold text-slate-800 text-lg tracking-tight">Patient Dashboard History</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="p-5 bg-teal-50/50 rounded-2xl flex items-center gap-4">
                            <div class="p-3 bg-teal-100 text-teal-700 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Items in Cart</span>
                                <span class="text-2xl font-black text-slate-850 block mt-0.5">{{ $cartCount }}</span>
                            </div>
                        </div>

                        <div class="p-5 bg-blue-50/50 rounded-2xl flex items-center gap-4">
                            <div class="p-3 bg-blue-100 text-blue-700 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Total Placed Orders</span>
                                <span class="text-2xl font-black text-slate-850 block mt-0.5">{{ $orderCount }}</span>
                            </div>
                        </div>

                        <div class="p-5 bg-emerald-50/50 rounded-2xl flex items-center gap-4">
                            <div class="p-3 bg-emerald-100 text-emerald-700 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Total Spent</span>
                                <span class="text-2xl font-black text-slate-850 block mt-0.5">₹{{ number_format($totalSpent, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            @endif

        </div>
    </div>
</x-app-layout>
