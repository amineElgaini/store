<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Users -->
            <div class="bg-white shadow rounded-lg p-5 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M5.121 17.804A10 10 0 1119.88 6.196a10 10 0 01-14.758 11.608z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-lg font-semibold text-gray-700">{{ $userCount }}</div>
                        <div class="text-sm text-gray-500">Users</div>
                    </div>
                </div>
            </div>

            <!-- Products -->
            <div class="bg-white shadow rounded-lg p-5 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M20 13V6a2 2 0 00-2-2h-3.5M4 6h16M4 6v10a2 2 0 002 2h12a2 2 0 002-2V6H4z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-lg font-semibold text-gray-700">{{ $productCount }}</div>
                        <div class="text-sm text-gray-500">Products</div>
                    </div>
                </div>
            </div>

            <!-- Orders -->
            <div class="bg-white shadow rounded-lg p-5 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 17v-2a4 4 0 014-4h6m2 2l-2-2-2 2"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-lg font-semibold text-gray-700">{{ $orderCount }}</div>
                        <div class="text-sm text-gray-500">Orders</div>
                    </div>
                </div>
            </div>

            <!-- Packages -->
            <div class="bg-white shadow rounded-lg p-5 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M20 12H4"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 4v16"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-lg font-semibold text-gray-700">{{ $packageCount }}</div>
                        <div class="text-sm text-gray-500">Packages</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
