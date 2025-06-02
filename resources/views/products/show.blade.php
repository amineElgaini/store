<x-app-layout>
    <x-flash-messages />
    <div class="py-12 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="productDetail()"  x-init="selectedImage = images[0]">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 items-start justify-center">
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
                    <h2 class="text-4xl font-bold text-gray-800 mb-2">{{$product->name}}</h2>
                    <div class="flex gap-2 items-end mb-4">
                        <p class="text-red-500 text-xl line-through">
                            {{ ceil($product->price + ($product->price / 10)) }} DH
                        </p>
                        <p class="text-green-600 text-3xl font-semibold">{{$product->price}} DH</p>
                    </div>

                    <!-- Color Selector -->
                    <div class="mb-4 p-4 rounded-md bg-white border">
                        <h3 class="font-semibold mb-2 text-xl">
                            Selected Color: <span class="text-gray-700" x-text="colors.find(c => c.id === selectedColor)?.name"></span>
                        </h3>
                        <div class="flex gap-2">
                            <template x-for="color in colors" :key="color.id">
                                <div 
                                    class="w-7 h-7 rounded-full border-2 cursor-pointer"
                                    :style="`background-color: ${color.code}`"
                                    :class="{ 'border-black': selectedColor === color.id, 'border-transparent': selectedColor !== color.id }"
                                    @click="selectedColor = color.id; selectVariant()">
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Size Selector -->
                    <div class="text-xl mb-4 p-4 rounded-md bg-white border">
                        <h3 class="font-semibold mb-2">Taille Options</h3>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="size in sizes" :key="size.id">
                                <button
                                    class="px-4 py-2 border rounded"
                                    :class="{
                                        'bg-gray-300 text-gray-500 cursor-not-allowed': !isSizeAvailable(size.id),
                                        'bg-blue-600 text-white': selectedSize === size.id && isSizeAvailable(size.id)
                                    }"
                                    :disabled="!isSizeAvailable(size.id)"
                                    @click="selectedSize = size.id; selectVariant()"
                                >
                                    <span x-text="size.name"></span>
                                </button>
                            </template>

                        </div>
                    </div>

                    <!-- Quantity Selector -->
                    <div class="mb-4 p-4 rounded-md bg-white border">
                        <h3 class="text-xl font-semibold mb-2">Max Quantity: <span x-text="maxQuantity()"></span> </h3>
                        <form method="POST" :action="`/cart/add-product/${selectedVariant}`">
                            @csrf
                            <div class="flex gap-2 items-center">
                                <input 
                                    type="number" 
                                    min="1"
                                    name="quantity"
                                    x-model.number="quantity" 
                                    class="w-20 px-3 py-2 border rounded h-12" 
                                    :max="maxQuantity()" 
                                    placeholder="1" 
                                />
                                <button 
                                    type="submit" 
                                    class="px-6 h-12 bg-black text-white rounded hover:bg-gray-800"
                                >
                                    Add To Cart
                                </button>
                            </div>
                            
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function productDetail() {
            return {
                cart: @json(session('cart', [])),

                variants: {!! json_encode(
                    $product->productVariants->map(function ($v) {
                        return [
                            'id' => $v->id,
                            'color_id' => $v->color_id,
                            'size_id' => $v->size_id,
                            'stock' => $v->stock,
                        ];
                    })
                ) !!},

                images: @json(
                    $product->productColorImages->isNotEmpty()
                        ? $product->productColorImages->map(fn($img) => asset('storage/' . $img->image))
                        : [asset('images/default-product-image.png')]
                ),
                selectedImage: null,
                
                colors: @json($colors),
                selectedColor: @json($colors->first()->id ?? ''),
    
                sizes: @json($sizes),
                selectedSize: null,
                isSizeAvailable(sizeId) {
                    return this.variants.some(v => v.color_id === this.selectedColor && v.size_id === sizeId);
                },

                selectedVariant: null,
                selectVariant() {
                    const selectedVariant = this.variants.find(v => v.color_id === this.selectedColor && v.size_id === this.selectedSize);
                    this.selectedVariant = selectedVariant ? selectedVariant.id : null;
                },
    
                quantity: 1,
                maxQuantity: 1,
                // maxQuantity() {
                //     const variant = this.variants.find(v =>
                //         v.color_id === this.selectedColor && v.size_id === this.selectedSize
                //     );
                //     return variant ? variant.stock : 0;
                // }
                maxQuantity() {
                    if (!this.selectedVariant) return 0;

                    // Find variant stock
                    const variant = this.variants.find(v => v.id === this.selectedVariant);
                    if (!variant) return 0;

                    // Find quantity in cart for this variant
                    const cartItem = this.cart.find(item => item.variant_id === this.selectedVariant);
                    const cartQuantity = cartItem ? cartItem.quantity : 0;

                    return Math.max(variant.stock - cartQuantity, 0);
                }
            };
        }
    </script>
    
</x-app-layout>
