<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->web(append: [
            \App\Http\Middleware\BlockImpersonatorModifications::class,
            \App\Http\Middleware\CheckSystemMaintenance::class,
        ]);
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'branch.status' => \App\Http\Middleware\CheckBranchStatus::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();

// Auto-detect public path for hosting (e.g. cPanel public_html)
$base = dirname(__DIR__);
if (isset($_SERVER['DOCUMENT_ROOT']) && is_dir($_SERVER['DOCUMENT_ROOT'])) {
    $docRoot = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\');
    if (file_exists($docRoot . '/build/manifest.json') || (file_exists($docRoot . '/index.php') && realpath($docRoot) !== realpath($base . '/public') && file_exists($docRoot . '/images/logo.png'))) {
        $app->usePublicPath($docRoot);
    }
} elseif (is_dir($base . '/public_html') && (file_exists($base . '/public_html/build/manifest.json') || file_exists($base . '/public_html/index.php'))) {
    $app->usePublicPath($base . '/public_html');
} elseif (is_dir(dirname($base) . '/public_html') && (file_exists(dirname($base) . '/public_html/build/manifest.json') || file_exists(dirname($base) . '/public_html/index.php'))) {
    $app->usePublicPath(dirname($base) . '/public_html');
}

return $app;
