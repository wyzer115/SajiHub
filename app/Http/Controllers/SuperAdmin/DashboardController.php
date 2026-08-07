<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBranches = Branch::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_price');
        $totalEmployees = User::where('role', '!=', 'superadmin')->count();
        $totalOrders = Order::count();
        
        $branches = Branch::withCount(['users', 'orders'])
            ->with(['users' => fn($q) => $q->where('role', 'admin_cabang')])
            ->get();
            
        $recentOrders = Order::with(['branch', 'user'])->latest()->take(10)->get();
        $monthlyRevenue = Order::where('payment_status', 'paid')->whereMonth('created_at', now()->month)->sum('total_price');
        $lastMonthRevenue = Order::where('payment_status', 'paid')->whereMonth('created_at', now()->subMonth()->month)->sum('total_price');
        $revenueGrowth = $lastMonthRevenue > 0 ? (($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;

        // Revenue per branch
        $branchRevenues = Branch::withSum(['orders' => function($q) {
            $q->where('payment_status', 'paid');
        }], 'total_price')->get();

        // Monthly trend for past 6 months
        $monthlyTrend = Order::where('payment_status', 'paid')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->limit(6)
            ->get();
            
        $monthlyLabels = [];
        $monthlyValues = [];
        foreach ($monthlyTrend as $trend) {
            $monthlyLabels[] = Carbon::create($trend->year, $trend->month, 1)->translatedFormat('F Y');
            $monthlyValues[] = (float) $trend->total;
        }

        return view('superadmin.dashboard', compact(
            'totalBranches', 'totalRevenue', 'totalEmployees', 'totalOrders', 'branches', 'recentOrders', 
            'monthlyRevenue', 'lastMonthRevenue', 'revenueGrowth', 'branchRevenues', 'monthlyLabels', 'monthlyValues'
        ));
    }
}
