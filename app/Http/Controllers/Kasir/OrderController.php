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

    public function scan()
    {
        return view('kasir.orders.scan');
    }

    public function lookupOrder(Request $request)
    {
        $code = trim($request->input('code', ''));
        if (empty($code)) {
            return response()->json(['success' => false, 'message' => 'Kode pesanan tidak boleh kosong.'], 422);
        }

        // Extract numbers from SAJI-ORD-123, ORD-123, #123, or 123
        preg_match('/\d+/', $code, $matches);
        $orderId = !empty($matches[0]) ? intval($matches[0]) : null;

        if (!$orderId) {
            return response()->json(['success' => false, 'message' => 'Format kode pesanan tidak valid.'], 404);
        }

        $order = Order::with(['items.menu', 'table', 'transaction'])
            ->where('branch_id', auth()->user()->branch_id)
            ->find($orderId);

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan #' . $orderId . ' tidak ditemukan di cabang ini.'], 404);
        }

        return response()->json([
            'success' => true,
            'order'   => [
                'id'             => $order->id,
                'customer_name'  => $order->customer_name,
                'table_number'   => $order->table ? $order->table->table_number : '🛍️ Bawa Pulang',
                'table_id'       => $order->table_id,
                'order_status'   => $order->order_status,
                'payment_status' => $order->payment_status,
                'payment_method' => $order->payment_method ?? 'cash',
                'total_price'    => (float) $order->total_price,
                'created_at'     => $order->created_at->format('d M Y, H:i'),
                'items'          => $order->items->map(fn($item) => [
                    'name'     => $item->menu->name ?? 'Menu',
                    'quantity' => $item->quantity,
                    'price'    => (float) $item->price,
                    'notes'    => $item->notes,
                ]),
                'transaction'    => $order->transaction ? [
                    'payment_proof' => $order->transaction->payment_proof ? asset($order->transaction->payment_proof) : null,
                ] : null,
            ]
        ]);
    }

    public function confirmPayment(Request $request, Order $order)
    {
        if ($order->branch_id !== auth()->user()->branch_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:cash,qris',
            'cash_paid'      => 'nullable|numeric|min:0',
            'payment_proof'  => 'nullable|image|max:10240', // max 10MB
        ]);

        $amount = (float) $order->total_price;
        $proofPath = null;

        if ($validated['payment_method'] === 'cash') {
            $cashPaid = floatval($validated['cash_paid'] ?? 0);
            if ($cashPaid < $amount) {
                return response()->json(['success' => false, 'message' => 'Nominal uang tunai kurang dari total tagihan!'], 422);
            }
            $cashChange = $cashPaid - $amount;
        } else {
            $cashPaid = $amount;
            $cashChange = 0;

            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filename = 'proof_' . $order->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('uploads/payment_proofs');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $filename);
                $proofPath = 'uploads/payment_proofs/' . $filename;
            }
        }

        DB::transaction(function () use ($order, $validated, $amount, $cashPaid, $cashChange, $proofPath) {
            $isTakeaway = ($order->table_id === null);
            $newOrderStatus = $isTakeaway ? 'completed' : 'cooking';

            $order->update([
                'payment_status' => 'paid',
                'order_status'   => $newOrderStatus,
                'payment_method' => $validated['payment_method'],
            ]);

            $txData = [
                'branch_id'      => $order->branch_id,
                'amount'         => $amount,
                'payment_method' => $validated['payment_method'],
                'status'         => 'completed',
                'merchant_id'    => $validated['payment_method'] === 'qris' ? 'ID1026528881513' : null,
                'cash_paid'      => $cashPaid,
                'cash_change'    => $cashChange,
                'paid_at'        => now(),
            ];

            if ($proofPath) {
                $txData['payment_proof'] = $proofPath;
            }

            Transaction::updateOrCreate(['order_id' => $order->id], $txData);

            if ($order->table) {
                $order->table->update(['status' => 'occupied']);
            }
        });

        return response()->json([
            'success'     => true,
            'message'     => 'Pembayaran pesanan #' . $order->id . ' berhasil dikonfirmasi dan lunas!',
            'receipt_url' => route('kasir.orders.receipt', $order->id),
        ]);
    }

    public function create()
    {
        $branchId = auth()->user()->branch_id;
        $menus = Menu::where('branch_id', $branchId)->with(['category', 'ingredients.inventory'])->get();
        return view('kasir.orders.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'payment_method' => 'nullable|in:cash,qris',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string',
        ]);

        $order = null;

        DB::transaction(function () use ($validated, &$order) {
            // Cashier POS orders are strictly Takeaway (no table)
            $order = Order::create([
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
                'table_id' => null, // Strictly Takeaway in Cashier POS
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
                        $proofPath = null;
                        if ($request->hasFile('payment_proof')) {
                            $file = $request->file('payment_proof');
                            $filename = 'proof_' . $order->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                            $destinationPath = public_path('uploads/payment_proofs');
                            if (!file_exists($destinationPath)) {
                                mkdir($destinationPath, 0755, true);
                            }
                            $file->move($destinationPath, $filename);
                            $proofPath = 'uploads/payment_proofs/' . $filename;
                        }

                        $txData = [
                            'branch_id'      => $order->branch_id,
                            'amount'         => $amount,
                            'payment_method' => 'qris',
                            'status'         => 'completed',
                            'merchant_id'    => $merchantId,
                            'paid_at'        => now(),
                        ];
                        if ($proofPath) {
                            $txData['payment_proof'] = $proofPath;
                        }

                        $transaction = Transaction::updateOrCreate(
                            ['order_id' => $order->id],
                            $txData
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

    public function financialReport(Request $request)
    {
        $branchId = auth()->user()->branch_id;
        $branch = auth()->user()->branch;

        $preset = $request->get('preset', 'today');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if (!$startDate && !$endDate) {
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
            }
        }

        $baseQuery = Order::where('branch_id', $branchId)
            ->where('payment_status', 'paid');

        if ($startDate && $endDate) {
            $baseQuery->whereBetween('created_at', [
                \Carbon\Carbon::parse($startDate)->startOfDay(),
                \Carbon\Carbon::parse($endDate)->endOfDay()
            ]);
        }

        // Financial KPIs
        $paidOrders = (clone $baseQuery)->with(['table', 'user', 'items.menu.category', 'transaction'])->latest()->get();
        $totalPaidRevenue = (float) $paidOrders->sum('total_price');
        $totalPaidOrdersCount = $paidOrders->count();

        $cashRevenue = (float) $paidOrders->where('payment_method', 'cash')->sum('total_price');
        $qrisRevenue = (float) $paidOrders->where('payment_method', 'qris')->sum('total_price');
        $avgTransactionValue = $totalPaidOrdersCount > 0 ? ($totalPaidRevenue / $totalPaidOrdersCount) : 0;

        // Sales Breakdown by Menu Category & Top Items
        $menuSales = [];
        $categorySales = [];

        foreach ($paidOrders as $order) {
            foreach ($order->items as $item) {
                $itemTotal = (float) ($item->price * $item->quantity);
                $menuName = $item->menu ? $item->menu->name : 'Unknown';
                $categoryName = ($item->menu && $item->menu->category) ? $item->menu->category->name : 'Lainnya';

                if (!isset($menuSales[$menuName])) {
                    $menuSales[$menuName] = [
                        'name' => $menuName,
                        'qty' => 0,
                        'total' => 0,
                    ];
                }
                $menuSales[$menuName]['qty'] += $item->quantity;
                $menuSales[$menuName]['total'] += $itemTotal;

                if (!isset($categorySales[$categoryName])) {
                    $categorySales[$categoryName] = [
                        'name' => $categoryName,
                        'qty' => 0,
                        'total' => 0,
                    ];
                }
                $categorySales[$categoryName]['qty'] += $item->quantity;
                $categorySales[$categoryName]['total'] += $itemTotal;
            }
        }

        usort($menuSales, fn($a, $b) => $b['total'] <=> $a['total']);
        $topMenus = array_slice($menuSales, 0, 5);

        $paginatedOrders = (clone $baseQuery)->with(['table', 'user', 'items.menu', 'transaction'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('kasir.reports', compact(
            'branch',
            'preset',
            'startDate',
            'endDate',
            'totalPaidRevenue',
            'totalPaidOrdersCount',
            'cashRevenue',
            'qrisRevenue',
            'avgTransactionValue',
            'categorySales',
            'topMenus',
            'paginatedOrders'
        ));
    }
}
