<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Variants for: {{ $product->name }}
        </h2>
    </x-slot>
  
    <div class="py-12 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-10">
  
            <div class="mb-6">
                <a href="{{ route('admin.products.index') }}"
                   class="inline-block text-blue-600 hover:underline text-sm">&larr; Back to Products</a>
            </div>
            
            <x-flash-messages />

            {{-- Existing Variants --}}
            <section>
                <h3 class="text-lg font-semibold mb-6">Existing Variants</h3>
                <div class="space-y-6">
                    @foreach($variants as $variant)
                        <form method="POST" action="{{ route('admin.variants.update', $variant) }}" enctype="multipart/form-data" class="border p-6 rounded-md bg-gray-50 shadow-sm flex flex-wrap gap-6 items-center">
                            @csrf
                            @method('PUT')
            
                            {{-- Size --}}
                            <div class="flex flex-col w-40">
                                <label for="size_id_{{ $variant->id }}" class="font-medium text-gray-700 mb-1">Size</label>
                                <select name="size_id" id="size_id_{{ $variant->id }}" required
                                    class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($sizes as $size)
                                        <option value="{{ $size->id }}" {{ $variant->size_id == $size->id ? 'selected' : '' }}>
                                            {{ $size->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
            
                            {{-- Color --}}
                            <div class="flex flex-col w-40">
                                <label for="color_id_{{ $variant->id }}" class="font-medium text-gray-700 mb-1">Color</label>
                                <select name="color_id" id="color_id_{{ $variant->id }}" required
                                    class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($colors as $color)
                                        <option value="{{ $color->id }}" {{ $variant->color_id == $color->id ? 'selected' : '' }}>
                                            {{ $color->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
            
                            {{-- Stock --}}
                            <div class="flex flex-col w-24">
                                <label for="stock_{{ $variant->id }}" class="font-medium text-gray-700 mb-1">Stock</label>
                                <input type="number" min="0" id="stock_{{ $variant->id }}" name="stock" value="{{ old('stock', $variant->stock) }}" required
                                    class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                            </div>
            
                            {{-- Image Preview & Input --}}
                            <div class="flex flex-col w-32">
                                <label for="image_{{ $variant->id }}" class="font-medium text-gray-700 mb-1">Current Image</label>
            
                                @php
                                    $image = $colorImages[$variant->color_id]->image ?? null;
                                @endphp
            
                                @if($image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="Color Image" class="w-32 h-32 object-cover rounded border mb-2" />
                                @else
                                    <div class="w-32 h-32 bg-gray-100 rounded border flex items-center justify-center text-gray-400 italic text-sm">
                                        No Image
                                    </div>
                                @endif
            
                                <input type="file" id="image_{{ $variant->id }}" name="image"
                                    class="border-gray-300 rounded-md shadow-sm" />
                            </div>
            
                            {{-- Buttons --}}
                            <div class="flex flex-col space-y-3 ml-auto">
                                <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Update</button>
            
                                <button 
                                    type="button"
                                    onclick="if(confirm('Are you sure you want to delete this variant?')) { document.getElementById('delete-variant-{{ $variant->id }}').submit() }"
                                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                                >Delete</button>
                            </div>
                        </form>
            
                        {{-- Moved delete form outside the update form --}}
                        <form id="delete-variant-{{ $variant->id }}" method="POST" action="{{ route('admin.variants.destroy', $variant) }}" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endforeach
                </div>
            </section>
            
  
            {{-- Add New Variant --}}
            <section>
                <h3 class="text-lg font-semibold mb-6">Add New Variant</h3>
                <form method="POST" action="{{ route('admin.products.variants.store', $product) }}" enctype="multipart/form-data" class="border p-6 rounded-md bg-white shadow flex flex-wrap gap-6 items-center">
                    @csrf
  
                    <div class="flex flex-col w-40">
                        <label for="size_id_new" class="font-medium text-gray-700 mb-1">Size</label>
                        <select name="size_id" id="size_id_new" required
                            class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select size</option>
                            @foreach($sizes as $size)
                                <option value="{{ $size->id }}">{{ $size->name }}</option>
                            @endforeach
                        </select>
                        @error('size_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
  
                    <div class="flex flex-col w-40">
                        <label for="color_id_new" class="font-medium text-gray-700 mb-1">Color</label>
                        <select name="color_id" id="color_id_new" required
                            class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select color</option>
                            @foreach($colors as $color)
                                <option value="{{ $color->id }}">{{ $color->name }}</option>
                            @endforeach
                        </select>
                        @error('color_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
  
                    <div class="flex flex-col w-24">
                        <label for="stock_new" class="font-medium text-gray-700 mb-1">Stock</label>
                        <input type="number" min="0" id="stock_new" name="stock" value="{{ old('stock') }}" required
                            class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                        @error('stock') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
  
                    <div class="flex flex-col w-32">
                        <label for="image_new" class="font-medium text-gray-700 mb-1">Image</label>
                        <input type="file" id="image_new" name="image"
                            class="border-gray-300 rounded-md shadow-sm" />
                        @error('image') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
  
                    <div class="flex items-end">
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">Add Variant</button>
                    </div>
                </form>
            </section>
  
        </div>
    </div>
  </x-app-layout>
  