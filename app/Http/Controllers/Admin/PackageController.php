<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Product;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
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

        foreach ($request->product_ids as $productId) {
            $quantity = $request->quantities[$productId] ?? 1;

            $package->packageDetails()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return redirect()->route('admin.packages.index')->with('success', 'Package created with products.');
    }

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
