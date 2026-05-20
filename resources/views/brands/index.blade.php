{{-- resources/views/brands/index.blade.php --}}

<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4">
        <!-- Heading and Add Button -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-4xl font-bold">Brands</h1>

            <a href="{{ route('brands.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow">
                Add Brand
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Brands Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-4 text-left font-semibold">ID</th>
                        <th class="p-4 text-left font-semibold">Name</th>
                        <th class="p-4 text-left font-semibold">Slug</th>
                        <th class="p-4 text-left font-semibold">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($brands as $brand)
                        <tr class="border-t">
                            <td class="p-4">{{ $brand->id }}</td>
                            <td class="p-4">{{ $brand->name }}</td>
                            <td class="p-4">{{ $brand->slug }}</td>
                            <td class="p-4">
                                @if($brand->status)
                                    <span class="text-green-600 font-medium">
                                        Active
                                    </span>
                                @else
                                    <span class="text-red-600 font-medium">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-4 text-center text-gray-500">
                                No brands found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $brands->links() }}
        </div>
    </div>
</x-app-layout>