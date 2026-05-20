<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-extrabold text-gray-900">Create Account</h2>
        <p class="text-sm text-gray-500 mt-1">Join Medicare to manage prescriptions & orders</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" 
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500 bg-gray-50"
                   required autofocus autocomplete="name">
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" 
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500 bg-gray-50"
                   required autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Password</label>
            <input id="password" type="password" name="password" 
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500 bg-gray-50"
                   required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" 
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500 bg-gray-50"
                   required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <!-- Register As (Role) -->
        <div>
            <label for="role" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Account Type / Register As</label>
            <select id="role" name="role" 
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500 bg-gray-50 font-medium text-gray-700" 
                    required>
                <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer (Buy Medicines)</option>
                <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff (Manage Store)</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Full Control)</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-1" />
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded-xl shadow-md transition duration-200 mt-6 flex justify-center items-center">
            Register Account
        </button>

        <!-- Login Link -->
        <div class="text-center pt-4 border-t border-gray-100 mt-6">
            <p class="text-sm text-gray-500">Already registered? 
                <a href="{{ route('login') }}" class="text-teal-600 hover:text-teal-700 font-bold transition">
                    Sign In
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
