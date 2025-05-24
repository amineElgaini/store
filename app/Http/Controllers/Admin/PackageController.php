<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageDetail;
use App\Models\Product;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        // $packages = Package::with('packageDetails.product')->paginate(10);
        $packages = Package::paginate(10);
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        // You might want to let admin select multiple products to add to package details
        // So pass all products for selection
        $products = Product::all();
        return view('admin.packages.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id',
        ]);

        // Create package
        $package = Package::create([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        // Create package details for each product
        foreach ($request->product_ids as $productId) {
            $package->packageDetails()->create(['product_id' => $productId]);
        }

        return redirect()->route('admin.packages.index')->with('success', 'Package created with products.');
    }

    public function edit(Package $package)
    {
        $products = Product::all();
        // get product_ids assigned to this package for pre-selecting
        $selectedProductIds = $package->packageDetails->pluck('product_id')->toArray();

        return view('admin.packages.edit', compact('package', 'products', 'selectedProductIds'));
    }

    public function update(Request $request, Package $package)
    {
        // Prevent update if package is used in any order
        // if ($package->orderPackageItems()->exists()) {
        //     return redirect()->back()->withErrors(["Cannot update: This package is already used in an order."]);
        // }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id',
            'is_active' => 'nullable|boolean',
        ]);

        // Update package
        $package->update([
            'name' => $request->name,
            'price' => $request->price,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : false,
        ]);

        // Sync package details (products)
        // Remove existing details not in new list and add new ones
        $package->packageDetails()->whereNotIn('product_id', $request->product_ids)->delete();

        $existingProductIds = $package->packageDetails->pluck('product_id')->toArray();

        // Add new details for product_ids that don't exist yet
        foreach ($request->product_ids as $productId) {
            if (!in_array($productId, $existingProductIds)) {
                $package->packageDetails()->create(['product_id' => $productId]);
            }
        }

        return redirect()->route('admin.packages.index')->with('success', 'Package updated with products.');
    }

    public function show(Package $package)
    {
        $package->load('packageDetails.product');
        return view('admin.packages.show', compact('package'));
    }

    public function destroy(Package $package)
    {
        // if ($package->orderPackageItems()->exists()) {
        //     $message = "Cannot delete package [{$package->id}] ({$package->name}) because it is used in one or more orders.";
        //     return redirect()->route('admin.packages.index')->withErrors([$message]);
        // }
    
        $package->delete();
    
        return redirect()->route('admin.packages.index')->with('success', 'Package deleted.');
    }
}
