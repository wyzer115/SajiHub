<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Inventory;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function index()
    {
        $branch = auth()->user()->branch;
        $branchId = $branch->id;

        // Pemasukan (Total Revenue)
        $totalRevenue = Order::where('branch_id', $branchId)
            ->where('payment_status', 'paid')
            ->sum('total_price');

        $monthRevenue = Order::where('branch_id', $branchId)
            ->where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');

        // Pengeluaran (Total Expenses)
        $totalExpenses = Expense::where('branch_id', $branchId)->sum('amount');
        $monthExpenses = Expense::where('branch_id', $branchId)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        // Laba Bersih
        $netProfit = $totalRevenue - $totalExpenses;
        $monthNetProfit = $monthRevenue - $monthExpenses;

        // Inventoris Stok Summary
        $inventories = Inventory::where('branch_id', $branchId)->get();
        $lowStockCount = $inventories->filter(fn($i) => $i->isLowStock())->count();
        $bahanMakananCount = $inventories->where('category', 'bahan_makanan')->count();
        $bahanMinumanCount = $inventories->where('category', 'bahan_minuman')->count();
        $peralatanCount = $inventories->where('category', 'peralatan')->count();

        // Recent Expenses
        $recentExpenses = Expense::where('branch_id', $branchId)
            ->latest('date')
            ->take(5)
            ->get();

        // Recent Orders
        $recentOrders = Order::where('branch_id', $branchId)
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

            $dayRev = Order::where('branch_id', $branchId)
                ->where('payment_status', 'paid')
                ->whereDate('created_at', $date->toDateString())
                ->sum('total_price');
            $chartRevenues[] = (float) $dayRev;

            $dayExp = Expense::where('branch_id', $branchId)
                ->whereDate('date', $date->toDateString())
                ->sum('amount');
            $chartExpenses[] = (float) $dayExp;
        }

        return view('owner.dashboard', compact(
            'branch',
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
        $branchId = auth()->user()->branch_id;
        $branch = auth()->user()->branch;

        $startDate = $request->filled('start_date') ? $request->start_date : now()->startOfMonth()->toDateString();
        $endDate = $request->filled('end_date') ? $request->end_date : now()->endOfMonth()->toDateString();

        $revenue = Order::where('branch_id', $branchId)
            ->where('payment_status', 'paid')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->sum('total_price');

        $totalOrders = Order::where('branch_id', $branchId)
            ->where('payment_status', 'paid')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->count();

        $expenses = Expense::where('branch_id', $branchId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $totalExpenses = $expenses->sum('amount');

        $expensesByCategory = $expenses->groupBy('category')->map(fn($group) => $group->sum('amount'));

        $netProfit = $revenue - $totalExpenses;

        return view('owner.reports', compact(
            'branch', 'startDate', 'endDate', 'revenue', 'totalOrders', 'expenses', 'totalExpenses', 'expensesByCategory', 'netProfit'
        ));
    }

    public function inventory()
    {
        $branchId = auth()->user()->branch_id;
        $branch = auth()->user()->branch;

        $inventories = Inventory::where('branch_id', $branchId)->orderBy('category')->get();
        $totalValuation = $inventories->sum(fn($i) => $i->stock * $i->unit_price);
        $lowStockItems = $inventories->filter(fn($i) => $i->isLowStock());

        return view('owner.inventory', compact('branch', 'inventories', 'totalValuation', 'lowStockItems'));
    }
}
