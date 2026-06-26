<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Search Medicines</h1>
                <p class="text-slate-500 text-sm mt-1 font-medium">Browse all available medicines and healthcare essentials</p>
            </div>

            @hasanyrole('admin|staff')
                <a href="{{ route('medicines.create') }}"
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white font-bold px-6 py-3 rounded-xl shadow-lg shadow-teal-500/20 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add Medicine
                </a>
            @endhasanyrole
        </div>

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

        <!-- Search and Filters Panel -->
        <div class="bg-white rounded-3xl border border-slate-200/50 p-6 shadow-sm mb-8 hover:border-teal-500/10 transition duration-300">
            <form action="{{ route('medicines.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <!-- Search Key -->
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-slate-455 uppercase tracking-widest mb-2">Search Product Name</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Type product name..." 
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-teal-500 focus:ring-teal-500 focus:outline-none transition bg-slate-50/50 font-medium">
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-455 uppercase tracking-widest mb-2">Category</label>
                    <select name="category" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-teal-500 focus:ring-teal-500 focus:outline-none transition bg-slate-50/50 font-bold text-slate-700">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Action buttons -->
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-teal-650 hover:bg-teal-700 text-white font-bold text-sm py-3 rounded-xl transition shadow-md shadow-teal-500/10 active:scale-95">
                        Apply Filters
                    </button>
                    <a href="{{ route('medicines.index') }}" class="bg-slate-100 hover:bg-slate-200 border border-slate-200/50 text-slate-650 font-bold text-sm py-3 px-5 rounded-xl transition text-center active:scale-95">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($medicines as $medicine)
                <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm hover:shadow-xl hover:border-teal-500/10 transition duration-300 overflow-hidden flex flex-col justify-between group relative">
                    <div class="p-5">
                        <div class="relative w-full h-44 bg-slate-50 rounded-2xl overflow-hidden mb-4 flex items-center justify-center border border-slate-100/50">
                            @if($medicine->image)
                                <img src="{{ asset($medicine->image) }}" alt="{{ $medicine->name }}" class="object-contain p-4 h-full w-full group-hover:scale-105 transition duration-300">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-teal-205" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z" />
                                </svg>
                            @endif

                            @if($medicine->prescription_required)
                                <span class="absolute top-3 right-3 bg-red-500/10 border border-red-500/20 text-red-650 text-[9px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider backdrop-blur-md">Rx Required</span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $medicine->brand->name }}</span>
                            <span class="text-[10px] text-teal-655 bg-teal-50 border border-teal-500/10 font-bold px-2 py-0.5 rounded-full">{{ $medicine->category->name }}</span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-base mt-2 group-hover:text-teal-700 transition line-clamp-1" title="{{ $medicine->name }}">{{ $medicine->name }}</h3>
                        <p class="text-slate-450 text-[11px] mt-2 line-clamp-2 leading-relaxed h-8">{{ $medicine->description ?? 'Premium medicine formulation for dynamic care.' }}</p>
                    </div>

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
                                            <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-orange-650 hover:from-orange-650 hover:to-orange-700 text-white font-bold text-xs py-2.5 rounded-xl transition shadow-md shadow-orange-500/10 active:scale-95">
                                                Buy Now
                                            </button>
                                        </form>
                                    @else
                                        <button class="w-full bg-slate-100 text-slate-450 cursor-not-allowed font-bold text-xs py-2.5 rounded-xl border border-slate-200/50" disabled>
                                            Out of Stock
                                        </button>
                                    @endif
                                @else
                                    <div class="flex-1 flex gap-2">
                                        <a href="{{ route('medicines.edit', $medicine) }}" class="flex-1 bg-slate-100 hover:bg-slate-200 border border-slate-200/60 text-slate-700 font-bold text-xs py-2.5 rounded-xl transition text-center block">
                                            Edit
                                        </a>
                                        <form action="{{ route('medicines.destroy', $medicine) }}" method="POST" onsubmit="return confirm('Delete this product?');" class="flex-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full bg-red-50 hover:bg-red-100 border border-red-200 text-red-650 font-bold text-xs py-2.5 rounded-xl transition">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
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
                <p class="text-slate-500 text-center col-span-full py-8">No medicines found matching those criteria.</p>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $medicines->links() }}
        </div>
    </div>
</x-app-layout>