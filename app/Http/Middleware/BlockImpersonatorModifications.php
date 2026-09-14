<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockImpersonatorModifications
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If session has 'impersonator_id', the Super Admin is in monitoring/impersonating mode
        if (session()->has('impersonator_id')) {
            // Block all non-safe methods (POST, PUT, PATCH, DELETE) except leaving impersonation
            if (!$request->isMethodSafe() && !$request->routeIs('superadmin.impersonate.leave')) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Mode Intip Aktif: Anda tidak diperbolehkan mengubah data di cabang ini.'
                    ], 403);
                }

                return redirect()->back()->with('error', 'Mode Intip Aktif: Anda hanya diperbolehkan memantau dasbor dan tidak diizinkan mengubah atau menambah data.');
            }
        }

        return $next($request);
    }
}
