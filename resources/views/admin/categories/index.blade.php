<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">

            <a href="{{ route('admin.categories.create') }}"
               class="mb-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Add Category
            </a>

            <x-flash-messages />

            <table class="min-w-full border-collapse border border-gray-200">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Name</th>
                        <th class="border px-4 py-2">Status</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td class="border px-4 py-2">{{ $category->id }}</td>
                            <td class="border px-4 py-2">{{ $category->name }}</td>
                            <td class="border px-4 py-2">
                                @if($category->is_active)
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">Inactive</span>
                                @endif
                            </td>
                            <td class="border px-4 py-2 space-x-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="text-yellow-600 hover:text-yellow-800">Edit</a>

                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
