<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Expense;
use App\Models\Inventory;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::all();
        $selectedBranchId = $request->get('branch_id');
        
        $selectedBranch = null;
        if ($selectedBranchId && $selectedBranchId !== 'all') {
            $selectedBranch = Branch::find($selectedBranchId);
        }

        // Branch filter helper query closures
        $orderQuery = function() use ($selectedBranchId) {
            $q = Order::query();
            if ($selectedBranchId && $selectedBranchId !== 'all') {
                $q->where('branch_id', $selectedBranchId);
            }
            return $q;
        };

        $expenseQuery = function() use ($selectedBranchId) {
            $q = Expense::query();
            if ($selectedBranchId && $selectedBranchId !== 'all') {
                $q->where('branch_id', $selectedBranchId);
            }
            return $q;
        };

        $inventoryQuery = function() use ($selectedBranchId) {
            $q = Inventory::query();
            if ($selectedBranchId && $selectedBranchId !== 'all') {
                $q->where('branch_id', $selectedBranchId);
            }
            return $q;
        };

        // Pemasukan (Total Revenue)
        $totalRevenue = $orderQuery()
            ->where('payment_status', 'paid')
            ->sum('total_price');

        $monthRevenue = $orderQuery()
            ->where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');

        // Pengeluaran (Total Expenses)
        $totalExpenses = $expenseQuery()->sum('amount');
        $monthExpenses = $expenseQuery()
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        // Laba Bersih
        $netProfit = $totalRevenue - $totalExpenses;
        $monthNetProfit = $monthRevenue - $monthExpenses;

        // Inventoris Stok Summary
        $inventories = $inventoryQuery()->get();
        $lowStockCount = $inventories->filter(fn($i) => $i->isLowStock())->count();
        $bahanMakananCount = $inventories->where('category', 'bahan_makanan')->count();
        $bahanMinumanCount = $inventories->where('category', 'bahan_minuman')->count();
        $peralatanCount = $inventories->where('category', 'peralatan')->count();

        // Recent Expenses
        $recentExpenses = $expenseQuery()
            ->with('branch')
            ->latest('date')
            ->take(5)
            ->get();

        // Recent Orders
        $recentOrders = $orderQuery()
            ->with('branch')
            ->where('payment_status', 'paid')
            ->latest()
            ->take(5)
            ->get();

        // Chart 7 Hari Terakhir
        $chartDates = [];
        $chartRevenues = [];
        $chartExpenses = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartDates[] = $date->format('d/m');

            $dayRev = $orderQuery()
                ->where('payment_status', 'paid')
                ->whereDate('created_at', $date->toDateString())
                ->sum('total_price');
            $chartRevenues[] = (float) $dayRev;

            $dayExp = $expenseQuery()
                ->whereDate('date', $date->toDateString())
                ->sum('amount');
            $chartExpenses[] = (float) $dayExp;
        }

        return view('owner.dashboard', compact(
            'branches',
            'selectedBranchId',
            'selectedBranch',
            'totalRevenue',
            'monthRevenue',
            'totalExpenses',
            'monthExpenses',
            'netProfit',
            'monthNetProfit',
            'inventories',
            'lowStockCount',
            'bahanMakananCount',
            'bahanMinumanCount',
            'peralatanCount',
            'recentExpenses',
            'recentOrders',
            'chartDates',
            'chartRevenues',
            'chartExpenses'
        ));
    }

    public function reports(Request $request)
    {
        $branches = Branch::all();
        $selectedBranchId = $request->get('branch_id');

        $selectedBranch = null;
        if ($selectedBranchId && $selectedBranchId !== 'all') {
            $selectedBranch = Branch::find($selectedBranchId);
        }

        $startDate = $request->filled('start_date') ? $request->start_date : now()->startOfMonth()->toDateString();
        $endDate = $request->filled('end_date') ? $request->end_date : now()->endOfMonth()->toDateString();

        $orderQuery = Order::where('payment_status', 'paid')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);

        $expenseQuery = Expense::whereBetween('date', [$startDate, $endDate]);

        if ($selectedBranchId && $selectedBranchId !== 'all') {
            $orderQuery->where('branch_id', $selectedBranchId);
            $expenseQuery->where('branch_id', $selectedBranchId);
        }

        $revenue = $orderQuery->sum('total_price');
        $totalOrders = $orderQuery->count();

        $expenses = $expenseQuery->with('branch')->get();
        $totalExpenses = $expenses->sum('amount');
        $expensesByCategory = $expenses->groupBy('category')->map(fn($group) => $group->sum('amount'));

        $netProfit = $revenue - $totalExpenses;

        return view('owner.reports', compact(
            'branches', 'selectedBranchId', 'selectedBranch', 'startDate', 'endDate', 'revenue', 'totalOrders', 'expenses', 'totalExpenses', 'expensesByCategory', 'netProfit'
        ));
    }

    public function inventory(Request $request)
    {
        $branches = Branch::all();
        $selectedBranchId = $request->get('branch_id');

        $selectedBranch = null;
        if ($selectedBranchId && $selectedBranchId !== 'all') {
            $selectedBranch = Branch::find($selectedBranchId);
        }

        $query = Inventory::with('branch')->orderBy('category');
        if ($selectedBranchId && $selectedBranchId !== 'all') {
            $query->where('branch_id', $selectedBranchId);
        }

        $inventories = $query->get();
        $totalValuation = $inventories->sum(fn($i) => $i->stock * $i->unit_price);
        $lowStockItems = $inventories->filter(fn($i) => $i->isLowStock());

        return view('owner.inventory', compact('branches', 'selectedBranchId', 'selectedBranch', 'inventories', 'totalValuation', 'lowStockItems'));
    }
}
