<x-app-layout>
  <x-slot name="header">
      <h2 class="text-xl font-bold text-gray-800">Your Cart</h2>
  </x-slot>

  <div class="p-6">
      <h3 class="text-lg font-semibold mb-4">Products</h3>
      @forelse($productItems as $item)
          <div class="mb-3 border p-4 rounded-lg">
              <p><strong>{{ $item['variant']->product->name }}</strong></p>
              <p>Size: {{ $item['variant']->size->name ?? 'N/A' }}, Color: {{ $item['variant']->color->name ?? 'N/A' }}</p>
              <p>Qty: {{ $item['quantity'] }}</p>
          </div>
      @empty
          <p>No products in cart.</p>
      @endforelse

      <h3 class="text-lg font-semibold mt-6 mb-4">Packages</h3>
      @forelse($packageItems as $package)
          <div class="mb-3 border p-4 rounded-lg">
              <p><strong>{{ $package->name }}</strong> ({{ $package->price }} DH)</p>
          </div>
      @empty
          <p>No packages in cart.</p>
      @endforelse

      <form action="{{ route('cart.clear') }}" method="POST" class="mt-6">
          @csrf
          <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Clear Cart</button>
      </form>
  </div>
</x-app-layout>
