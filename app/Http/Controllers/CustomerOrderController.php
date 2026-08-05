<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerOrderController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::all();
        $selectedBranch = null;
        $menus = collect();
        $tables = collect();
        $selectedTable = null;

        if ($request->filled('branch_id')) {
            $selectedBranch = Branch::find($request->branch_id);
            if ($selectedBranch) {
                $menus = Menu::where('branch_id', $selectedBranch->id)
                    ->where('status', 'available')
                    ->with('category')
                    ->get();
                $tables = Table::where('branch_id', $selectedBranch->id)->get();

                if ($request->filled('table')) {
                    $tableNum = $request->table;
                    $selectedTable = Table::where('branch_id', $selectedBranch->id)
                        ->where(function($query) use ($tableNum) {
                            $query->where('table_number', $tableNum)
                                  ->orWhere('table_number', 'Table ' . $tableNum)
                                  ->orWhere('table_number', 'Meja ' . $tableNum)
                                  ->orWhere('table_number', ltrim($tableNum, '0'));
                        })->first();
                }
            }
        }

        // Get past orders for this customer
        $myOrders = Order::where('user_id', auth()->id())
            ->with(['branch', 'table', 'items.menu'])
            ->latest()
            ->paginate(5);

        return view('customer.order', compact('branches', 'selectedBranch', 'menus', 'tables', 'myOrders', 'selectedTable'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'table_id' => 'required|exists:tables,id',
            'payment_method' => 'required|in:cash,qris,transfer',
            'items' => 'required|array',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string',
        ]);

        $order = null;

        DB::transaction(function () use ($validated, &$order) {
            $isInstantPayment = in_array($validated['payment_method'], ['qris', 'transfer']);
            
            $order = Order::create([
                'branch_id' => $validated['branch_id'],
                'user_id' => auth()->id(),
                'table_id' => $validated['table_id'],
                'customer_name' => auth()->user()->name,
                'order_status' => 'pending',
                'payment_status' => $isInstantPayment ? 'paid' : 'unpaid',
                'payment_method' => $validated['payment_method'],
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

            // Update table status to occupied
            Table::where('id', $validated['table_id'])->update(['status' => 'occupied']);
        });

        return redirect()->route('pesan')->with('success', 'Pesanan Anda #' . $order->id . ' berhasil dikirim ke kasir & dapur! Mohon lakukan pembayaran di kasir.');
    }
}
