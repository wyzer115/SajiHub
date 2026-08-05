<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use App\Models\Table;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::where('branch_id', auth()->user()->branch_id);

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->get('status') == 'active') {
            $query->where('order_status', '!=', 'completed');
        } elseif ($request->filled('order_status')) {
            $query->where('order_status', $request->order_status);
        }

        if (!$request->has('date') || $request->date == 'today') {
            $query->whereDate('created_at', today());
        }

        $orders = $query->with(['table', 'user'])->latest()->paginate(15);
        return view('kasir.orders.index', compact('orders'));
    }

    public function create()
    {
        $branchId = auth()->user()->branch_id;
        $menus = Menu::where('branch_id', $branchId)->where('status', 'available')->with('category')->get();
        $tables = Table::where('branch_id', $branchId)->get();
        return view('kasir.orders.create', compact('menus', 'tables'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'table_id' => 'nullable|exists:tables,id',
            'items' => 'required|array',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, &$order) {
            $order = Order::create([
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
                'table_id' => $validated['table_id'],
                'customer_name' => $validated['customer_name'],
                'order_status' => 'pending',
                'payment_status' => 'paid',
                'total_price' => 0,
            ]);

            $totalPrice = 0;
            foreach ($validated['items'] as $item) {
                $menu = Menu::find($item['menu_id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'quantity' => $item['quantity'],
                    'price' => $menu->price,
                    'notes' => $item['notes'] ?? null,
                ]);
                $totalPrice += $menu->price * $item['quantity'];
            }

            $order->update(['total_price' => $totalPrice]);

            if (!empty($validated['table_id'])) {
                Table::where('id', $validated['table_id'])->update(['status' => 'occupied']);
            }
        });

        return redirect()->route('kasir.orders.index')->with('success', 'Pesanan berhasil dibuat.');
    }

    public function show(Order $order)
    {
        if ($order->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }
        $order->load(['items.menu', 'table']);
        return view('kasir.orders.show', compact('order'));
    }

    public function pay(Order $order)
    {
        if ($order->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        $order->update([
            'payment_status' => 'paid',
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        if ($order->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        $validated = $request->validate([
            'order_status' => 'required|in:pending,cooking,served,completed',
        ]);

        $order->update(['order_status' => $validated['order_status']]);

        if ($validated['order_status'] === 'completed' && $order->table_id) {
            Table::where('id', $order->table_id)->update(['status' => 'empty']);
        }

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
