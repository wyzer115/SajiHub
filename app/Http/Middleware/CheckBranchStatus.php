<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBranchStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && !$user->isSuperAdmin()) {
            $branch = $user->branch;

            if ($branch && $branch->status !== 'buka') {
                $statusLabel = $branch->status === 'tutup' ? 'ditutup' : 'dalam pemeliharaan (maintenance)';
                $message = "Sistem Kasir Dikunci: Cabang sedang {$statusLabel} oleh Pusat.";

                if ($branch->status_note) {
                    $message .= " Catatan: " . $branch->status_note;
                }

                // Check if accessing restricted order creation/saving routes
                $restrictedRoutes = [
                    'kasir.orders.create',
                    'kasir.orders.store',
                    'pesan',
                    'pesan.store'
                ];

                if (in_array($request->route()->getName(), $restrictedRoutes)) {
                    if ($request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => $message
                        ], 403);
                    }

                    // If they are on customer order page, redirect to landing
                    if ($request->route()->getName() === 'pesan') {
                        return redirect()->route('landing')->with('error', $message);
                    }

                    return redirect()->back()->with('error', $message);
                }
            }
        }

        return $next($request);
    }
}
