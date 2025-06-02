<?php

namespace App\Http\Controllers;

use App\Models\order;
use App\Models\OrderProductItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = order::with(['user'])->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            // Save input data to session
            session()->put('checkout_form', $request->only(['name', 'address', 'phone']));
            // Redirect to login with intended set
            return redirect()->guest(route('login'));
        }
    
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:1000',
            'phone' => 'required|string|max:20',
        ]);
    
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->withErrors('Your cart is empty.');
        }
    
        // Calculate total price from DB
        $totalPrice = 0;
        foreach ($cart as $item) {
            $variant = ProductVariant::with('product')->find($item['variant_id']);
            if (!$variant->product()) {
                return back()->withErrors('One of the products in your cart is not available.');
            }
            $totalPrice += $variant->product->price * $item['quantity'];
        }
    
        $user = auth()->user();
    
        DB::transaction(function () use ($cart, $request, $user, $totalPrice) {
            $shippingInfo = json_encode([
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
            ]);
    
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'shipping_info' => $shippingInfo,
            ]);
    
            foreach ($cart as $item) {
                $variant = ProductVariant::find($item['variant_id']);
    
                OrderProductItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $variant->id,
                    'quantity' => $item['quantity'],
                    'price' => $variant->price, // <-- Add this price field here
                ]);
    
                // Optional: Decrease stock
                $variant->decrement('stock', $item['quantity']);
            }
        });
    
        session()->forget('cart');
        session()->forget('checkout_form');
    
        return redirect()->route('cart.index')->with('success', 'Order placed successfully.');
    }

    public function show(Order $order)
    {
        $order->load([
            'user',
            'orderProductItems.productVariant',
            'orderPackageItems.orderPackageVariantItems.productVariant'
        ]);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
{
    $request->validate([
        'status' => 'required|in:pending,completed,cancelled',
    ]);

    $newStatus = $request->status;
    $oldStatus = $order->status;

    if ($newStatus === $oldStatus) {
        return back()->with('info', "Order status is already {$newStatus}.");
    }

    // Load relationships (variants instead of products)
    $order->load([
        'orderProductItems.productVariant',
        'orderPackageItems.orderPackageVariantItems.productVariant'
    ]);

    DB::beginTransaction();

    try {
        // 1. Revert stock if cancelling a completed order
        if (($oldStatus === 'cancelled')) {
              // Check stock
              foreach ($order->orderProductItems as $item) {
                if ($item->productVariant->stock < $item->quantity) {
                    throw new \Exception("Not enough stock for variant of product ID {$item->productVariant->product_id}.");
                }
            }

            foreach ($order->orderPackageItems as $pkgItem) {
                foreach ($pkgItem->orderPackageVariantItems as $pkgVariantItem) {
                    if ($pkgVariantItem->productVariant->stock < $pkgVariantItem->quantity) {
                        throw new \Exception("Not enough stock for variant in package ID {$pkgItem->package_id}.");
                    }
                }
            }

            // Deduct
            foreach ($order->orderProductItems as $item) {
                $item->productVariant->decrement('stock', $item->quantity);
            }

            foreach ($order->orderPackageItems as $pkgItem) {
                foreach ($pkgItem->orderPackageVariantItems as $pkgVariantItem) {
                    $pkgVariantItem->productVariant->decrement('stock', $pkgVariantItem->quantity);
                }
            }
        }

        // 2. Deduct stock if changing from cancelled to completed
        if ($newStatus === 'cancelled') {
            foreach ($order->orderProductItems as $item) {
                $item->productVariant->increment('stock', $item->quantity);
            }

            foreach ($order->orderPackageItems as $pkgItem) {
                foreach ($pkgItem->orderPackageVariantItems as $pkgVariantItem) {
                    $pkgVariantItem->productVariant->increment('stock', $pkgVariantItem->quantity);
                }
            }
        }

        $order->update(['status' => $newStatus]);

        DB::commit();

        return back()->with('success', "Order #{$order->id} status updated to {$newStatus}.");
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors('Error updating order status: ' . $e->getMessage());
    }
}


}
