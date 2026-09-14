<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Inventory;
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
            'stock'      => 'required|integer|min:0',
            'unit'       => 'required|string|max:50',
            'min_stock'  => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'notes'      => 'nullable|string|max:1000',
        ]);

        $validated['branch_id'] = auth()->user()->branch_id;

        $inventory = Inventory::create($validated);

        // Optionally record expense if unit_price > 0 and stock > 0
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
            'stock'      => 'required|integer|min:0',
            'unit'       => 'required|string|max:50',
            'min_stock'  => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'notes'      => 'nullable|string|max:1000',
        ]);

        $oldStock = $inventory->stock;
        $inventory->update($validated);

        // If stock was increased, record expense for added stock
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

    public function opname()
    {
        $branchId = auth()->user()->branch_id;
        $inventories = Inventory::where('branch_id', $branchId)->orderBy('name')->get();
        return view('supervisor.opname', compact('inventories'));
    }

    public function storeOpname(Request $request)
    {
        $validated = $request->validate([
            'item_name'      => 'required|string|max:255',
            'unit'           => 'required|string|max:50',
            'unit_price'     => 'required|numeric|min:0',
            'physical_stock' => 'required|numeric|min:0',
            'reason'         => 'required|in:rusak_basi,lost_hilang,selisih_hitung,koreksi_stok',
            'notes'          => 'nullable|string|max:500',
        ]);

        $branchId = auth()->user()->branch_id;

        // Find existing inventory item by name or create a new inventory item
        $inventory = Inventory::where('branch_id', $branchId)
            ->whereRaw('LOWER(name) = ?', [strtolower(trim($validated['item_name']))])
            ->first();

        if (!$inventory) {
            $inventory = Inventory::create([
                'branch_id'  => $branchId,
                'name'       => trim($validated['item_name']),
                'category'   => 'Bahan Baku',
                'stock'      => $validated['physical_stock'],
                'unit'       => trim($validated['unit']),
                'unit_price' => $validated['unit_price'],
                'min_stock'  => 5,
            ]);
            $systemStock = 0;
        } else {
            $systemStock = $inventory->stock;
            $inventory->update([
                'unit'       => trim($validated['unit']),
                'unit_price' => $validated['unit_price'],
                'stock'      => $validated['physical_stock'],
            ]);
        }

        $physicalStock = $validated['physical_stock'];
        $diff = $physicalStock - $systemStock;

        if ($diff < 0 && $inventory->unit_price > 0) {
            $wasteCost = abs($diff) * $inventory->unit_price;
            Expense::create([
                'branch_id' => $branchId,
                'title'     => 'Stock Opname Loss: ' . $inventory->name,
                'category'  => 'pembelian_bahan',
                'amount'    => $wasteCost,
                'date'      => now(),
                'notes'     => 'Stock Opname selisih (' . $diff . ' ' . $inventory->unit . ') Alasan: ' . str_replace('_', ' ', $validated['reason']) . '. ' . ($validated['notes'] ?? ''),
            ]);
        }

        return redirect()->route('supervisor.inventory.index')
            ->with('success', 'Stock Opname "' . $inventory->name . '" berhasil disimpan secara manual. Stok fisik: ' . $physicalStock . ' ' . $inventory->unit . ' (Harga: Rp ' . number_format($inventory->unit_price, 0, ',', '.') . ').');
    }
}
