<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // CartController.php
public function addProduct(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'color' => 'required|string',
        'size' => 'required|string',
    ]);

    // You might want to find the matching variant here
    $variant = ProductVariant::where('product_id', $request->product_id)
                ->whereHas('color', fn ($q) => $q->where('name', $request->color))
                ->whereHas('size', fn ($q) => $q->where('name', $request->size))
                ->first();

    if (!$variant || $variant->stock < 1) {
        return back()->with('error', 'Product variant not available.');
    }

    // Example: push to session cart
    $cart = session()->get('cart', []);
    $cart[] = [
        'variant_id' => $variant->id,
        'quantity' => 1,
    ];
    session()->put('cart', $cart);

    return back()->with('success', 'Added to cart!');
}

}
