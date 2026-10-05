@php
    $manifestPath = public_path('build/manifest.json');
    $isHot = file_exists(public_path('hot'));
    $host = request()->getHost();
    $isLocal = in_array($host, ['localhost', '127.0.0.1', '::1']) || str_ends_with($host, '.test') || str_ends_with($host, '.local');

    // Check potential alternative manifest locations (e.g. shared hosting public_html)
    if (!file_exists($manifestPath)) {
        $possiblePaths = [
            base_path('public_html/build/manifest.json'),
            dirname(base_path()) . '/public_html/build/manifest.json',
            base_path('public/build/manifest.json'),
        ];
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $manifestPath = $path;
                break;
            }
        }
    }

    $hasManifest = file_exists($manifestPath);
    $manifestData = null;
    if ($hasManifest) {
        $manifestData = json_decode(@file_get_contents($manifestPath), true);
    }
@endphp

@if ($isHot && $isLocal)
    {{-- Local Vite HMR server --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@elseif ($hasManifest && isset($manifestData['resources/css/app.css']['file']))
    {{-- Production compiled assets from Vite manifest --}}
    @php
        $cssFile = $manifestData['resources/css/app.css']['file'];
        $jsFile = $manifestData['resources/js/app.js']['file'] ?? null;
        $fontCss = $manifestData['_fonts-C9MNnjVw.css']['file'] ?? null;
    @endphp
    @if ($fontCss)
        <link rel="stylesheet" href="{{ asset('build/' . $fontCss) }}">
    @endif
    <link rel="preload" as="style" href="{{ asset('build/' . $cssFile) }}">
    <link rel="stylesheet" href="{{ asset('build/' . $cssFile) }}">
    @if ($jsFile)
        <link rel="modulepreload" href="{{ asset('build/' . $jsFile) }}">
        <script type="module" src="{{ asset('build/' . $jsFile) }}"></script>
    @endif
@else
    {{-- Fallback: Look for built files directly in build/assets --}}
    @php
        $buildDir = is_dir(public_path('build/assets')) 
            ? public_path('build/assets') 
            : (is_dir(base_path('public_html/build/assets')) ? base_path('public_html/build/assets') : null);
        $cssFiles = $buildDir ? glob($buildDir . '/app-*.css') : [];
        $jsFiles = $buildDir ? glob($buildDir . '/app-*.js') : [];
        $foundCss = !empty($cssFiles) ? basename($cssFiles[0]) : null;
        $foundJs = !empty($jsFiles) ? basename($jsFiles[0]) : null;
    @endphp

    @if ($foundCss)
        <link rel="stylesheet" href="{{ asset('build/assets/' . $foundCss) }}">
        @if ($foundJs)
            <script type="module" src="{{ asset('build/assets/' . $foundJs) }}"></script>
        @endif
    @else
        {{-- Standalone CDN Fallback if build directory was not uploaded or missing --}}
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            brand: {
                                50: '#fff5f5', 100: '#fed7d7', 200: '#feb2b2', 300: '#fc8181',
                                400: '#fa1e0e', 500: '#bd2000', 600: '#8c0000', 700: '#700000',
                                800: '#540000', 900: '#380000'
                            },
                            saji: { maroon: '#8c0000', rust: '#bd2000', red: '#fa1e0e', yellow: '#ffbe0f' },
                            dark: {
                                50: '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0', 300: '#cbd5e1',
                                400: '#94a3b8', 500: '#64748b', 600: '#475569', 700: '#334155',
                                800: '#1e293b', 900: '#0f172a', 950: '#020617'
                            }
                        }
                    }
                }
            }
        </script>
        <style>
            [x-cloak] { display: none !important; }
            .sidebar-transition { transition: width 0.3s ease, transform 0.3s ease; }
            .animate-fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
            @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
            .glass { background: rgba(30, 41, 59, 0.8); backdrop-filter: blur(12px); border: 1px solid rgba(71, 85, 105, 0.3); }
        </style>
    @endif
@endif

<style>
    [x-cloak] { display: none !important; }
    /* Pastikan tampilan kamera pemindai QR tidak mirror / tidak terbalik */
    #reader video,
    #qr-reader video,
    #qr-reader__scan_region video,
    #kasir-qr-reader video,
    div[id*="reader"] video {
        transform: scaleX(1) !important;
        -webkit-transform: scaleX(1) !important;
    }
</style>
