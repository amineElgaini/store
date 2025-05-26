<?php

namespace App\Http\Controllers;

use App\Models\order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = order::with(['user'])->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load([
            'user',
            'productItems.product',
            'packageItems.package.packageDetails.product'
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
            return back()->with('info', 'Order status is already ' . $newStatus);
        }

        $order->load('productItems.product', 'packageItems.package.packageDetails.product');

        DB::beginTransaction();
        // lock is optional cause quantity constraints

        try {
            if ($oldStatus === 'cancelled') {
                // Validate stock availability
                foreach ($order->productItems as $productItem) {
                    if ($productItem->product->stock < $productItem->quantity) {
                        throw new \Exception("Not enough stock for product {$productItem->product->name}.");
                    }
                }

                foreach ($order->packageItems as $packageItem) {
                    foreach ($packageItem->package->packageDetails as $detail) {
                        $required = $packageItem->quantity * $detail->quantity;
                        if ($detail->product->stock < $required) {
                            throw new \Exception("Not enough stock for package product {$detail->product->name}.");
                        }
                    }
                }

                // Deduct stock
                foreach ($order->productItems as $productItem) {
                    $productItem->product->decrement('stock', $productItem->quantity);
                }

                foreach ($order->packageItems as $packageItem) {
                    foreach ($packageItem->package->packageDetails as $detail) {
                        $detail->product->decrement('stock', $packageItem->quantity * $detail->quantity);
                    }
                }
            }

            if ($newStatus === 'cancelled') {
                foreach ($order->productItems as $productItem) {
                    $productItem->product->increment('stock', $productItem->quantity);
                }

                foreach ($order->packageItems as $packageItem) {
                    foreach ($packageItem->package->packageDetails as $detail) {
                        $detail->product->increment('stock', $packageItem->quantity * $detail->quantity);
                    }
                }
            }

            $order->update(['status' => $newStatus]);

            DB::commit();

            return back()->with('success', "Order #{$order->id} status updated to {$newStatus}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('An error occurred while updating the order: ' . $e->getMessage());
        }
    }

}
