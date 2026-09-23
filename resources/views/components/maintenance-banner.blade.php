@php
    $user = auth()->user();
    $isMaintenance = \App\Models\SystemSetting::isMaintenanceMode();
@endphp

@if($user && $user->isSuperAdmin() && $isMaintenance)
    <div class="fixed top-0 left-0 right-0 z-[9999] bg-gradient-to-r from-red-600 via-rose-600 to-red-700 text-white px-4 py-2 text-xs sm:text-sm font-extrabold shadow-md flex items-center justify-between">
        <div class="flex items-center gap-2 mx-auto">
            <span class="w-2.5 h-2.5 rounded-full bg-white animate-ping"></span>
            <span>⚡ PERHATIAN: Mode Maintenance Sedang Aktif! Website dinonaktifkan untuk publik.</span>
            <a href="{{ route('superadmin.maintenance.index') }}" class="ml-3 underline hover:text-amber-200 transition-colors font-black">
                Kelola Mode Maintenance &rarr;
            </a>
        </div>
    </div>
    <style>
        body {
            padding-top: 2.5rem !important;
        }
        aside#sidebar, main, header {
            top: 2.5rem !important;
        }
    </style>
@endif
