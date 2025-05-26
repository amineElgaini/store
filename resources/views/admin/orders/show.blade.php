<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Order Details') }}
      </h2>
  </x-slot>

  <div class="py-12 max-w-5xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white shadow rounded-lg p-8">
          <div class="mb-6 border-b pb-4">
              <h3 class="text-2xl font-bold text-gray-900">Order #{{ $order->id }}</h3>
              <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('F j, Y, g:i A') }}</p>
          </div>

          <div class="grid md:grid-cols-2 gap-6 mb-6">
              <div>
                  <p class="text-gray-700">
                      <span class="font-medium">User:</span>
                      {{ $order->user->name ?? 'N/A' }}
                  </p>
                  <p class="text-gray-700 mt-2">
                      <span class="font-medium">Status:</span>
                      <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                          @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                          @elseif($order->status === 'completed') bg-green-100 text-green-800
                          @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                          @endif">
                          {{ ucfirst($order->status) }}
                      </span>
                  </p>
              </div>
              <div>
                  <p class="text-gray-700">
                      <span class="font-medium">Total Price:</span>
                      <span class="text-indigo-600 font-bold">{{ number_format($order->total_price, 2) }} DH</span>
                  </p>
              </div>
          </div>

          <hr class="my-6">

          {{-- Product Items --}}
          <div class="mb-10">
              <h4 class="text-lg font-semibold text-gray-800 mb-4">Product Items</h4>
              @if($order->productItems->isEmpty())
                  <p class="text-sm text-gray-500">No products in this order.</p>
              @else
                  <ul class="space-y-4">
                      @foreach($order->productItems as $item)
                          <li class="flex items-center space-x-4">
                              @if($item->product && $item->product->image)
                                  <img src="{{ asset('storage/' . $item->product->image) }}"
                                       alt="{{ $item->product->name }}"
                                       class="w-16 h-16 object-cover rounded border">
                              @else
                                  <div class="w-16 h-16 bg-gray-200 flex items-center justify-center rounded text-sm text-gray-500">
                                      N/A
                                  </div>
                              @endif

                              <div>
                                  <div class="font-semibold text-gray-900">{{ $item->product->name ?? 'Deleted Product' }}</div>
                                  <div class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</div>
                              </div>
                          </li>
                      @endforeach
                  </ul>
              @endif
          </div>

          {{-- Package Items --}}
          <div>
              <h4 class="text-lg font-semibold text-gray-800 mb-4">Package Items</h4>
              @if($order->packageItems->isEmpty())
                  <p class="text-sm text-gray-500">No packages in this order.</p>
              @else
                  <ul class="space-y-6">
                      @foreach($order->packageItems as $item)
                          <li class="bg-gray-50 rounded-lg p-4 border">
                              <div class="flex items-center space-x-4">
                                  @if($item->package && $item->package->image)
                                      <img src="{{ asset('storage/' . $item->package->image) }}"
                                           alt="{{ $item->package->name }}"
                                           class="w-16 h-16 object-cover rounded border">
                                  @else
                                      <div class="w-16 h-16 bg-gray-200 flex items-center justify-center rounded text-sm text-gray-500">
                                          N/A
                                      </div>
                                  @endif

                                  <div>
                                      <div class="font-semibold text-gray-900">{{ $item->package->name ?? 'Deleted Package' }}</div>
                                      <div class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</div>
                                  </div>
                              </div>

                              {{-- Package Details --}}
                              @if(!empty($item->package->packageDetails))
                                  <div class="mt-4 ml-2">
                                      <h5 class="text-sm font-semibold text-gray-700 mb-2">Included Products:</h5>
                                      <ul class="pl-4 space-y-1 list-disc text-sm text-gray-600">
                                          @foreach($item->package->packageDetails as $detail)
                                              <li>
                                                  {{ $detail->product->name ?? 'Deleted Product' }} — Id: {{ $detail->product->id }} — Qty: {{ $detail->quantity}}
                                              </li>
                                          @endforeach
                                      </ul>
                                  </div>
                              @endif
                          </li>
                      @endforeach
                  </ul>
              @endif
          </div>

          <div class="mt-10">
              <a href="{{ route('admin.orders.index') }}"
                 class="inline-flex items-center px-5 py-2 bg-gray-700 text-white text-sm font-semibold rounded hover:bg-gray-800">
                  ← Back to Orders
              </a>
          </div>
      </div>
  </div>
</x-app-layout>
