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
        // $order->load(['user', 'productItems.product', 'packageItems.package']);
        
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
    
        // No change
        if ($newStatus === $oldStatus) {
            return back()->with('info', 'Order status is already ' . $newStatus);
        }
    
        DB::beginTransaction();
    
        try {
            // If changing from cancelled to pending/completed
            if ($oldStatus === 'cancelled' && in_array($newStatus, ['pending', 'completed'])) {
                foreach ($order->productItems as $item) {
                    if ($item->product->stock < $item->quantity) {
                        DB::rollBack();
                        return back()->withErrors("Not enough stock for product {$item->product->name}.");
                    }
                }
    
                foreach ($order->packageItems as $item) {
                    foreach ($item->package->packageDetails as $detail) {
                        if ($detail->product->stock < $item->quantity) {
                            DB::rollBack();
                            return back()->withErrors("Not enough stock for package product {$detail->product->name}.");
                        }
                    }
                }
    
                // Deduct stock
                foreach ($order->productItems as $item) {
                    $item->product->decrement('stock', $item->quantity);
                }
    
                foreach ($order->packageItems as $item) {
                    foreach ($item->package->packageDetails as $detail) {
                        $detail->product->decrement('stock', $item->quantity);
                    }
                }
            }
    
            // If changing to cancelled
            if ($newStatus === 'cancelled' && in_array($oldStatus, ['pending', 'completed'])) {
                foreach ($order->productItems as $item) {
                    $item->product->increment('stock', $item->quantity);
                }
    
                foreach ($order->packageItems as $item) {
                    foreach ($item->package->packageDetails as $detail) {
                        $detail->product->increment('stock', $item->quantity);
                    }
                }
            }
    
            // Update order status
            $order->update(['status' => $newStatus]);
    
            DB::commit();
    
            return back()->with('success', "Order #{$order->id} status updated to {$newStatus}.");
    
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('An error occurred while updating the order: ' . $e->getMessage());
        }
    }    

}
