<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Add Product') }}
      </h2>
  </x-slot>

  <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white shadow-sm sm:rounded-lg p-6">

        <div class="mb-6">
            <a href="{{ route('admin.products.index') }}"
               class="inline-block text-blue-600 hover:underline text-sm">&larr; Back to Products</a>
        </div>

        <x-flash-messages />

          <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
              @csrf

              <div class="mb-4">
                  <label for="name" class="block font-medium text-sm text-gray-700">Product Name</label>
                  <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                  @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
              </div>

              <div class="mb-4">
                  <label for="image" class="block font-medium text-sm text-gray-700">Product Image</label>
                  <input id="image" name="image" type="file"
                      class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                      file:rounded-md file:border-0 file:text-sm file:font-semibold
                      file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                  @error('image') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
              </div>

              <div class="mb-4">
                  <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
                  <textarea id="description" name="description" rows="3"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
                  @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
              </div>

              <div class="mb-4 grid grid-cols-2 gap-4">
                  <div>
                      <label for="price" class="block font-medium text-sm text-gray-700">Price</label>
                      <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price') }}" required
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                      @error('price') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                  </div>
                  <div class="mb-6">
                    <label for="category_id" class="block font-medium text-sm text-gray-700">Category</label>
                    <select id="category_id" name="category_id" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
              </div>

              <div class="flex items-center gap-4">
                  <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                      Create
                  </button>
                  <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
              </div>
          </form>

      </div>
  </div>
</x-app-layout>
