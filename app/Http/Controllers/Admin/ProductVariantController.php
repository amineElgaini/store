<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductColorImage;
use App\Models\ProductVariant;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductVariantController extends Controller
{
/*     public function edit(Product $product)
    {
        $sizes = Size::all();
        $colors = Color::all();
        $variants = $product->variants()->with(['size', 'color'])->get();

        return view('admin.variants.edit', compact('product', 'sizes', 'colors', 'variants'));
    } */

    public function edit(Product $product)
{
    $sizes = Size::all();
    $colors = Color::all();

    // Load variants with size and color
    $variants = $product->variants()->with(['size', 'color'])->get();

    // Load color images for this product, keyed by color_id for easy lookup in view
    $colorImages = ProductColorImage::where('product_id', $product->id)
                    ->get()
                    ->keyBy('color_id');

    return view('admin.variants.edit', compact('product', 'sizes', 'colors', 'variants', 'colorImages'));
}

   
    
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'size_id'   => 'required|exists:sizes,id',
            'color_id'  => 'required|exists:colors,id',
            'stock'     => 'required|integer|min:0',
            'image'     => 'nullable|image|max:2048',
        ]);
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('color_images', 'public');
    
            // Create a new record in product_color_images table (no update)
            ProductColorImage::create([
                'product_id' => $product->id,
                'color_id' => $data['color_id'],
                'image' => $imagePath,
            ]);
        }
    
        // Create the variant
        $product->variants()->create([
            'size_id' => $data['size_id'],
            'color_id' => $data['color_id'],
            'stock' => $data['stock'],
        ]);
    
        return back()->with('success', 'Variant added successfully.');
    }
    

    public function update(Request $request, ProductVariant $variant)
    {
        $data = $request->validate([
            'size_id'   => 'required|exists:sizes,id',
            'color_id'  => 'required|exists:colors,id',
            'stock'     => 'required|integer|min:0',
            'image'     => 'nullable|image|max:2048',
        ]);
    
        // Handle image upload for color
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('color_images', 'public');
    
            // Delete the old image file if it exists
            $colorImage = ProductColorImage::where('product_id', $variant->product_id)
                                           ->where('color_id', $data['color_id'])
                                           ->first();
    
            if ($colorImage && $colorImage->image) {
                Storage::disk('public')->delete($colorImage->image);
            }
    
            // Update or create the color image record
            ProductColorImage::updateOrCreate(
                [
                    'product_id' => $variant->product_id,
                    'color_id' => $data['color_id'],
                ],
                [
                    'image' => $imagePath,
                    'updated_at' => now(),
                    'created_at' => now(), // optional; Eloquent handles this if using timestamps
                ]
            );
        }
    
        // Update the variant
        $variant->update([
            'size_id' => $data['size_id'],
            'color_id' => $data['color_id'],
            'stock' => $data['stock'],
        ]);
    
        return back()->with('success', 'Variant updated successfully.');
    }
    

    public function destroy(ProductVariant $variant)
    {
        // No need to delete image here, since it belongs to ProductColorImage and may be shared
        $variant->delete();

        return back()->with('success', 'Variant deleted successfully.');
    }
}
