<?php

namespace App\Http\Controllers\AdminCabang;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $validated = $request->validate([
            'table_number' => 'required|string|max:50',
        ]);

        $validated['branch_id'] = auth()->user()->branch_id;
        $validated['qr_code_token'] = Str::random(32);
        
        Table::create($validated);

        return redirect()->back()->with('success', 'Meja berhasil dibuat.');
    }

    public function update(Request $request, Table $table)
    {
        if ($table->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        $validated = $request->validate([
            'table_number' => 'required|string|max:50',
            'status' => 'required|in:empty,occupied',
        ]);

        $table->update($validated);

        return redirect()->back()->with('success', 'Meja berhasil diperbarui.');
    }

    public function destroy(Table $table)
    {
        if ($table->branch_id !== auth()->user()->branch_id) {
            abort(403);
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
}
