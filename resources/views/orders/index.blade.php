<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                    @hasanyrole('admin|staff')
                        Order Management Dashboard
                    @else
                        Your Order History
                    @endhasanyrole
                </h1>
                <p class="text-slate-500 text-sm mt-1 font-medium">
                    @hasanyrole('admin|staff')
                        Manage and track client prescriptions and fulfillments
                    @else
                        Track status of placed prescription and OTC orders
                    @endhasanyrole
                </p>
            </div>
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

        @if($orders->isEmpty())
            <div class="bg-white rounded-3xl border border-slate-200/50 p-16 text-center shadow-sm max-w-2xl mx-auto">
                <div class="w-24 h-24 bg-teal-50 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-inner text-teal-650">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 mb-2">No Orders Found</h2>
                <p class="text-slate-500 mb-8 max-w-sm mx-auto font-medium">There are no orders registered under this account context.</p>
                <a href="{{ route('medicines.index') }}" class="bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white font-bold px-8 py-3.5 rounded-xl shadow-lg shadow-teal-500/20 transition">
                    Start Shopping
                </a>
            </div>
        @else
            <div class="flex flex-col gap-6">
                @foreach($orders as $order)
                    <div class="bg-white border border-slate-200/50 rounded-3xl p-6 shadow-sm flex flex-col gap-6 hover:border-teal-500/10 transition duration-300">
                        
                        <!-- Order Top Header -->
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-100 pb-5 gap-4">
                            <div>
                                <div class="flex items-center gap-3">
                                    <span class="text-lg font-extrabold text-slate-900">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    
                                    <!-- Status Badges -->
                                    @if($order->status === 'pending')
                                        <span class="bg-yellow-500/10 text-yellow-700 border border-yellow-500/20 text-xs font-bold px-3.5 py-1 rounded-full uppercase tracking-wider">Pending</span>
                                    @elseif($order->status === 'processing')
                                        <span class="bg-blue-500/10 text-blue-700 border border-blue-500/20 text-xs font-bold px-3.5 py-1 rounded-full uppercase tracking-wider">Processing</span>
                                    @elseif($order->status === 'shipped')
                                        <span class="bg-indigo-555/10 text-indigo-700 border border-indigo-500/20 text-xs font-bold px-3.5 py-1 rounded-full uppercase tracking-wider">Shipped</span>
                                    @elseif($order->status === 'delivered')
                                        <span class="bg-emerald-500/10 text-emerald-700 border border-emerald-500/20 text-xs font-bold px-3.5 py-1 rounded-full uppercase tracking-wider">Delivered</span>
                                    @else
                                        <span class="bg-red-500/10 text-red-705 border border-red-500/20 text-xs font-bold px-3.5 py-1 rounded-full uppercase tracking-wider">Cancelled</span>
                                    @endif
                                </div>
                                <span class="text-xs text-slate-400 font-semibold block mt-1.5">Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</span>
                            </div>

                            <div class="flex items-center gap-6">
                                <div class="text-left sm:text-right">
                                    <span class="text-[10px] text-slate-400 block uppercase tracking-widest font-extrabold">Total Payable</span>
                                    <span class="text-2xl font-black text-teal-750">₹{{ number_format($order->total_amount, 2) }}</span>
                                </div>

                                <!-- Admin actions to modify status -->
                                @hasanyrole('admin|staff')
                                    <form action="{{ route('orders.status', $order) }}" method="POST" class="flex gap-2 items-center">
                                        @csrf
                                        <select name="status" class="border border-slate-200 rounded-xl px-3 py-2 text-xs focus:border-teal-500 focus:ring-teal-500 focus:outline-none transition bg-slate-50 font-bold text-slate-700">
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        <button type="submit" class="bg-teal-650 hover:bg-teal-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition shadow-md shadow-teal-500/10 active:scale-95">
                                            Update
                                        </button>
                                    </form>
                                @endhasanyrole
                            </div>
                        </div>

                        <!-- Order Information Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            
                            <!-- Delivery Info -->
                            <div>
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3.5">Delivery Details</h4>
                                <div class="text-sm text-slate-750 flex flex-col gap-2 font-medium">
                                    @hasanyrole('admin|staff')
                                        <span class="font-bold text-slate-900">{{ $order->user->name }} ({{ $order->user->email }})</span>
                                    @endhasanyrole
                                    <span><span class="font-bold text-slate-400">Phone:</span> {{ $order->phone }}</span>
                                    <span><span class="font-bold text-slate-400">Address:</span> {{ $order->shipping_address }}</span>
                                    <span><span class="font-bold text-slate-400">Payment:</span> {{ strtoupper($order->payment_method) }}</span>
                                    @if($order->coupon_code)
                                        <span class="mt-1 flex items-center gap-1.5"><span class="font-bold text-teal-600">Coupon:</span> <span class="bg-teal-50 text-teal-700 text-xs px-2 py-0.5 rounded-md font-mono font-bold border border-teal-500/10">{{ $order->coupon_code }}</span></span>
                                        <span><span class="font-bold text-teal-600">Discount:</span> <span class="text-teal-700 font-extrabold">-₹{{ number_format($order->discount_amount, 2) }}</span></span>
                                    @endif
                                </div>
                            </div>

                            <!-- Prescription Attachment -->
                            <div>
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3.5">Prescription Document</h4>
                                @if($order->prescription_path)
                                    <a href="{{ asset($order->prescription_path) }}" 
                                       target="_blank" 
                                       class="inline-flex items-center gap-2 text-teal-650 hover:text-teal-800 text-sm font-bold border border-teal-500/10 bg-teal-50/50 px-4 py-2.5 rounded-xl transition shadow-sm hover:border-teal-500/25">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        View Uploaded Rx
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400 italic font-medium">No prescription required / uploaded for this order.</span>
                                @endif
                            </div>

                            <!-- Purchased Items List -->
                            <div>
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3.5">Items Ordered</h4>
                                <div class="flex flex-col gap-3">
                                    @foreach($order->items as $item)
                                        <div class="flex justify-between items-center text-sm gap-2">
                                            <div class="truncate flex-1">
                                                <span class="font-bold text-slate-800 block truncate">{{ $item->medicine->name }}</span>
                                                <span class="text-xs text-slate-400 font-bold block mt-0.5">Qty: {{ $item->quantity }} @ ₹{{ number_format($item->price, 2) }}</span>
                                            </div>
                                            <span class="font-extrabold text-slate-750 text-right min-w-[70px]">₹{{ number_format($item->price * $item->quantity, 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>

                    </div>
                @endforeach

                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
