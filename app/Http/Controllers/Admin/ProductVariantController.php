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
use Illuminate\Validation\Rule;

class ProductVariantController extends Controller
{
    public function edit(Product $product)
    {
        $sizes = Size::all();
        $colors = Color::all();

        $product->load(['productVariants.color', 'productVariants.size', 'productColorImages']);
        return view('admin.variants.edit', compact('product', 'sizes', 'colors'));
    }
    
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'size_id' => [
                'required',
                'exists:sizes,id',
            ],
            'color_id' => [
                'required',
                'exists:colors,id',
                Rule::unique('product_variants')->where(function ($query) use ($product, $request) {
                    return $query->where('product_id', $product->id)
                                 ->where('size_id', $request->size_id)
                                 ->where('color_id', $request->color_id);
                }),
            ],
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ], [
            'color_id.unique' => 'This variant (color + size) already exists for this product.',
        ]);
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('color_images', 'public');
    
            ProductColorImage::create([
                'product_id' => $product->id,
                'color_id' => $data['color_id'],
                'image' => $imagePath,
            ]);
        }
    
        $product->productVariants()->create([
            'size_id' => $data['size_id'],
            'color_id' => $data['color_id'],
            'stock' => $data['stock'],
        ]);
    
        return back()->with('success', 'Variant added successfully.');
    }

    public function update(Request $request, ProductVariant $variant)
    {
        $data = $request->validate([
            'size_id' => [
                'required',
                'exists:sizes,id',
            ],
            'color_id' => [
                'required',
                'exists:colors,id',
                Rule::unique('product_variants')->where(function ($query) use ($request, $variant) {
                    return $query->where('product_id', $variant->product_id)
                                 ->where('size_id', $request->size_id)
                                 ->where('color_id', $request->color_id)
                                 ->where('id', '!=', $variant->id);
                }),
            ],
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ], [
            'color_id.unique' => 'This variant (color + size) already exists for this product.',
        ]);
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('color_images', 'public');
    
            $colorImage = ProductColorImage::firstWhere([
                ['product_id', $variant->product_id],
                ['color_id', $data['color_id']],
            ]);
    
            if ($colorImage && $colorImage->image) {
                Storage::disk('public')->delete($colorImage->image);
            }
    
            ProductColorImage::updateOrCreate(
                [
                    'product_id' => $variant->product_id,
                    'color_id' => $data['color_id'],
                ],
                [
                    'image' => $imagePath,
                ]
            );
        }
    
        $variant->update([
            'size_id' => $data['size_id'],
            'color_id' => $data['color_id'],
            'stock' => $data['stock'],
        ]);
    
        return back()->with('success', 'Variant updated successfully.');
    }

    public function destroy(ProductVariant $variant)
{
    $usedInOrderProducts = $variant->orderProductItems()->exists();
    $usedInOrderPackageVariants = $variant->orderPackageVariantItems()->exists();

    if ($usedInOrderProducts || $usedInOrderPackageVariants) {
        return back()->with('error', 'Cannot delete variant because it is used in existing orders.');
    }

    $variant->delete();

    return back()->with('success', 'Variant deleted successfully.');
}

}
