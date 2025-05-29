<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ $product->name }} - Details
      </h2>
  </x-slot>

  <div class="py-12 max-w-5xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white p-6 rounded shadow-sm">

          <div class="mb-6">
              <a href="{{ route('admin.products.index') }}"
                 class="inline-block text-blue-600 hover:underline text-sm">&larr; Back to Products</a>
          </div>

          <div class="flex flex-col md:flex-row gap-6">
            <div class="w-full md:w-1/2">
                @if($product->image)
                    <img 
                        src="{{ asset('storage/' . $product->image) }}"
                        alt="{{ $product->name }}"
                        class="max-w-full max-h-64 rounded border object-contain mx-auto"
                    />
                @else
                    <div class="w-full h-64 bg-gray-100 flex items-center justify-center text-gray-400 italic rounded">
                        No Main Image
                    </div>
                @endif
            </div>
            

              <div class="w-full md:w-1/2 space-y-2">
                  <p><strong>Name:</strong> {{ $product->name }}</p>
                  <p><strong>Category:</strong> {{ $product->category->name ?? '-' }}</p>
                  <p><strong>Price:</strong> {{ number_format($product->price, 2) }} DH</p>
                  <p><strong>Status:</strong>
                      @if($product->is_active)
                          <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">Active</span>
                      @else
                          <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-semibold">Inactive</span>
                      @endif
                  </p>
                  <p><strong>Description:</strong></p>
                  <p class="whitespace-pre-wrap">{{ $product->description ?? 'No description available.' }}</p>
              </div>
          </div>

          <hr class="my-6">

          <h3 class="text-lg font-semibold mb-4">Variants</h3>

          @forelse($product->productVariants as $variant)
              <div class="flex justify-between items-center border p-4 mb-3 rounded">
                  <div>
                      <p><strong>Color:</strong> {{ $variant->color->name ?? '-' }}</p>
                      <p><strong>Size:</strong> {{ $variant->size->name ?? '-' }}</p>
                      <p><strong>Stock:</strong> {{ $variant->stock }}</p>
                  </div>

                  @php
                      $colorImage = $product->productColorImages
                          ->where('color_id', $variant->color_id)
                          ->first();
                  @endphp

                  @if($colorImage)
                      <img src="{{ asset('storage/' . $colorImage->image) }}"
                           class="w-16 h-16 object-cover rounded border"
                           alt="Color Image">
                  @else
                      <div class="w-16 h-16 bg-gray-100 text-xs flex items-center justify-center text-gray-500 rounded">
                          No Image
                      </div>
                  @endif
              </div>
          @empty
              <p class="text-gray-500 italic">No variants available.</p>
          @endforelse

      </div>
  </div>
</x-app-layout>
