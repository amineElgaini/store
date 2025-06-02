<?php

namespace App\Http\Controllers;

use App\Models\ProductColorImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);
    
        $cartItems = collect($cart)->map(function ($item) {
            $variant = ProductVariant::with(['product', 'color', 'size'])
                ->find($item['variant_id']);
    
            if (!$variant) return null;
    
            // Get the image using product_id and color_id from variant
            $image = optional(
                ProductColorImage::where('product_id', $variant->product_id)
                    ->where('color_id', $variant->color_id)
                    ->first()
            )->image;
    
            return [
                'id' => $variant->id,
                'title' => $variant->product->name,
                'price' => $variant->product->price,
                'image' => $image
                    ? asset('storage/' . $image)
                    : asset('images/default-product-image.png'),
                'color' => $variant->color?->name ?? 'N/A',
                'size' => $variant->size?->name ?? 'N/A',
                'quantity' => $item['quantity'],
            ];
        })->filter()->values();
    
        $subtotal = $cartItems->sum(fn($item) => $item['price'] * $item['quantity']);
        $cartCount = $cartItems->sum(fn($item) => $item['quantity']);
    
        session(['cart_count' => $cartCount]);
    
        return view('cart.index', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
        ]);
    }
    
    

    public function addProduct(Request $request, ProductVariant $variant)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
    
        if ($variant->stock < $request->quantity) {
            return back()->withErrors( 'Requested quantity not available.');
        }
    
        $cart = session()->get('cart', []);
        $foundKey = null;
    
        foreach ($cart as $key => $item) {
            if ($item['variant_id'] == $variant->id) {
                $foundKey = $key;
                break;
            }
        }
    
        if ($foundKey !== null) {
            $newQuantity = $cart[$foundKey]['quantity'] + $request->quantity;
    
            if ($newQuantity > $variant->stock) {
                return back()->withErrors( 'Total quantity exceeds available stock.');
            }
    
            $cart[$foundKey]['quantity'] = $newQuantity;
        } else {
            $cart[] = [
                'variant_id' => $variant->id,
                'quantity' => $request->quantity,
            ];
        }
    
        session()->put('cart', $cart);
        $cartCount = collect($cart)->sum('quantity');
        session(['cart_count' => $cartCount]);

        return back()->with('success', 'Product added to cart!');
    }

    public function removeProduct($variantId)
    {
        $cart = session()->get('cart', []);
        $cart = array_filter($cart, fn($item) => $item['variant_id'] != $variantId);
        session()->put('cart', $cart);
        $cartCount = collect($cart)->sum('quantity');
        session(['cart_count' => $cartCount]);
        return redirect()->route('cart.index')->with('success', 'Item removed.');
    }

}
