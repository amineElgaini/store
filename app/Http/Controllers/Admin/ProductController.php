<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'productVariants'])->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load(['productVariants.color', 'productVariants.size', 'productColorImages']);
        return view('admin.products.show', compact('product'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return back()->with('success', 'Product created successfully.');
    }

  public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return back()->with('success', 'Product updated successfully.');
    }



    public function destroy(Product $product)
    {
        $usedInPackages = $product->packageDetails()->exists();
        $orderProductItems = $product->productVariants()
        ->whereHas('orderProductItems')
        ->exists();

    
        if ($usedInPackages || $orderProductItems) {
            return redirect()->route('admin.products.index')->withErrors([
                "Cannot delete product already exists in: packages or orders"
            ]);
        }
    
        $imagePath = $product->image;
        $product->delete();
    
        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        return back()->with('success', 'Product deleted successfully.');
    }
}
