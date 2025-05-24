<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Update Order Status') }} - #{{ $order->id }}
      </h2>
  </x-slot>

  <div class="py-12 max-w-2xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white p-6 rounded shadow">

        <x-flash-messages />

          {{-- Order Status Form --}}
          <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="space-y-4">
              @csrf
              @method('PUT')

              <div>
                  <label for="status" class="block font-semibold mb-1">Order Status</label>
                  <select name="status" id="status" class="w-full border-gray-300 rounded p-2" required>
                      <option value="">-- Select Status --</option>
                      <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                      <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                      <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                  </select>
              </div>

              <div>
                  <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                      Update Status
                  </button>
              </div>
          </form>

          {{-- Current Status --}}
          <div class="mt-6 text-sm text-gray-600">
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
</x-app-layout>
