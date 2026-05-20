<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Brand</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

<div class="max-w-3xl mx-auto mt-10 bg-white shadow rounded-lg p-6">
    <h1 class="text-3xl font-bold mb-6">Add Brand</h1>

    <form action="{{ route('brands.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block mb-2 font-medium">Brand Name</label>
            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                class="w-full border border-gray-300 rounded px-4 py-2"
                required
            >
        </div>

        <div class="mb-4">
            <label class="block mb-2 font-medium">Description</label>
            <textarea
                name="description"
                rows="4"
                class="w-full border border-gray-300 rounded px-4 py-2"
            >{{ old('description') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="status" checked class="mr-2">
                Active
            </label>
        </div>

        <div class="flex gap-3">
            <button
                type="submit"
                class="bg-green-600 text-white px-6 py-2 rounded"
            >
                Save Brand
            </button>

            <a
                href="{{ route('brands.index') }}"
                class="bg-gray-500 text-white px-6 py-2 rounded"
            >
                Cancel
            </a>
        </div>
    </form>
</div>

</body>
</html>