<x-app-layout>
    <div class="max-w-3xl mx-auto py-8 px-4">
        <h1 class="text-3xl font-bold mb-6">Add Category</h1>

        <form action="{{ route('categories.store') }}" method="POST"
              class="bg-white shadow rounded-lg p-6">
            @csrf

            <!-- Category Name -->
            <div class="mb-4">
                <label class="block mb-2 font-medium">Category Name</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="w-full border border-gray-300 rounded px-4 py-2"
                    required
                >

                @error('name')
                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block mb-2 font-medium">Description</label>
                <textarea
                    name="description"
                    rows="4"
                    class="w-full border border-gray-300 rounded px-4 py-2"
                >{{ old('description') }}</textarea>
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input
                        type="checkbox"
                        name="status"
                        checked
                        class="mr-2"
                    >
                    Active
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded"
                >
                    Save Category
                </button>

                <a
                    href="{{ route('categories.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>