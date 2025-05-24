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
                      <td class="border border-gray-300 px-4 py-2">{{ $product->stock }}</td>
                      <td class="border border-gray-300 px-4 py-2">
                        @if($product->is_active)
                          <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">Active</span>
                        @else
                          <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-semibold">Inactive</span>
                        @endif
                      </td>
                      
                      <td class="border border-gray-300 px-4 py-2 space-x-2 flex flex-wrap gap-2">
                        <div x-data="{ open: false }">
                          <button @click="open = true" class="text-blue-600">View</button>
                          <div
                            x-show="open"
                            x-transition.opacity
                            style="background-color: rgba(0,0,0,0.5);"
                            class="fixed inset-0 flex items-center justify-center z-50"
                            @keydown.escape.window="open = false"
                          >
                            <div
                              class="bg-white rounded shadow-lg p-6 max-w-lg max-h-[80vh] overflow-auto"
                              @click.away="open = false"
                            >
                              <h3 class="text-lg font-semibold mb-4">{{ $product->name }} Details</h3>
                              
                              @if($product->image)
                                  <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="max-w-full max-h-48 rounded mb-4 mx-auto" />
                              @else
                                  <div class="w-full h-48 bg-gray-200 flex items-center justify-center rounded mb-4 text-gray-400 italic">
                                      No Image Available
                                  </div>
                              @endif
                      
                              <p class="whitespace-pre-wrap">{{ $product->description ?? 'No description available.' }}</p>
                      
                              <button @click="open = false" class="mt-4 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                Close
                              </button>
                            </div>
                          </div>
                        </div>
                          <a href="{{ route('admin.products.edit', $product) }}" class="text-yellow-600 hover:text-yellow-800">Edit</a>
                          <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline" onsubmit="return confirm('Delete this product?')">
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
              {{ $products->links() }}
          </div>

      </div>
  </div>
</x-app-layout>
