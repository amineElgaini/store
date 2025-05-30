<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Package') }}
        </h2>
    </x-slot>
    
    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form method="POST" action="{{ route('admin.packages.update', $package) }}">
                @csrf
                @method('PUT')
            
                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700">Name</label>
                    <input type="text" name="name" 
                           value="{{ old('name', $package->name) }}"
                           class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700">Price</label>
                    <input type="number" name="price" step="0.01" 
                           value="{{ old('price', $package->price) }}"
                           class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                    @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4 flex items-center space-x-2">
                    <input 
                        type="checkbox" 
                        name="is_active" 
                        id="is_active"
                        value="1"
                        {{ old('is_active', $package->is_active) ? 'checked' : '' }}
                        class="form-checkbox h-5 w-5 text-blue-600"
                    />
                    <label for="is_active" class="font-medium text-sm text-gray-700">Is Active</label>
                </div>
                @error('is_active') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700 mb-2">Select Products</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 max-h-64 overflow-auto border rounded p-2">
                        @php
                            $selectedProductIds = $package->packageDetails->pluck('product_id')->toArray();
                        @endphp
                        @foreach($products as $product)
                        @php
                            $isChecked = in_array($product->id, old('product_ids', $selectedProductIds));
                            $oldQuantities = old('quantities', $productQuantities ?? []);
                            $quantityValue = $oldQuantities[$product->id] ?? 1;
                        @endphp
                    
                        <label class="flex items-start space-x-3 border rounded p-2 cursor-pointer hover:bg-gray-50">
                            <input
                                type="checkbox"
                                name="product_ids[]"
                                value="{{ $product->id }}"
                                class="form-checkbox h-5 w-5 text-blue-600 mt-2"
                                {{ $isChecked ? 'checked' : '' }}
                            />
                    
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded" />
                            @else
                                <div class="w-12 h-12 bg-gray-200 flex items-center justify-center rounded text-gray-400 text-xs italic">
                                    No Image
                                </div>
                            @endif
                    
                            <div class="flex-1">
                                <div class="font-medium">{{ $product->name }}</div>
                                <div class="text-sm text-gray-600">${{ number_format($product->price, 2) }}</div>
                                <div class="mt-2">
                                    <label class="text-sm text-gray-700">Quantity:</label>
                                    <input
                                        type="number"
                                        name="quantities[{{ $product->id }}]"
                                        value="{{ $quantityValue }}"
                                        min="1"
                                        class="form-input w-20 mt-1 rounded-md shadow-sm"
                                    />
                                </div>
                            </div>
                        </label>
                    @endforeach
                    
                    </div>
                    @error('product_ids') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div class="flex items-center gap-4">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" type="submit">
                        Save
                    </button>
                    <a href="{{ route('admin.packages.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
