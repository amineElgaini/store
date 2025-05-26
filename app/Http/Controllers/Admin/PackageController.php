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
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:1',
        ]);

        $package = Package::create([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        // Create package details for each product
        foreach ($request->product_ids as $productId) {
            $quantity = $request->quantities[$productId] ?? 1;

            $package->packageDetails()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return redirect()->route('admin.packages.index')->with('success', 'Package created with products.');
    }

/*     public function edit(Package $package)
    {
        if ($package->orderPackageItems()->exists()) {
            return redirect()->back()->withErrors(["Cannot update: This package is already used in an order."]);
        }
        
        $products = Product::all();
        $selectedProductIds = $package->packageDetails->pluck('product_id')->toArray();

        $productQuantities = $package->packageDetails->pluck('quantity', 'product_id')->toArray();

        return view('admin.packages.edit', compact('package', 'products', 'selectedProductIds', 'productQuantities'));
    }

    public function update(Request $request, Package $package)
    {
        if ($package->orderPackageItems()->exists()) {
            return redirect()->back()->withErrors(["Cannot update: This package is already used in an order."]);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);


    
        $package->update([
            'name' => $request->name,
            'price' => $request->price,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : false,
        ]);
    
        // Delete removed products
        $package->packageDetails()->whereNotIn('product_id', $request->product_ids)->delete();
    
        $existingDetails = $package->packageDetails->keyBy('product_id');
    
        foreach ($request->product_ids as $productId) {
            $quantity = $request->quantities[$productId] ?? 1;
    
            if ($existingDetails->has($productId)) {
                // Update existing
                $existingDetails[$productId]->update(['quantity' => $quantity]);
            } else {
                // Create new
                $package->packageDetails()->create([
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
            }
        }
    
        return redirect()->route('admin.packages.index')->with('success', 'Package updated with products and quantities.');
    } */

    public function show(Package $package)
    {
        $package->load('packageDetails.product');
        return view('admin.packages.show', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);
    
        $package->update([
            'price' => $request->price,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);
    
        return redirect()->route('admin.packages.index')->with('success', 'Package updated.');
    }

    public function destroy(Package $package)
    {
        if ($package->orderPackageItems()->exists()) {
            return redirect()
                ->route('admin.packages.index')
                ->withErrors([ 'Cannot delete package because it is part of an existing order.']);
        }
        $package->delete();
    
        return redirect()->route('admin.packages.index')->with('success', 'Package deleted.');
    }
}
