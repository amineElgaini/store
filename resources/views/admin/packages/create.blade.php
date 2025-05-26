<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Package') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form method="POST" action="{{ route('admin.packages.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700">Price</label>
                    <input type="number" name="price" step="0.01" value="{{ old('price') }}"
                           class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                    @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700 mb-2">Select Products</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 max-h-64 overflow-auto border rounded p-2">
                        @foreach($products as $product)
                            <label class="flex items-start space-x-3 border rounded p-2 cursor-pointer hover:bg-gray-50">
                                <input
                                    type="checkbox"
                                    name="product_ids[]"
                                    value="{{ $product->id }}"
                                    class="form-checkbox h-5 w-5 text-blue-600 mt-2"
                                    @if(is_array(old('product_ids')) && in_array($product->id, old('product_ids'))) checked @endif
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
                                            value="{{ old('quantities.' . $product->id, 1) }}"
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
                
                
                {{-- <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700">Select Products</label>
                    <select name="product_ids[]" multiple class="form-select w-full rounded-md shadow-sm">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                    @error('product_ids') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div> --}}

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
