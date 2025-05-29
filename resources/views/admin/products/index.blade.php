<!-- resources/views/admin/products/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>
  
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
  
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4">
                Add Product
            </a>
  
            <x-flash-messages />
  
            <table class="min-w-full border-collapse border border-gray-200">
                <thead>
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">ID</th>
                        <th class="border border-gray-300 px-4 py-2">Name</th>
                        <th class="border border-gray-300 px-4 py-2">Category</th>
                        <th class="border border-gray-300 px-4 py-2">Price</th>
                        <th class="border border-gray-300 px-4 py-2">Stock</th>
                        <th class="border border-gray-300 px-4 py-2">Status</th>
                        <th class="border border-gray-300 px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr x-data="{ open: false }">
                        <td class="border border-gray-300 px-4 py-2">{{ $product->id }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $product->name }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $product->category->name ?? '-' }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ number_format($product->price, 2) }} DH</td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ $product->getTotalStockAttribute() }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                          @if($product->is_active)
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">Active</span>
                          @else
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-semibold">Inactive</span>
                          @endif
                        </td>
                        
                        <td class="border border-gray-300 px-4 py-2 space-x-2 flex flex-wrap gap-2" x-data="{ deleteOpen: false }">

                            <a href="{{ route('admin.products.show', $product) }}"
                               class="text-blue-600 hover:text-blue-800 text-sm underline">Show</a>
                        
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="text-yellow-600 hover:text-yellow-800 text-sm underline">Edit Product</a>
                        
                            <a href="{{ route('admin.products.variants.edit', $product) }}"
                               class="text-green-600 hover:text-green-800 text-sm underline">Edit Variants</a>
                        
                            <button @click="deleteOpen = true"
                                    class="text-red-600 hover:text-red-800 text-sm underline">Delete</button>
                        
                            <!-- Delete confirmation modal -->
                            <div
                                x-show="deleteOpen"
                                x-transition.opacity
                                style="background-color: rgba(0,0,0,0.5);"
                                class="fixed inset-0 flex items-center justify-center z-50"
                                @keydown.escape.window="deleteOpen = false"
                            >
                                <div class="bg-white rounded shadow-lg p-6 max-w-sm w-full" @click.away="deleteOpen = false">
                                    <h3 class="text-lg font-semibold mb-4 text-red-700">Delete Product</h3>
                                    <p>Are you sure you want to delete <strong>{{ $product->name }}</strong>?</p>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="mt-4">
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
                {{ $products->links() }}
            </div>
  
        </div>
    </div>
</x-app-layout>
  