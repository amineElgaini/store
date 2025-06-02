<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50" x-data="{ showFilters: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Our Products</h1>
                <p class="text-gray-600">Discover our curated collection of premium products</p>
            </div>

            <!-- Mobile Filter Toggle Button -->
            <div class="mb-4 lg:hidden">
                <button 
                    @click="showFilters = !showFilters"
                    class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow hover:bg-blue-700 transition">
                    <span x-text="showFilters ? 'Hide Filters' : 'Show Filters'"></span>
                </button>
            </div>

            <form method="GET" class="lg:grid lg:grid-cols-4 lg:gap-8">
                <!-- Filters -->
                <div x-data="filters()">
                    <!-- Mobile Filters (Toggleable) -->
                    <div 
                        class="space-y-6 mb-8 lg:hidden"
                        x-show="showFilters"
                        x-transition
                    >
                        @include('partials.filters')
                    </div>
                  
                    <!-- Desktop Filters (Always Visible) -->
                    <div class="space-y-6 hidden lg:block">
                        @include('partials.filters')
                    </div>

                    <!-- Hidden Inputs for submission -->
                    <template x-for="categoryId in selectedCategories" :key="categoryId">
                        <input type="hidden" name="categories[]" :value="categoryId">
                    </template>
                    <input type="hidden" name="min_price" :value="minPrice">
                    <input type="hidden" name="max_price" :value="maxPrice">
                </div>
                  
                <!-- Product Grid -->
                <div class="lg:col-span-3 mt-8 lg:mt-0">
                    <p class="text-gray-600 mb-6">
                        Showing {{ $products->count() }} of {{ $products->count() }} products
                    </p>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-6">
                        @forelse ($products as $product)
                            <a href="{{ route('products.show', $product->id) }}"
                               class="group block border border-gray-200 rounded-2xl overflow-hidden shadow hover:shadow-lg transition-all duration-300 bg-white transform hover:scale-[1.02]">
                               
                                {{-- Image Container (1:1 aspect ratio) --}}
                                <div class="relative w-full aspect-square bg-gradient-to-br from-gray-50 to-gray-100 overflow-hidden">
                                    <img src="{{ $product->image ? 'storage/'.$product->image : 'images/default-product-image.png' }}"
                                         alt="{{ $product->name }}"
                                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110" />
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </div>
                    
                                {{-- Product Content --}}
                                <div class="p-3 flex flex-col justify-between h-[11rem] sm:h-[12rem]">
                                    {{-- Category --}}
                                    <span class="text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-100 px-2 py-1 rounded-full mb-2 inline-block truncate">
                                        {{ $product->category->name }}
                                    </span>
                    
                                    {{-- Name --}}
                                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 leading-tight group-hover:text-indigo-600 transition-colors duration-300 truncate">
                                        {{ $product->name }}
                                    </h3>
                    
                                    {{-- Description --}}
                                    <p class="text-xs sm:text-sm text-gray-500 mt-1 line-clamp-2 leading-snug">
                                        {{ $product->description }}
                                    </p>
                    
                                    {{-- Price --}}
                                    <div class="mt-auto text-lg sm:text-xl font-extrabold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                                        ${{ number_format($product->price, 2) }}
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-full text-center text-gray-500 py-16">
                                <div class="max-w-md mx-auto">
                                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2M4 13h2m13-8l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                    <p class="text-lg font-medium text-gray-600 mb-2">No products found</p>
                                    <p class="text-gray-400">Try adjusting your search criteria to find what you're looking for.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>                    
                    

                    {{ $products->links() }}

                </div>
            </form>
        </div>
    </div>
<script>
function filters() {
    return {
        minPrice: @json(request('min_price')),
        maxPrice: @json(request('max_price')),
        selectedCategories: @json(request('categories', [])),
    }
}
</script>

</x-app-layout>

