<!-- Price Filter -->
<div class="bg-white/80 backdrop-blur-sm rounded shadow p-4">
    <h2 class="font-semibold text-lg text-gray-900 mb-3">Price Range</h2>
    <div class="flex items-center gap-2 text-sm text-gray-600">
        <input 
            type="number" 
            min="0" 
            max="10000" 
            placeholder="Min"
            x-model.number="minPrice"
            class="w-20 border border-gray-300 rounded px-2 py-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        >
        <span class="text-gray-400">-</span>
        <input 
            type="number" 
            min="0" 
            max="10000" 
            placeholder="Max"
            x-model.number="maxPrice"
            class="w-20 border border-gray-300 rounded px-2 py-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        >
    </div>
  </div>
  
  <!-- Category Filter -->
  <div class="bg-white/80 backdrop-blur-sm rounded shadow p-4 mt-4">
    <h2 class="font-semibold text-lg text-gray-900 mb-3">Categories</h2>
    <div class="flex flex-wrap gap-2">
        @foreach ($allCategories as $category)
            <label class="flex items-center space-x-2 text-sm text-gray-700 bg-gray-100 rounded px-3 py-2 cursor-pointer hover:bg-gray-200 transition-colors">
                <input 
                    type="checkbox" 
                    :value="{{ $category->id }}"
                    x-model="selectedCategories"
                    class="border-gray-300 rounded text-blue-600 focus:ring-blue-500"
                >
                <span>{{ $category->name }}</span>
            </label>
        @endforeach
    </div>
  </div>
  

  
  <!-- Filter Actions -->
  <div class="flex flex-col sm:flex-row gap-2 mt-6">
    <button 
        type="submit" 
        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded shadow transition-colors duration-200"
    >
        Apply Filters
    </button>
    <a 
        href="{{ route('products.index') }}" 
        class="flex-1 text-center border border-gray-300 text-gray-700 font-medium py-2 px-4 rounded hover:bg-gray-50 transition-colors duration-200"
    >
        Clear All
    </a>
  </div>
  