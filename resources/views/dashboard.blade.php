<x-app-layout>
    @php
        $user = auth()->user();
        
        if ($user->hasRole('admin') || $user->hasRole('staff')) {
            $totalMedicines = \App\Models\Medicine::count();
            $lowStock = \App\Models\Medicine::where('stock_quantity', '<=', 5)->count();
            $pendingOrders = \App\Models\Order::whereIn('status', ['pending', 'processing'])->count();
            $revenue = \App\Models\Order::where('status', 'delivered')->sum('total_amount');
            
            // Add dashboard queues
            $recentOrders = \App\Models\Order::with('user')->orderBy('created_at', 'desc')->take(5)->get();
            $lowStockMedicines = \App\Models\Medicine::with('brand')->where('stock_quantity', '<=', 5)->orderBy('stock_quantity', 'asc')->take(5)->get();
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
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            border: 1px solid rgba(226, 232, 240, 0.8);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .truemeds-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(13, 148, 136, 0.05);
            border-color: rgba(13, 148, 136, 0.15);
        }
        .search-container {
            background: linear-gradient(135deg, #f0fdf4 0%, #f0fdfa 100%);
            border-radius: 32px;
            border: 1px solid rgba(13, 148, 136, 0.08);
        }
        .doctor-stripe {
            background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%);
            border: 1px solid rgba(13, 148, 136, 0.12);
            border-radius: 24px;
        }
    </style>

    <div class="bg-slate-50/50 min-h-screen py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-10">
            
            @if($user->hasRole('admin') || $user->hasRole('staff'))
                <!-- ================= ADMIN / STAFF SYSTEM ================= -->
                <div class="bg-gradient-to-br from-slate-900 via-teal-950 to-slate-900 rounded-3xl p-8 md:p-10 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-teal-500/10 rounded-full filter blur-3xl"></div>
                    <span class="inline-flex items-center gap-1.5 bg-teal-500/20 text-teal-300 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider border border-teal-500/30">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                        {{ $user->hasRole('admin') ? 'Administrator Access' : 'Store Staff Access' }}
                    </span>
                    <h1 class="text-3xl md:text-4xl font-extrabold mt-4 tracking-tight">Welcome back, {{ $user->name }}!</h1>
                    <p class="text-slate-400 mt-2 text-sm max-w-xl font-light">Medicare's centralized admin suite gives you instant access to stock levels, metrics, and billing operations.</p>
                </div>

                <!-- Admin Statistics Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white p-6 rounded-3xl border border-slate-200/50 shadow-sm flex items-center gap-5">
                        <div class="p-4 bg-teal-500/10 text-teal-600 rounded-2xl border border-teal-500/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Total Medicines</span>
                            <span class="text-3xl font-black text-slate-900 block mt-0.5">{{ $totalMedicines }}</span>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-3xl border border-slate-200/50 shadow-sm flex items-center gap-5">
                        <div class="p-4 bg-red-500/10 text-red-650 rounded-2xl border border-red-500/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Low Stock Alerts</span>
                            <span class="text-3xl font-black text-slate-900 block mt-0.5">{{ $lowStock }}</span>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-3xl border border-slate-200/50 shadow-sm flex items-center gap-5">
                        <div class="p-4 bg-indigo-500/10 text-indigo-650 rounded-2xl border border-indigo-500/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Active Orders</span>
                            <span class="text-3xl font-black text-slate-900 block mt-0.5">{{ $pendingOrders }}</span>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-3xl border border-slate-200/50 shadow-sm flex items-center gap-5">
                        <div class="p-4 bg-emerald-500/10 text-emerald-650 rounded-2xl border border-emerald-500/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Total Revenue</span>
                            <span class="text-3xl font-black text-slate-900 block mt-0.5">₹{{ number_format($revenue, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Management Operations -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('medicines.index') }}" class="bg-white border border-slate-200/50 hover:border-teal-500/30 rounded-3xl p-6 shadow-sm hover:shadow-md transition duration-300 block text-left group">
                        <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-650 flex items-center justify-center mb-5 group-hover:bg-gradient-to-br group-hover:from-teal-500 group-hover:to-emerald-600 group-hover:text-white transition duration-300 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-900 text-lg group-hover:text-teal-700 transition">Inventory & Pricing</h3>
                        <p class="text-xs text-slate-500 mt-2 font-medium leading-relaxed">Update catalogs, adjust markdowns, and monitor inventory warning triggers.</p>
                    </a>

                    <a href="{{ route('orders.index') }}" class="bg-white border border-slate-200/50 hover:border-teal-500/30 rounded-3xl p-6 shadow-sm hover:shadow-md transition duration-300 block text-left group">
                        <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-650 flex items-center justify-center mb-5 group-hover:bg-gradient-to-br group-hover:from-teal-500 group-hover:to-emerald-600 group-hover:text-white transition duration-300 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-900 text-lg group-hover:text-teal-700 transition">Order Fulfillment</h3>
                        <p class="text-xs text-slate-500 mt-2 font-medium leading-relaxed">Review doctor prescriptions, assign shipping carriers, and change fulfillment status.</p>
                    </a>

                    <a href="{{ route('categories.index') }}" class="bg-white border border-slate-200/50 hover:border-teal-500/30 rounded-3xl p-6 shadow-sm hover:shadow-md transition duration-300 block text-left group">
                        <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-650 flex items-center justify-center mb-5 group-hover:bg-gradient-to-br group-hover:from-teal-500 group-hover:to-emerald-600 group-hover:text-white transition duration-300 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-900 text-lg group-hover:text-teal-700 transition">Taxonomy Structure</h3>
                        <p class="text-xs text-slate-500 mt-2 font-medium leading-relaxed">Create brand labels, configure therapy categories, and structure web navigation tags.</p>
                    </a>
                </div>

                <!-- Recent Activity & Stock Warnings Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Left: Recent Customer Orders (7 Columns) -->
                    <div class="lg:col-span-7 bg-white rounded-3xl p-6 border border-slate-200/50 shadow-sm space-y-6">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold text-slate-900 text-lg tracking-tight">Recent Customer Orders</h3>
                            <a href="{{ route('orders.index') }}" class="text-xs font-bold text-teal-605 hover:text-teal-800 transition">Manage Orders &rarr;</a>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                                        <th class="pb-3">Order ID</th>
                                        <th class="pb-3">Customer</th>
                                        <th class="pb-3">Status</th>
                                        <th class="pb-3 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 text-slate-700 font-medium">
                                    @forelse($recentOrders as $order)
                                        <tr class="hover:bg-slate-50/50 transition">
                                            <td class="py-3.5 font-bold text-slate-800">#{{ $order->id }}</td>
                                            <td class="py-3.5">{{ $order->user->name }}</td>
                                            <td class="py-3.5">
                                                @if($order->status === 'pending')
                                                    <span class="bg-amber-500/10 text-amber-700 text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Pending</span>
                                                @elseif($order->status === 'processing')
                                                    <span class="bg-blue-500/10 text-blue-700 text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Processing</span>
                                                @elseif($order->status === 'shipped')
                                                    <span class="bg-indigo-500/10 text-indigo-705 text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Shipped</span>
                                                @elseif($order->status === 'delivered')
                                                    <span class="bg-emerald-500/10 text-emerald-705 text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Delivered</span>
                                                @else
                                                    <span class="bg-red-500/10 text-red-700 text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Cancelled</span>
                                                @endif
                                            </td>
                                            <td class="py-3.5 text-right font-extrabold text-slate-900">₹{{ number_format($order->total_amount, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-6 text-center text-slate-400 font-semibold">No recent orders placed.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Right: Low Stock Warnings (5 Columns) -->
                    <div class="lg:col-span-5 bg-white rounded-3xl p-6 border border-slate-200/50 shadow-sm space-y-6">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold text-slate-900 text-lg tracking-tight">Low Inventory Warnings</h3>
                            <a href="{{ route('medicines.index') }}" class="text-xs font-bold text-teal-605 hover:text-teal-800 transition">View Catalog &rarr;</a>
                        </div>
                        
                        <div class="flex flex-col gap-4">
                            @forelse($lowStockMedicines as $med)
                                <div class="flex items-center justify-between border-b border-slate-50 pb-3 last:border-b-0 last:pb-0">
                                    <div class="truncate flex-1 pr-4">
                                        <span class="font-bold text-slate-800 text-xs block truncate">{{ $med->name }}</span>
                                        <span class="text-[10px] text-slate-400 font-bold block mt-0.5">{{ $med->brand->name }}</span>
                                    </div>
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-xl {{ $med->stock_quantity == 0 ? 'bg-red-500/10 text-red-650' : 'bg-orange-500/10 text-orange-650' }}">
                                        {{ $med->stock_quantity }} Left
                                    </span>
                                </div>
                            @empty
                                <div class="py-8 text-center text-slate-400 font-semibold text-xs">
                                    All inventory supplies are fully stocked.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            @else
                <!-- ================= CUSTOMER PORTAL SYSTEM ================= -->
                <!-- Greeting card -->
                <div class="bg-gradient-to-br from-teal-800 to-teal-950 rounded-3xl p-8 md:p-10 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-teal-500/10 rounded-full filter blur-3xl"></div>
                    <span class="bg-teal-500/20 border border-teal-500/30 text-teal-300 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">
                        Customer Portal
                    </span>
                    <h1 class="text-3xl md:text-4xl font-extrabold mt-4 tracking-tight">Welcome, {{ $user->name }}!</h1>
                    <p class="text-slate-350 mt-2 text-sm max-w-xl font-light">Order your prescriptions, track delivery progress, and check medical recommendations instantly.</p>
                </div>

                <!-- Customer KPI Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <!-- Item 1 -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-200/50 shadow-sm flex items-center gap-5">
                        <div class="p-4 bg-teal-500/10 text-teal-600 rounded-2xl border border-teal-500/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Items in Cart</span>
                            <span class="text-3xl font-black text-slate-900 block mt-0.5">{{ $cartCount }}</span>
                        </div>
                    </div>

                    <!-- Item 2 -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-200/50 shadow-sm flex items-center gap-5">
                        <div class="p-4 bg-indigo-500/10 text-indigo-650 rounded-2xl border border-indigo-500/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Total Orders placed</span>
                            <span class="text-3xl font-black text-slate-900 block mt-0.5">{{ $orderCount }}</span>
                        </div>
                    </div>

                    <!-- Item 3 -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-200/50 shadow-sm flex items-center gap-5">
                        <div class="p-4 bg-emerald-500/10 text-emerald-650 rounded-2xl border border-emerald-500/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Total Delivered Spend</span>
                            <span class="text-3xl font-black text-slate-900 block mt-0.5">₹{{ number_format($totalSpent, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Medication Routine Tracker Widget -->
                <div x-data="{
                    newPillName: '',
                    newPillTime: 'Morning',
                    pills: [
                        { id: 1, name: 'Chewable Limcee 500mg', time: 'Afternoon', taken: true, custom: false },
                        { id: 2, name: 'Amoxyclav 625 Duo', time: 'Morning', taken: false, custom: false },
                        { id: 3, name: 'Strepsils Lozenge', time: 'Night', taken: true, custom: false }
                    ],
                    addPill() {
                        if (!this.newPillName.trim()) return;
                        this.pills.push({
                            id: Date.now(),
                            name: this.newPillName.trim(),
                            time: this.newPillTime,
                            taken: false,
                            custom: true
                        });
                        this.newPillName = '';
                    },
                    removePill(id) {
                        this.pills = this.pills.filter(p => p.id !== id);
                    },
                    get percentTaken() {
                        if (this.pills.length === 0) return 0;
                        const takenCount = this.pills.filter(p => p.taken).length;
                        return Math.round((takenCount / this.pills.length) * 100);
                    },
                    get takenCount() {
                        return this.pills.filter(p => p.taken).length;
                    }
                }" class="bg-white rounded-3xl p-6 border border-slate-200/50 shadow-sm hover:border-teal-500/10 transition">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-lg tracking-tight flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-650" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Medication Intake Tracker
                            </h3>
                            <p class="text-xs text-slate-550 mt-1 font-medium">Log your daily pill courses and track dosage compliance.</p>
                        </div>
                        <div class="flex items-center gap-2 bg-slate-50 px-3.5 py-1.5 rounded-xl border border-slate-200/30">
                            <span class="text-xs font-bold text-slate-700">Today's Progress:</span>
                            <span class="text-xs font-black text-teal-700" x-text="`${takenCount}/${pills.length} taken (${percentTaken}%)`"></span>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden mb-6">
                        <div class="h-full bg-teal-500 rounded-full transition-all duration-300" :style="`width: ${percentTaken}%`"></div>
                    </div>

                    <!-- Reminders Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- List -->
                        <div class="space-y-3">
                            <template x-for="pill in pills" :key="pill.id">
                                <div class="flex items-center justify-between p-3.5 rounded-2xl border transition"
                                     :class="pill.taken ? 'bg-teal-50/20 border-teal-200/50' : 'bg-white border-slate-200/60'">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" x-model="pill.taken" class="rounded border-slate-350 text-teal-650 focus:ring-teal-500 h-4.5 w-4.5 cursor-pointer">
                                        <div>
                                            <span x-text="pill.name" :class="pill.taken ? 'line-through text-slate-450' : 'text-slate-805'" class="text-xs font-bold"></span>
                                            <span x-text="pill.time" class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5"></span>
                                        </div>
                                    </div>
                                    <template x-if="pill.custom">
                                        <button @click="removePill(pill.id)" class="text-slate-400 hover:text-red-500 text-xs font-bold px-2 py-1 rounded transition">&times;</button>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <!-- Inline Add Reminder Form -->
                        <div class="bg-slate-50/50 rounded-2xl p-5 border border-slate-200/40">
                            <h4 class="text-[10px] font-extrabold text-slate-450 uppercase tracking-widest mb-4">Add Medication Schedule</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Medicine Name</label>
                                    <input type="text" x-model="newPillName" placeholder="e.g. Paracetamol 500mg" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-teal-500 transition font-semibold">
                                </div>
                                <div>
                                    <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Dosage Time</label>
                                    <select x-model="newPillTime" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-655 focus:outline-none focus:border-teal-500 transition">
                                        <option value="Morning">Morning (After Breakfast)</option>
                                        <option value="Afternoon">Afternoon (After Lunch)</option>
                                        <option value="Night">Evening / Night (Before Bed)</option>
                                    </select>
                                </div>
                                <button @click="addPill" type="button" class="w-full bg-teal-650 hover:bg-teal-700 text-white font-bold text-xs py-3 rounded-xl transition shadow-md shadow-teal-500/10 active:scale-95">
                                    Add Intake Reminder
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Search container -->
                <div class="search-container p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
                    <div class="max-w-md">
                        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Need specific clinical medicines?</h2>
                        <p class="text-slate-650 text-xs mt-1.5 font-medium leading-relaxed">Search through thousands of OTC & Rx pharmaceuticals on India's most modern secure platform.</p>
                    </div>
                    <form action="{{ route('medicines.index') }}" method="GET" class="w-full md:w-auto flex-1 max-w-md bg-white p-1.5 rounded-2xl flex border border-slate-200/40 shadow-md">
                        <input type="text" name="search" placeholder="Type tablets, capsules, syrups..." class="flex-1 px-4 py-2.5 text-slate-800 rounded-xl focus:outline-none focus:ring-0 text-xs placeholder:text-slate-400 font-medium">
                        <button type="submit" class="bg-teal-650 hover:bg-teal-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition">Search</button>
                    </form>
                </div>

                <!-- 3. Doctor Call assistance Stripe -->
                <div class="doctor-stripe p-6 flex flex-col md:flex-row items-center justify-between gap-4 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center shadow-sm text-teal-600 overflow-hidden border border-teal-500/10 shrink-0">
                            <!-- Friendly Doctor SVG Avatar -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-teal-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-teal-950 text-sm">Call us to place medicine orders directly</h4>
                            <p class="text-xs text-teal-705 font-medium mt-0.5">Quick order support. Working Hours: 8:00 AM to 10:00 PM</p>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <a href="tel:09240250346" class="text-xl md:text-2xl font-black text-teal-800 hover:text-teal-950 transition block">
                            09240250346
                        </a>
                    </div>
                </div>

                <!-- 4. Recommended Medicines Shelf -->
                <div class="space-y-6">
                    <div class="flex justify-between items-baseline">
                        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Recommended For You</h2>
                        <a href="{{ route('medicines.index') }}" class="text-xs font-bold text-teal-605 hover:text-teal-800 transition">View Catalog &rarr;</a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($featuredMedicines as $medicine)
                            <div class="truemeds-card p-5 flex flex-col justify-between group overflow-hidden bg-white">
                                <div>
                                    <!-- Image container -->
                                    <div class="relative w-full h-40 bg-slate-50 rounded-2xl overflow-hidden mb-4 flex items-center justify-center border border-slate-100 p-2">
                                        @if($medicine->image)
                                            <img src="{{ asset($medicine->image) }}" alt="{{ $medicine->name }}" class="object-contain h-full w-full group-hover:scale-105 transition duration-300">
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-teal-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z" />
                                            </svg>
                                        @endif
                                        
                                        @if($medicine->prescription_required)
                                            <span class="absolute top-2.5 right-2.5 bg-red-500/10 border border-red-500/20 text-red-650 text-[8px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Rx</span>
                                        @endif
                                    </div>

                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest block">{{ $medicine->brand->name }}</span>
                                    <h3 class="font-extrabold text-slate-900 text-sm mt-1 line-clamp-1 group-hover:text-teal-700 transition" title="{{ $medicine->name }}">{{ $medicine->name }}</h3>
                                    <p class="text-[10px] text-teal-650 font-bold mt-0.5">{{ $medicine->category->name }}</p>
                                </div>

                                <div class="mt-4 border-t border-slate-100 pt-3">
                                    <div class="flex items-baseline gap-1.5 mb-3">
                                        <span class="text-lg font-black text-teal-750">₹{{ number_format($medicine->selling_price, 2) }}</span>
                                        @if($medicine->mrp > $medicine->selling_price)
                                            <span class="text-xs text-slate-400 line-through font-semibold">₹{{ number_format($medicine->mrp, 2) }}</span>
                                        @endif
                                    </div>

                                    <form action="{{ route('cart.add', $medicine) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full bg-teal-650 hover:bg-teal-700 text-white font-bold text-xs py-2.5 rounded-xl transition shadow-md shadow-teal-500/10 active:scale-95">
                                            Add to Cart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 5. Testimonials (What our customers have to say) -->
                <div class="space-y-6 border-t border-slate-200/50 pt-10">
                    <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight text-center">Loved by 10,000+ Customers</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Review 1 -->
                        <div class="bg-white p-6 rounded-3xl border border-slate-200/50 shadow-sm flex flex-col justify-between hover:border-teal-550/10 transition">
                            <div class="space-y-3">
                                <div class="flex text-orange-400 font-bold text-sm">
                                    ★★★★★
                                </div>
                                <h4 class="font-extrabold text-slate-800 text-sm">Convenient doorstep delivery</h4>
                                <p class="text-xs text-slate-500 leading-relaxed font-medium">"I can order from anywhere and at any time. Medicare provides secure doorstep delivery. Extremely helpful for my parents!"</p>
                            </div>
                            <span class="text-[10px] text-slate-450 font-bold block mt-4">- Subhash Sehgal</span>
                        </div>

                        <!-- Review 2 -->
                        <div class="bg-white p-6 rounded-3xl border border-slate-200/50 shadow-sm flex flex-col justify-between hover:border-teal-550/10 transition">
                            <div class="space-y-3">
                                <div class="flex text-orange-400 font-bold text-sm">
                                    ★★★★★
                                </div>
                                <h4 class="font-extrabold text-slate-800 text-sm">Very user friendly app</h4>
                                <p class="text-xs text-slate-500 leading-relaxed font-medium">"Excellent user interface. Substitute information is readily available, and customer service response is fast."</p>
                            </div>
                            <span class="text-[10px] text-slate-450 font-bold block mt-4">- Deepali Sharma</span>
                        </div>

                        <!-- Review 3 -->
                        <div class="bg-white p-6 rounded-3xl border border-slate-200/50 shadow-sm flex flex-col justify-between hover:border-teal-550/10 transition">
                            <div class="space-y-3">
                                <div class="flex text-orange-400 font-bold text-sm">
                                    ★★★★★
                                </div>
                                <h4 class="font-extrabold text-slate-800 text-sm">Unbeatable pricing and discounts</h4>
                                <p class="text-xs text-slate-500 leading-relaxed font-medium">"Medicare consistently offers the best rates on original clinical products. Applying coupons is super easy too!"</p>
                            </div>
                            <span class="text-[10px] text-slate-450 font-bold block mt-4">- Ramesh Iyer</span>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
