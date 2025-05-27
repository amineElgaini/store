<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ addOpen: false }">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">

            <!-- Add Category Button -->
            <button @click="addOpen = true"
                class="mb-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Add Category
            </button>

            <!-- Add Category Modal -->
            <div
                x-show="addOpen"
                x-transition.opacity
                class="fixed inset-0 z-50 flex items-center justify-center"
                style="background-color: rgba(0,0,0,0.5);"
                @keydown.escape.window="addOpen = false"
            >
                <div class="bg-white rounded shadow-lg p-6 w-full max-w-md" @click.away="addOpen = false">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Add Category</h3>

                    <form method="POST" action="{{ route('admin.categories.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="new_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Category Name
                            </label>
                            <input 
                                type="text" 
                                id="new_name" 
                                name="name" 
                                value="{{ old('name') }}" 
                                required 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                            @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="button" @click="addOpen = false"
                                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <x-flash-messages />

            <table class="min-w-full border-collapse border border-gray-200">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Name</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr x-data="{ editOpen: false, deleteOpen: false }">
                            <td class="border px-4 py-2">{{ $category->id }}</td>
                            <td class="border px-4 py-2">{{ $category->name }}</td>
                            <td class="border px-4 py-2 space-x-2 flex flex-wrap gap-2">

                                <!-- Edit Button -->
                                <button @click="editOpen = true"
                                        class="text-yellow-600 hover:text-yellow-800 text-sm underline">
                                    Edit
                                </button>

                                <!-- Edit Modal -->
                                <div
                                    x-show="editOpen"
                                    x-transition.opacity
                                    class="fixed inset-0 z-50 flex items-center justify-center"
                                    style="background-color: rgba(0,0,0,0.5);"
                                    @keydown.escape.window="editOpen = false"
                                >
                                    <div class="bg-white rounded shadow-lg p-6 w-full max-w-md" @click.away="editOpen = false">
                                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Edit Category</h3>

                                        <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                                            @csrf
                                            @method('PUT')

                                            <div class="mb-4">
                                                <label for="name-{{ $category->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                    Category Name
                                                </label>
                                                <input 
                                                    type="text" 
                                                    id="name-{{ $category->id }}" 
                                                    name="name" 
                                                    value="{{ old('name', $category->name) }}" 
                                                    required 
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                >
                                                @error('name')
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="flex justify-end gap-2">
                                                <button type="button" @click="editOpen = false"
                                                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                                    Cancel
                                                </button>
                                                <button type="submit"
                                                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                                    Save
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Delete Button -->
                                <button @click="deleteOpen = true"
                                        class="text-red-600 hover:text-red-800 text-sm underline">
                                    Delete
                                </button>

                                <!-- Delete Confirmation Modal -->
                                <div
                                    x-show="deleteOpen"
                                    x-transition.opacity
                                    style="background-color: rgba(0,0,0,0.5);"
                                    class="fixed inset-0 flex items-center justify-center z-50"
                                    @keydown.escape.window="deleteOpen = false"
                                >
                                    <div class="bg-white rounded shadow-lg p-6 max-w-sm w-full" @click.away="deleteOpen = false">
                                        <h3 class="text-lg font-semibold mb-4 text-red-700">Delete Category</h3>
                                        <p>Are you sure you want to delete <strong>{{ $category->name }}</strong>?</p>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="mt-4">
                                            @csrf
                                            @method('DELETE')
                                            <div class="text-right">
                                                <button type="submit"
                                                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                                    Confirm
                                                </button>
                                                <button type="button"
                                                        @click="deleteOpen = false"
                                                        class="ml-2 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                                    Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
