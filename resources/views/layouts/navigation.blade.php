@php
    $cartItemCount = 0;
    $pendingPrescriptionCount = 0;
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->hasRole('customer')) {
            $cart = \App\Models\Cart::where('user_id', $user->id)->first();
            if ($cart) {
                $cartItemCount = $cart->items()->sum('quantity');
            }
        } else {
            $pendingPrescriptionCount = \App\Models\Prescription::where('status', 'pending')->count();
        }
    }
@endphp

<nav x-data="{ open: false }" class="glass-panel sticky top-0 z-50 shadow-sm border-b border-teal-500/10">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                        <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-500 to-teal-700 flex items-center justify-center text-white font-extrabold text-lg shadow-md transition group-hover:scale-105">M</span>
                        <span class="text-xl font-bold tracking-tight text-teal-800 transition group-hover:text-teal-900">Medi<span class="text-orange-500">care</span></span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'border-teal-600 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Dashboard
                    </a>
                    
                    <a href="{{ route('medicines.index') }}" 
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-semibold transition {{ request()->routeIs('medicines.*') ? 'border-teal-600 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Medicines
                    </a>

                    @hasanyrole('admin|staff')
                        <a href="{{ route('categories.index') }}" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-semibold transition {{ request()->routeIs('categories.*') ? 'border-teal-600 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Categories
                        </a>
                        
                        <a href="{{ route('brands.index') }}" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-semibold transition {{ request()->routeIs('brands.*') ? 'border-teal-600 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Brands
                        </a>
                    @endhasanyrole

                    @role('customer')
                        <a href="{{ route('cart.index') }}" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-semibold transition {{ request()->routeIs('cart.*') ? 'border-teal-600 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <span>Cart</span>
                            @if($cartItemCount > 0)
                                <span class="ml-1.5 px-2 py-0.5 text-[10px] font-bold bg-orange-500 text-white rounded-full leading-none shadow-sm">{{ $cartItemCount }}</span>
                            @endif
                        </a>
                    @endrole

                    <a href="{{ route('orders.index') }}" 
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-semibold transition {{ request()->routeIs('orders.*') ? 'border-teal-600 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Orders
                    </a>

                    <a href="{{ route('prescriptions.index') }}" 
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-semibold transition {{ request()->routeIs('prescriptions.*') ? 'border-teal-600 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <span>Prescriptions</span>
                        @if($pendingPrescriptionCount > 0)
                            <span class="ml-1.5 px-2 py-0.5 text-[10px] font-bold bg-red-500 text-white rounded-full leading-none shadow-sm">{{ $pendingPrescriptionCount }}</span>
                        @endif
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2.5 px-3 py-1.5 border border-gray-150 rounded-xl text-sm font-semibold text-gray-700 bg-gray-50/50 hover:bg-gray-50 transition focus:outline-none">
                            <span class="w-7 h-7 rounded-full bg-teal-600 text-white flex items-center justify-center text-xs font-extrabold shadow-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            <span>{{ Auth::user()->name }}</span>

                            <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-400 hover:text-gray-500 hover:bg-gray-50 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-50 border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('medicines.index')" :active="request()->routeIs('medicines.*')">
                {{ __('Medicines') }}
            </x-responsive-nav-link>

            @hasanyrole('admin|staff')
                <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                    {{ __('Categories') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('brands.index')" :active="request()->routeIs('brands.*')">
                    {{ __('Brands') }}
                </x-responsive-nav-link>
            @endhasanyrole

            @role('customer')
                <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">
                    <span class="flex justify-between items-center w-full">
                        <span>Cart</span>
                        @if($cartItemCount > 0)
                            <span class="px-2 py-0.5 text-[10px] font-bold bg-orange-500 text-white rounded-full">{{ $cartItemCount }}</span>
                        @endif
                    </span>
                </x-responsive-nav-link>
            @endrole

            <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                {{ __('Orders') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('prescriptions.index')" :active="request()->routeIs('prescriptions.*')">
                <span class="flex justify-between items-center w-full">
                    <span>Prescriptions</span>
                    @if($pendingPrescriptionCount > 0)
                        <span class="px-2 py-0.5 text-[10px] font-bold bg-red-500 text-white rounded-full">{{ $pendingPrescriptionCount }}</span>
                    @endif
                </span>
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-150">
            <div class="px-4 flex items-center gap-3">
                <span class="w-9 h-9 rounded-full bg-teal-600 text-white flex items-center justify-center text-sm font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                <div>
                    <div class="font-bold text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
