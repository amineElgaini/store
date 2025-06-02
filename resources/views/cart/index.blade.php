<x-app-layout>
    <div class="max-w-6xl mx-auto px-3 sm:px-4 lg:px-6 py-4 sm:py-6 lg:py-8">
        <div class="mb-4 sm:mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Shopping Cart</h1>
            <p class="text-sm sm:text-base text-gray-600">{{ $cartItems->count() }} items in your cart</p>
        </div>

        @if($cartItems->isEmpty())
            <div class="text-center py-8 sm:py-12">
                <p class="text-lg sm:text-xl text-gray-500 mb-4">Your cart is empty</p>
                <a href="{{ route('products.index') }}">
                    <button class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium py-2 px-4 rounded shadow">
                        Continue Shopping
                    </button>
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-3 sm:space-y-4">
                    @foreach($cartItems as $item)
                        <div class="bg-white rounded shadow overflow-hidden">
                            <div class="p-3 sm:p-4 lg:p-6 flex items-center space-x-3 sm:space-x-4">
                                <!-- Image -->
                                <div class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover" />
                                </div>

                                <!-- Info -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm sm:text-base lg:text-lg font-medium text-gray-900 truncate">{{ $item['title'] }}</h3>
                                    <div class="flex items-center space-x-2 sm:space-x-4 mt-1 text-xs sm:text-sm text-gray-500">
                                        <span>Color: {{ $item['color'] }}</span>
                                        <span>Size: {{ $item['size'] }}</span>
                                    </div>
                                    <p class="text-sm sm:text-base lg:text-lg font-semibold text-gray-900 mt-2">{{ number_format($item['price'], 2) }} DH</p>
                                </div>

                                <!-- Quantity and Remove -->
                                <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-3">
                                    <span class="text-sm sm:text-base lg:text-lg font-medium">{{ $item['quantity'] }}</span>
                                    <form method="POST" action="{{ route('cart.removeProduct', $item['id']) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="p-1.5 sm:p-2 text-red-600 hover:bg-red-50 rounded-lg" title="Remove item">
                                            <svg xmlns="http://www.w3.org/2000/svg" 
                                                class="h-5 w-5" fill="none" viewBox="0 0 24 24" 
                                                stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" 
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3m5 0H6" />
                                            </svg>

                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded shadow sticky top-20 sm:top-24 p-4 sm:p-6">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4">Contact Information</h2>

                        <form method="POST" action="{{ route('orders.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input name="name" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-blue-300">
                            </div>

                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <textarea name="address" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-blue-300"></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input name="phone" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-blue-300">
                            </div>

                            <div class="border-t pt-3 mb-4">
                                <div class="flex justify-between text-base sm:text-lg font-semibold">
                                    <span>Total</span>
                                    <span>{{ number_format($subtotal, 2) }} DH</span>
                                </div>
                            </div>

                            <input type="hidden" name="total_price" value="{{ $subtotal }}" />

                            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white text-sm sm:text-base font-medium py-2 px-4 rounded shadow">
                                Place Order
                            </button>
                        </form>

                        <a href="{{ route('products.index') }}" class="block mt-3">
                            <button class="w-full border border-gray-300 text-gray-700 font-medium py-2 px-4 rounded hover:bg-gray-50 transition-colors duration-200 text-sm sm:text-base">
                                Continue Shopping
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
