<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Categories') }}
      </h2>
  </x-slot>

  <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">

      <div class="flex justify-end mb-4">
          <a href="{{ route('admin.categories.create') }}" 
             class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
              Add Category
          </a>
      </div>

      @if(session('success'))
          <div class="mb-4 rounded-md bg-green-50 p-4 text-green-800">
              {{ session('success') }}
          </div>
      @endif

      <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                  <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                      <th scope="col" class="relative px-6 py-3">
                          <span class="sr-only">Actions</span>
                      </th>
                  </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                  @foreach($categories as $category)
                  <tr>
                      <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $category->id }}</td>
                      <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $category->name }}</td>
                      <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium space-x-2">

                          <a href="{{ route('admin.categories.edit', $category) }}" 
                             class="text-yellow-600 hover:text-yellow-800">
                             Edit
                          </a>

                          <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Delete this category?')">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="text-red-600 hover:text-red-800">
                                  Delete
                              </button>
                          </form>

                      </td>
                  </tr>
                  @endforeach
              </tbody>
          </table>
      </div>

      <div class="mt-6">
          {{ $categories->links() }}
      </div>

  </div>
</x-app-layout>
