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
        $branches        = Branch::all();
        $selectedBranch  = null;
        $selectedTable   = null;
        $menus           = collect();
        $tables          = collect();

        // Support Table & Branch detection via URL parameters (e.g. ?branch_id=1&table=01 or ?branch_id=1&table=Table%201 or token)
        if ($request->filled('branch_id')) {
            $selectedBranch = Branch::find($request->branch_id);
        }

        if ($request->filled('table')) {
            $tableQuery = Table::query();
            if ($selectedBranch) {
                $tableQuery->where('branch_id', $selectedBranch->id);
            }
            
            $tableParam = $request->table;
            $selectedTable = $tableQuery->where(function($q) use ($tableParam) {
                $q->where('qr_code_token', $tableParam)
                  ->orWhere('table_number', $tableParam)
                  ->orWhere('table_number', 'Table ' . $tableParam)
                  ->orWhere('table_number', ltrim($tableParam, '0'))
                  ->orWhere('id', $tableParam);
            })->first();

            if ($selectedTable && !$selectedBranch) {
                $selectedBranch = $selectedTable->branch;
            }
        }

        if ($selectedBranch) {
            $menus  = Menu::where('branch_id', $selectedBranch->id)
                ->where('status', 'available')
                ->with('category')
                ->get();
            $tables = Table::where('branch_id', $selectedBranch->id)->get();
        }

        // Get past orders for this customer (if logged in)
        $myOrders = collect();
        if (auth()->check()) {
            $myOrders = Order::where('user_id', auth()->id())
                ->with(['branch', 'table', 'items.menu'])
                ->latest()
                ->paginate(5);
        }

        return view('customer.order', compact(
            'branches', 'selectedBranch', 'selectedTable', 'menus', 'tables', 'myOrders'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id'      => 'required|exists:branches,id',
            'table_id'       => 'required|exists:tables,id',
            'customer_name'  => 'nullable|string|max:255',
            'payment_method' => 'required|in:cash,qris,transfer',
            'items'          => 'required|array|min:1',
            'items.*.menu_id'  => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes'    => 'nullable|string|max:500',
        ]);

        $table = Table::findOrFail($validated['table_id']);
        $customerName = auth()->check() ? auth()->user()->name : ($validated['customer_name'] ?: 'Pelanggan Meja ' . $table->table_number);
        $userId = auth()->check() ? auth()->id() : null;

        $order = null;

        DB::transaction(function () use ($validated, $table, $customerName, $userId, &$order) {
            $isInstantPayment = in_array($validated['payment_method'], ['qris', 'transfer']);

            $order = Order::create([
                'branch_id'      => $validated['branch_id'],
                'user_id'        => $userId,
                'table_id'       => $table->id,
                'customer_name'  => $customerName,
                'order_status'   => 'pending',
                'payment_status' => $isInstantPayment ? 'paid' : 'unpaid',
                'payment_method' => $validated['payment_method'],
                'total_price'    => 0,
            ]);

            $totalPrice = 0;
            foreach ($validated['items'] as $item) {
                $menu = Menu::find($item['menu_id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id'  => $menu->id,
                    'quantity' => $item['quantity'],
                    'price'    => $menu->price,
                    'notes'    => $item['notes'] ?? null,
                ]);
                $totalPrice += $menu->price * $item['quantity'];
            }

            $order->update(['total_price' => $totalPrice]);

            // Update table status to occupied
            $table->update(['status' => 'occupied']);
        });

        return redirect()->route('pesan', ['branch_id' => $validated['branch_id'], 'table_id' => $validated['table_id']])
            ->with('success', 'Pesanan Anda #' . $order->id . ' berhasil dikirim ke Kasir & Dapur! Silakan tunggu pesanan disajikan.');
    }
}
