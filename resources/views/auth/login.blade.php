<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <h2 class="text-2xl font-extrabold text-gray-900">Welcome Back</h2>
        <p class="text-sm text-gray-500 mt-1">Please sign in to access your Medicare account</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" 
                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-teal-500 focus:ring-teal-500 bg-gray-50"
                   required autofocus autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label for="password" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-teal-600 hover:text-teal-700 font-semibold" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" 
                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-teal-500 focus:ring-teal-500 bg-gray-50"
                   required autocomplete="current-password">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember" 
                       class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500 h-4.5 w-4.5">
                <span class="ms-2 text-sm font-medium text-gray-600">Remember me</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-md transition duration-200 flex justify-center items-center gap-2">
            Sign In
        </button>

        <!-- Register Link -->
        <div class="text-center pt-4 border-t border-gray-100 mt-6">
            <p class="text-sm text-gray-500">Don't have an account? 
                <a href="{{ route('register') }}" class="text-teal-600 hover:text-teal-700 font-bold transition">
                    Create account
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
