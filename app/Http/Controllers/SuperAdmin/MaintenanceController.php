<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Order;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MaintenanceController extends Controller
{
    /**
     * Show maintenance mode management dashboard.
     */
    public function index()
    {
        $maintenance = SystemSetting::getMaintenanceData();
        $totalBranches = Branch::count();
        $activeOrders = Order::whereIn('order_status', ['pending', 'processing'])->count();

        return view('superadmin.maintenance.index', compact(
            'maintenance',
            'totalBranches',
            'activeOrders'
        ));
    }

    /**
     * Update maintenance mode configuration.
     */
    public function update(Request $request)
    {
        $request->validate([
            'maintenance_mode'     => 'nullable|boolean',
            'maintenance_title'    => 'nullable|string|max:255',
            'maintenance_message'  => 'nullable|string|max:1000',
            'maintenance_end_time' => 'nullable|date',
        ]);

        $isActive = $request->boolean('maintenance_mode');

        SystemSetting::set('maintenance_mode', $isActive);
        SystemSetting::set('maintenance_title', $request->input('maintenance_title', 'SajiHub Sedang Dalam Pemeliharaan'));
        SystemSetting::set('maintenance_message', $request->input('maintenance_message', 'Kami sedang melakukan peningkatan performa dan pemeliharaan server SajiHub. Sistem akan segera kembali normal.'));
        SystemSetting::set('maintenance_end_time', $request->input('maintenance_end_time'));
        SystemSetting::set('maintenance_updated_at', now()->toDateTimeString());
        SystemSetting::set('maintenance_updated_by', auth()->user()->name ?? 'Super Admin');

        $message = $isActive
            ? 'Mode Pemeliharaan berhasil DIAKTIFKAN. Website kini non-aktif untuk publik dan staf cabang.'
            : 'Mode Pemeliharaan berhasil DINONAKTIFKAN. Website kini kembali normal dan dapat diakses publik.';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Quick toggle maintenance mode (e.g. from dashboard).
     */
    public function toggle(Request $request)
    {
        $current = SystemSetting::isMaintenanceMode();
        $newStatus = !$current;

        SystemSetting::set('maintenance_mode', $newStatus);
        SystemSetting::set('maintenance_updated_at', now()->toDateTimeString());
        SystemSetting::set('maintenance_updated_by', auth()->user()->name ?? 'Super Admin');

        $message = $newStatus
            ? 'Mode Pemeliharaan berhasil DIAKTIFKAN. Website saat ini non-aktif untuk umum.'
            : 'Mode Pemeliharaan berhasil DINONAKTIFKAN. Website kembali ONLINE untuk semua pengguna.';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Regenerate secret bypass token.
     */
    public function regenerateSecret(Request $request)
    {
        $newSecret = Str::random(28);
        SystemSetting::set('maintenance_secret', $newSecret);

        return redirect()->back()->with('success', 'Bypass Secret Token berhasil diperbarui.');
    }

    /**
     * Preview the maintenance page without putting the site down.
     */
    public function preview()
    {
        $maintenance = SystemSetting::getMaintenanceData();

        return response()->view('errors.maintenance', [
            'maintenance' => $maintenance,
            'isPreview'   => true,
        ], 200);
    }
}
