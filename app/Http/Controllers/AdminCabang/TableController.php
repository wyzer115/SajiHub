<?php

namespace App\Http\Controllers\AdminCabang;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::where('branch_id', auth()->user()->branch_id)
            ->withCount(['orders' => function($q) {
                $q->whereIn('order_status', ['pending', 'cooking', 'served']);
            }])->get();
            
        return view('admin.tables.index', compact('tables'));
    }

    public function store(Request $request)
    {
        $branchId = auth()->user()->branch_id;

        $validated = $request->validate([
            'table_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('tables')->where('branch_id', $branchId),
            ],
            'capacity' => 'nullable|integer|min:1|max:100',
        ], [
            'table_number.unique' => 'Nomor meja sudah terdaftar di cabang ini.',
        ]);

        $validated['branch_id'] = $branchId;
        $validated['capacity'] = $validated['capacity'] ?? 4;
        $validated['qr_code_token'] = Str::random(32);
        
        Table::create($validated);

        return redirect()->back()->with('success', 'Meja berhasil dibuat dengan kapasitas ' . $validated['capacity'] . ' kursi.');
    }

    public function update(Request $request, Table $table)
    {
        if ($table->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        $branchId = auth()->user()->branch_id;

        $validated = $request->validate([
            'table_number' => [
                'sometimes',
                'required',
                'string',
                'max:50',
                Rule::unique('tables')->where('branch_id', $branchId)->ignore($table->id),
            ],
            'capacity' => 'sometimes|required|integer|min:1|max:100',
            'status' => 'sometimes|required|in:empty,occupied',
        ], [
            'table_number.unique' => 'Nomor meja sudah terdaftar di cabang ini.',
        ]);

        $table->update($validated);

        if (isset($validated['status']) && $validated['status'] === 'empty') {
            \App\Models\Order::where('table_id', $table->id)
                ->whereIn('order_status', ['pending', 'cooking', 'served'])
                ->update(['order_status' => 'completed']);
        }

        return redirect()->back()->with('success', 'Data meja ' . $table->table_number . ' berhasil diperbarui.');
    }

    public function destroy(Table $table)
    {
        if ($table->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        // Keamanan data: Cegah penghapusan meja jika sedang ada pesanan aktif
        if ($table->orders()->whereIn('order_status', ['pending', 'cooking', 'served'])->exists()) {
            return redirect()->back()->with('error', 'Meja ' . $table->table_number . ' tidak dapat dihapus karena masih memproses pesanan aktif.');
        }

        $table->delete();

        return redirect()->back()->with('success', 'Meja berhasil dihapus.');
    }

    public function regenerateQr(Table $table)
    {
        if ($table->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        $table->update(['qr_code_token' => Str::random(32)]);

        return redirect()->back()->with('success', 'Token QR Code berhasil dibuat ulang.');
    }

    public function showQr(Table $table)
    {
        if ($table->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        $tableIdentifier = $table->qr_code_token ?: $table->table_number;
        // Internal web-only QR Code format: SAJIHUB-TABLE-{branch_id}-{token}
        $tableQrData = 'SAJIHUB-TABLE-' . $table->branch_id . '-' . $tableIdentifier;
        $orderUrl = url('/order') . '?branch_id=' . $table->branch_id . '&table=' . $tableIdentifier;
        
        // Optimized error correction (ecc=M) & tight margin for large, ultra-sharp readable modules
        $qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=500x500&ecc=M&margin=4&data=' . urlencode($tableQrData);

        return view('admin.tables.qr', compact('table', 'tableQrData', 'orderUrl', 'qrImageUrl'));
    }
}
