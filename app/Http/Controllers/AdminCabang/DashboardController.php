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
        
        $popularMenus = \App\Models\Menu::where('branch_id', $branch->id)
            ->with('category')
            ->withSum('orderItems as count', 'quantity')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'branch', 'todayRevenue', 'todayOrders', 'activeOrders', 'totalMenus', 'recentOrders', 'popularMenus'
        ));
    }
}
