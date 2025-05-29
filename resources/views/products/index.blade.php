<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products Page') }}
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <form method="GET" class="mb-6 flex gap-4 justify-center items-center">
            <label for="category" class="font-semibold text-gray-700">Category:</label>
            <select name="category" id="category" class="rounded border-gray-300" onchange="this.form.submit()">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @if(request('category') == $category->id) selected @endif>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </form>

        <div class="flex flex-wrap justify-center gap-6">
            @forelse($products as $product)
                <div class="w-52 border rounded-xl shadow hover:shadow-lg transition duration-300 bg-white">
                    <div class="flex justify-center p-4">
                        @if ($product->image)
                            <img 
                                src="{{ asset('storage/' . $product->image) }}"
                                alt="{{ $product->name }}"
                                class="w-36 h-40 object-cover rounded-lg"
                            >
                        @else
                            <img 
                                {{-- src="{{ asset('/images/default-product-image.png') }}" --}}
                                src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRgd3tOFHqUr2bm1aAInSv8mgmfeDshhOGjxA&s"

                                alt="Default Image"
                                class="w-36 h-40 object-cover rounded-lg"
                            >
                        @endif
                    </div>
                    <div class="px-4 pb-4">
                        <h2 class="text-lg font-semibold truncate">{{ $product->name }}</h2>
                        <p class="text-sm text-gray-600 line-clamp-2 mb-1">{{ $product->description }}</p>
                        <p class="text-green-600 font-bold mb-2">{{ $product->price }} DH</p>
                        <a href="{{ route('products.show', $product->id) }}" class="text-blue-600 hover:underline">View Details</a>
                    </div>
                </div>
            @empty
                <p>No products found for the selected category.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
