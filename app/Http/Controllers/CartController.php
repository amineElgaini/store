<?php

namespace App\Http\Controllers;

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
        
            // Now we fetch the color image based on product_id and color_id
            $image = $variant->colorImage()->image;
        
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
        
    
        $subtotal = $cartItems->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
        
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
    
        return back()->with('success', 'Product added to cart!');
    }

    public function removeProduct($variantId)
    {
        $cart = session()->get('cart', []);
        $cart = array_filter($cart, fn($item) => $item['variant_id'] != $variantId);
        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Item removed.');
    }

}
