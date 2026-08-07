<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $selectedYear  = $request->get('year', now()->year);
        $selectedMonth = $request->get('month', '');

        // Global revenue totals
        $totalRevenue        = Order::where('payment_status', 'paid')->sum('total_price');
        $monthlyRevenue      = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');
        $todayRevenue        = Order::where('payment_status', 'paid')
            ->whereDate('created_at', today())
            ->sum('total_price');
        $totalOrders         = Order::where('payment_status', 'paid')->count();

        // Revenue per branch (all-time)
        $revenueByBranch = Branch::withCount(['orders as paid_orders_count' => fn($q) => $q->where('payment_status', 'paid')])
            ->withSum(['orders as total_revenue' => fn($q) => $q->where('payment_status', 'paid')], 'total_price')
            ->get();

        // Monthly revenue trend – last 6 months (for chart)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date   = now()->subMonths($i);
            $label  = $date->locale('id')->isoFormat('MMM YY');
            $amount = Order::where('payment_status', 'paid')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_price');
            $monthlyTrend[] = ['label' => $label, 'amount' => (float)$amount];
        }

        // Revenue per branch per month (for stacked/grouped chart)
        $branches = Branch::orderBy('name')->get();
        $branchMonthlyData = [];
        foreach ($branches as $branch) {
            $data = [];
            for ($i = 5; $i >= 0; $i--) {
                $date   = now()->subMonths($i);
                $amount = Order::where('payment_status', 'paid')
                    ->where('branch_id', $branch->id)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('total_price');
                $data[] = (float)$amount;
            }
            $branchMonthlyData[] = [
                'label' => $branch->name,
                'data'  => $data,
            ];
        }

        // Top transactions per branch this month
        $branchStats = Branch::with(['orders' => function ($q) {
            $q->where('payment_status', 'paid')
              ->whereMonth('created_at', now()->month)
              ->whereYear('created_at', now()->year);
        }])->get()->map(function ($branch) {
            return [
                'branch'         => $branch,
                'monthly_rev'    => $branch->orders->sum('total_price'),
                'monthly_orders' => $branch->orders->count(),
            ];
        })->sortByDesc('monthly_rev');

        // Recent paid orders
        $recentOrders = Order::with(['branch', 'table'])
            ->where('payment_status', 'paid')
            ->latest()
            ->take(15)
            ->get();

        $chartLabels = array_column($monthlyTrend, 'label');
        $chartData   = array_column($monthlyTrend, 'amount');

        return view('superadmin.reports.index', compact(
            'totalRevenue', 'monthlyRevenue', 'todayRevenue', 'totalOrders',
            'revenueByBranch', 'monthlyTrend', 'branchMonthlyData',
            'branchStats', 'recentOrders', 'branches',
            'chartLabels', 'chartData'
        ));
    }
}
