<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // public function index()
    // {
    //     $products = Product::with([
    //         'category',
    //         'productVariants.size',
    //         'productVariants.color',
    //         'productColorImages'
    //     ])
    //     ->where('is_active', true)
    //     ->get();

    //     return view('products.index', compact('products'));
    // }

public function index(Request $request)
{
    $query = Product::query();

    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }

    $products = $query->get();
    $categories = Category::all();

    return view('products.index', compact('products', 'categories'));
}


    public function show(Product $product)
    {
        $product->load(['productVariants.size', 'productVariants.color', 'productColorImages.color']);
    
        $colors = $product->productColorImages->pluck('color')->values();
    
        // Map variants grouped by color name, listing sizes & stock
        $variantsByColor = $product->productVariants->groupBy(fn($variant) => $variant->color->name)
            ->map(fn($variants) => $variants->map(fn($v) => [
                'size' => $v->size->name,
                'stock' => $v->stock,
            ]));
            
        $sizes = Size::all();

        return view('products.show', compact('sizes', 'product', 'colors', 'variantsByColor'));
    }
}
