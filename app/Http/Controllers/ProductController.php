<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->filled('min_price'), fn($q) => $q->where('price', '>=', $request->min_price))
            ->when($request->filled('max_price'), fn($q) => $q->where('price', '<=', $request->max_price))
            ->when($request->filled('categories'), fn($q) => $q->whereIn('category_id', $request->categories))
            ->paginate(12);
        $categories = Cache::remember('all_categories', now()->addHour(), fn() => Category::all());
    
        return view('products.index', [
            'products' => $products,
            'allCategories' => $categories,
            'selectedCategories' => $request->categories ?? [],
            'min' => $request->min_price,
            'max' => $request->max_price,
        ]);
    }
    
        
    public function show(Product $product)
    {
        $product->load(['productVariants.size', 'productVariants.color', 'productColorImages.color']);
    
        $colors = $product->productColorImages->pluck('color')->values();
        $sizes = Size::all();
        return view('products.show', compact('sizes', 'product', 'colors'));
    }
}
