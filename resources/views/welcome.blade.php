<x-app-layout>

  <div class="relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
      <div class="text-center">
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
          Discover
          <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
            Premium
          </span>
          Products
        </h1>
        <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
          Explore our curated collection of high-quality products designed to elevate your lifestyle.
          From essentials to luxury items, find everything you need.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-6 py-3 text-lg font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 rounded-lg shadow-lg transition-all">
            <span>Shop Now</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
          </a>
          <a href="#learn-more" class="inline-flex items-center justify-center px-6 py-3 text-lg font-medium text-gray-700 border border-gray-300 hover:bg-gray-50 rounded-lg transition-all">
            Learn More
          </a>
        </div>
      </div>
    </div>
  
    {{-- Decorative Blobs --}}
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
      <div class="absolute top-20 left-20 w-64 h-64 bg-blue-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
      <div class="absolute top-20 right-20 w-64 h-64 bg-purple-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
      <div class="absolute -bottom-8 left-1/2 w-64 h-64 bg-pink-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>
    </div>
  </div>
  
</x-app-layout>
