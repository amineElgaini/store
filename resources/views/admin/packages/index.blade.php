<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Packages') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">

            <a href="{{ route('admin.packages.create') }}"
               class="mb-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Add Package
            </a>

            <x-flash-messages />

            <table class="min-w-full border-collapse border border-gray-200">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Id</th>
                        <th class="border px-4 py-2">Name</th>
                        <th class="border px-4 py-2">Price</th>
                        <th class="border px-4 py-2">Status</th> <!-- New Status column -->
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($packages as $package)
                    <tr x-data="{ open: false }">
                        <td class="border px-4 py-2">{{ $package->id }}</td>
                        <td class="border px-4 py-2">{{ $package->name }}</td>
                        <td class="border px-4 py-2">{{ number_format($package->price, 2) }} DH</td>
                        <td class="border px-4 py-2">
                            @if($package->is_active)
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">Active</span>
                            @else
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-semibold">Inactive</span>
                            @endif
                        </td>
                        <td class="border px-4 py-2 space-x-2">
                            <button @click="open = true"
                                class="text-blue-600 hover:text-blue-800">
                                More
                            </button>
                            <a href="{{ route('admin.packages.edit', $package) }}" class="text-yellow-600 hover:text-yellow-800">Edit</a>

                            <form method="POST" action="{{ route('admin.packages.destroy', $package) }}" class="inline" onsubmit="return confirm('Delete this package?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                            </form>

                            <div
                                x-show="open"
                                x-transition.opacity
                                style="background-color: rgba(0,0,0,0.5);"
                                class="fixed inset-0 flex items-center justify-center z-50"
                                @keydown.escape.window="open = false"
                            >
                                <div
                                    class="bg-white rounded shadow-lg p-6 max-w-lg max-h-[80vh] overflow-auto"
                                    @click.away="open = false"
                                >
                                    <h3 class="text-lg font-semibold mb-4">Products in Package: {{ $package->name }}</h3>

                                    <ul>
                                        @foreach($package->packageDetails as $detail)
                                            <li class="flex items-center justify-between border-b py-2">
                                                <div class="flex items-center space-x-3">
                                                    @if($detail->product->image)
                                                        <img src="{{ asset('storage/' . $detail->product->image) }}"
                                                            alt="{{ $detail->product->name }}"
                                                            class="w-10 h-10 object-cover rounded"
                                                        />
                                                    @else
                                                        <div class="w-10 h-10 bg-gray-200 flex items-center justify-center rounded text-gray-400 text-xs italic">
                                                            No Image
                                                        </div>
                                                    @endif
                                                    <span>{{ $detail->product->name }}</span>
                                                </div>
                                                <span class="font-semibold">{{ number_format($detail->product->price, 2) }} DH</span>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <!-- Package Price -->
                                    <div class="mt-4 pt-3 text-right text-lg font-bold">
                                        Total Package Price: {{ number_format($package->price, 2) }} DH
                                    </div>

                                    <div class="text-right mt-4">
                                        <button @click="open = false"
                                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $packages->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
