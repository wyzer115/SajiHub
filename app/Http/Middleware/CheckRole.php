<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        if ($userRole === 'superadmin') {
            return $next($request);
        }

        if (in_array('dapur', $roles) || in_array('koki', $roles)) {
            $roles[] = 'dapur';
            $roles[] = 'koki';
        }

        if (!in_array($userRole, $roles)) {
            abort(403, 'Unauthorized. Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
