<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    
    public function index(Request $request)
    {
        $max = $request->input('max_price');
        $selectedCategories = $request->input('categories', []);
    
        $products = Product::query()
            ->when(!empty($max), fn ($query) => $query->where('price', '<=', $max))
            ->when(!empty($selectedCategories), fn ($query) =>
                $query->whereIn('category_id', $selectedCategories)
            )
            ->paginate(12);
    
        $allCategories = Category::all();
    
        return view('products.index', [
            'products' => $products,
            'max' => $max,
            'allCategories' => $allCategories,
            'selectedCategories' => $selectedCategories,
        ]);
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
