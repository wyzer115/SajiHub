<?php

namespace App\Http\Controllers\Koki;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index()
    {
        $branch = auth()->user()->branch;

        $orders = Order::where('branch_id', $branch->id)
            ->whereIn('order_status', ['pending', 'cooking'])
            ->with(['items.menu', 'table'])
            ->orderBy('created_at', 'asc')
            ->get();
            
        return view('koki.kitchen', compact('orders', 'branch'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        if ($order->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        $validated = $request->validate([
            'order_status' => 'required|in:cooking,served,completed',
        ]);

        $order->update(['order_status' => $validated['order_status']]);

        if ($validated['order_status'] === 'completed' && $order->table) {
            $order->table->update(['status' => 'empty']);
        }

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function menuStatus()
    {
        $branchId = auth()->user()->branch_id;
        $menus = \App\Models\Menu::where('branch_id', $branchId)->with('category')->orderBy('category_id')->get();
        return view('koki.menu_status', compact('menus'));
    }

    public function toggleMenuStatus(Request $request, \App\Models\Menu $menu)
    {
        if ($menu->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        $newStatus = $menu->status === 'available' ? 'sold_out' : 'available';
        $menu->update(['status' => $newStatus]);

        $statusLabel = $newStatus === 'available' ? 'Tersedia kembali' : 'Habis (Sold Out)';
        return redirect()->back()->with('success', 'Status menu "' . $menu->name . '" berhasil diubah menjadi: ' . $statusLabel . '.');
    }

    public function history()
    {
        $branchId = auth()->user()->branch_id;
        $completedOrders = Order::where('branch_id', $branchId)
            ->whereIn('order_status', ['served', 'completed'])
            ->whereDate('updated_at', today())
            ->with(['items.menu', 'table'])
            ->latest('updated_at')
            ->paginate(15);

        return view('koki.history', compact('completedOrders'));
    }
}
