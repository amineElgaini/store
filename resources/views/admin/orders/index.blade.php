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
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-800 text-sm underline">View</a>
                        
                            <!-- Edit Trigger -->
                            <div x-data="{ editOpen: false }" class="inline">
                                <button @click="editOpen = true" class="text-yellow-600 hover:text-yellow-800 text-sm underline">Edit</button>
                        
                                <!-- Edit Modal -->
                                <div
                                    x-show="editOpen"
                                    x-transition.opacity
                                    class="fixed inset-0 z-50 flex items-center justify-center"
                                    style="background-color: rgba(0,0,0,0.5);"
                                    @keydown.escape.window="editOpen = false"
                                >
                                    <div class="bg-white p-6 rounded shadow w-full max-w-md" @click.away="editOpen = false">
                                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Update Order #{{ $order->id }}</h3>
                        
                                        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="space-y-4">
                                            @csrf
                                            @method('PUT')
                        
                                            <div>
                                                <label for="status-{{ $order->id }}" class="block font-medium text-sm mb-1">Order Status</label>
                                                <select name="status" id="status-{{ $order->id }}" class="w-full border-gray-300 rounded p-2" required>
                                                    <option value="">-- Select Status --</option>
                                                    
                                                    @if($order->status !== 'pending')
                                                        <option value="pending">Pending</option>
                                                    @endif
                                                    
                                                    @if($order->status !== 'completed')
                                                        <option value="completed">Completed</option>
                                                    @endif
                                                    
                                                    @if($order->status !== 'cancelled')
                                                        <option value="cancelled">Cancelled</option>
                                                    @endif
                                                </select>
                                                
                                            </div>
                        
                                            <div class="flex justify-end gap-2">
                                                <button type="button" @click="editOpen = false" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                                    Update
                                                </button>
                                            </div>
                                        </form>
                        
                                        <div class="mt-4 text-sm text-gray-600">
                                            <strong>Current Status:</strong>
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                                {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
