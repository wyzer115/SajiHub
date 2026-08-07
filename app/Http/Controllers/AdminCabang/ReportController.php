<?php

namespace App\Http\Controllers\AdminCabang;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
<<<<<<< HEAD
=======
use Carbon\Carbon;
>>>>>>> a471717247185442b2b06268d4e157d25322f3c7
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
<<<<<<< HEAD
        $branch    = auth()->user()->branch;
        $branchId  = $branch->id;

        // Summary stats
        $todayRevenue   = $branch->orders()->where('payment_status', 'paid')->whereDate('created_at', today())->sum('total_price');
        $weekRevenue    = $branch->orders()->where('payment_status', 'paid')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_price');
        $monthRevenue   = $branch->orders()->where('payment_status', 'paid')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total_price');
        $totalRevenue   = $branch->orders()->where('payment_status', 'paid')->sum('total_price');

        $todayOrders    = $branch->orders()->whereDate('created_at', today())->count();
        $monthOrders    = $branch->orders()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $pendingOrders  = $branch->orders()->whereIn('order_status', ['pending', 'cooking'])->count();

        // Daily revenue – last 30 days (for chart)
        $dailyTrend = [];
        for ($i = 29; $i >= 0; $i--) {
            $date   = now()->subDays($i)->toDateString();
            $amount = $branch->orders()->where('payment_status', 'paid')
                ->whereDate('created_at', $date)
                ->sum('total_price');
            $dailyTrend[] = ['label' => now()->subDays($i)->format('d/m'), 'amount' => (float)$amount];
        }

        // Monthly trend – last 6 months
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date   = now()->subMonths($i);
            $amount = $branch->orders()->where('payment_status', 'paid')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_price');
            $monthlyTrend[] = ['label' => $date->locale('id')->isoFormat('MMM YY'), 'amount' => (float)$amount];
        }

        // Top menus
        $topMenus = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->where('orders.branch_id', $branchId)
            ->where('orders.payment_status', 'paid')
            ->select('menus.name', DB::raw('SUM(order_items.quantity) as total_qty'), DB::raw('SUM(order_items.quantity * order_items.price) as total_rev'))
            ->groupBy('menus.id', 'menus.name')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // Recent paid transactions
        $recentOrders = $branch->orders()
            ->with(['items.menu', 'table'])
            ->where('payment_status', 'paid')
            ->latest()
            ->take(20)
            ->get();

        $chartDailyLabels  = array_column($dailyTrend, 'label');
        $chartDailyData    = array_column($dailyTrend, 'amount');
        $chartMonthLabels  = array_column($monthlyTrend, 'label');
        $chartMonthData    = array_column($monthlyTrend, 'amount');

        return view('admin.reports.index', compact(
            'branch',
            'todayRevenue', 'weekRevenue', 'monthRevenue', 'totalRevenue',
            'todayOrders', 'monthOrders', 'pendingOrders',
            'topMenus', 'recentOrders',
            'chartDailyLabels', 'chartDailyData',
            'chartMonthLabels', 'chartMonthData'
        ));
    }
