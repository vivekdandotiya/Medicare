<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Search Medicines</h1>
                <p class="text-gray-500 text-sm mt-1">Browse all available medicines and healthcare essentials</p>
            </div>

            @hasanyrole('admin|staff')
                <a href="{{ route('medicines.create') }}"
                   class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white font-semibold px-5 py-2.5 rounded-lg shadow-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add Medicine
                </a>
            @endhasanyrole
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r-lg mb-8 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg mb-8 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Search and Filters Panel -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm mb-8">
            <form action="{{ route('medicines.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <!-- Search Key -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Search Product Name</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Type product name..." 
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500">
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Category</label>
                    <select name="category" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500">
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
                    <button type="submit" class="flex-1 bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm py-3 rounded-lg transition shadow-sm">
                        Apply Filters
                    </button>
                    <a href="{{ route('medicines.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold text-sm py-3 px-4 rounded-lg transition text-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($medicines as $medicine)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg transition duration-300 overflow-hidden flex flex-col justify-between group">
                    <div class="p-6">
                        <div class="relative w-full h-48 bg-gray-50 rounded-xl overflow-hidden mb-4 flex items-center justify-center">
                            @if($medicine->image)
                                <img src="{{ asset($medicine->image) }}" alt="{{ $medicine->name }}" class="object-cover h-full w-full group-hover:scale-105 transition duration-300">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-teal-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z" />
                                </svg>
                            @endif

                            @if($medicine->prescription_required)
                                <span class="absolute top-3 right-3 bg-red-100 text-red-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Prescription Required</span>
                            @endif
                        </div>

                        <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider">{{ $medicine->brand->name }}</span>
                        <h3 class="font-bold text-gray-900 text-lg mt-1 group-hover:text-teal-700 transition">{{ $medicine->name }}</h3>
                        <p class="text-xs text-teal-600 mt-0.5">{{ $medicine->category->name }}</p>
                        
                        <!-- Stock Status -->
                        <div class="mt-2 flex items-center gap-1.5">
                            @if($medicine->stock_quantity > 5)
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                <span class="text-xs text-green-600 font-medium">In Stock ({{ $medicine->stock_quantity }})</span>
                            @elseif($medicine->stock_quantity > 0)
                                <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                                <span class="text-xs text-orange-600 font-medium">Low Stock ({{ $medicine->stock_quantity }} left)</span>
                            @else
                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                <span class="text-xs text-red-600 font-medium">Out of Stock</span>
                            @endif
                        </div>

                        <p class="text-gray-500 text-xs mt-3 line-clamp-2">{{ $medicine->description ?? 'No description available.' }}</p>
                    </div>

                    <div class="px-6 pb-6 pt-0 border-t border-gray-50">
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

                        <!-- Action buttons based on Role -->
                        <div class="mt-4 flex gap-2">
                            @hasanyrole('admin|staff')
                                <a href="{{ route('medicines.edit', $medicine) }}" 
                                   class="flex-1 bg-teal-50 hover:bg-teal-100 text-teal-700 font-semibold text-sm py-2.5 rounded-xl transition text-center">
                                    Edit
                                </a>
                                
                                <form action="{{ route('medicines.destroy', $medicine) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this medicine?')"
                                      class="inline-block flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-semibold text-sm py-2.5 rounded-xl transition text-center">
                                        Delete
                                    </button>
                                </form>
                            @else
                                @if($medicine->stock_quantity > 0)
                                    <form action="{{ route('cart.add', $medicine) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm py-2.5 rounded-xl transition">
                                            Add to Cart
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('cart.add', $medicine) }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="buy_now" value="1">
                                        <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold text-sm py-2.5 rounded-xl transition">
                                            Buy Now
                                        </button>
                                    </form>
                                @else
                                    <button class="w-full bg-gray-100 text-gray-400 font-semibold text-sm py-2.5 rounded-xl cursor-not-allowed" disabled>
                                        Out of Stock
                                    </button>
                                @endif
                            @endhasanyrole
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center col-span-full py-16">No medicines match your search criteria.</p>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $medicines->links() }}
        </div>
    </div>
</x-app-layout>