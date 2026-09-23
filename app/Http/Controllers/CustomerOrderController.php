<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\Transaction;
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

        // Support Table & Branch detection via URL parameters
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
                  ->orWhere('table_number', 'Meja ' . $tableParam)
                  ->orWhere('table_number', ltrim($tableParam, '0'))
                  ->orWhere('id', $tableParam);
            })->first();

            if ($selectedTable && !$selectedBranch) {
                $selectedBranch = $selectedTable->branch;
            }
        }

        if (!$selectedBranch) {
            $selectedBranch = $branches->first();
        }

        if ($selectedBranch) {
            $menus  = Menu::where('branch_id', $selectedBranch->id)
                ->with(['category', 'ingredients.inventory'])
                ->get();
            $tables = Table::where('branch_id', $selectedBranch->id)->get();
        }

        $receiptOrder = null;
        $receiptOrderId = session('receipt_order_id') ?? $request->get('receipt_id');
        if ($receiptOrderId) {
            $receiptOrder = Order::with(['items.menu', 'branch', 'table', 'transaction'])->find($receiptOrderId);
        }

        return view('customer.order', compact(
            'branches', 'selectedBranch', 'selectedTable', 'menus', 'tables', 'receiptOrder'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id'      => 'required|exists:branches,id',
            'table_id'       => 'required|exists:tables,id',
            'customer_name'  => 'required|string|max:255',
            'order_notes'    => 'nullable|string|max:500',
            'payment_method' => 'required|in:cash,qris',
            'items'          => 'required|array|min:1',
            'items.*.menu_id'  => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes'    => 'nullable|string|max:500',
        ]);

        $table = Table::findOrFail($validated['table_id']);
        $customerName = trim($validated['customer_name']);
        $userId = auth()->check() ? auth()->id() : null;

        $order = null;

        DB::transaction(function () use ($validated, $table, $customerName, $userId, &$order) {
            $order = Order::create([
                'branch_id'      => $validated['branch_id'],
                'user_id'        => $userId,
                'table_id'       => $table->id,
                'customer_name'  => $customerName,
                'order_status'   => 'pending',
                'payment_status' => 'unpaid', // Dine-in customer must confirm & pay at cashier
                'payment_method' => $validated['payment_method'],
                'total_price'    => 0,
            ]);

            $totalPrice = 0;
            $itemIdx = 0;
            foreach ($validated['items'] as $item) {
                $menu = Menu::with('ingredients.inventory')->find($item['menu_id']);
                $itemNotes = $item['notes'] ?? null;
                if ($itemIdx === 0 && !empty($validated['order_notes'])) {
                    $itemNotes = $itemNotes ? ($itemNotes . ' | Note: ' . $validated['order_notes']) : ('Note: ' . $validated['order_notes']);
                }
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id'  => $menu->id,
                    'quantity' => $item['quantity'],
                    'price'    => $menu->price,
                    'notes'    => $itemNotes,
                ]);
                $totalPrice += $menu->price * $item['quantity'];
                $itemIdx++;

                // Deduct inventory stock based on recipe (BOM)
                if ($menu && $menu->ingredients) {
                    foreach ($menu->ingredients as $ingredient) {
                        if ($ingredient->inventory) {
                            $usedQty = (float) $ingredient->quantity * (int) $item['quantity'];
                            $currentStock = (float) $ingredient->inventory->stock;
                            $ingredient->inventory->update([
                                'stock' => max(0, round($currentStock - $usedQty, 2))
                            ]);
                        }
                    }
                }
            }

            $order->update(['total_price' => $totalPrice]);

            Transaction::create([
                'order_id'       => $order->id,
                'branch_id'      => $validated['branch_id'],
                'amount'         => $totalPrice,
                'payment_method' => $validated['payment_method'],
                'status'         => 'pending',
                'merchant_id'    => $validated['payment_method'] === 'qris' ? 'ID1026528881513' : null,
                'paid_at'        => null,
            ]);

            // Update table status to occupied
            $table->update(['status' => 'occupied']);
        });

        return redirect()->route('pesan.receipt', $order->id)
            ->with('success', 'Pesanan Anda #' . $order->id . ' berhasil dibuat. Silakan tunjukkan QR Konfirmasi ke Kasir!');
    }

    public function showReceipt(Order $order)
    {
        $order->load(['items.menu', 'branch', 'table', 'transaction']);
        return view('customer.receipt', compact('order'));
    }

    public function checkStatus(Order $order)
    {
        return response()->json([
            'payment_status' => $order->payment_status,
            'order_status'   => $order->order_status,
        ]);
    }
}