=======
        $branch = auth()->user()->branch;

        $preset = $request->get('preset', 'monthly');
        
        switch ($preset) {
            case 'today':
                $startDateInput = Carbon::today()->toDateString();
                $endDateInput = Carbon::today()->toDateString();
                break;
            case 'weekly':
                $startDateInput = Carbon::now()->subDays(6)->toDateString(); // 7 days including today
                $endDateInput = Carbon::now()->toDateString();
                break;
            case 'yearly':
                $startDateInput = Carbon::now()->subDays(364)->toDateString(); // 365 days including today
                $endDateInput = Carbon::now()->toDateString();
                break;
            case 'all':
                $firstOrder = Order::where('branch_id', $branch->id)->where('payment_status', 'paid')->oldest()->first();
                $startDateInput = $firstOrder ? $firstOrder->created_at->toDateString() : Carbon::now()->subYears(5)->toDateString();
                $endDateInput = Carbon::now()->toDateString();
                break;
            case 'monthly':
            default:
                $preset = 'monthly';
                $startDateInput = Carbon::now()->subDays(29)->toDateString(); // 30 days including today
                $endDateInput = Carbon::now()->toDateString();
                break;
        }

        $startDate = Carbon::parse($startDateInput)->startOfDay();
        $endDate = Carbon::parse($endDateInput)->endOfDay();

        // Query orders in range
        $ordersQuery = Order::where('branch_id', $branch->id)
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate]);

        $ordersList = (clone $ordersQuery)->with(['table', 'user'])->latest()->get();

        $totalRevenue = $ordersList->sum('total_price');
        $totalOrders = $ordersList->count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Group by payment method
        $paymentMethods = $ordersList->groupBy('payment_method')
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('total_price')
                ];
            });

        // Daily revenue for chart
        $dailyRevenueRaw = Order::where('branch_id', $branch->id)
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as total'),
                DB::raw('COUNT(id) as count')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Build a complete date list in range so that dates with 0 revenue are not missing in chart
        $chartLabels = [];
        $chartData = [];
        $chartOrderCounts = [];

        $currentDay = clone $startDate;
        // Limit daily resolution points in chart to avoid freezing browser on "all" preset
        $dateDiff = $startDate->diffInDays($endDate);
        
        if ($dateDiff <= 60) {
            while ($currentDay->lte($endDate)) {
                $formattedDate = $currentDay->toDateString();
                $chartLabels[] = $currentDay->translatedFormat('d M Y');
                
                $dayData = $dailyRevenueRaw->firstWhere('date', $formattedDate);
                $chartData[] = $dayData ? (float) $dayData->total : 0.0;
                $chartOrderCounts[] = $dayData ? (int) $dayData->count : 0;

                $currentDay->addDay();
            }
        } else {
            // Group by month for longer periods
            $monthlyRevenueRaw = Order::where('branch_id', $branch->id)
                ->where('payment_status', 'paid')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('SUM(total_price) as total'),
                    DB::raw('COUNT(id) as count')
                )
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();

            foreach ($monthlyRevenueRaw as $m) {
                $chartLabels[] = Carbon::create($m->year, $m->month, 1)->translatedFormat('F Y');
                $chartData[] = (float) $m->total;
                $chartOrderCounts[] = (int) $m->count;
            }
        }

        return view('admin.reports.index', compact(
            'branch',
            'preset',
            'startDateInput',
            'endDateInput',
            'totalRevenue',
            'totalOrders',
            'averageOrderValue',
            'paymentMethods',
            'ordersList',
            'chartLabels',
            'chartData',
            'chartOrderCounts'
        ));
    }

    public function export(Request $request)
    {
        $branch = auth()->user()->branch;

        $preset = $request->get('preset', 'monthly');
        
        switch ($preset) {
            case 'today':
                $startDateInput = Carbon::today()->toDateString();
                $endDateInput = Carbon::today()->toDateString();
                break;
            case 'weekly':
                $startDateInput = Carbon::now()->subDays(6)->toDateString();
                $endDateInput = Carbon::now()->toDateString();
                break;
            case 'yearly':
                $startDateInput = Carbon::now()->subDays(364)->toDateString();
                $endDateInput = Carbon::now()->toDateString();
                break;
            case 'all':
                $firstOrder = Order::where('branch_id', $branch->id)->where('payment_status', 'paid')->oldest()->first();
                $startDateInput = $firstOrder ? $firstOrder->created_at->toDateString() : Carbon::now()->subYears(5)->toDateString();
                $endDateInput = Carbon::now()->toDateString();
                break;
            case 'monthly':
            default:
                $preset = 'monthly';
                $startDateInput = Carbon::now()->subDays(29)->toDateString();
                $endDateInput = Carbon::now()->toDateString();
                break;
        }

        $startDate = Carbon::parse($startDateInput)->startOfDay();
        $endDate = Carbon::parse($endDateInput)->endOfDay();

        // Query orders in range
        $ordersList = Order::where('branch_id', $branch->id)
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['table', 'user'])
            ->latest()
            ->get();

        $totalRevenue = $ordersList->sum('total_price');
        $totalOrders = $ordersList->count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Group by payment method
        $paymentMethods = $ordersList->groupBy('payment_method')
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('total_price')
                ];
            });

        $filename = 'Laporan_Keuangan_' . str_replace(' ', '_', $branch->name) . '_' . $startDateInput . '_to_' . $endDateInput . '.xls';

        return response()->view('admin.reports.export_excel', compact(
            'branch', 'preset', 'startDateInput', 'endDateInput',
            'totalRevenue', 'totalOrders', 'averageOrderValue', 'paymentMethods', 'ordersList'
        ))
        ->header('Content-Type', 'application/vnd.ms-excel')
        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
        ->header('Cache-Control', 'max-age=0');
    }
>>>>>>> a471717247185442b2b06268d4e157d25322f3c7
}
