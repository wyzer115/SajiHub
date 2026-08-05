<?php

namespace App\Http\Controllers\AdminCabang;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $branch = auth()->user()->branch;

        // Default: past 30 days
        $startDateInput = $request->get('start_date', Carbon::now()->subDays(30)->toDateString());
        $endDateInput = $request->get('end_date', Carbon::now()->toDateString());

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
        while ($currentDay->lte($endDate)) {
            $formattedDate = $currentDay->toDateString();
            $chartLabels[] = $currentDay->translatedFormat('d M Y');
            
            $dayData = $dailyRevenueRaw->firstWhere('date', $formattedDate);
            $chartData[] = $dayData ? (float) $dayData->total : 0.0;
            $chartOrderCounts[] = $dayData ? (int) $dayData->count : 0;

            $currentDay->addDay();
        }

        return view('admin.reports.index', compact(
            'branch',
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
}
