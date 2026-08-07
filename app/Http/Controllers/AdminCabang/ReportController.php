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
}
