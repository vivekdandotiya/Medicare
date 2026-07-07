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

        <!-- Search and Filters Container -->
        <form action="{{ route('medicines.index') }}" method="GET" x-data="{
            maxPrice: {{ request('max_price', 1000) }},
            compareItems: [],
            showCompareModal: false,
            toggleCompare(item) {
                if (this.isInCompare(item.id)) {
                    this.compareItems = this.compareItems.filter(i => i.id !== item.id);
                } else {
                    if (this.compareItems.length >= 3) {
                        alert('You can compare a maximum of 3 medicines.');
                        return;
                    }
                    this.compareItems.push(item);
                }
            },
            isInCompare(id) {
                return this.compareItems.some(i => i.id === id);
            },
            clearCompare() {
                this.compareItems = [];
            }
        }">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                
                <!-- Left Sidebar: Filter controls -->
                <div class="lg:col-span-1 bg-white rounded-3xl border border-slate-200/60 p-6 h-fit sticky top-24 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                        <h2 class="text-base font-black text-slate-800 tracking-tight flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter Essentials
                        </h2>
                        <a href="{{ route('medicines.index') }}" class="text-xs font-bold text-slate-400 hover:text-teal-600 transition">Reset</a>
                    </div>

                    <!-- Category List -->
                    <div class="mb-6">
                        <label class="block text-[10px] font-extrabold text-slate-450 uppercase tracking-widest mb-3">Health Category</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="radio" name="category" value="" {{ request('category') == '' ? 'checked' : '' }}
                                       class="rounded-full border-slate-250 text-teal-650 focus:ring-teal-500 h-4 w-4" onchange="this.form.submit()">
                                <span class="text-xs font-semibold text-slate-650 group-hover:text-slate-900 transition">All Categories</span>
                            </label>
                            @foreach($categories as $category)
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="radio" name="category" value="{{ $category->id }}" {{ request('category') == $category->id ? 'checked' : '' }}
                                           class="rounded-full border-slate-250 text-teal-650 focus:ring-teal-500 h-4 w-4" onchange="this.form.submit()">
                                    <span class="text-xs font-semibold text-slate-650 group-hover:text-slate-900 transition">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Brand List -->
                    <div class="mb-6 border-t border-slate-100 pt-6">
                        <label class="block text-[10px] font-extrabold text-slate-455 uppercase tracking-widest mb-3">Pharmaceutical Brand</label>
                        <select name="brand" onchange="this.form.submit()" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-700 bg-slate-50 focus:border-teal-500 focus:outline-none transition">
                            <option value="">All Brands</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Slider -->
                    <div class="mb-6 border-t border-slate-100 pt-6">
                        <div class="flex justify-between items-center mb-3">
                            <label class="text-[10px] font-extrabold text-slate-455 uppercase tracking-widest">Max Price</label>
                            <span class="text-xs font-black text-teal-700 bg-teal-50 px-2 py-0.5 rounded border border-teal-200/35">₹<span x-text="maxPrice"></span></span>
                        </div>
                        <input type="range" name="max_price" min="50" max="1500" step="25" x-model="maxPrice"
                               class="w-full h-1.5 bg-slate-150 rounded-lg appearance-none cursor-pointer accent-teal-650"
                               onchange="this.form.submit()">
                        <div class="flex justify-between text-[10px] text-slate-400 font-bold mt-2">
                            <span>₹50</span>
                            <span>₹1,500</span>
                        </div>
                    </div>

                    <!-- Prescription Required Toggle -->
                    <div class="mb-6 border-t border-slate-100 pt-6 font-sans">
                        <label class="flex items-center justify-between cursor-pointer group">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-750 group-hover:text-slate-900 transition">Prescription Only (Rx)</span>
                                <span class="text-[10px] text-slate-400 font-medium">Show prescription-only items</span>
                            </div>
                            <div class="relative">
                                <input type="checkbox" name="rx_only" value="1" {{ request('rx_only') == '1' ? 'checked' : '' }}
                                       class="sr-only peer" onchange="this.form.submit()">
                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-teal-600"></div>
                            </div>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-teal-650 hover:bg-teal-700 text-white font-bold text-xs py-3 rounded-xl transition shadow-md shadow-teal-500/10 active:scale-95">
                        Apply Current Filters
                    </button>
                </div>

                <!-- Right Column: Search + Active Pills + Grid -->
                <div class="lg:col-span-3">
                    
                    <!-- Search Header Input Bar -->
                    <div class="bg-white rounded-2xl border border-slate-200/50 p-4 shadow-sm mb-6 flex gap-3 items-center hover:border-teal-500/10 transition">
                        <div class="flex-1 relative">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search by medicine name, brand, usage (e.g. 'fever', 'cough')..."
                                   class="w-full border border-slate-150 rounded-xl pl-10 pr-4 py-3 text-xs focus:border-teal-500 focus:outline-none transition bg-slate-50/50 font-semibold text-slate-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 absolute left-3.5 top-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <button type="submit" class="bg-teal-650 hover:bg-teal-700 text-white font-bold text-xs px-6 py-3 rounded-xl transition shadow-sm active:scale-95">
                            Search
                        </button>
                    </div>

                    <!-- Active Filter Badges -->
                    @if(request('search') || request('category') || request('brand') || request('max_price') || request('rx_only'))
                        <div class="flex flex-wrap gap-2 items-center mb-6">
                            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Active Filters:</span>
                            @if(request('search'))
                                <span class="inline-flex items-center gap-1 bg-teal-50 border border-teal-200/50 text-teal-700 text-[10px] font-bold px-2.5 py-1 rounded-md">
                                    Search: "{{ request('search') }}"
                                </span>
                            @endif
                            @if(request('category'))
                                @php $catName = $categories->find(request('category'))?->name; @endphp
                                @if($catName)
                                    <span class="inline-flex items-center gap-1 bg-teal-50 border border-teal-200/50 text-teal-700 text-[10px] font-bold px-2.5 py-1 rounded-md">
                                        Category: {{ $catName }}
                                    </span>
                                  @endif
                            @endif
                            @if(request('brand'))
                                @php $brandName = $brands->find(request('brand'))?->name; @endphp
                                @if($brandName)
                                    <span class="inline-flex items-center gap-1 bg-teal-50 border border-teal-200/50 text-teal-700 text-[10px] font-bold px-2.5 py-1 rounded-md">
                                        Brand: {{ $brandName }}
                                    </span>
                                @endif
                            @endif
                            @if(request('max_price'))
                                <span class="inline-flex items-center gap-1 bg-teal-50 border border-teal-200/50 text-teal-700 text-[10px] font-bold px-2.5 py-1 rounded-md">
                                    Price &lt;= ₹{{ request('max_price') }}
                                </span>
                            @endif
                            @if(request('rx_only') == '1')
                                <span class="inline-flex items-center gap-1 bg-teal-50 border border-teal-200/50 text-teal-700 text-[10px] font-bold px-2.5 py-1 rounded-md">
                                    Rx Only
                                </span>
                            @endif
                            <a href="{{ route('medicines.index') }}" class="text-[10px] font-bold text-slate-500 hover:text-red-500 underline ml-2 transition">Clear All</a>
                        </div>
                    @endif

                    <!-- Products Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($medicines as $medicine)
                            <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm hover:shadow-xl hover:border-teal-500/10 transition duration-300 overflow-hidden flex flex-col justify-between group relative">
                                <div class="p-5">
                                    <div @click="window.dispatchEvent(new CustomEvent('open-medicine-modal', { detail: { id: {{ $medicine->id }}, name: '{{ addslashes($medicine->name) }}', brand_name: '{{ addslashes($medicine->brand->name) }}', category_name: '{{ addslashes($medicine->category->name) }}', selling_price: {{ $medicine->selling_price }}, mrp: {{ $medicine->mrp }}, description: '{{ addslashes($medicine->description) }}', prescription_required: {{ $medicine->prescription_required ? 'true' : 'false' }}, image: '{{ $medicine->image }}', stock_quantity: {{ $medicine->stock_quantity }} } }))"
                                         class="relative w-full h-44 bg-slate-50 rounded-2xl overflow-hidden mb-4 flex items-center justify-center border border-slate-100/50 cursor-pointer hover:opacity-90 transition">
                                        @if($medicine->image)
                                            <img src="{{ asset($medicine->image) }}" alt="{{ $medicine->name }}" class="object-contain p-4 h-full w-full group-hover:scale-105 transition duration-300">
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-teal-255" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z" />
                                            </svg>
                                        @endif

                                        @if($medicine->prescription_required)
                                            <span class="absolute top-3 right-3 bg-red-500/10 border border-red-500/20 text-red-655 text-[9px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider backdrop-blur-md">Rx Required</span>
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $medicine->brand->name }}</span>
                                        <span class="text-[10px] text-teal-655 bg-teal-50 border border-teal-500/10 font-bold px-2 py-0.5 rounded-full">{{ $medicine->category->name }}</span>
                                    </div>
                                    <h3 @click="window.dispatchEvent(new CustomEvent('open-medicine-modal', { detail: { id: {{ $medicine->id }}, name: '{{ addslashes($medicine->name) }}', brand_name: '{{ addslashes($medicine->brand->name) }}', category_name: '{{ addslashes($medicine->category->name) }}', selling_price: {{ $medicine->selling_price }}, mrp: {{ $medicine->mrp }}, description: '{{ addslashes($medicine->description) }}', prescription_required: {{ $medicine->prescription_required ? 'true' : 'false' }}, image: '{{ $medicine->image }}', stock_quantity: {{ $medicine->stock_quantity }} } }))"
                                        class="font-bold text-slate-900 text-base mt-2 group-hover:text-teal-700 transition line-clamp-1 cursor-pointer" title="{{ $medicine->name }}">{{ $medicine->name }}</h3>
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
                                                    <button type="submit" formaction="{{ route('cart.add', $medicine) }}" formmethod="POST" class="flex-1 bg-teal-650 hover:bg-teal-700 text-white font-bold text-xs py-2.5 rounded-xl transition shadow-md shadow-teal-600/10 active:scale-95">
                                                        Add Cart
                                                    </button>
                                                    
                                                    <button type="submit" formaction="{{ route('cart.add', $medicine) }}" formmethod="POST" class="flex-1 bg-gradient-to-r from-orange-500 to-orange-650 hover:from-orange-650 hover:to-orange-700 text-white font-bold text-xs py-2.5 rounded-xl transition shadow-md shadow-orange-500/10 active:scale-95">
                                                        <input type="hidden" name="buy_now" value="1">
                                                        Buy Now
                                                    </button>
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
                                                    <button type="submit" formaction="{{ route('medicines.destroy', $medicine) }}" formmethod="POST" onclick="return confirm('Delete this product?');" class="flex-1 bg-red-50 hover:bg-red-100 border border-red-200 text-red-650 font-bold text-xs py-2.5 rounded-xl transition">
                                                        @method('DELETE')
                                                        Delete
                                                    </button>
                                                </div>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="w-full bg-teal-650 hover:bg-teal-700 text-white font-bold text-xs py-2.5 rounded-xl transition text-center block shadow-md shadow-teal-600/10">
                                                Log in to Buy
                                            </a>
                                    </div>

                                    <!-- Compare Toggle Button -->
                                    <button type="button" @click="toggleCompare({{ json_encode([
                                        'id' => $medicine->id,
                                        'name' => $medicine->name,
                                        'brand' => $medicine->brand->name,
                                        'category' => $medicine->category->name,
                                        'price' => $medicine->selling_price,
                                        'mrp' => $medicine->mrp,
                                        'description' => $medicine->description ?? 'No description provided.',
                                        'rx' => $medicine->prescription_required ? 'Yes' : 'No',
                                        'image' => $medicine->image
                                    ]) }})"
                                            class="w-full mt-3 border border-slate-200 hover:border-teal-500 hover:bg-teal-50/20 text-slate-500 hover:text-teal-655 font-bold text-[11px] py-2 rounded-xl transition flex items-center justify-center gap-1 active:scale-[0.98]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
                                        </svg>
                                        <span x-text="isInCompare({{ $medicine->id }}) ? 'Added to Compare' : 'Compare Specs'"></span>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-slate-500 text-center col-span-full py-8">No medicines found matching those criteria.</p>
                        @endforelse
                    </div>

                    <div class="mt-8">
                        {{ $medicines->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </form>

        <!-- Sticky Bottom Compare Bar -->
        <div x-show="compareItems.length > 0" class="fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 shadow-2xl py-4 px-6 transition duration-300 font-sans" style="display: none;">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex items-center -space-x-4">
                        <template x-for="item in compareItems" :key="item.id">
                            <div class="w-12 h-12 rounded-full border-2 border-white bg-slate-50 flex items-center justify-center overflow-hidden shadow-md">
                                <template x-if="item.image">
                                    <img :src="'/' + item.image" class="w-full h-full object-contain p-1">
                                </template>
                                <template x-if="!item.image">
                                    <span class="text-[9px] font-bold text-teal-700 font-sans" x-text="item.name.substring(0,3)"></span>
                                </template>
                            </div>
                        </template>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-slate-800 text-xs">Compare Medicines</h4>
                        <p class="text-[10px] text-slate-400 font-bold block mt-0.5" x-text="`${compareItems.length} of 3 selected`"></p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" @click="clearCompare()" class="text-xs font-bold text-slate-400 hover:text-slate-655 transition px-3 py-2 rounded-xl">Clear All</button>
                    <button type="button" @click="showCompareModal = true" class="bg-teal-655 hover:bg-teal-700 text-white font-bold text-xs px-6 py-2.5 rounded-xl transition shadow-md shadow-teal-500/10 active:scale-95">Compare Specs</button>
                </div>
            </div>
        </div>

        <!-- Comparison Details Modal -->
        <div x-show="showCompareModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 font-sans" style="display: none;">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showCompareModal = false"></div>
            <div class="bg-white rounded-3xl max-w-4xl w-full p-6 shadow-2xl relative z-10 border border-slate-200">
                <div class="flex justify-between items-center pb-4 border-b border-slate-100 mb-6">
                    <h4 class="font-black text-slate-900 text-base">Medicine Comparison Table</h4>
                    <button type="button" @click="showCompareModal = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center font-bold transition">&times;</button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-150">
                                <th class="py-3 font-bold text-slate-400 uppercase tracking-widest min-w-[120px]">Parameters</th>
                                <template x-for="item in compareItems" :key="item.id">
                                    <th class="py-3 px-4 font-bold text-slate-800 text-sm max-w-[200px]" x-text="item.name"></th>
                                </template>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <!-- Manufacturer -->
                            <tr>
                                <td class="py-3 font-bold text-slate-500 uppercase tracking-wider">Manufacturer / Brand</td>
                                <template x-for="item in compareItems" :key="item.id">
                                    <td class="py-3 px-4 font-semibold text-slate-700" x-text="item.brand"></td>
                                </template>
                            </tr>
                            <!-- Category -->
                            <tr>
                                <td class="py-3 font-bold text-slate-500 uppercase tracking-wider">Health Category</td>
                                <template x-for="item in compareItems" :key="item.id">
                                    <td class="py-3 px-4 font-semibold text-slate-700" x-text="item.category"></td>
                                </template>
                            </tr>
                            <!-- Selling Price -->
                            <tr>
                                <td class="py-3 font-bold text-slate-500 uppercase tracking-wider">Selling Price</td>
                                <template x-for="item in compareItems" :key="item.id">
                                    <td class="py-3 px-4 font-extrabold text-teal-700 text-sm" x-text="`₹${item.price.toFixed(2)}`"></td>
                                </template>
                            </tr>
                            <!-- MRP -->
                            <tr>
                                <td class="py-3 font-bold text-slate-500 uppercase tracking-wider">MRP Price</td>
                                <template x-for="item in compareItems" :key="item.id">
                                    <td class="py-3 px-4 font-semibold text-slate-400 line-through" x-text="`₹${item.mrp.toFixed(2)}`"></td>
                                </template>
                            </tr>
                            <!-- Rx Required -->
                            <tr>
                                <td class="py-3 font-bold text-slate-500 uppercase tracking-wider">Rx Required</td>
                                <template x-for="item in compareItems" :key="item.id">
                                    <td class="py-3 px-4">
                                        <span :class="item.rx === 'Yes' ? 'bg-red-50 text-red-650' : 'bg-emerald-50 text-emerald-650'"
                                              class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider"
                                              x-text="item.rx"></span>
                                    </td>
                                </template>
                            </tr>
                            <!-- Description -->
                            <tr>
                                <td class="py-3 font-bold text-slate-500 uppercase tracking-wider">Details</td>
                                <template x-for="item in compareItems" :key="item.id">
                                    <td class="py-3 px-4 font-medium text-slate-505 leading-relaxed max-w-[220px]" x-text="item.description"></td>
                                </template>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>