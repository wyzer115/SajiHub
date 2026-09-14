<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;

class ImpersonateController extends Controller
{
    public function start(Branch $branch)
    {
        // Find the admin user of the target branch
        $branchAdminUser = User::where('branch_id', $branch->id)
            ->where('role', 'admin_cabang')
            ->first();

        if (!$branchAdminUser) {
            return redirect()->back()->with('error', 'Cabang ini belum memiliki Admin Cabang.');
        }

        // Save original Super Admin ID in session
        session(['impersonator_id' => auth()->id()]);

        // Login as the branch admin
        auth()->login($branchAdminUser);

        return redirect()->route('admin.dashboard')->with('success', "Anda sedang mengintip Dasbor Cabang: {$branch->name}");
    }

    public function leave()
    {
        if (!session()->has('impersonator_id')) {
            return redirect()->route('login');
        }

        $superAdminId = session('impersonator_id');

        // Log back in as Super Admin
        auth()->loginUsingId($superAdminId);

        // Clear the session key
        session()->forget('impersonator_id');

        return redirect()->route('superadmin.dashboard')->with('success', 'Kembali ke Dasbor Super Admin.');
    }
}
