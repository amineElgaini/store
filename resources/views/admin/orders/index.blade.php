<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Orders') }}
      </h2>
  </x-slot>

  <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white p-6 shadow rounded">
        <x-flash-messages />

          <table class="min-w-full border-collapse border border-gray-200">
              <thead>
                  <tr>
                      <th class="border px-4 py-2">ID</th>
                      <th class="border px-4 py-2">User</th>
                      <th class="border px-4 py-2">Total</th>
                      <th class="border px-4 py-2">Status</th>
                      <th class="border px-4 py-2">Created</th>
                      <th class="border px-4 py-2">Actions</th>
                  </tr>
              </thead>
              <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td class="border px-4 py-2">{{ $order->id }}</td>
                        <td class="border px-4 py-2">{{ $order->user->name ?? 'N/A' }}</td>
                        <td class="border px-4 py-2">{{ number_format($order->total_price, 2) }} DH</td>
                        
                        {{-- Status Badge --}}
                        <td class="border px-4 py-2">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
            
                        <td class="border px-4 py-2">{{ $order->created_at->format('Y-m-d') }}</td>
            
                        {{-- Actions --}}
                        <td class="border px-4 py-2 space-x-2">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:underline">View</a>
                            <a href="{{ route('admin.orders.edit', $order) }}" class="text-yellow-600 hover:underline">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center px-4 py-2 text-gray-500">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
            
          </table>

          <div class="mt-4">
              {{ $orders->links() }}
          </div>
      </div>
  </div>
</x-app-layout>
