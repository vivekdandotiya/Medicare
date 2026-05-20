<x-app-layout>
    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('medicines.index') }}" class="text-teal-600 hover:text-teal-700 font-semibold text-sm flex items-center gap-1">
                &larr; Back to Medicines List
            </a>
            <h1 class="text-3xl font-extrabold text-gray-900 mt-2">Edit Medicine</h1>
            <p class="text-gray-500 text-sm">Update prices, stock quantities, and details for {{ $medicine->name }}</p>
        </div>

        <form action="{{ route('medicines.update', $medicine) }}"
              method="POST"
              enctype="multipart/form-data"
              class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Medicine Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Medicine Name</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', $medicine->name) }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500"
                           required>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                    <select name="category_id"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500"
                            required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $medicine->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Brand -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Brand</label>
                    <select name="brand_id"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500"
                            required>
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}"
                                {{ old('brand_id', $medicine->brand_id) == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Medicine Image (Optional)</label>
                    <input type="file"
                           name="image"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500">
                    @if($medicine->image)
                        <div class="mt-2 flex items-center gap-2">
                            <span class="text-xs text-gray-500">Current Image:</span>
                            <img src="{{ asset($medicine->image) }}" class="w-10 h-10 object-cover rounded-lg border border-gray-100">
                        </div>
                    @endif
                </div>

                <!-- MRP -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">MRP (₹)</label>
                    <input type="number"
                           step="0.01"
                           name="mrp"
                           value="{{ old('mrp', $medicine->mrp) }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500"
                           required>
                </div>

                <!-- Selling Price -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Selling Price (₹)</label>
                    <input type="number"
                           step="0.01"
                           name="selling_price"
                           value="{{ old('selling_price', $medicine->selling_price) }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500"
                           required>
                </div>

                <!-- Stock Quantity -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Stock Quantity</label>
                    <input type="number"
                           name="stock_quantity"
                           value="{{ old('stock_quantity', $medicine->stock_quantity) }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500"
                           required>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea name="description"
                          rows="4"
                          class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500">{{ old('description', $medicine->description) }}</textarea>
            </div>

            <!-- Options Checkboxes -->
            <div class="mt-6 flex flex-col gap-3">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox"
                           name="prescription_required"
                           value="1"
                           {{ old('prescription_required', $medicine->prescription_required) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-teal-600 focus:ring-teal-500 h-4.5 w-4.5">
                    <span class="ml-2.5 text-sm font-medium text-gray-700">Prescription Required</span>
                </label>

                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox"
                           name="status"
                           value="1"
                           {{ old('status', $medicine->status) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-teal-600 focus:ring-teal-500 h-4.5 w-4.5">
                    <span class="ml-2.5 text-sm font-medium text-gray-700">Active / Listed in Shop</span>
                </label>
            </div>

            <!-- Submit buttons -->
            <div class="mt-8 flex gap-3">
                <button type="submit"
                        class="bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm px-6 py-3 rounded-lg transition shadow-sm">
                    Save Changes
                </button>

                <a href="{{ route('medicines.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold text-sm px-6 py-3 rounded-lg transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
