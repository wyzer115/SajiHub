<?php

namespace App\Http\Middleware;

use App\Models\SystemSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSystemMaintenance
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Skip static assets & system files
        if ($this->shouldPassThroughAssets($request)) {
            return $next($request);
        }

        // 2. If maintenance mode is NOT active, proceed normally
        if (!SystemSetting::isMaintenanceMode()) {
            return $next($request);
        }

        $maintenance = SystemSetting::getMaintenanceData();

        // 3. Superadmin is ALWAYS allowed to access the entire application
        if (auth()->check() && auth()->user()->isSuperAdmin()) {
            return $next($request);
        }

        // 4. Check for secret bypass token in URL query (?bypass=SECRET) or cookie
        $bypassQuery = $request->query('bypass');
        $bypassCookie = $request->cookie('sajihub_maintenance_bypass');
        $validSecret = $maintenance['secret'] ?? null;

        if ($validSecret && ($bypassCookie === $validSecret || $bypassQuery === $validSecret)) {
            $response = $next($request);
            if ($bypassQuery === $validSecret && $response instanceof \Illuminate\Http\Response) {
                // Attach cookie for 24 hours (1440 minutes)
                $response->withCookie(cookie('sajihub_maintenance_bypass', $validSecret, 1440));
            }
            return $response;
        }

        // 5. Allow access to login, logout, and bypass handler
        if ($request->is('login') || $request->is('logout') || $request->is('maintenance/bypass*')) {
            return $next($request);
        }

        // 6. If AJAX or API request, return JSON 503
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success'     => false,
                'maintenance' => true,
                'message'     => $maintenance['message'],
                'end_time'    => $maintenance['end_time'],
            ], 503);
        }

        // 7. Otherwise, render the dedicated 503 Maintenance Page
        return response()->view('errors.maintenance', [
            'maintenance' => $maintenance,
        ], 503);
    }

    /**
     * Determine if the request should bypass maintenance checks for assets.
     */
    protected function shouldPassThroughAssets(Request $request): bool
    {
        return $request->is(
            'images/*',
            'build/*',
            'storage/*',
            'favicon.ico',
            'robots.txt',
            'up'
        );
    }
}
