<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>@yield('title', 'SajiHUB - Manajemen Kuliner Enterprise')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('partials.head-assets')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @if(session()->has('impersonator_id'))
    <style>
        main a, 
        main button, 
        main input, 
        main select, 
        main textarea, 
        main [role="button"] {
            cursor: not-allowed !important;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mainContent = document.querySelector('main');
            if (mainContent) {
                const interactiveSelector = 'a, button, input, select, textarea, [role="button"], [type="submit"]';
                
                mainContent.addEventListener('click', (e) => {
                    const target = e.target.closest(interactiveSelector);
                    if (target) {
                        e.preventDefault();
                        e.stopPropagation();
                        showImpersonateAlert();
                    }
                }, true);

                mainContent.addEventListener('keydown', (e) => {
                    const target = e.target.closest('input, select, textarea');
                    if (target) {
                        e.preventDefault();
                        showImpersonateAlert();
                    }
                }, true);
            }
        });

        function showImpersonateAlert() {
            let toast = document.getElementById('impersonate-toast');
            if (toast) {
                toast.remove();
            }

            toast = document.createElement('div');
            toast.id = 'impersonate-toast';
            toast.className = 'fixed top-20 right-6 z-[9999] flex items-center gap-3 bg-red-500 text-white px-5 py-3.5 rounded-xl shadow-2xl border border-red-400/20 transform translate-y-2 opacity-0 transition-all duration-300 font-medium';
            toast.innerHTML = `
                <svg class="w-5 h-5 flex-shrink-0 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <span>Mode Intip: Anda tidak dapat mengubah data apapun!</span>
            `;

            document.body.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
            });

            setTimeout(() => {
                toast.classList.add('translate-y-2', 'opacity-0');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }
    </script>
    @endif
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-dark-950 text-dark-300 font-sans antialiased overflow-x-hidden">
    @php
        $user = auth()->user();
    @endphp

    <x-impersonate-banner />
    <x-branch-status-banner />
    <x-maintenance-banner />
    
    @if($user && $user->isKasir())
        <x-checkout-modal />
    @endif

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar Backdrop (Mobile) -->
        <div id="mobile-sidebar-backdrop" class="fixed inset-0 bg-black/50 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity duration-300"></div>
        <div id="sidebar-overlay" class="hidden"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-[260px] bg-white border-r border-stone-200 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out sidebar-transition flex flex-col h-full shadow-xl">
            <!-- Logo Area -->
            <div class="h-16 flex items-center px-6 border-b border-stone-200 bg-white">
                <a href="{{ route('landing') }}" class="flex items-center gap-2.5 flex-shrink-0 group">
                    <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" width="128" height="32" decoding="async" class="h-8 w-auto object-contain flex-shrink-0 drop-shadow-sm group-hover:scale-105 transition-transform duration-200">
                    <span class="font-black text-xl tracking-tight text-[#8C0000] leading-none select-none flex-shrink-0">
                        Saji<span class="text-[#FFBE0F]">HUB</span>
                    </span>
                </a>
            </div>

            <!-- Branch Info (If applicable) -->
            @if($user && !$user->isSuperAdmin())
            <div class="px-6 py-3.5 border-b border-stone-200 bg-stone-50">
                <div class="text-[10px] font-bold text-stone-500 uppercase tracking-wider">Lokasi Cabang</div>
                <div class="text-xs font-extrabold text-[#BD2000] truncate mt-0.5">
                    @if($user->isOwner())
                        Kantor Pusat
                    @else
                        {{ $user->branch->name ?? 'Pusat' }}
                    @endif
                </div>
            </div>
            @endif

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto p-4 space-y-1.5 scrollbar-thin">
                @if($user && $user->isSuperAdmin())
                    <!-- SuperAdmin Nav -->
                    <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('superadmin.dashboard') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        <span>Ringkasan</span>
                    </a>
                    
                    <a href="{{ route('superadmin.branches.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('superadmin.branches.*') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span>Manajemen Cabang</span>
                    </a>

                    <div class="h-px bg-stone-200 my-3"></div>

                    <a href="{{ route('superadmin.reports') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('superadmin.reports') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <span>Laporan Pendapatan Seluruh Cabang</span>
                    </a>

                    <a href="{{ route('superadmin.users.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('superadmin.users.*') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span>Kelola Admin Cabang</span>
                    </a>

                    <div class="h-px bg-stone-200 my-3"></div>

                    @php
                        $isSysMaint = \App\Models\SystemSetting::isMaintenanceMode();
                    @endphp
                    <a href="{{ route('superadmin.maintenance.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('superadmin.maintenance.*') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 {{ $isSysMaint ? 'text-red-600 animate-pulse' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Mode Pemeliharaan</span>
                        </div>
                        @if($isSysMaint)
                            <span class="px-2 py-0.5 text-[10px] font-black rounded-full bg-red-100 text-red-700 border border-red-200">AKTIF</span>
                        @else
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200">Aktif</span>
                        @endif
                    </a>
                @endif

                @if($user && $user->isAdminCabang())
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span>Beranda</span>
                    </a>
                    <a href="{{ route('admin.menus.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('admin.menus.*') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        <span>Kelola Menu</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        <span>Kelola Kategori</span>
                    </a>
                    <a href="{{ route('admin.tables.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('admin.tables.*') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                        <span>Meja & QR Code</span>
                    </a>
                    <a href="{{ route('admin.staff.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('admin.staff.*') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>Kelola Staff Cabang</span>
                    </a>
                    <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('admin.reports') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <span>Laporan Keuangan</span>
                    </a>
                @endif

                @if($user && $user->isOwner())
                    <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('owner.dashboard') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span>Beranda</span>
                    </a>
                    <a href="{{ route('owner.reports') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('owner.reports') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <span>Laporan Laba Rugi</span>
                    </a>
                    <a href="{{ route('owner.inventory') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('owner.inventory') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        <span>Aset & Stok Gudang</span>
                    </a>
                @endif

                @if($user && $user->isSupervisor())
                    <a href="{{ route('supervisor.inventory.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('supervisor.inventory.*') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        <span>Stok & Inventaris</span>
                    </a>
                    <a href="{{ route('supervisor.expenses.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('supervisor.expenses.*') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>Pengeluaran Operasional</span>
                    </a>
                @endif

                @if($user && $user->isKasir())
                    <a href="{{ route('kasir.orders.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('kasir.orders.index') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        <span>Daftar Pesanan</span>
                    </a>
                    <a href="{{ route('kasir.orders.create') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('kasir.orders.create') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        <span>Buat Pesanan Baru</span>
                    </a>
                    <a href="{{ route('kasir.orders.scan') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('kasir.orders.scan') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 {{ request()->routeIs('kasir.orders.scan') ? 'text-[#BD2000]' : 'text-stone-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            <span>Pindai QR Konfirmasi</span>
                        </div>
                        @if(request()->routeIs('kasir.orders.scan'))
                            <span class="w-2 h-2 rounded-full bg-[#BD2000]"></span>
                        @endif
                    </a>
                    <a href="{{ route('kasir.tables.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('kasir.tables.*') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                        <span>Status dan Meja</span>
                    </a>
                    <a href="{{ route('kasir.transactions') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('kasir.transactions') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Riwayat Transaksi</span>
                    </a>
                    <a href="{{ route('kasir.reports') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('kasir.reports') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Laporan Keuangan Kasir</span>
                    </a>
                @endif

                @if($user && $user->isDapur())
                    <a href="{{ route('koki.kitchen') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('koki.kitchen') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"></path></svg>
                        <span>Monitor Dapur</span>
                    </a>
                    <a href="{{ route('koki.menu-status') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('koki.menu-status') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                        <span>Ketersediaan Menu</span>
                    </a>
                    <a href="{{ route('koki.history') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('koki.history') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Riwayat Masak</span>
                    </a>
                @endif
            </nav>

            <!-- Bottom Actions -->
            <div class="p-4 border-t border-stone-200">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] transition-colors text-left font-semibold">
                        <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Header Bar -->
            <header class="h-16 bg-white border-b border-stone-200 flex items-center justify-between px-4 sm:px-6 z-30 shadow-sm">
                <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                    <button id="sidebar-toggle" class="p-2 text-stone-500 hover:text-[#BD2000] lg:hidden rounded-xl hover:bg-stone-100 transition-colors shrink-0" aria-label="Buka Menu">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div class="min-w-0">
                        <!-- Breadcrumb -->
                        <nav class="hidden sm:flex items-center gap-1.5 text-[11px] font-semibold text-stone-400 mb-0.5" aria-label="Breadcrumb">
                            @hasSection('breadcrumb')
                                @yield('breadcrumb')
                            @else
                                @php
                                    $breadcrumbRole = 'SajiHUB';
                                    if ($user) {
                                        if ($user->isSuperAdmin()) $breadcrumbRole = 'Super Admin';
                                        elseif ($user->isAdminCabang()) $breadcrumbRole = 'Admin Cabang';
                                        elseif ($user->isOwner()) $breadcrumbRole = 'Pemilik';
                                        elseif ($user->isSupervisor()) $breadcrumbRole = 'Supervisor';
                                        elseif ($user->isKasir()) $breadcrumbRole = 'Kasir';
                                        elseif ($user->isDapur()) $breadcrumbRole = 'Staf Dapur';
                                    }
                                @endphp
                                <span>{{ $breadcrumbRole }}</span>
                                <svg class="w-3 h-3 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                <span class="text-stone-600 font-bold">@yield('page-title', 'Beranda')</span>
                            @endif
                        </nav>
                        <h1 class="text-base sm:text-lg font-black text-[#8C0000] tracking-tight leading-tight truncate">@yield('page-title', 'Beranda')</h1>
                    </div>
                </div>

                <!-- User Dropdown & Status -->
                <div class="flex items-center gap-2 sm:gap-4 shrink-0">
                    <!-- Status Kasir Online Badge -->
                    <div class="inline-flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-[11px] sm:text-xs font-bold shadow-xs">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="hidden sm:inline">{{ $user && $user->branch ? $user->branch->name : ($user && ($user->isSuperAdmin() || $user->isOwner()) ? 'Kantor Pusat' : 'Pusat') }}</span>
                        <span class="text-emerald-400 font-medium hidden sm:inline">•</span>
                        <span class="text-emerald-700 font-extrabold">Aktif</span>
                    </div>

                    @if($user)
                    <div class="flex items-center gap-2 sm:gap-3 border-l border-stone-200 pl-2.5 sm:pl-4">
                        <div class="text-right hidden sm:block">
                            <div class="text-sm font-extrabold text-[#1C1917] leading-tight">{{ $user->name }}</div>
                            <div class="text-xs text-[#BD2000] font-bold capitalize">
                                @if($user->isSuperAdmin()) Super Admin
                                @elseif($user->isAdminCabang()) Admin Cabang
                                @elseif($user->isOwner()) Pemilik
                                @elseif($user->isSupervisor()) Supervisor
                                @elseif($user->isKasir()) Kasir
                                @elseif($user->isDapur()) Staf Dapur
                                @else {{ $user->role }} @endif
                            </div>
                        </div>
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-[#BD2000]/10 border border-[#BD2000]/30 flex items-center justify-center text-[#BD2000] font-black text-xs sm:text-sm shadow-xs shrink-0">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    </div>
                    @endif
                </div>
            </header>

            <!-- Main Content Container -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-[#FAF8F5] text-[#1C1917] overflow-x-hidden">
                <x-alert />
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Global Toast Notification Container (Mobile: Top-Center, Desktop: Top-Right) -->
    <div id="toast-container" class="fixed top-4 inset-x-4 max-w-sm mx-auto sm:top-6 sm:right-6 sm:left-auto sm:mx-0 sm:max-w-md z-[99999] pointer-events-none space-y-2.5 flex flex-col items-center sm:items-end"></div>

    <!-- Toggle & Toast & SweetAlert Script -->
    <script>
        // SweetAlert2 Global Configuration for Web Modals
        window.customSwal = Swal.mixin({
            customClass: {
                popup: 'rounded-3xl shadow-2xl font-sans border border-stone-200 p-6 bg-white max-w-md w-full',
                title: 'text-lg font-black text-[#1C1917]',
                htmlContainer: 'text-sm font-semibold text-stone-600 mt-2',
                confirmButton: 'px-5 py-2.5 rounded-xl font-bold bg-[#BD2000] hover:bg-[#8C0000] text-white shadow-md transition-all cursor-pointer mx-1.5 inline-flex items-center justify-center',
                cancelButton: 'px-5 py-2.5 rounded-xl font-bold bg-stone-200 text-stone-700 hover:bg-stone-300 transition-all cursor-pointer mx-1.5 inline-flex items-center justify-center'
            },
            buttonsStyling: false,
            backdrop: 'rgba(28, 25, 23, 0.65)'
        });

        // Floating Web Toast Notification Handler
        window.showToast = function(message, type = 'info') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            // Visual tokens per type
            let iconSvg = '';
            let iconWrapClass = '';

            if (type === 'success') {
                iconWrapClass = 'bg-emerald-50 text-emerald-600 border border-emerald-200/60';
                iconSvg = `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>`;
            } else if (type === 'error' || type === 'danger') {
                iconWrapClass = 'bg-red-50 text-red-600 border border-red-200/60';
                iconSvg = `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>`;
            } else if (type === 'warning') {
                iconWrapClass = 'bg-amber-50 text-amber-600 border border-amber-200/60';
                iconSvg = `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>`;
            } else {
                iconWrapClass = 'bg-stone-100 text-stone-700 border border-stone-200';
                iconSvg = `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`;
            }

            const toast = document.createElement('div');
            toast.className = 'w-full bg-white shadow-xl border border-stone-200/90 rounded-2xl p-4 flex items-center gap-3.5 pointer-events-auto transform transition-all duration-300 ease-out -translate-y-2 opacity-0 select-text';
            toast.innerHTML = `
                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 ${iconWrapClass}">
                    ${iconSvg}
                </div>
                <div class="flex-1 text-xs sm:text-sm font-semibold text-stone-800 leading-snug">
                    ${message}
                </div>
                <button type="button" aria-label="Tutup" class="text-stone-400 hover:text-stone-600 p-1 rounded-lg hover:bg-stone-100 transition-colors shrink-0 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;

            const closeBtn = toast.querySelector('button');
            let isRemoved = false;
            const removeToast = () => {
                if (isRemoved) return;
                isRemoved = true;
                toast.classList.add('-translate-y-2', 'opacity-0');
                setTimeout(() => {
                    if (toast.parentElement) toast.remove();
                }, 300);
            };

            closeBtn.addEventListener('click', removeToast);

            container.appendChild(toast);
            requestAnimationFrame(() => {
                toast.classList.remove('-translate-y-2', 'opacity-0');
            });

            setTimeout(removeToast, 3500);
        };

        // Override native window.alert with modern Web Toast
        window.alert = function(message) {
            window.showToast(message, 'warning');
        };

        // Intercept native window.confirm to prevent browser dialog popups
        window.confirm = function(message) {
            window.showToast(message, 'warning');
            return false;
        };

        // Global Confirm Helper for Forms or Action Buttons (Pengganti confirm())
        window.showConfirm = function(event, message = 'Apakah Anda yakin ingin melanjutkan tindakan ini?', title = 'Konfirmasi Tindakan', confirmText = 'Ya, Lanjutkan') {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            const target = event ? event.target : null;
            const targetForm = target ? (target.tagName === 'FORM' ? target : target.closest('form')) : null;
            const targetHref = target ? (target.tagName === 'A' ? target.href : target.closest('a')?.href) : null;

            window.customSwal.fire({
                title: title,
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (targetForm) {
                        targetForm.submit();
                    } else if (targetHref) {
                        window.location.href = targetHref;
                    }
                }
            });
            return false;
        };

        // Display session flash messages as Toasts if present
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', () => {
                window.showToast(@json(session('success')), 'success');
            });
        @endif
        @if(session('error'))
            document.addEventListener('DOMContentLoaded', () => {
                window.showToast(@json(session('error')), 'error');
            });
        @endif
        @if(session('warning'))
            document.addEventListener('DOMContentLoaded', () => {
                window.showToast(@json(session('warning')), 'warning');
            });
        @endif

        // Mobile Sidebar Navigation Drawer & Backdrop Handler
        function initMobileSidebar() {
            if (window.__mobileSidebarInitialized) return;
            window.__mobileSidebarInitialized = true;

            const toggleBtn = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('mobile-sidebar-backdrop') || document.getElementById('sidebar-overlay');

            if (!sidebar) return;

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                if (backdrop) backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden', 'lg:overflow-auto');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
                if (backdrop) backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden', 'lg:overflow-auto');
            }

            function toggleSidebar() {
                const isOpen = !sidebar.classList.contains('-translate-x-full');
                if (isOpen) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    toggleSidebar();
                });
            }

            if (backdrop) {
                backdrop.addEventListener('click', (e) => {
                    e.stopPropagation();
                    closeSidebar();
                });
                backdrop.addEventListener('touchstart', () => {
                    closeSidebar();
                }, { passive: true });
            }

            // Click outside handler: close drawer when clicking outside sidebar in mobile
            document.addEventListener('click', (e) => {
                if (window.innerWidth >= 1024) return; // Desktop unaffected
                const isOpen = !sidebar.classList.contains('-translate-x-full');
                if (isOpen && !sidebar.contains(e.target) && (!toggleBtn || !toggleBtn.contains(e.target))) {
                    closeSidebar();
                }
            });

            // Keyboard handler: close drawer on Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' || e.key === 'Esc') {
                    closeSidebar();
                }
            });

            // Auto close if resized to desktop
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    closeSidebar();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', initMobileSidebar);
    </script>
    @include('components.logout-modal')
    @stack('scripts')
</body>
</html>