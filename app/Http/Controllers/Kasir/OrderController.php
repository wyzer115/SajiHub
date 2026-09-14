<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use App\Models\Table;
use App\Models\OrderItem;
use App\Models\Transaction;
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

        $orders = $query->with(['table', 'user', 'items.menu', 'transaction'])->latest()->paginate(15);
        return view('kasir.orders.index', compact('orders'));
    }

    public function create()
    {
        $branchId = auth()->user()->branch_id;
        $menus = Menu::where('branch_id', $branchId)->with(['category', 'ingredients.inventory'])->get();
        $tables = Table::where('branch_id', $branchId)->get();

        // Auto-heal table status: set to 'empty' if no active pending/cooking/served order exists
        foreach ($tables as $table) {
            if ($table->status === 'occupied') {
                $hasActiveOrder = Order::where('table_id', $table->id)
                    ->whereIn('order_status', ['pending', 'cooking', 'served'])
                    ->exists();
                if (!$hasActiveOrder) {
                    $table->update(['status' => 'empty']);
                }
            }
        }

        return view('kasir.orders.create', compact('menus', 'tables'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'order_type' => 'nullable|in:dine_in,takeaway',
            'table_id' => 'nullable|exists:tables,id',
            'payment_method' => 'nullable|in:cash,qris',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string',
        ]);

        $table = null;
        if (!empty($validated['table_id'])) {
            $table = Table::find($validated['table_id']);
            if ($table && $table->status === 'occupied') {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Meja ' . $table->table_number . ' sedang terisi (occupied) dan tidak dapat dipilih.'
                    ], 422);
                }
                return back()->withErrors(['table_id' => 'Meja ' . $table->table_number . ' sedang terisi (occupied) dan tidak dapat dipilih.'])->withInput();
            }
        }

        $order = null;

        DB::transaction(function () use ($validated, $table, &$order) {
            // ALWAYS CREATE PENDING DRAFT ORDER WITH UNPAID STATUS
            $order = Order::create([
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
                'table_id' => $table ? $table->id : null,
                'customer_name' => $validated['customer_name'],
                'order_status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method' => $validated['payment_method'] ?? 'cash',
                'total_price' => 0,
            ]);

            $totalPrice = 0;
            foreach ($validated['items'] as $item) {
                $menu = Menu::with('ingredients.inventory')->find($item['menu_id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'quantity' => $item['quantity'],
                    'price' => $menu->price,
                    'notes' => $item['notes'] ?? null,
                ]);
                $totalPrice += $menu->price * $item['quantity'];

                // Deduct inventory stock based on recipe (BOM)
                if ($menu && $menu->ingredients) {
                    foreach ($menu->ingredients as $ingredient) {
                        if ($ingredient->inventory) {
                            $usedQty = $ingredient->quantity * $item['quantity'];
                            $ingredient->inventory->decrement('stock', $usedQty);
                        }
                    }
                }
            }

            $order->update(['total_price' => $totalPrice]);
            if ($table) {
                $table->update(['status' => 'occupied']);
            }

            // Create initial pending transaction
            Transaction::create([
                'order_id' => $order->id,
                'branch_id' => auth()->user()->branch_id,
                'amount' => $totalPrice,
                'payment_method' => $validated['payment_method'] ?? 'cash',
                'status' => 'pending',
                'merchant_id' => ($validated['payment_method'] ?? '') === 'qris' ? 'ID1026528881513' : null,
            ]);
        });

        $order->load(['items.menu', 'table']);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil ditahan di status Pending. Silakan lakukan pembayaran pada modal.',
                'order'   => [
                    'id' => $order->id,
                    'customer_name' => $order->customer_name,
                    'table_number' => $order->table ? ($order->table->table_number) : '🛍️ Takeaway',
                    'total_price' => floatval($order->total_price),
                    'payment_method' => $order->payment_method,
                    'items' => $order->items->map(function ($item) {
                        return [
                            'name' => $item->menu->name ?? 'Menu',
                            'qty'  => $item->quantity,
                            'price' => floatval($item->price),
                        ];
                    }),
                ]
            ]);
        }

        return redirect()->route('kasir.orders.show', $order)
            ->with('success', 'Pesanan #' . $order->id . ' ditahan di status Pending. Silakan pilih metode pembayaran.');
    }

    public function show(Order $order)
    {
        if ($order->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }
        $order->load(['items.menu', 'table', 'user', 'branch', 'transaction']);
        return view('kasir.orders.show', compact('order'));
    }

    public function receipt(Order $order)
    {
        if ($order->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }
        $order->load(['items.menu', 'table', 'user', 'branch', 'transaction']);
        return view('kasir.orders.receipt', compact('order'));
    }

    public function pay(Order $order)
    {
        if ($order->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        DB::transaction(function () use ($order) {
            $isTakeaway = ($order->table_id === null);
            $newOrderStatus = $isTakeaway 
                ? 'completed' 
                : (in_array($order->order_status, ['cooking', 'served', 'completed']) ? $order->order_status : 'pending');

            $order->update([
                'payment_status' => 'paid',
                'order_status'   => $newOrderStatus,
            ]);
            if ($order->table && $newOrderStatus === 'completed') {
                $order->table->update(['status' => 'empty']);
            }

            Transaction::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'branch_id' => $order->branch_id,
                    'amount' => $order->total_price,
                    'payment_method' => $order->payment_method ?? 'cash',
                    'status' => 'completed',
                    'merchant_id' => $order->payment_method === 'qris' ? 'ID1026528881513' : null,
                    'paid_at' => now(),
                ]
            );
        });

        return redirect()->back()->with('success', 'Pembayaran pesanan berhasil dilunasi.');
    }

    public function processPayment(Request $request, Order $order)
    {
        if ($order->branch_id !== auth()->user()->branch_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:cash,qris',
            'status'         => 'required|in:pending,completed,cancelled',
            'cash_paid'      => 'nullable|numeric|min:0',
            'merchant_id'    => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($validated, $order, &$transaction) {
                $amount = floatval($order->total_price);
                $isTakeaway = ($order->table_id === null);
                $newOrderStatus = $isTakeaway 
                    ? 'completed' 
                    : (in_array($order->order_status, ['cooking', 'served', 'completed']) ? $order->order_status : 'pending');

                if ($validated['payment_method'] === 'cash') {
                    $cashPaid = floatval($validated['cash_paid'] ?? 0);
                    if ($cashPaid < $amount) {
                        throw new \Exception('Nominal pembayaran kurang dari total tagihan!');
                    }
                    $cashChange = $cashPaid - $amount;

                    $transaction = Transaction::updateOrCreate(
                        ['order_id' => $order->id],
                        [
                            'branch_id'      => $order->branch_id,
                            'amount'         => $amount,
                            'payment_method' => 'cash',
                            'status'         => 'completed',
                            'cash_paid'      => $cashPaid,
                            'cash_change'    => $cashChange,
                            'paid_at'        => now(),
                        ]
                    );

                    $order->update([
                        'payment_status' => 'paid',
                        'order_status'   => $newOrderStatus,
                        'payment_method' => 'cash',
                    ]);

                    if ($order->table && $newOrderStatus === 'completed') {
                        $order->table->update(['status' => 'empty']);
                    }
                } else {
                    // QRIS Payment Simulation
                    $status = $validated['status'];
                    $merchantId = $validated['merchant_id'] ?? 'ID1026528881513';

                    if ($status === 'completed') {
                        $transaction = Transaction::updateOrCreate(
                            ['order_id' => $order->id],
                            [
                                'branch_id'      => $order->branch_id,
                                'amount'         => $amount,
                                'payment_method' => 'qris',
                                'status'         => 'completed',
                                'merchant_id'    => $merchantId,
                                'paid_at'        => now(),
                            ]
                        );

                        $order->update([
                            'payment_status' => 'paid',
                            'order_status'   => $newOrderStatus,
                            'payment_method' => 'qris',
                        ]);

                        if ($order->table && $newOrderStatus === 'completed') {
                            $order->table->update(['status' => 'empty']);
                        }
                    } elseif ($status === 'pending') {
                        $transaction = Transaction::updateOrCreate(
                            ['order_id' => $order->id],
                            [
                                'branch_id'      => $order->branch_id,
                                'amount'         => $amount,
                                'payment_method' => 'qris',
                                'status'         => 'pending',
                                'merchant_id'    => $merchantId,
                            ]
                        );
                    } else {
                        // Cancelled - Status order tetap BELUM DIBAYAR & PENDING
                        $transaction = Transaction::updateOrCreate(
                            ['order_id' => $order->id],
                            [
                                'branch_id'      => $order->branch_id,
                                'amount'         => $amount,
                                'payment_method' => 'qris',
                                'status'         => 'cancelled',
                                'merchant_id'    => $merchantId,
                            ]
                        );
                    }
                }
            });

            return response()->json([
                'success'        => true,
                'message'        => 'Pembayaran berhasil diproses.',
                'receipt_url'    => route('kasir.orders.receipt', $order->id),
                'order_id'       => $order->id,
                'payment_status' => $order->fresh()->payment_status,
                'order_status'   => $order->fresh()->order_status,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        if ($order->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        $validated = $request->validate([
            'order_status' => 'required|in:pending,cooking,served,completed,cancelled'
        ]);

        $order->update(['order_status' => $validated['order_status']]);

        if (in_array($validated['order_status'], ['completed', 'cancelled']) && $order->table) {
            $order->table->update(['status' => 'empty']);
        }

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function transactions(Request $request)
    {
        $branchId = auth()->user()->branch_id;
        $query = Order::where('branch_id', $branchId);

        $preset = $request->get('preset');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if ($preset) {
            switch ($preset) {
                case 'today':
                    $startDate = now()->toDateString();
                    $endDate = now()->toDateString();
                    break;
                case 'weekly':
                    $startDate = now()->startOfWeek()->toDateString();
                    $endDate = now()->toDateString();
                    break;
                case 'monthly':
                    $startDate = now()->startOfMonth()->toDateString();
                    $endDate = now()->toDateString();
                    break;
                case 'yearly':
                    $startDate = now()->startOfYear()->toDateString();
                    $endDate = now()->toDateString();
                    break;
            }
        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                \Carbon\Carbon::parse($startDate)->startOfDay(),
                \Carbon\Carbon::parse($endDate)->endOfDay()
            ]);
        } elseif ($startDate) {
            $query->where('created_at', '>=', \Carbon\Carbon::parse($startDate)->startOfDay());
        } elseif ($endDate) {
            $query->where('created_at', '<=', \Carbon\Carbon::parse($endDate)->endOfDay());
        }

        $totalPaidRevenue = (clone $query)->where('payment_status', 'paid')->sum('total_price');
        $orders = $query->with(['table', 'user', 'items.menu', 'transaction'])->latest()->paginate(15)->withQueryString();

        return view('kasir.transactions', compact('orders', 'totalPaidRevenue', 'preset', 'startDate', 'endDate'));
    }
}
