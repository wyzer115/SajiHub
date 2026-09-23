<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>@yield('title', 'SajiHUB - Manajemen Kuliner Enterprise')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
</head>
<body class="bg-dark-950 text-dark-300 font-sans antialiased overflow-x-hidden">
    <x-impersonate-banner />
    <x-branch-status-banner />
    <x-maintenance-banner />
    <x-checkout-modal />

    @php
        $user = auth()->user();
    @endphp

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar Overlay (Mobile) -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-stone-900/60 z-40 hidden lg:hidden backdrop-blur-sm"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-[260px] bg-white border-r border-stone-200 transform -translate-x-full lg:translate-x-0 sidebar-transition flex flex-col h-full shadow-xl">
            <!-- Logo Area -->
            <div class="h-16 flex items-center px-6 border-b border-stone-200 bg-white">
                <a href="{{ route('landing') }}" class="flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" class="h-9 w-auto object-contain drop-shadow-sm">
                </a>
            </div>

            <!-- Branch Info (If applicable) -->
            @if($user && !$user->isSuperAdmin())
            <div class="px-6 py-3.5 border-b border-stone-200 bg-stone-50">
                <div class="text-[10px] font-bold text-stone-500 uppercase tracking-wider">Lokasi Cabang</div>
                <div class="text-xs font-extrabold text-[#BD2000] truncate mt-0.5">
                    @if($user->isOwner())
                        Multi-Cabang (Pusat)
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
                        <span>Laporan Omzet Global</span>
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
                            <span>Mode Maintenance</span>
                        </div>
                        @if($isSysMaint)
                            <span class="px-2 py-0.5 text-[10px] font-black rounded-full bg-red-100 text-red-700 border border-red-200">AKTIF</span>
                        @else
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200">Online</span>
                        @endif
                    </a>
                @endif

                @if($user && $user->isAdminCabang())
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"></path></svg>
                        <span>Dashboard</span>
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
                        <span>Dasbor Utama</span>
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
                        <span>Pesanan</span>
                    </a>
                    <a href="{{ route('kasir.orders.create') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('kasir.orders.create') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        <span>Buat Pesanan Kasir</span>
                    </a>
                    <a href="{{ route('kasir.orders.scan') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('kasir.orders.scan') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5 text-[#BD2000]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        <span>Scan QR Konfirmasi</span>
                    </a>
                    <a href="{{ route('kasir.tables.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('kasir.tables.*') ? 'bg-[#BD2000]/10 text-[#BD2000] border-l-4 border-[#BD2000] font-extrabold shadow-sm' : 'text-stone-600 hover:bg-stone-100 hover:text-[#BD2000] font-semibold' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                        <span>Status & QR Meja</span>
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
            <header class="h-16 bg-white border-b border-stone-200 flex items-center justify-between px-6 z-30 shadow-sm">
                <div class="flex items-center gap-4">
                    <button id="sidebar-toggle" class="p-2 text-stone-500 hover:text-[#BD2000] lg:hidden rounded-xl hover:bg-stone-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h1 class="text-lg font-black text-[#8C0000] tracking-tight">@yield('page-title', 'Dashboard')</h1>
                </div>

                <!-- User Dropdown & Status -->
                <div class="flex items-center gap-4">
                    @if($user)
                    <div class="flex items-center gap-3 border-l border-stone-200 pl-4">
                        <div class="text-right hidden sm:block">
                            <div class="text-sm font-extrabold text-[#1C1917] leading-tight">{{ $user->name }}</div>
                            <div class="text-xs text-[#BD2000] font-bold capitalize">
                                @if($user->isSuperAdmin()) Super Admin
                                @elseif($user->isAdminCabang()) Admin Cabang
                                @elseif($user->isOwner()) Pemilik (Owner)
                                @elseif($user->isSupervisor()) Supervisor
                                @elseif($user->isKasir()) Kasir
                                @elseif($user->isDapur()) Staf Dapur
                                @else {{ $user->role }} @endif
                            </div>
                        </div>
                        <div class="w-9 h-9 rounded-full bg-[#BD2000]/10 border border-[#BD2000]/30 flex items-center justify-center text-[#BD2000] font-black text-sm">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    </div>
                    @endif
                </div>
            </header>

            <!-- Main Content Container -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8 bg-[#FAF8F5] text-[#1C1917]">
                <x-alert />
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Global Toast Notification Container -->
    <div id="toast-container" class="fixed top-6 right-6 z-[99999] space-y-3 pointer-events-none"></div>

    <!-- Toggle & Toast & SweetAlert Script -->
    <script>
        // SweetAlert2 Global Configuration
        window.customSwal = Swal.mixin({
            customClass: {
                popup: 'rounded-3xl shadow-2xl font-sans border border-stone-100 p-6',
                title: 'text-lg font-black text-[#1C1917]',
                htmlContainer: 'text-sm font-semibold text-stone-600',
                confirmButton: 'px-6 py-2.5 rounded-xl font-bold bg-[#BD2000] text-white shadow-md hover:bg-[#8C0000] transition-all cursor-pointer mx-1.5',
                cancelButton: 'px-6 py-2.5 rounded-xl font-bold bg-stone-200 text-stone-700 hover:bg-stone-300 transition-all cursor-pointer mx-1.5'
            },
            buttonsStyling: false
        });

        // Override native window.alert with SweetAlert2
        window.alert = function(message, title = 'Pemberitahuan') {
            window.customSwal.fire({
                title: title,
                text: message,
                icon: 'warning',
                confirmButtonText: 'Mengerti'
            });
        };

        // Global Confirm Helper for Forms or Action Buttons
        window.showConfirm = function(event, message = 'Apakah Anda yakin ingin melanjutkan tindakan ini?', title = 'Konfirmasi Tindakan', confirmText = 'Ya, Lanjutkan') {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            const targetForm = event ? (event.target.tagName === 'FORM' ? event.target : event.target.closest('form')) : null;

            window.customSwal.fire({
                title: title,
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed && targetForm) {
                    targetForm.submit();
                }
            });
            return false;
        };

        window.showToast = function(message, type = 'info') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            const bgClass = type === 'success' ? 'bg-emerald-600 border-emerald-500 text-white' :
                            type === 'error' ? 'bg-red-600 border-red-500 text-white' :
                            type === 'warning' ? 'bg-amber-500 border-amber-400 text-white' :
                            'bg-stone-900 border-stone-800 text-white';
            const icon = type === 'success' ? '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>' : 
                         type === 'error' ? '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>' : 
                         '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';

            toast.className = `pointer-events-auto flex items-center gap-3 px-4 py-3 rounded-2xl shadow-2xl border text-xs font-bold transition-all duration-300 transform translate-y-2 opacity-0 max-w-sm ${bgClass}`;
            toast.innerHTML = `
                <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center font-black text-sm shrink-0">${icon}</div>
                <span class="flex-1">${message}</span>
            `;

            container.appendChild(toast);
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
            });

            setTimeout(() => {
                toast.classList.add('translate-y-2', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        };

        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (toggleBtn && sidebar && overlay) {
                toggleBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                });

                overlay.addEventListener('click', () => {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>