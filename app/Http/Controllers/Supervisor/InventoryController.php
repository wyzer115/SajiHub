<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\StockReconciliation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $branchId = auth()->user()->branch_id;
        $query = Inventory::where('branch_id', $branchId);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $inventories = $query->latest()->paginate(15);

        return view('supervisor.inventory.index', compact('inventories'));
    }

    public function create()
    {
        return view('supervisor.inventory.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'category'   => 'required|in:bahan_makanan,bahan_minuman,peralatan',
            'stock'      => 'required|numeric|min:0',
            'unit'       => 'required|string|max:50',
            'min_stock'  => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'notes'      => 'nullable|string|max:1000',
        ]);

        $validated['branch_id'] = auth()->user()->branch_id;

        $inventory = Inventory::create($validated);

        if ($validated['unit_price'] > 0 && $validated['stock'] > 0) {
            $totalCost = $validated['unit_price'] * $validated['stock'];
            Expense::create([
                'branch_id' => auth()->user()->branch_id,
                'title'     => 'Pembelian Awal Stok: ' . $validated['name'],
                'category'  => $validated['category'] === 'peralatan' ? 'peralatan' : 'pembelian_bahan',
                'amount'    => $totalCost,
                'date'      => now(),
                'notes'     => 'Input stok baru ' . $validated['stock'] . ' ' . $validated['unit'],
            ]);
        }

        return redirect()->route('supervisor.inventory.index')
            ->with('success', 'Barang inventaris "' . $validated['name'] . '" berhasil ditambahkan.');
    }

    public function edit(Inventory $inventory)
    {
        if ($inventory->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        return view('supervisor.inventory.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        if ($inventory->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'category'   => 'required|in:bahan_makanan,bahan_minuman,peralatan',
            'stock'      => 'required|numeric|min:0',
            'unit'       => 'required|string|max:50',
            'min_stock'  => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'notes'      => 'nullable|string|max:1000',
        ]);

        $oldStock = $inventory->stock;
        $inventory->update($validated);

        $addedStock = $validated['stock'] - $oldStock;
        if ($addedStock > 0 && $validated['unit_price'] > 0) {
            Expense::create([
                'branch_id' => auth()->user()->branch_id,
                'title'     => 'Restok Barang: ' . $validated['name'],
                'category'  => $validated['category'] === 'peralatan' ? 'peralatan' : 'pembelian_bahan',
                'amount'    => $addedStock * $validated['unit_price'],
                'date'      => now(),
                'notes'     => 'Penambahan stok +' . $addedStock . ' ' . $validated['unit'],
            ]);
        }

        return redirect()->route('supervisor.inventory.index')
            ->with('success', 'Barang inventaris "' . $validated['name'] . '" berhasil diperbarui.');
    }

    public function destroy(Inventory $inventory)
    {
        if ($inventory->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        $name = $inventory->name;
        $inventory->delete();

        return redirect()->route('supervisor.inventory.index')
            ->with('success', 'Barang inventaris "' . $name . '" berhasil dihapus.');
    }

    public function opname(Request $request)
    {
        $branchId = auth()->user()->branch_id;
        $date = $request->get('date', Carbon::today()->toDateString());
        $prevDate = Carbon::parse($date)->subDay()->toDateString();

        $inventories = Inventory::where('branch_id', $branchId)->orderBy('name')->get();

        // Calculate PLU (Theoretical usage from POS sales on selected date)
        $orders = Order::where('branch_id', $branchId)
            ->where('payment_status', 'paid')
            ->whereDate('created_at', $date)
            ->with('items.menu.ingredients')
            ->get();

        $pluSales = [];
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                if ($item->menu && $item->menu->ingredients) {
                    foreach ($item->menu->ingredients as $ing) {
                        $pluSales[$ing->inventory_id] = ($pluSales[$ing->inventory_id] ?? 0) + ($item->quantity * $ing->quantity);
                    }
                }
            }
        }

        // Get yesterday's reconciliations to determine Opening Stock
        $prevRecons = StockReconciliation::where('branch_id', $branchId)
            ->whereDate('date', $prevDate)
            ->get()
            ->keyBy('inventory_id');

        // Get today's reconciliations if already recorded
        $todayRecons = StockReconciliation::where('branch_id', $branchId)
            ->whereDate('date', $date)
            ->get()
            ->keyBy('inventory_id');

        foreach ($inventories as $inv) {
            $inv->plu_sales = (float) ($pluSales[$inv->id] ?? 0);
            
            if (isset($todayRecons[$inv->id])) {
                $inv->today_recon = $todayRecons[$inv->id];
                $inv->opening_stock = (float) $todayRecons[$inv->id]->opening_stock;
            } elseif (isset($prevRecons[$inv->id])) {
                $inv->today_recon = null;
                $inv->opening_stock = (float) $prevRecons[$inv->id]->closing_stock;
            } else {
                $inv->today_recon = null;
                $inv->opening_stock = (float) $inv->stock;
            }
        }

        $recentReconciliations = StockReconciliation::where('branch_id', $branchId)
            ->with('inventory', 'user')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('supervisor.opname', compact('inventories', 'date', 'recentReconciliations'));
    }

    public function storeOpname(Request $request)
    {
        $branchId = auth()->user()->branch_id;
        $userId = auth()->id();
        $mode = $request->input('mode', 'existing');

        if ($mode === 'new') {
            $validated = $request->validate([
                'item_name'  => 'required|string|max:255',
                'category'   => 'nullable|string|max:50',
                'unit'       => 'required|string|max:50',
                'unit_price' => 'required|numeric|min:0',
                'stock'      => 'required|numeric|min:0',
                'min_stock'  => 'nullable|numeric|min:0',
            ]);

            $category = $validated['category'] ?? 'bahan_makanan';
            $minStock = isset($validated['min_stock']) && $validated['min_stock'] !== '' ? (float)$validated['min_stock'] : 5;

            $inventory = Inventory::create([
                'branch_id'  => $branchId,
                'name'       => trim($validated['item_name']),
                'category'   => $category,
                'stock'      => $validated['stock'],
                'unit'       => trim($validated['unit']),
                'unit_price' => $validated['unit_price'],
                'min_stock'  => $minStock,
            ]);

            if ($validated['unit_price'] > 0 && $validated['stock'] > 0) {
                Expense::create([
                    'branch_id' => $branchId,
                    'title'     => 'Pembelian Awal Stok: ' . $inventory->name,
                    'category'  => $category === 'peralatan' ? 'peralatan' : 'pembelian_bahan',
                    'amount'    => $validated['unit_price'] * $validated['stock'],
                    'date'      => now(),
                    'notes'     => 'Input stok baru dari form opname ' . $validated['stock'] . ' ' . $inventory->unit,
                ]);
            }

            return redirect()->route('supervisor.opname.index')
                ->with('success', 'Barang baru "' . $inventory->name . '" berhasil ditambahkan ke inventaris cabang dengan stok awal ' . $inventory->stock . ' ' . $inventory->unit . '.');
        }

        // Practical Adjustment & Stock Opname Handler
        $actionType = $request->input('action_type', 'set_actual'); // 'set_actual', 'add', 'reduce'

        $validated = $request->validate([
            'date'            => 'nullable|date',
            'inventory_id'    => 'required|exists:inventories,id',
            'action_type'     => 'nullable|in:set_actual,add,reduce',
            'actual_stock'    => 'nullable|numeric|min:0',
            'qty_change'      => 'nullable|numeric|min:0',
            // Legacy / direct fallback
            'opening_stock'   => 'nullable|numeric',
            'stock_in'        => 'nullable|numeric|min:0',
            'stock_out_waste' => 'nullable|numeric|min:0',
            'closing_stock'   => 'nullable|numeric|min:0',
            'reason'          => 'required|string|max:100',
            'notes'           => 'nullable|string|max:500',
        ]);

        $date = $validated['date'] ?? Carbon::today()->toDateString();
        $inventory = Inventory::where('branch_id', $branchId)->findOrFail($validated['inventory_id']);
        $unit = $inventory->unit;
        $unitPrice = (float) $inventory->unit_price;
        $openingStock = (float) $inventory->stock;

        $stockIn = 0;
        $stockOutWaste = 0;
        $closingStock = $openingStock;
        $changeNote = '';

        if ($actionType === 'add') {
            $qty = (float) ($request->input('qty_change') ?? $request->input('stock_in') ?? 0);
            $stockIn = $qty;
            $closingStock = $openingStock + $qty;
            $changeNote = "+{$qty} {$unit} (Restok Masuk)";
        } elseif ($actionType === 'reduce') {
            $qty = (float) ($request->input('qty_change') ?? $request->input('stock_out_waste') ?? 0);
            $stockOutWaste = $qty;
            $closingStock = max(0, $openingStock - $qty);
            $changeNote = "-{$qty} {$unit} (Pengurangan / Rusak)";
        } else {
            // 'set_actual' or fallback closing_stock
            $closingStock = (float) ($request->input('actual_stock') ?? $request->input('closing_stock') ?? $openingStock);
            $diffQty = $closingStock - $openingStock;
            if ($diffQty > 0) {
                $stockIn = $diffQty;
                $changeNote = "Koreksi Fisik dari {$openingStock} ke {$closingStock} {$unit} (+{$diffQty} {$unit})";
            } elseif ($diffQty < 0) {
                $stockOutWaste = abs($diffQty);
                $changeNote = "Koreksi Fisik dari {$openingStock} ke {$closingStock} {$unit} ({$diffQty} {$unit})";
            } else {
                $changeNote = "Stok dicek & dikonfirmasi tetap {$closingStock} {$unit}";
            }
        }

        // Calculate PLU Sales for this item on the specified date
        $orders = Order::where('branch_id', $branchId)
            ->where('payment_status', 'paid')
            ->whereDate('created_at', $date)
            ->with('items.menu.ingredients')
            ->get();

        $pluSales = 0;
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                if ($item->menu && $item->menu->ingredients) {
                    foreach ($item->menu->ingredients as $ing) {
                        if ($ing->inventory_id == $inventory->id) {
                            $pluSales += ($item->quantity * $ing->quantity);
                        }
                    }
                }
            }
        }

        // Calculate physical usage and difference
        $usePhysical = ($openingStock + $stockIn - $stockOutWaste) - $closingStock;
        $diff = $closingStock - $openingStock;
        $lossCost = 0;

        if ($stockOutWaste > 0 && $unitPrice > 0 && in_array($validated['reason'], ['rusak_basi', 'lost_hilang'])) {
            $lossCost = $stockOutWaste * $unitPrice;
        }

        // Update current inventory stock
        $inventory->update([
            'stock' => $closingStock,
        ]);

        // Record or Update Stock Reconciliation
        $reconciliation = StockReconciliation::create([
            'branch_id'       => $branchId,
            'inventory_id'    => $inventory->id,
            'user_id'         => $userId,
            'date'            => $date,
            'opening_stock'   => $openingStock,
            'stock_in'        => $stockIn,
            'stock_out_waste' => $stockOutWaste,
            'closing_stock'   => $closingStock,
            'use_physical'    => $usePhysical,
            'plu_sales'       => $pluSales,
            'diff'            => $diff,
            'unit_price'      => $unitPrice,
            'loss_cost'       => $lossCost,
            'reason'          => $validated['reason'],
            'notes'           => $changeNote . ($validated['notes'] ? ' | ' . $validated['notes'] : ''),
        ]);

        // If add action, record expense for purchase
        if ($actionType === 'add' && $stockIn > 0 && $unitPrice > 0) {
            Expense::create([
                'branch_id' => $branchId,
                'title'     => 'Restok Bahan: ' . $inventory->name,
                'category'  => $inventory->category === 'peralatan' ? 'peralatan' : 'pembelian_bahan',
                'amount'    => $stockIn * $unitPrice,
                'date'      => $date,
                'notes'     => 'Penambahan stok +' . $stockIn . ' ' . $unit . ' dari penyesuaian stok. ' . ($validated['notes'] ?? ''),
            ]);
        } elseif ($lossCost > 0) {
            Expense::create([
                'branch_id' => $branchId,
                'title'     => 'Barang Rusak/Basi: ' . $inventory->name,
                'category'  => 'pembelian_bahan',
                'amount'    => $lossCost,
                'date'      => $date,
                'notes'     => 'Stok susut/rusak ' . $stockOutWaste . ' ' . $unit . ' @ Rp ' . number_format($unitPrice, 0, ',', '.') . '. ' . ($validated['notes'] ?? ''),
            ]);
        }

        return redirect()->route('supervisor.opname.index', ['date' => $date])
            ->with('success', 'Stok "' . $inventory->name . '" berhasil diperbarui menjadi ' . (float)$closingStock . ' ' . $unit . ' (' . $changeNote . ').');
    }
}
