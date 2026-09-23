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

        // Filter Periode Grafik Dasbor (Hari Ini, Minggu Ini, Bulan Ini, atau Tanggal Kustom)
        $period = $request->get('period', 'week');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $period = 'custom';
        }

        $chartDates = [];
        $chartRevenues = [];
        $chartExpenses = [];
        $periodLabel = 'Minggu Ini';

        if ($period === 'today') {
            $periodLabel = 'Hari Ini';
            $startDate = now()->toDateString();
            $endDate = now()->toDateString();

            // Slot waktu hari ini: 08:00, 11:00, 14:00, 17:00, 20:00, 23:00
            $timeBlocks = [
                ['label' => '08:00', 'from' => '00:00:00', 'to' => '08:59:59'],
                ['label' => '11:00', 'from' => '09:00:00', 'to' => '11:59:59'],
                ['label' => '14:00', 'from' => '12:00:00', 'to' => '14:59:59'],
                ['label' => '17:00', 'from' => '15:00:00', 'to' => '17:59:59'],
                ['label' => '20:00', 'from' => '18:00:00', 'to' => '20:59:59'],
                ['label' => '23:00', 'from' => '21:00:00', 'to' => '23:59:59'],
            ];

            foreach ($timeBlocks as $block) {
                $chartDates[] = $block['label'];

                $slotRev = $orderQuery()
                    ->where('payment_status', 'paid')
                    ->whereDate('created_at', now()->toDateString())
                    ->whereTime('created_at', '>=', $block['from'])
                    ->whereTime('created_at', '<=', $block['to'])
                    ->sum('total_price');
                $chartRevenues[] = (float) $slotRev;

                $slotExp = $expenseQuery()
                    ->whereDate('date', now()->toDateString())
                    ->whereTime('created_at', '>=', $block['from'])
                    ->whereTime('created_at', '<=', $block['to'])
                    ->sum('amount');
                $chartExpenses[] = (float) $slotExp;
            }
        } elseif ($period === 'month') {
            $periodLabel = 'Bulan Ini';
            $startDate = now()->startOfMonth()->toDateString();
            $endDate = now()->toDateString();

            $start = now()->startOfMonth();
            $end = now();
            for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                $chartDates[] = $d->format('d/m');

                $dayRev = $orderQuery()
                    ->where('payment_status', 'paid')
                    ->whereDate('created_at', $d->toDateString())
                    ->sum('total_price');
                $chartRevenues[] = (float) $dayRev;

                $dayExp = $expenseQuery()
                    ->whereDate('date', $d->toDateString())
                    ->sum('amount');
                $chartExpenses[] = (float) $dayExp;
            }
        } elseif ($period === 'custom' && $startDate && $endDate) {
            $start = \Carbon\Carbon::parse($startDate);
            $end = \Carbon\Carbon::parse($endDate);
            $periodLabel = $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y');

            $diffDays = $start->diffInDays($end);
            $step = $diffDays > 30 ? (int)ceil($diffDays / 30) : 1;

            for ($d = $start->copy(); $d->lte($end); $d->addDays($step)) {
                $chartDates[] = $d->format('d/m');

                $dayRev = $orderQuery()
                    ->where('payment_status', 'paid')
                    ->whereDate('created_at', $d->toDateString())
                    ->sum('total_price');
                $chartRevenues[] = (float) $dayRev;

                $dayExp = $expenseQuery()
                    ->whereDate('date', $d->toDateString())
                    ->sum('amount');
                $chartExpenses[] = (float) $dayExp;
            }
        } else {
            // Default: 'week' (Minggu Ini)
            $period = 'week';
            $periodLabel = 'Minggu Ini';
            $startDate = now()->subDays(6)->toDateString();
            $endDate = now()->toDateString();

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
            'period',
            'periodLabel',
            'startDate',
            'endDate',
            'chartDates',
            'chartRevenues',
            'chartExpenses'
        ));
    }

    public function reports(Request $request)
    {
        $data = $this->getReportData($request);
        return view('owner.reports', $data);
    }

    public function exportReports(Request $request)
    {
        $data = $this->getReportData($request);
        $branchName = $data['selectedBranch'] ? str_replace(' ', '_', $data['selectedBranch']->name) : 'Semua_Cabang';
        $filename = 'Laporan_PL_SajiHub_' . $branchName . '_' . $data['startDate'] . '_sd_' . $data['endDate'] . '.xls';

        return response()->view('owner.reports_export_excel', $data)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'max-age=0');
    }

    private function getReportData(Request $request)
    {
        $branches = Branch::all();
        $selectedBranchId = $request->get('branch_id');

        $selectedBranch = null;
        if ($selectedBranchId && $selectedBranchId !== 'all') {
            $selectedBranch = Branch::find($selectedBranchId);
        }

        $preset = $request->get('preset');
        if ($preset === 'today') {
            $startDate = now()->toDateString();
            $endDate = now()->toDateString();
            $periodLabel = 'Hari Ini (' . now()->format('d/m/Y') . ')';
        } elseif ($preset === 'week') {
            $startDate = now()->subDays(6)->toDateString();
            $endDate = now()->toDateString();
            $periodLabel = 'Minggu Ini (' . now()->subDays(6)->format('d/m') . ' - ' . now()->format('d/m/Y') . ')';
        } elseif ($preset === 'month') {
            $startDate = now()->startOfMonth()->toDateString();
            $endDate = now()->toDateString();
            $periodLabel = 'Bulan Ini (' . now()->format('F Y') . ')';
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $preset = 'custom';
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $periodLabel = \Carbon\Carbon::parse($startDate)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($endDate)->format('d/m/Y');
        } else {
            $preset = 'month';
            $startDate = now()->startOfMonth()->toDateString();
            $endDate = now()->toDateString();
            $periodLabel = 'Bulan Ini (' . now()->format('F Y') . ')';
        }

        $startDateTime = \Carbon\Carbon::parse($startDate)->startOfDay();
        $endDateTime = \Carbon\Carbon::parse($endDate)->endOfDay();

        $orderQuery = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDateTime, $endDateTime]);

        $expenseQuery = Expense::whereBetween('date', [$startDate, $endDate]);

        if ($selectedBranchId && $selectedBranchId !== 'all') {
            $orderQuery->where('branch_id', $selectedBranchId);
            $expenseQuery->where('branch_id', $selectedBranchId);
        }

        $revenue = (float) (clone $orderQuery)->sum('total_price');
        $totalOrders = (clone $orderQuery)->count();
        $avgOrderValue = $totalOrders > 0 ? (float) round($revenue / $totalOrders) : 0;

        $expenses = (clone $expenseQuery)->with('branch')->orderBy('date', 'desc')->get();
        $paginatedExpenses = (clone $expenseQuery)->with('branch')->orderBy('date', 'desc')->paginate(10, ['*'], 'expenses_page')->withQueryString();
        $totalExpenses = (float) $expenses->sum('amount');
        $expensesByCategory = $expenses->groupBy('category')->map(fn($group) => (float) $group->sum('amount'));

        $netProfit = $revenue - $totalExpenses;
        $profitMargin = $revenue > 0 ? round(($netProfit / $revenue) * 100, 1) : 0;
        $expenseRatio = $revenue > 0 ? round(($totalExpenses / $revenue) * 100, 1) : 0;

        // Payment Methods Breakdown
        $paymentMethodsRaw = (clone $orderQuery)
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_price) as total'))
            ->groupBy('payment_method')
            ->get();
        $paymentMethods = [];
        foreach ($paymentMethodsRaw as $pm) {
            $methodKey = strtolower($pm->payment_method ?? 'lainnya');
            $paymentMethods[$methodKey] = [
                'count' => (int) $pm->count,
                'total' => (float) $pm->total,
                'percentage' => $revenue > 0 ? round(($pm->total / $revenue) * 100, 1) : 0,
            ];
        }

        // Branch Performance Breakdown (jika melihat Semua Cabang)
        $branchPerformances = collect();
        if (!$selectedBranch) {
            $branchPerformances = $branches->map(function ($b) use ($startDateTime, $endDateTime, $startDate, $endDate) {
                $bRev = (float) Order::where('payment_status', 'paid')
                    ->where('branch_id', $b->id)
                    ->whereBetween('created_at', [$startDateTime, $endDateTime])
                    ->sum('total_price');
                $bOrders = Order::where('payment_status', 'paid')
                    ->where('branch_id', $b->id)
                    ->whereBetween('created_at', [$startDateTime, $endDateTime])
                    ->count();
                $bExp = (float) Expense::where('branch_id', $b->id)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->sum('amount');
                $bProfit = $bRev - $bExp;
                $bMargin = $bRev > 0 ? round(($bProfit / $bRev) * 100, 1) : 0;
                return [
                    'branch' => $b,
                    'revenue' => $bRev,
                    'orders' => $bOrders,
                    'expense' => $bExp,
                    'profit' => $bProfit,
                    'margin' => $bMargin,
                ];
            });
        }

        // Top 5 Menu Terlaris di periode ini
        $topMenus = \App\Models\OrderItem::whereHas('order', function ($q) use ($startDateTime, $endDateTime, $selectedBranchId) {
            $q->where('payment_status', 'paid')
              ->whereBetween('created_at', [$startDateTime, $endDateTime]);
            if ($selectedBranchId && $selectedBranchId !== 'all') {
                $q->where('branch_id', $selectedBranchId);
            }
        })
        ->with('menu')
        ->select('menu_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(quantity * price) as total_revenue'))
        ->groupBy('menu_id')
        ->orderByDesc('total_revenue')
        ->take(5)
        ->get();

        return compact(
            'branches',
            'selectedBranchId',
            'selectedBranch',
            'preset',
            'periodLabel',
            'startDate',
            'endDate',
            'revenue',
            'totalOrders',
            'avgOrderValue',
            'expenses',
            'paginatedExpenses',
            'totalExpenses',
            'expensesByCategory',
            'netProfit',
            'profitMargin',
            'expenseRatio',
            'paymentMethods',
            'branchPerformances',
            'topMenus'
        );
    }

    public function inventory(Request $request)
    {
        $branches = Branch::all();
        $selectedBranchId = $request->get('branch_id');

        $selectedBranch = null;
        if ($selectedBranchId && $selectedBranchId !== 'all') {
            $selectedBranch = Branch::find($selectedBranchId);
        }

        $baseQuery = Inventory::with('branch')->orderBy('category');
        if ($selectedBranchId && $selectedBranchId !== 'all') {
            $baseQuery->where('branch_id', $selectedBranchId);
        }

        $allInventories = (clone $baseQuery)->get();
        $totalValuation = $allInventories->sum(fn($i) => $i->stock * $i->unit_price);
        $lowStockItems = $allInventories->filter(fn($i) => $i->isLowStock());

        $inventories = $baseQuery->paginate(10)->withQueryString();

        return view('owner.inventory', compact('branches', 'selectedBranchId', 'selectedBranch', 'inventories', 'totalValuation', 'lowStockItems'));
    }
}
