<x-app-layout>
    <div class="py-12 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="productDetail()">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 items-center justify-center">
                <!-- Left: Image Gallery -->
                <div class="flex flex-col items-center">
                    <img :src="selectedImage" alt="Product Image" class="h-[300px] w-[300px] object-cover rounded-lg">
                    <div class="flex gap-2 mt-4 justify-center">
                        <template x-for="img in images">
                            <img 
                                :src="img" 
                                class="w-20 h-20 object-cover rounded cursor-pointer border-2"
                                :class="{ 'border-blue-500': selectedImage === img }"
                                @click="selectedImage = img">
                        </template>
                    </div>
                </div>

                <!-- Right: Product Info -->
                <div class="flex flex-col">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">{{$product->name}}</h2>
                    <p class="text-red-500 line-through">
                        {{ ceil($product->price + ($product->price / 10)) }} DH
                    </p>
                    <p class="text-green-600 text-lg font-semibold mb-4">{{$product->price}} DH</p>

                    <!-- Description -->
                    <div class="">
                        <h3 class="font-semibold mb-2">Description</h3>
                        <div class="flex flex-wrap gap-2">
                            {{$product->description}}
                        </div>
                    </div>

                    <div class="border m-2"></div>

                    <!-- Color Selector -->
                    <div class="mb-4">
                        <h3 class="font-semibold mb-2">Selected Color: <span class="text-gray-700" x-text="selectedColor"></span></h3>
                        <div class="flex gap-2">
                            <template x-for="color in colors">
                                <div 
                                    class="w-6 h-6 rounded-full border-2 cursor-pointer"
                                    :style="`background-color: ${color.code}`"
                                    :class="{ 'border-black': selectedColor === color.name }"
                                    @click="selectedColor = color.name">
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Size Selector -->
                    <div class="mb-6">
                        <h3 class="font-semibold mb-2">Taille Options</h3>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="size in sizes" :key="size.id">
                                <button
                                    class="px-4 py-2 border rounded"
                                    :class="{
                                        'bg-gray-300 text-gray-500 cursor-not-allowed': !isSizeAvailable(size.name),
                                        'bg-blue-600 text-white': selectedSize === size.name && isSizeAvailable(size.name)
                                    }"
                                    :disabled="!isSizeAvailable(size.name)"
                                    @click="selectedSize = size.name"
                                >
                                    <span x-text="size.name"></span>
                                </button>
                            </template>
                            
                        </div>
                    </div>

                    <!-- Quantity Selector -->
                    <div class="mb-6">
                        <h3 class="font-semibold mb-2">Quantity</h3>
                        <input 
                            type="number" 
                            min="1" 
                            x-model.number="quantity" 
                            class="w-20 px-3 py-2 border rounded" 
                            :max="maxQuantity()" 
                            placeholder="1" />
                    </div>

                    <button class="w-full sm:w-64 bg-black text-white py-3 rounded hover:bg-gray-800">Add To Cart</button>

                </div>
            </div>
        </div>
    </div>

    <script>
        function productDetail() {
            return {
                selectedImage: '{{ asset('storage/' . ($product->productColorImages->first()?->image ?? 'default.png')) }}',
                images: @json($product->productColorImages->map(fn($img) => asset('storage/' . $img->image))),
                selectedColor: @json($colors->first()?->name ?? ''),
                colors: @json($colors),
                selectedSize: '',

                sizes: @json($sizes),

                variantsByColor: @json($variantsByColor),

                isSizeAvailable(sizeName) {
                    const variants = this.variantsByColor[this.selectedColor] || [];
                    return variants.some(v => v.size === sizeName && v.stock > 0);
                }
            };
        }
    </script>
</x-app-layout>
