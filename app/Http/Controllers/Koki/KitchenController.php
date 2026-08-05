<?php

namespace App\Http\Controllers\Koki;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index()
    {
        $orders = Order::where('branch_id', auth()->user()->branch_id)
            ->whereIn('order_status', ['pending', 'cooking'])
            ->where('payment_status', 'paid')
            ->with(['items.menu', 'table'])
            ->orderBy('created_at', 'asc')
            ->get();
            
        return view('koki.kitchen', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        if ($order->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        $validated = $request->validate([
            'order_status' => 'required|in:cooking,served',
        ]);

        $order->update(['order_status' => $validated['order_status']]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
