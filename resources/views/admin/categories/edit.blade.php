<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Edit Category') }}
      </h2>
  </x-slot>

  <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white shadow-sm rounded-lg p-6">

          <form method="POST" action="{{ route('admin.categories.update', $category) }}">
              @csrf
              @method('PUT')

              <div class="mb-4">
                  <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
                  <input 
                      type="text" 
                      id="name" 
                      name="name" 
                      value="{{ old('name', $category->name) }}" 
                      required 
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  >
                  @error('name')
                      <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                  @enderror
              </div>

              <div class="flex items-center gap-4">
                  <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                      Update
                  </button>
                  <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                      Cancel
                  </a>
              </div>
          </form>

      </div>
  </div>
</x-app-layout>
