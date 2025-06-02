<nav x-data="{ mobileMenuOpen: false }" class="bg-white/80 backdrop-blur-md border-b border-gray-200/50 sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-16">
      {{-- Logo --}}
      <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
        <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center transform group-hover:scale-105 transition-transform duration-200">
          <span class="text-white font-bold text-sm">E</span>
        </div>
        <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">ElegantStore</span>
      </a>

      {{-- Desktop Navigation --}}
      <div class="hidden md:flex items-center space-x-8">
        <a href="{{ route('products.index') }}" class="relative px-3 py-2 text-sm font-medium transition-colors duration-200 {{ request()->is('products') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}">
          Products
          @if (request()->is('products'))
            <div class="absolute -bottom-1 left-0 right-0 h-0.5 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full"></div>
          @endif
        </a>
      </div>

      {{-- Right Section --}}
      <div class="flex items-center space-x-4">
        {{-- Cart --}}
        <a href="{{ route('cart.index') }}" class="relative p-2 hover:bg-gray-100 rounded-full">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.293 1.293a1 1 0 001.414 1.414L7 13zM17 13l1.293 1.293a1 1 0 01-1.414 1.414L17 13z" />
          </svg>
          @if(session('cart_count', 0) > 0)
            <span class="absolute -top-1 -right-1 h-5 w-5 flex items-center justify-center p-0 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-xs rounded-full">
              {{ session('cart_count') }}
            </span>
          @endif
        </a>
        

        {{-- Authenticated --}}
        @auth
          <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center space-x-2 hover:bg-gray-100 rounded-full p-2">
              <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 2a5 5 0 00-3.9 8.18A7 7 0 003 17h14a7 7 0 00-3.1-6.82A5 5 0 0010 2z" clip-rule="evenodd" />
                </svg>
              </div>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.939l3.71-3.71a.75.75 0 111.06 1.061l-4.24 4.24a.75.75 0 01-1.06 0l-4.24-4.24a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
              </svg>
            </button>
            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 shadow-lg rounded-lg py-2 z-50">
              <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-50">View Profile</a>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-50 text-red-600">Log Out</button>
              </form>
            </div>
          </div>
        @endauth

        {{-- Guest --}}
        @guest
          <div class="hidden md:flex items-center space-x-2">
            <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-4 py-2 text-sm font-medium rounded">Login</a>
            <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-4 py-2 text-sm font-medium rounded shadow-sm">Register</a>
          </div>
        @endguest

        {{-- Mobile Menu Button --}}
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 hover:bg-gray-100 rounded-full">
          <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          <svg x-show="mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileMenuOpen" class="md:hidden py-4 border-t border-gray-200/50">
      <div class="flex flex-col space-y-3">
        <a href="{{ route('products.index') }}" class="px-3 py-2 text-base font-medium rounded-lg transition-colors {{ request()->is('products') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
          Products
        </a>
        {{-- <a href="{{ route('packages.index') }}" class="px-3 py-2 text-base font-medium rounded-lg transition-colors {{ request()->is('packages') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
          Packages
        </a> --}}

        {{-- Mobile Auth --}}
        @guest
          <div class="flex flex-col space-y-2 pt-3 border-t border-gray-200">
            <a href="{{ route('login') }}" class="justify-start text-left px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-blue-50">Login</a>
            <a href="{{ route('register') }}" class="justify-start text-left px-3 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded">Register</a>
          </div>
        @endguest
      </div>
    </div>
  </div>
</nav>
