<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Medicare - Premium Online Pharmacy & Healthcare Store</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <!-- Vite Styles & Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            h1, h2, h3, h4, .font-display {
                font-family: 'Outfit', sans-serif;
            }
        </style>
    </head>
    <body class="bg-slate-50/50 text-slate-800 antialiased selection:bg-teal-500 selection:text-white">
        
        <!-- Header / Navigation -->
        <header class="glass-panel sticky top-0 z-50 shadow-sm border-b border-slate-200/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex justify-between items-center">
                
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <span class="w-11 h-11 rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 flex items-center justify-center text-white font-extrabold text-xl shadow-lg shadow-teal-500/20 transition group-hover:scale-105">M</span>
                        <span class="text-2xl font-bold tracking-tight text-teal-900">Medi<span class="text-orange-500">care</span></span>
                    </a>
                </div>

                <!-- Navigation & Auth Links -->
                <div class="flex items-center gap-6">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-slate-600 hover:text-teal-600 font-semibold text-sm transition">Dashboard</a>
                        
                        <!-- Cart Icon -->
                        <a href="{{ route('cart.index') }}" class="relative flex items-center justify-center w-11 h-11 rounded-xl bg-slate-100/80 hover:bg-teal-50 hover:text-teal-600 text-slate-600 transition shadow-sm border border-slate-200/40">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            @php
                                $cart = \App\Models\Cart::where('user_id', auth()->id())->first();
                                $cartCount = $cart ? $cart->items()->sum('quantity') : 0;
                            @endphp
                            @if($cartCount > 0)
                                <span class="absolute -top-1.5 -right-1.5 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-full text-[10px] w-5 h-5 flex items-center justify-center font-extrabold shadow-md shadow-orange-550/20 animate-pulse">{{ $cartCount }}</span>
                            @endif
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-slate-100 hover:bg-red-550/10 hover:text-red-650 border border-slate-200/60 text-slate-650 font-semibold text-sm px-4 py-2 rounded-xl transition">Log Out</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-600 hover:text-teal-600 font-semibold text-sm transition">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white font-bold text-sm px-6 py-2.5 rounded-xl transition shadow-lg shadow-teal-500/20">Register</a>
                        @endif
                    @endauth
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-slate-900 via-teal-950 to-slate-900 text-white py-20 px-4 overflow-hidden border-b border-teal-950/20">
            <!-- Decorative blur backdrops -->
            <div class="absolute w-[500px] h-[500px] bg-teal-500/10 rounded-full filter blur-[120px] -top-40 -left-40 animate-pulse"></div>
            <div class="absolute w-[400px] h-[400px] bg-orange-500/5 rounded-full filter blur-[100px] -bottom-20 -right-20"></div>

            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-center relative z-10">
                <div class="lg:col-span-7">
                    <span class="inline-flex items-center gap-1.5 bg-teal-500/20 border border-teal-500/30 text-teal-300 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-6">
                        <span class="w-1.5 h-1.5 bg-teal-400 rounded-full animate-ping"></span>
                        Premium Healthcare Solution
                    </span>
                    <h1 class="text-4xl sm:text-6xl font-extrabold leading-tight tracking-tight">
                        Genuine Medicines. <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-emerald-400 to-orange-400">Delivered Safely & Fast.</span>
                    </h1>
                    <p class="text-slate-300 mt-6 text-lg max-w-xl font-light leading-relaxed">
                        Medicare is your trusted digital healthcare partner. Access original medications, syrups, and wellness supplements with up to <span class="font-bold text-orange-400">25% savings</span>.
                    </p>
                    
                    <!-- Search Widget -->
                    <form action="{{ route('medicines.index') }}" method="GET" class="mt-8 bg-white p-2 rounded-2xl flex shadow-2xl shadow-slate-950/40 max-w-xl border border-slate-200/20">
                        <input type="text" name="search" placeholder="Search for syrups, tablets, herbal remedies..." class="flex-1 px-4 py-3 text-slate-800 rounded-xl focus:outline-none focus:ring-0 text-sm placeholder:text-slate-400 font-medium">
                        <button type="submit" class="bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-650 text-white font-bold text-sm px-8 py-3 rounded-xl transition-all shadow-md shadow-orange-500/20 hover:scale-[1.02] flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            Search
                        </button>
                    </form>
                </div>
                <div class="lg:col-span-5 hidden lg:flex justify-center relative">
                    <div class="glass-panel bg-white/5 border border-white/10 p-8 rounded-3xl shadow-2xl max-w-md backdrop-blur-md relative overflow-hidden group hover:border-teal-500/30 transition duration-500">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-teal-500/10 rounded-full blur-2xl group-hover:bg-teal-500/20 transition duration-500"></div>
                        
                        <div class="flex items-start gap-4 mb-6">
                            <span class="p-3.5 rounded-2xl bg-teal-500/10 text-teal-400 border border-teal-500/20 flex-shrink-0"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg></span>
                            <div>
                                <h3 class="font-bold text-base text-slate-100">100% Genuine Guarantee</h3>
                                <p class="text-xs text-slate-400 mt-1 leading-relaxed">Directly sourced from trusted global manufacturers and tested clinics.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4 mb-6">
                            <span class="p-3.5 rounded-2xl bg-teal-500/10 text-teal-400 border border-teal-500/20 flex-shrink-0"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></span>
                            <div>
                                <h3 class="font-bold text-base text-slate-100">Super Fast Delivery</h3>
                                <p class="text-xs text-slate-400 mt-1 leading-relaxed">Swift shipping. Free delivery on orders over ₹500.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <span class="p-3.5 rounded-2xl bg-teal-500/10 text-teal-400 border border-teal-500/20 flex-shrink-0"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg></span>
                            <div>
                                <h3 class="font-bold text-base text-slate-100">Interactive Medical Advisor</h3>
                                <p class="text-xs text-slate-400 mt-1 leading-relaxed">Ask our AI Health Assistant chatbot in the bottom right corner for immediate recommendation.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Categories Section -->
        <section class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center md:text-left mb-12 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-950">Shop Healthcare Categories</h2>
                    <p class="text-slate-500 mt-2 font-medium">Explore premium items designed to support your wellness journey</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @forelse($categories as $category)
                    <a href="{{ route('medicines.index', ['category' => $category->id]) }}" class="bg-white p-6 rounded-2xl border border-slate-200/50 shadow-sm hover:shadow-lg hover:border-teal-500/25 transition duration-300 text-center group flex flex-col items-center">
                        <div class="w-16 h-16 rounded-2xl bg-teal-550/5 text-teal-650 flex items-center justify-center mb-4 group-hover:bg-gradient-to-br group-hover:from-teal-500 group-hover:to-emerald-600 group-hover:text-white group-hover:rotate-6 transition duration-300 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm group-hover:text-teal-750 transition text-center">{{ $category->name }}</h3>
                        <span class="text-[11px] text-slate-400 mt-1 font-semibold group-hover:text-teal-600/70 transition">{{ count($category->medicines ?? []) }} items</span>
                    </a>
                @empty
                    <p class="text-slate-500 text-center col-span-full">No categories available.</p>
                @endforelse
            </div>
        </section>

        <!-- Featured Medicines Section -->
        <section class="py-20 bg-white border-t border-b border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-12 gap-4">
                    <div>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-950">Featured Healthcare Products</h2>
                        <p class="text-slate-500 mt-2 font-medium">Find popular medicines, syrups, and vitamins in store</p>
                    </div>
                    <a href="{{ route('medicines.index') }}" class="text-teal-650 hover:text-teal-700 font-bold text-sm flex items-center gap-1.5 group transition">
                        View All Catalog
                        <span class="group-hover:translate-x-1 transition duration-200">&rarr;</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @forelse($medicines as $medicine)
                        <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm hover:shadow-xl hover:border-teal-500/10 transition duration-300 overflow-hidden flex flex-col justify-between group relative">
                            
                            <!-- Card Body -->
                            <div class="p-5">
                                <div class="relative w-full h-44 bg-slate-50 rounded-2xl overflow-hidden mb-4 flex items-center justify-center border border-slate-100/50">
                                    @if($medicine->image)
                                        <img src="{{ asset($medicine->image) }}" alt="{{ $medicine->name }}" class="object-contain p-4 h-full w-full group-hover:scale-105 transition duration-300">
                                    @else
                                        <!-- Fallback pill SVG -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-teal-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z" />
                                        </svg>
                                    @endif

                                    <!-- Prescription requirement badge -->
                                    @if($medicine->prescription_required)
                                        <span class="absolute top-3 right-3 bg-red-500/10 border border-red-500/20 text-red-650 text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider backdrop-blur-md">Rx Required</span>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $medicine->brand->name }}</span>
                                    <span class="text-[10px] text-teal-650 bg-teal-50 border border-teal-500/10 font-bold px-2 py-0.5 rounded-full">{{ $medicine->category->name }}</span>
                                </div>
                                <h3 class="font-bold text-slate-900 text-base mt-2 group-hover:text-teal-700 transition line-clamp-1" title="{{ $medicine->name }}">{{ $medicine->name }}</h3>
                                <p class="text-slate-450 text-[11px] mt-2 line-clamp-2 leading-relaxed h-8">{{ $medicine->description ?? 'Premium medicine formulation for dynamic care.' }}</p>
                            </div>

                            <!-- Card Footer -->
                            <div class="px-5 pb-5 pt-0 border-t border-slate-100/80 mt-2">
                                <div class="flex items-baseline gap-2 mt-4">
                                    <span class="text-xl font-black text-teal-750">₹{{ number_format($medicine->selling_price, 2) }}</span>
                                    @if($medicine->mrp > $medicine->selling_price)
                                        <span class="text-xs text-slate-400 line-through">₹{{ number_format($medicine->mrp, 2) }}</span>
                                        @php
                                            $discount = (($medicine->mrp - $medicine->selling_price) / $medicine->mrp) * 100;
                                        @endphp
                                        <span class="text-[10px] font-extrabold text-orange-600 bg-orange-50 px-1.5 py-0.5 rounded-md border border-orange-500/10">{{ round($discount) }}% OFF</span>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-4 flex gap-2">
                                    @auth
                                        @if(auth()->user()->hasRole('customer'))
                                            @if($medicine->stock_quantity > 0)
                                                <form action="{{ route('cart.add', $medicine) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    <button type="submit" class="w-full bg-teal-650 hover:bg-teal-700 text-white font-bold text-xs py-2.5 rounded-xl transition shadow-md shadow-teal-600/10 active:scale-95">
                                                        Add Cart
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('cart.add', $medicine) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    <input type="hidden" name="buy_now" value="1">
                                                    <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-orange-650 hover:from-orange-650 hover:to-orange-750 text-white font-bold text-xs py-2.5 rounded-xl transition shadow-md shadow-orange-500/10 active:scale-95">
                                                        Buy Now
                                                    </button>
                                                </form>
                                            @else
                                                <button class="w-full bg-slate-100 text-slate-450 cursor-not-allowed font-bold text-xs py-2.5 rounded-xl border border-slate-200/50" disabled>
                                                    Out of Stock
                                                </button>
                                            @endif
                                        @else
                                            <a href="{{ route('medicines.edit', $medicine) }}" class="w-full bg-slate-550/5 hover:bg-slate-550/10 border border-slate-200 text-slate-700 font-bold text-xs py-2.5 rounded-xl transition text-center block">
                                                Edit Product
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="w-full bg-teal-650 hover:bg-teal-700 text-white font-bold text-xs py-2.5 rounded-xl transition text-center block shadow-md shadow-teal-600/10">
                                            Log in to Buy
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-500 text-center col-span-full py-8">No medicines found. Please contact admin to seed medicines.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Wellness & Health Insights Section -->
        <section x-data="{
            activeArticle: null,
            readProgress: 0,
            simulatedTimer: null,
            openModal(article) {
                this.activeArticle = article;
                this.readProgress = 0;
                if (this.simulatedTimer) clearInterval(this.simulatedTimer);
                this.simulatedTimer = setInterval(() => {
                    if (this.readProgress < 100) {
                        this.readProgress += 5;
                    } else {
                        clearInterval(this.simulatedTimer);
                    }
                }, 100);
            },
            closeModal() {
                this.activeArticle = null;
                if (this.simulatedTimer) clearInterval(this.simulatedTimer);
            },
            articles: [
                {
                    id: 1,
                    title: 'Natural Remedies for Cold & Dry Cough',
                    subtitle: 'Combating Winter Allergies & Throat Pain',
                    category: 'Herbal Care',
                    readTime: '4 min read',
                    author: 'Dr. Ananya Sharma, MD',
                    avatar: 'AS',
                    date: 'June 25, 2026',
                    content: 'As winter approaches, dry cough and throat irritation become extremely common. While clinical syrups like Dabur Honitus or Himalaya Koflet provide swift relief, natural lifestyle shifts can accelerate recovery. Integrating ginger-infused warm water, raw honey, and steam inhalation before bed helps soothe bronchial pathways. Remember: if throat irritation persists for more than 5 days or is accompanied by high fever, consult a certified physician.',
                    recommendations: [
                        { name: 'Himalaya Koflet', link: '/medicines?search=Koflet' },
                        { name: 'Dabur Honitus', link: '/medicines?search=Honitus' },
                        { name: 'Strepsils Lozenges', link: '/medicines?search=Strepsils' }
                    ],
                    bgColor: 'from-emerald-50/50 to-teal-50/30',
                    borderCol: 'border-teal-500/20',
                    textColor: 'text-teal-700',
                    badgeBg: 'bg-teal-50 text-teal-700'
                },
                {
                    id: 2,
                    title: 'Daily Vitamins: Boost Immunity & Energy',
                    subtitle: 'Understanding Limcee & Essential Nutrients',
                    category: 'Nutrition & Health',
                    readTime: '3 min read',
                    author: 'Dr. Rahul Mehta, DNB',
                    avatar: 'RM',
                    date: 'June 24, 2026',
                    content: 'Vitamin C is a powerful antioxidant that supports cellular immune function. Daily supplements like Limcee (500mg chewable Vitamin C tablets) assist in strengthening skin barriers, tissue healing, and iron absorption. Regular intake is crucial during weather transitions. Ensure you combine supplements with citrus fruits, leafy greens, and adequate hydration for optimal absorption and energy levels.',
                    recommendations: [
                        { name: 'Limcee Vitamin C', link: '/medicines?search=Limcee' }
                    ],
                    bgColor: 'from-amber-50/50 to-orange-50/30',
                    borderCol: 'border-orange-500/20',
                    textColor: 'text-orange-700',
                    badgeBg: 'bg-orange-50 text-orange-700'
                },
                {
                    id: 3,
                    title: 'Understanding Antibiotics and Prescriptions',
                    subtitle: 'Safety Rules & Guidelines for Medication Courses',
                    category: 'Clinical Guide',
                    readTime: '5 min read',
                    author: 'Dr. Sarah Pierce, PharmD',
                    avatar: 'SP',
                    date: 'June 22, 2026',
                    content: 'Antibiotic resistance is a rising clinical concern. It occurs when bacteria adapt to survive the drugs meant to kill them. Always complete the entire prescribed course of medicines, even if symptoms vanish early. Stopping treatment prematurely can lead to recurrent, stronger infections. Only purchase antibiotics with a valid doctor prescription certified by registered practitioners.',
                    recommendations: [
                        { name: 'Upload Prescription', link: '/prescriptions' }
                    ],
                    bgColor: 'from-blue-50/50 to-indigo-50/30',
                    borderCol: 'border-blue-500/20',
                    textColor: 'text-blue-700',
                    badgeBg: 'bg-blue-50 text-blue-700'
                }
            ]
        }" class="py-16 bg-slate-50 border-t border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Header -->
                <div class="text-center max-w-3xl mx-auto mb-12">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-teal-50 text-teal-700 border border-teal-200/50 mb-3 uppercase tracking-wider">
                        Wellness & Health Insights
                    </span>
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight sm:text-4xl">
                        Expert Advice & Clinical Guides
                    </h2>
                    <p class="mt-4 text-slate-550 font-normal leading-relaxed text-sm sm:text-base">
                        Read trusted health articles curated by professional medical advisors to assist you in everyday healthcare, wellness, and symptom management.
                    </p>
                </div>

                <!-- Articles Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <template x-for="article in articles" :key="article.id">
                        <div class="bg-white rounded-2xl border border-slate-150 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-350 flex flex-col group h-full">
                            <!-- Card Header Splash -->
                            <div :class="`h-2 bg-gradient-to-r ${article.bgColor}`" class="w-full"></div>
                            
                            <div class="p-6 flex-1 flex flex-col justify-between">
                                <div>
                                    <!-- Meta -->
                                    <div class="flex items-center justify-between mb-4">
                                        <span x-text="article.category" :class="article.badgeBg" class="text-[11px] font-bold px-2.5 py-1 rounded-md tracking-wide"></span>
                                        <span x-text="article.readTime" class="text-xs text-slate-400 font-medium"></span>
                                    </div>

                                    <!-- Title -->
                                    <h3 x-text="article.title" class="text-lg font-black text-slate-800 tracking-tight group-hover:text-teal-650 transition duration-150 mb-2 leading-snug"></h3>
                                    <!-- Subtitle -->
                                    <p x-text="article.subtitle" class="text-xs text-slate-500 font-medium mb-4 leading-relaxed line-clamp-2"></p>
                                </div>

                                <div class="mt-6 border-t border-slate-100 pt-4 flex items-center justify-between">
                                    <!-- Author info -->
                                    <div class="flex items-center gap-2.5">
                                        <div :class="`w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center font-bold text-xs ${article.textColor}`" x-text="article.avatar"></div>
                                        <div>
                                            <p class="text-[11px] font-bold text-slate-700" x-text="article.author"></p>
                                            <p class="text-[10px] text-slate-400 font-medium" x-text="article.date"></p>
                                        </div>
                                    </div>

                                    <!-- Read CTA Button -->
                                    <button @click="openModal(article)" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-50 border border-slate-200 text-slate-600 hover:bg-teal-50 hover:border-teal-300 hover:text-teal-700 transition active:scale-95">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0/0/24/24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9/5l7/7-7/7" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Reading Modal Overlay -->
            <div x-show="activeArticle" 
                 class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-6"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 style="display: none;">
                
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeModal()"></div>

                <!-- Modal Content -->
                <div class="bg-white rounded-3xl max-w-2xl w-full shadow-2xl overflow-hidden relative z-10 border border-slate-200">
                    <!-- Reading Progress Bar -->
                    <div class="h-1.5 w-full bg-slate-100">
                        <div class="h-full bg-teal-500 transition-all duration-100" :style="`width: ${readProgress}%`"></div>
                    </div>

                    <!-- Header -->
                    <div class="p-6 sm:p-8 pb-4 flex justify-between items-start">
                        <div>
                            <span x-text="activeArticle?.category" :class="activeArticle?.badgeBg" class="text-xs font-bold px-3 py-1 rounded-md tracking-wider"></span>
                            <h2 x-text="activeArticle?.title" class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight mt-3 leading-snug"></h2>
                            <p x-text="activeArticle?.subtitle" class="text-sm text-slate-500 font-medium mt-1 leading-relaxed"></p>
                        </div>
                        <button @click="closeModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0/0/24/24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6/18L18/6M6/6l12/12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Author Meta & Date -->
                    <div class="px-6 sm:px-8 pb-4 border-b border-slate-100 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-teal-50 flex items-center justify-center font-bold text-sm text-teal-700" x-text="activeArticle?.avatar"></div>
                        <div>
                            <p class="text-sm font-bold text-slate-800" x-text="activeArticle?.author"></p>
                            <p class="text-xs text-slate-400 font-medium" x-text="`Published: ${activeArticle?.date} • ${activeArticle?.readTime}`"></p>
                        </div>
                    </div>

                    <!-- Body Content -->
                    <div class="p-6 sm:p-8 max-h-[350px] overflow-y-auto">
                        <p x-text="activeArticle?.content" class="text-slate-600 font-normal leading-relaxed text-sm sm:text-base whitespace-pre-line"></p>
                        
                        <!-- Recommended Products Widget -->
                        <div class="mt-8 bg-slate-50 rounded-2xl p-5 border border-slate-250/30">
                            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Recommended Products / Action</h4>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="rec in activeArticle?.recommendations">
                                    <a :href="rec.link" class="inline-flex items-center gap-1 bg-white hover:bg-teal-50 border border-slate-200 hover:border-teal-300 text-xs font-bold text-slate-700 hover:text-teal-700 px-3 py-2 rounded-xl transition shadow-sm active:scale-95">
                                        <svg xmlns="http://www.w3.org/2050/svg" class="h-3.5 w-3.5 text-teal-500" fill="none" viewBox="0/0/24/24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16/11V7a4/4/0/00-8/0v4M5/9h14l1/12H4L5/9z" />
                                        </svg>
                                        <span x-text="rec.name"></span>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="bg-slate-50 border-t border-slate-100 p-4 sm:p-6 flex justify-end gap-3">
                        <button @click="closeModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs transition active:scale-95">
                            Close Guide
                        </button>
                        <a href="/medicines" class="px-5 py-2.5 rounded-xl bg-teal-650 hover:bg-teal-700 text-white font-bold text-xs transition shadow-md shadow-teal-600/10 active:scale-95">
                            Browse Store
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-slate-900 text-slate-450 py-16 border-t border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                    <div class="md:col-span-2">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-10 h-10 rounded-xl bg-teal-500 flex items-center justify-center text-white font-extrabold text-lg shadow-md shadow-teal-500/20">M</span>
                            <span class="text-2xl font-bold tracking-tight text-white">Medi<span class="text-orange-500">care</span></span>
                        </div>
                        <p class="text-slate-400 text-sm max-w-sm font-light leading-relaxed">
                            Medicare is India's leading and trusted online medical retailer. We provide direct access to verified clinical brands, fast shipping, and support.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-white font-bold text-sm mb-4 uppercase tracking-wider">Quick Links</h4>
                        <ul class="space-y-2 text-sm font-medium">
                            <li><a href="{{ route('medicines.index') }}" class="hover:text-teal-400 transition">Medicines Catalog</a></li>
                            <li><a href="{{ route('prescriptions.index') }}" class="hover:text-teal-400 transition">Prescriptions</a></li>
                            <li><a href="{{ route('orders.index') }}" class="hover:text-teal-400 transition">My Orders</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-bold text-sm mb-4 uppercase tracking-wider">Support</h4>
                        <ul class="space-y-2 text-sm font-medium">
                            <li><a href="#" class="hover:text-teal-400 transition">Privacy Policy</a></li>
                            <li><a href="#" class="hover:text-teal-400 transition">Terms of Service</a></li>
                            <li><a href="#" class="hover:text-teal-400 transition">Contact Support</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-slate-800/80 pt-8 text-center sm:flex sm:justify-between sm:text-left">
                    <p class="text-xs text-slate-500">&copy; {{ date('Y') }} Medicare Premium Pharmacy. All rights reserved.</p>
                    <p class="text-xs text-slate-500 mt-2 sm:mt-0">Designed for professional pharmaceutical care.</p>
                </div>
            </div>
        </footer>

        <!-- Global Chatbot Widget -->
        @include('components.chatbot')
    </body>
</html>
