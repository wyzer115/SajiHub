<?php

namespace App\Http\Controllers\AdminCabang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $branch = auth()->user()->branch;
        
        $todayRevenue = $branch->orders()->where('payment_status', 'paid')->whereDate('created_at', today())->sum('total_price');
        $todayOrders = $branch->orders()->whereDate('created_at', today())->count();
        $activeOrders = $branch->orders()->whereIn('order_status', ['pending', 'cooking'])->count();
        $totalMenus = $branch->menus()->count();
        
        $recentOrders = $branch->orders()->with(['items.menu', 'table', 'user'])->latest()->take(10)->get();
        
        $popularMenus = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->where('orders.branch_id', $branch->id)
            ->select('menus.name', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->groupBy('menus.id', 'menus.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'branch', 'todayRevenue', 'todayOrders', 'activeOrders', 'totalMenus', 'recentOrders', 'popularMenus'
        ));
    }
}
