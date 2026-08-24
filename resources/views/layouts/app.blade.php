<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SajiHUB - Manajemen Kuliner Enterprise')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

    @php
        $user = auth()->user();
    @endphp

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar Overlay (Mobile) -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-dark-950/80 z-40 hidden lg:hidden backdrop-blur-sm"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-[260px] bg-dark-900 border-r border-dark-800 transform -translate-x-full lg:translate-x-0 sidebar-transition flex flex-col h-full shadow-2xl">
            <!-- Logo Area -->
            <div class="h-16 flex items-center px-6 border-b border-dark-800">
                <a href="#" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" class="h-10 w-auto">
                </a>
            </div>

            <!-- Branch Info (If applicable) -->
            @if($user && !$user->isSuperAdmin())
            <div class="px-6 py-4 border-b border-dark-800">
                <div class="text-xs font-semibold text-dark-500 uppercase tracking-wider mb-1">Lokasi Cabang</div>
                <div class="text-sm text-white font-medium truncate">{{ $user->branch->name ?? 'Pusat' }}</div>
            </div>
            @endif

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto p-4 space-y-1 scrollbar-thin">
                @if($user && $user->isSuperAdmin())
                    <!-- SuperAdmin Nav -->
                    <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('superadmin.dashboard') ? 'bg-brand-500/10 text-brand-500 border-l-2 border-brand-500' : 'text-dark-300 hover:bg-dark-800 hover:text-white transition-colors' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        <span class="font-medium text-sm">Ringkasan</span>
                    </a>
                    
                    <a href="{{ route('superadmin.branches.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('superadmin.branches.*') ? 'bg-brand-500/10 text-brand-500 border-l-2 border-brand-500' : 'text-dark-300 hover:bg-dark-800 hover:text-white transition-colors' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span class="font-medium text-sm">Manajemen Cabang</span>
                    </a>

                    <a href="{{ route('superadmin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('superadmin.users.*') ? 'bg-brand-500/10 text-brand-500 border-l-2 border-brand-500' : 'text-dark-300 hover:bg-dark-800 hover:text-white transition-colors' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="font-medium text-sm">Kelola Admin</span>
                    </a>
                @endif

                @if($user && $user->isAdminCabang())
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-brand-500/10 text-brand-500 border-l-2 border-brand-500' : 'text-dark-300 hover:bg-dark-800 hover:text-white transition-colors' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"></path></svg>
                        <span class="font-medium text-sm">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.menus.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.menus.*') ? 'bg-brand-500/10 text-brand-500 border-l-2 border-brand-500' : 'text-dark-300 hover:bg-dark-800 hover:text-white transition-colors' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        <span class="font-medium text-sm">Menu</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-brand-500/10 text-brand-500 border-l-2 border-brand-500' : 'text-dark-300 hover:bg-dark-800 hover:text-white transition-colors' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        <span class="font-medium text-sm">Kategori</span>
                    </a>
                    <a href="{{ route('admin.tables.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.tables.*') ? 'bg-brand-500/10 text-brand-500 border-l-2 border-brand-500' : 'text-dark-300 hover:bg-dark-800 hover:text-white transition-colors' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                        <span class="font-medium text-sm">Meja & QR</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-brand-500/10 text-brand-500 border-l-2 border-brand-500' : 'text-dark-300 hover:bg-dark-800 hover:text-white transition-colors' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="font-medium text-sm">Staf & Karyawan</span>
                    </a>
                    <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.reports') ? 'bg-brand-500/10 text-brand-500 border-l-2 border-brand-500' : 'text-dark-300 hover:bg-dark-800 hover:text-white transition-colors' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <span class="font-medium text-sm">Laporan Keuangan</span>
                    </a>
                @endif

                @if($user && $user->isKasir())
                    <a href="{{ route('kasir.orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('kasir.orders.index') ? 'bg-brand-500/10 text-brand-500 border-l-2 border-brand-500' : 'text-dark-300 hover:bg-dark-800 hover:text-white transition-colors' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        <span class="font-medium text-sm">Pesanan</span>
                    </a>
                    <a href="{{ route('kasir.orders.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('kasir.orders.create') ? 'bg-brand-500/10 text-brand-500 border-l-2 border-brand-500' : 'text-dark-300 hover:bg-dark-800 hover:text-white transition-colors' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        <span class="font-medium text-sm">Buat Pesanan</span>
                    </a>
                @endif

                @if($user && $user->isKoki())
                    <a href="{{ route('koki.kitchen') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('koki.kitchen') ? 'bg-brand-500/10 text-brand-500 border-l-2 border-brand-500' : 'text-dark-300 hover:bg-dark-800 hover:text-white transition-colors' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"></path></svg>
                        <span class="font-medium text-sm">Dapur</span>
                    </a>
                @endif
            </nav>

            <!-- Bottom Actions -->
            <div class="p-4 border-t border-dark-800">
                <div class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-dark-500 opacity-70 cursor-not-allowed mb-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium text-sm">Bantuan</span>
                    <span class="ml-auto text-[10px] font-bold bg-dark-800 px-1.5 py-0.5 rounded text-dark-400">SOON</span>
                </div>
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-dark-300 hover:bg-dark-800 hover:text-white transition-colors text-left">
                        <svg class="w-5 h-5 text-red-500/70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="font-medium text-sm">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div id="main-content" class="flex-1 flex flex-col min-w-0 overflow-hidden bg-dark-950 relative">
            
            <!-- Topbar (Glass Effect) -->
            <header class="h-16 glass sticky top-0 z-30 px-4 sm:px-6 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-4">
                    <button id="sidebar-toggle" class="lg:hidden text-dark-300 hover:text-white focus:outline-none bg-dark-800 p-2 rounded-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h1 class="text-xl font-semibold text-white tracking-tight hidden sm:block">@yield('page-title', 'Dashboard')</h1>
                </div>

                <!-- User Profile Dropdown -->
                <div class="flex items-center gap-4">
                    @if($user)
                    <div class="relative">
                        <button type="button" data-dropdown-toggle="user-dropdown" class="flex items-center gap-3 focus:outline-none p-1 rounded-lg hover:bg-dark-800/50 transition-colors">
                            <div class="text-right hidden sm:block">
                                <div class="text-sm font-semibold text-white">{{ $user->name }}</div>
                                @if($user->isSuperAdmin())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-brand-500/10 text-brand-500 border border-brand-500/20">Super Admin</span>
                                @elseif($user->isAdminCabang())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-500/10 text-blue-500 border border-blue-500/20">Admin Cabang</span>
                                @elseif($user->isKasir())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-green-500/10 text-green-500 border border-green-500/20">Kasir</span>
                                @elseif($user->isKoki())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-purple-500/10 text-purple-500 border border-purple-500/20">Koki</span>
                                @endif
                            </div>
                            <div class="w-9 h-9 rounded-full bg-dark-700 border-2 border-dark-600 flex items-center justify-center text-white font-bold shadow-sm">
                                {{ $user->isSuperAdmin() ? 'S' : substr($user->name, 0, 1) }}
                            </div>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-dark-800 border border-dark-700 rounded-xl shadow-lg py-1 z-50">
                            <div class="px-4 py-2 border-b border-dark-700 sm:hidden">
                                <div class="text-sm font-medium text-white truncate">{{ $user->name }}</div>
                            </div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-dark-200 hover:bg-dark-700 hover:text-white transition-colors">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 scrollbar-thin">
                
                <!-- Flash Messages -->
                @if(session('success'))
                <div data-auto-dismiss class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 px-4 py-3 rounded-xl flex items-start gap-3 shadow-sm transition-all duration-300">
                    <svg class="w-5 h-5 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h3 class="text-sm font-medium">Berhasil!</h3>
                        <p class="text-xs opacity-90 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
                @endif
                
                @if(session('error'))
                <div data-auto-dismiss class="mb-6 bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-xl flex items-start gap-3 shadow-sm transition-all duration-300">
                    <svg class="w-5 h-5 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h3 class="text-sm font-medium">Gagal!</h3>
                        <p class="text-xs opacity-90 mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                @yield('content')

                <!-- Footer -->
                <footer class="mt-12 py-6 border-t border-dark-800 text-center text-sm text-dark-500">
                    &copy; {{ date('Y') }} SajiHUB Enterprise. Semua Hak Dilindungi.
                </footer>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
