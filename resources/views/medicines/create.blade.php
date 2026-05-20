<x-app-layout>
    <div class="max-w-5xl mx-auto py-8 px-4">
        <h1 class="text-4xl font-bold mb-6">Add Medicine</h1>

        <form action="{{ route('medicines.store') }}"
              method="POST"
              class="bg-white shadow rounded-lg p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Medicine Name -->
                <div>
                    <label class="block mb-2 font-medium">Medicine Name</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           class="w-full border border-gray-300 rounded px-4 py-2"
                           required>
                </div>

                <!-- Category -->
                <!-- Category -->
<div>
    <label class="block mb-2 font-medium">Category</label>
    <select name="category_id"
            class="w-full border border-gray-300 rounded px-4 py-2"
            required>
        <option value="">Select Category</option>

        @foreach($categories as $category)
            <option value="{{ $category->id }}"
                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>

<!-- Brand -->
<div>
    <label class="block mb-2 font-medium">Brand</label>
    <select name="brand_id"
            class="w-full border border-gray-300 rounded px-4 py-2"
            required>
        <option value="">Select Brand</option>

        @foreach($brands as $brand)
            <option value="{{ $brand->id }}"
                {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                {{ $brand->name }}
            </option>
        @endforeach
    </select>
</div>

                <!-- MRP -->
                <div>
                    <label class="block mb-2 font-medium">MRP (₹)</label>
                    <input type="number"
                           step="0.01"
                           name="mrp"
                           value="{{ old('mrp') }}"
                           class="w-full border border-gray-300 rounded px-4 py-2"
                           required>
                </div>

                <!-- Selling Price -->
                <div>
                    <label class="block mb-2 font-medium">Selling Price (₹)</label>
                    <input type="number"
                           step="0.01"
                           name="selling_price"
                           value="{{ old('selling_price') }}"
                           class="w-full border border-gray-300 rounded px-4 py-2"
                           required>
                </div>

                <!-- Stock Quantity -->
                <div>
                    <label class="block mb-2 font-medium">Stock Quantity</label>
                    <input type="number"
                           name="stock_quantity"
                           value="{{ old('stock_quantity', 0) }}"
                           class="w-full border border-gray-300 rounded px-4 py-2"
                           required>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6">
                <label class="block mb-2 font-medium">Description</label>
                <textarea name="description"
                          rows="4"
                          class="w-full border border-gray-300 rounded px-4 py-2">{{ old('description') }}</textarea>
            </div>

            <!-- Checkboxes -->
            <div class="mt-6 space-y-3">
                <label class="flex items-center">
                    <input type="checkbox"
                           name="prescription_required"
                           class="mr-2">
                    Prescription Required
                </label>

                <label class="flex items-center">
                    <input type="checkbox"
                           name="status"
                           checked
                           class="mr-2">
                    Active
                </label>
            </div>

            <!-- Buttons -->
            <div class="mt-6 flex gap-3">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg">
                    Save Medicine
                </button>

                <a href="{{ route('medicines.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>