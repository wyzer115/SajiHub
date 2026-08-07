<?php

namespace App\Http\Controllers\AdminCabang;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
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
}
