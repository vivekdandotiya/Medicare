<x-app-layout>
    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('categories.index') }}" class="text-teal-600 hover:text-teal-700 font-semibold text-sm flex items-center gap-1">
                &larr; Back to Categories List
            </a>
            <h1 class="text-3xl font-extrabold text-gray-900 mt-2">Edit Category</h1>
            <p class="text-gray-500 text-sm">Update details for the category</p>
        </div>

        <form action="{{ route('categories.update', $category) }}"
              method="POST"
              enctype="multipart/form-data"
              class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Category Name</label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $category->name) }}"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500"
                       required>
            </div>

            <!-- Image Upload -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Category Image (Optional)</label>
                <input type="file"
                       name="image"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500">
                @if($category->image)
                    <div class="mt-2 flex items-center gap-2">
                        <span class="text-xs text-gray-500">Current Image:</span>
                        <img src="{{ asset($category->image) }}" class="w-10 h-10 object-cover rounded-lg border border-gray-100">
                    </div>
                @endif
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description (Optional)</label>
                <textarea name="description"
                          rows="4"
                          class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500">{{ old('description', $category->description) }}</textarea>
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox"
                           name="status"
                           value="1"
                           {{ old('status', $category->status) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-teal-600 focus:ring-teal-500 h-4.5 w-4.5">
                    <span class="ml-2.5 text-sm font-medium text-gray-700">Active</span>
                </label>
            </div>

            <!-- Submit -->
            <div class="flex gap-3">
                <button type="submit"
                        class="bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm px-6 py-3 rounded-lg transition shadow-sm">
                    Save Changes
                </button>
                <a href="{{ route('categories.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold text-sm px-6 py-3 rounded-lg transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
