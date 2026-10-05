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
        $selectedBranch = null;
        $selectedTable  = null;

        // Support Table & Branch detection via URL parameters (Scan QR Meja)
        if ($request->filled('branch_id')) {
            $selectedBranch = Branch::find($request->branch_id);
        }

        if ($request->filled('table')) {
            $tableQuery = Table::query();
            if ($selectedBranch) {
                $tableQuery->where('branch_id', $selectedBranch->id);
            }
            
            $tableParam = $request->table;
            $cleanParam = trim(str_replace(['Meja', 'meja', 'Table', 'table', '-', ' '], '', $tableParam));
            $selectedTable = $tableQuery->where(function($q) use ($tableParam, $cleanParam) {
                $q->where('qr_code_token', $tableParam)
                  ->orWhere('table_number', $tableParam)
                  ->orWhere('table_number', str_replace('-', ' ', $tableParam))
                  ->orWhere('table_number', str_replace(' ', '-', $tableParam))
                  ->orWhere('table_number', 'Table ' . $tableParam)
                  ->orWhere('table_number', 'Meja ' . $tableParam)
                  ->orWhere('table_number', ltrim($tableParam, '0'))
                  ->orWhere('id', $tableParam);

                if (!empty($cleanParam)) {
                    $q->orWhere('table_number', $cleanParam)
                      ->orWhere('table_number', 'Meja ' . $cleanParam)
                      ->orWhere('table_number', 'Meja ' . str_pad($cleanParam, 2, '0', STR_PAD_LEFT))
                      ->orWhere('table_number', ltrim($cleanParam, '0'));
                }
            })->first();

            if ($selectedTable && !$selectedBranch) {
                $selectedBranch = $selectedTable->branch;
            }
        }

        // Pesanan harus melalui scan QR meja restoran
        if (!$selectedTable) {
            return redirect()->route('landing')->with('warning', 'Silakan pindai (scan) QR Code di meja Anda terlebih dahulu untuk memesan menu.');
        }

        if (!$selectedBranch) {
            $selectedBranch = $selectedTable->branch;
        }

        if ($selectedBranch && $selectedBranch->status !== 'buka') {
            $statusText = $selectedBranch->status === 'maintenance' ? 'sedang dalam pemeliharaan (maintenance)' : 'saat ini sedang tutup';
            return redirect()->route('landing')->with('error', 'Mohon maaf, cabang ' . $selectedBranch->name . ' ' . $statusText . '. Silakan hubungi staf kami.');
        }

        $menus = Menu::where('branch_id', $selectedBranch->id)
            ->where('status', 'available')
            ->with(['category', 'ingredients.inventory'])
            ->get();

        return view('customer.order', compact(
            'selectedBranch', 'selectedTable', 'menus'
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
            'items.*.quantity' => 'required|integer|min:1|max:100',
            'items.*.notes'    => 'nullable|string|max:500',
        ]);

        $branch = Branch::findOrFail($validated['branch_id']);
        if ($branch->status !== 'buka') {
            return redirect()->back()->with('error', 'Cabang ini sedang tidak dapat menerima pesanan (' . ucfirst($branch->status) . ').');
        }

        // Keamanan: Pastikan meja benar-benar milik cabang yang dipilih
        $table = Table::where('id', $validated['table_id'])
            ->where('branch_id', $branch->id)
            ->first();

        if (!$table) {
            return redirect()->back()->with('error', 'Meja yang dipilih tidak valid untuk cabang ini.')->withInput();
        }

        $customerName = trim($validated['customer_name']);
        $userId = auth()->check() ? auth()->id() : null;

        $order = null;

        try {
            DB::transaction(function () use ($validated, $branch, $table, $customerName, $userId, &$order) {
                $order = Order::create([
                    'branch_id'      => $branch->id,
                    'user_id'        => $userId,
                    'table_id'       => $table->id,
                    'customer_name'  => $customerName,
                    'order_status'   => 'pending',
                    'payment_status' => 'unpaid', // Dine-in customer must confirm & pay at cashier
                    'payment_method' => $validated['payment_method'],
                    'total_price'    => 0,
                    'confirmed_at'   => null, // Must be confirmed via QR scan by cashier
                ]);

                $totalPrice = 0;
                $itemIdx = 0;
                foreach ($validated['items'] as $item) {
                    // Keamanan: Pastikan menu aktif dan milik cabang yang valid
                    $menu = Menu::with('ingredients.inventory')
                        ->where('branch_id', $branch->id)
                        ->where('status', 'available')
                        ->find($item['menu_id']);

                    if (!$menu) {
                        throw new \Exception('Salah satu menu yang dipilih tidak tersedia di cabang ini.');
                    }

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

                    // Deduct inventory stock safely with row-level locking to avoid race condition
                    if ($menu->ingredients) {
                        foreach ($menu->ingredients as $ingredient) {
                            if ($ingredient->inventory_id) {
                                $inv = \App\Models\Inventory::where('id', $ingredient->inventory_id)
                                    ->where('branch_id', $branch->id)
                                    ->lockForUpdate()
                                    ->first();

                                if ($inv) {
                                    $usedQty = (float) $ingredient->quantity * (int) $item['quantity'];
                                    $currentStock = (float) $inv->stock;
                                    $inv->update([
                                        'stock' => max(0, round($currentStock - $usedQty, 2))
                                    ]);
                                }
                            }
                        }
                    }
                }

                $order->update(['total_price' => $totalPrice]);

                Transaction::create([
                    'order_id'       => $order->id,
                    'branch_id'      => $branch->id,
                    'amount'         => $totalPrice,
                    'payment_method' => $validated['payment_method'],
                    'status'         => 'pending',
                    'merchant_id'    => $validated['payment_method'] === 'qris' ? 'ID1026528881513' : null,
                    'paid_at'        => null,
                ]);

                // Update table status to occupied
                $table->update(['status' => 'occupied']);
            });

            // Berikan izin akses struk di sesi pelanggan (Mencegah IDOR enumeration)
            session()->put("customer_order_{$order->id}", true);
            session()->push('customer_orders', $order->id);

            return redirect()->route('pesan.receipt', $order->id)
                ->with('success', 'Pesanan Anda #' . $order->id . ' berhasil dibuat. Silakan tunjukkan QR Konfirmasi ke Kasir!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage())->withInput();
        }
    }

    public function showReceipt(Order $order)
    {
        // Keamanan IDOR: Hanya izinkan pemilik sesi pesanan atau staf terautentikasi
        if (!auth()->check()) {
            $hasAccess = session()->get("customer_order_{$order->id}") 
                || in_array($order->id, session()->get('customer_orders', []));
            if (!$hasAccess) {
                return redirect()->route('landing')->with('error', 'Sesi struk pesanan tidak ditemukan atau telah berakhir.');
            }
        }

        $order->load(['items.menu', 'branch', 'table', 'transaction']);
        return view('customer.receipt', compact('order'));
    }

    public function checkStatus(Order $order)
    {
        // Keamanan IDOR: Hanya izinkan pemilik sesi pesanan atau staf terautentikasi
        if (!auth()->check()) {
            $hasAccess = session()->get("customer_order_{$order->id}") 
                || in_array($order->id, session()->get('customer_orders', []));
            if (!$hasAccess) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        }

        return response()->json([
            'payment_status' => $order->payment_status,
            'order_status'   => $order->order_status,
            'is_confirmed'   => !is_null($order->confirmed_at),
        ]);
    }
}
