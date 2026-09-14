<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class SupervisorExpenseController extends Controller
{
    public function index(Request $request)
    {
        $branchId = auth()->user()->branch_id;
        $query = Expense::where('branch_id', $branchId);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $expenses = $query->latest('date')->paginate(15);
        $totalMonthExpenses = Expense::where('branch_id', $branchId)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        return view('supervisor.expenses.index', compact('expenses', 'totalMonthExpenses'));
    }

    public function create()
    {
        return view('supervisor.expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'category' => 'required|in:pembelian_bahan,peralatan,gaji_harian,operasional,lain_lain',
            'amount'   => 'required|numeric|min:1',
            'date'     => 'required|date',
            'notes'    => 'nullable|string|max:1000',
        ]);

        $validated['branch_id'] = auth()->user()->branch_id;

        Expense::create($validated);

        return redirect()->route('supervisor.expenses.index')
            ->with('success', 'Catatan pengeluaran "' . $validated['title'] . '" berhasil ditambahkan.');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        $title = $expense->title;
        $expense->delete();

        return redirect()->route('supervisor.expenses.index')
            ->with('success', 'Pengeluaran "' . $title . '" berhasil dihapus.');
    }
}
