<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                    @hasanyrole('admin|staff')
                        Order Management Dashboard
                    @else
                        Your Order History
                    @endhasanyrole
                </h1>
                <p class="text-gray-500 text-sm mt-1">
                    @hasanyrole('admin|staff')
                        Manage and track client prescriptions and fulfillments
                    @else
                        Track status of placed prescription and OTC orders
                    @endhasanyrole
                </p>
            </div>
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

        @if($orders->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 p-16 text-center shadow-sm">
                <div class="w-24 h-24 bg-teal-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">No Orders Found</h2>
                <p class="text-gray-500 mb-8 max-w-sm mx-auto">There are no orders registered under this account context.</p>
                <a href="{{ route('medicines.index') }}" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-6 py-3 rounded-lg shadow-sm transition">
                    Start Shopping
                </a>
            </div>
        @else
            <div class="flex flex-col gap-6">
                @foreach($orders as $order)
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col gap-6">
                        
                        <!-- Order Top Header -->
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-50 pb-4 gap-4">
                            <div>
                                <div class="flex items-center gap-3">
                                    <span class="text-lg font-extrabold text-gray-950">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    
                                    <!-- Status Badges -->
                                    @if($order->status === 'pending')
                                        <span class="bg-yellow-50 text-yellow-700 border border-yellow-100 text-xs font-semibold px-3 py-1 rounded-full">Pending</span>
                                    @elseif($order->status === 'processing')
                                        <span class="bg-blue-50 text-blue-700 border border-blue-100 text-xs font-semibold px-3 py-1 rounded-full">Processing</span>
                                    @elseif($order->status === 'shipped')
                                        <span class="bg-indigo-50 text-indigo-700 border border-indigo-100 text-xs font-semibold px-3 py-1 rounded-full">Shipped</span>
                                    @elseif($order->status === 'delivered')
                                        <span class="bg-green-50 text-green-700 border border-green-100 text-xs font-semibold px-3 py-1 rounded-full">Delivered</span>
                                    @else
                                        <span class="bg-red-50 text-red-700 border border-red-100 text-xs font-semibold px-3 py-1 rounded-full">Cancelled</span>
                                    @endif
                                </div>
                                <span class="text-xs text-gray-400 block mt-1">Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</span>
                            </div>

                            <div class="flex items-center gap-6">
                                <div class="text-left sm:text-right">
                                    <span class="text-xs text-gray-400 block uppercase tracking-wider font-semibold">Total Amount</span>
                                    <span class="text-xl font-extrabold text-teal-700">₹{{ number_format($order->total_amount, 2) }}</span>
                                </div>

                                <!-- Admin actions to modify status -->
                                @hasanyrole('admin|staff')
                                    <form action="{{ route('orders.status', $order) }}" method="POST" class="flex gap-2 items-center">
                                        @csrf
                                        <select name="status" class="border border-gray-200 rounded-lg px-3 py-1.5 text-xs focus:border-teal-500 focus:ring-teal-500">
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs px-3 py-2 rounded-lg transition shadow-sm">
                                            Update
                                        </button>
                                    </form>
                                @endhasanyrole
                            </div>
                        </div>

                        <!-- Order Information Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <!-- Delivery Info -->
                            <div>
                                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Delivery Details</h4>
                                <div class="text-sm text-gray-700 flex flex-col gap-1">
                                    @hasanyrole('admin|staff')
                                        <span class="font-bold">{{ $order->user->name }} ({{ $order->user->email }})</span>
                                    @endhasanyrole
                                    <span><span class="font-semibold text-gray-500">Phone:</span> {{ $order->phone }}</span>
                                    <span><span class="font-semibold text-gray-500">Address:</span> {{ $order->shipping_address }}</span>
                                    <span><span class="font-semibold text-gray-500">Payment:</span> {{ strtoupper($order->payment_method) }}</span>
                                </div>
                            </div>

                            <!-- Prescription Attachment -->
                            <div>
                                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Prescription Document</h4>
                                @if($order->prescription_path)
                                    <a href="{{ asset($order->prescription_path) }}" 
                                       target="_blank" 
                                       class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 text-sm font-semibold border border-teal-100 bg-teal-50 px-4 py-2.5 rounded-xl transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        View Uploaded Rx
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400 italic">No prescription required / uploaded for this order.</span>
                                @endif
                            </div>

                            <!-- Purchased Items List -->
                            <div>
                                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Items Ordered</h4>
                                <div class="flex flex-col gap-3">
                                    @foreach($order->items as $item)
                                        <div class="flex justify-between items-center text-sm">
                                            <div class="truncate">
                                                <span class="font-semibold text-gray-800">{{ $item->medicine->name }}</span>
                                                <span class="text-xs text-gray-400 block">Quantity: {{ $item->quantity }} @ ₹{{ number_format($item->price, 2) }}</span>
                                            </div>
                                            <span class="font-bold text-gray-700">₹{{ number_format($item->price * $item->quantity, 2) }}</span>
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
