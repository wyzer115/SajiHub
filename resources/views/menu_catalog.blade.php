<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Daftar Menu & Katalog Kuliner — SajiHUB</title>
    <meta name="description" content="Lihat seluruh daftar menu kuliner otentik, pilihan minuman segar, dan hidangan penutup khas SajiHUB dengan harga terjangkau.">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.alert = function(message, title = 'Pemberitahuan') {
            Swal.fire({
                title: title,
                text: message,
                icon: 'warning',
                confirmButtonColor: '#BD2000',
                confirmButtonText: 'Mengerti',
                customClass: { popup: 'rounded-3xl shadow-2xl font-sans' }
            });
        };
    </script>
    @include('partials.head-assets')
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #FAF8F5;
            color: #1C1917;
        }
        /* QR Code Scanner Custom Styles */
        #qr-reader-container {
            min-height: 270px !important;
            background-color: #0c0a09 !important;
            position: relative !important;
        }
        #qr-reader {
            border: none !important;
            width: 100% !important;
            min-height: 260px !important;
            background: #0c0a09 !important;
        }
        #qr-reader video,
        #qr-reader__scan_region video {
            border-radius: 1rem !important;
            object-fit: cover !important;
            width: 100% !important;
            height: 100% !important;
            min-height: 260px !important;
            max-height: 380px !important;
            display: block !important;
            transform: scaleX(1) !important;
            -webkit-transform: scaleX(1) !important;
        }
        #qr-reader img[alt="Info icon"],
        #qr-reader__header_message,
        #qr-reader__dashboard_section_csr,
        #qr-reader__dashboard_section_swaplink,
        #qr-reader__status_span,
        #qr-reader canvas {
            display: none !important;
        }
        #qr-reader__scan_region {
            border: none !important;
            background: transparent !important;
            width: 100% !important;
            min-height: 260px !important;
        }
        #qr-reader button {
            background-color: #BD2000 !important;
            color: white !important;
            border-radius: 0.75rem !important;
            padding: 0.6rem 1.2rem !important;
            font-weight: 800 !important;
            font-size: 0.8rem !important;
            border: none !important;
            cursor: pointer !important;
            box-shadow: 0 4px 6px -1px rgba(189, 32, 0, 0.2) !important;
            margin-top: 0.5rem !important;
        }
        #qr-reader select {
            background-color: #FAF8F5 !important;
            border: 1px solid #E7E5E4 !important;
            border-radius: 0.75rem !important;
            padding: 0.5rem 1rem !important;
            font-size: 0.8rem !important;
            font-weight: 700 !important;
            color: #1C1917 !important;
            margin-bottom: 0.5rem !important;
        }

        .scrollbar-none::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-none {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(35px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }
        .reveal-scale {
            transform: scale(0.93);
        }
        .reveal-on-scroll.is-visible {
            opacity: 1;
            transform: translate(0) scale(1);
        }
    </style>
</head>
<body class="antialiased overflow-x-hidden bg-[#FAF8F5] text-[#1C1917]">

    {{-- 1. NAVIGASI ATAS (HEADER) --}}
    <nav class="w-full bg-white border-b border-stone-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            
            {{-- Logo Merk --}}
            <a href="{{ route('landing') }}" class="flex items-center gap-2.5 sm:gap-3 group flex-shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" class="h-8 sm:h-9 w-auto object-contain flex-shrink-0 drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
                <span class="font-black text-xl sm:text-2xl tracking-tight text-[#8C0000] leading-none select-none flex-shrink-0">
                    Saji<span class="text-[#FFBE0F]">HUB</span>
                </span>
            </a>

            {{-- Menu Navigasi Tengah --}}
            <div class="hidden lg:flex items-center gap-8 text-sm font-bold tracking-wide">
                <a href="{{ route('landing') }}#beranda" class="nav-link text-stone-600 border-transparent py-1 border-b-2 hover:text-[#BD2000] transition-all">Beranda</a>
                <a href="{{ route('landing') }}#menu-terlaris" class="nav-link text-stone-600 border-transparent py-1 border-b-2 hover:text-[#BD2000] transition-all">Menu Terlaris</a>
                <a href="{{ route('menu.catalog') }}" class="nav-link text-[#BD2000] border-[#BD2000] py-1 border-b-2 font-black transition-all">Lihat Menu</a>
            </div>

            {{-- Tombol Aksi Kanan --}}
            <div class="flex items-center gap-1.5 sm:gap-3 flex-shrink-0">
                <button type="button" onclick="openQrScannerModal()" class="px-2.5 sm:px-4 py-2 sm:py-2.5 min-h-[44px] rounded-xl text-xs sm:text-sm font-bold text-[#BD2000] bg-white border border-[#BD2000] hover:bg-[#BD2000]/10 transition-all shadow-sm flex items-center justify-center gap-1.5 sm:gap-2 cursor-pointer flex-shrink-0">
                    <svg class="w-4 h-4 text-[#BD2000] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 116 0z"/>
                    </svg>
                    <span>Pindai QR<span class="hidden min-[400px]:inline"> Meja</span></span>
                </button>

                @auth
                    <div class="flex items-center gap-2 sm:gap-3 border-l border-stone-200 pl-2 sm:pl-4 flex-shrink-0">
                        <span class="text-sm font-bold text-stone-700 hidden sm:inline">Halo, <span class="text-[#BD2000] font-black">{{ auth()->user()->name }}</span></span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-3 sm:px-4 py-2 rounded-xl min-h-[44px] text-xs sm:text-sm font-bold text-stone-700 bg-stone-100 hover:bg-stone-200 transition-colors cursor-pointer flex items-center justify-center">
                                Keluar
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="bg-[#BD2000] text-white px-3.5 sm:px-6 py-2 sm:py-2.5 min-h-[44px] rounded-xl text-xs sm:text-base font-semibold hover:bg-[#8C0000] transition shadow-md inline-flex items-center justify-center flex-shrink-0">
                        Masuk
                    </a>
                @endauth
            </div>

        </div>
    </nav>

    {{-- 2. HERO HEADER KATALOG --}}
    <section class="w-full py-12 sm:py-16 bg-white border-b border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <div class="inline-flex items-center gap-1.5 text-xs font-bold text-[#BD2000] uppercase tracking-wider bg-[#BD2000]/10 px-3.5 py-1.5 rounded-full border border-[#BD2000]/20">
                <svg class="w-4 h-4 text-[#BD2000]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Katalog Menu Restoran
            </div>
            
            <h1 class="text-3xl sm:text-5xl font-black text-[#8C0000] tracking-tight">
                Daftar Menu SajiHUB
            </h1>

            <p class="text-slate-600 text-base sm:text-lg font-medium max-w-2xl mx-auto leading-relaxed">
                Jelajahi seluruh pilihan masakan khas Nusantara dan minuman segar otentik dari restoran kami.
            </p>
        </div>
    </section>

    {{-- 3. FILTER & PENCARIAN --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        @php
            $selectedBranch = $branches->firstWhere('id', request('branch_id'));
            $selectedBranchName = $selectedBranch ? $selectedBranch->name : 'Semua Cabang';

            $sortOptions = [
                'popular' => 'Terpopuler',
                'price_low' => 'Harga: Terendah',
                'price_high' => 'Harga: Tertinggi',
                'name' => 'Nama (A - Z)',
            ];
            $currentSort = request('sort', 'popular');
            $selectedSortName = $sortOptions[$currentSort] ?? 'Terpopuler';
        @endphp

        <form id="catalog-filter-form" method="GET" action="{{ route('menu.catalog') }}" class="bg-white border border-stone-200 rounded-3xl p-5 sm:p-6 shadow-sm space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                {{-- Input Pencarian --}}
                <div class="lg:col-span-2 relative">
                    <label class="block text-xs font-bold text-stone-500 uppercase mb-1.5">Cari Nama Menu</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nasi Goreng, Ayam Bakar, Es Teh..." 
                            class="w-full pl-10 pr-4 py-3 bg-stone-50 border border-stone-300 rounded-2xl text-sm font-semibold focus:outline-none focus:border-[#BD2000] transition-all">
                        <svg class="w-5 h-5 text-stone-400 absolute left-3.5 top-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Filter Cabang (Custom Popover Dropdown) --}}
                <div class="relative" id="dropdown-branch-wrapper">
                    <label class="block text-xs font-bold text-stone-500 uppercase mb-1.5">Cabang Restoran</label>
                    <input type="hidden" name="branch_id" id="filter-branch-id" value="{{ request('branch_id') }}">
                    <button type="button" 
                            id="dropdown-branch-btn"
                            onclick="toggleDropdownPopover('branch')"
                            aria-haspopup="listbox"
                            aria-expanded="false"
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-300 rounded-2xl text-sm font-semibold text-[#1C1917] flex items-center justify-between text-left hover:border-stone-400 focus:outline-none focus:border-[#BD2000] transition-all cursor-pointer">
                        <span class="truncate font-semibold text-stone-800" id="dropdown-branch-label">{{ $selectedBranchName }}</span>
                        <svg class="w-4 h-4 text-stone-400 transition-transform duration-200 shrink-0 ml-2" id="dropdown-branch-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    {{-- Popover Menu --}}
                    <div id="dropdown-branch-menu" 
                         class="hidden absolute top-full left-0 mt-1.5 w-full z-50 bg-white border border-slate-200 rounded-xl shadow-lg py-1 max-h-60 overflow-y-auto"
                         role="listbox">
                        <button type="button" 
                                onclick="selectFilterBranch('', 'Semua Cabang')"
                                class="w-full text-left px-4 py-2.5 text-xs sm:text-sm font-semibold flex items-center justify-between transition-colors cursor-pointer {{ !request('branch_id') ? 'bg-[#BD2000]/10 text-[#BD2000] font-black' : 'text-stone-700 hover:bg-stone-50' }}">
                            <span>Semua Cabang</span>
                            @if(!request('branch_id'))
                                <svg class="w-4 h-4 text-[#BD2000]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </button>
                        @foreach($branches as $b)
                            <button type="button" 
                                    onclick="selectFilterBranch('{{ $b->id }}', '{{ addslashes($b->name) }}')"
                                    class="w-full text-left px-4 py-2.5 text-xs sm:text-sm font-semibold flex items-center justify-between transition-colors cursor-pointer {{ request('branch_id') == $b->id ? 'bg-[#BD2000]/10 text-[#BD2000] font-black' : 'text-stone-700 hover:bg-stone-50' }}">
                                <span class="truncate">{{ $b->name }}</span>
                                @if(request('branch_id') == $b->id)
                                    <svg class="w-4 h-4 text-[#BD2000]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Filter Urutan (Custom Popover Dropdown) --}}
                <div class="relative" id="dropdown-sort-wrapper">
                    <label class="block text-xs font-bold text-stone-500 uppercase mb-1.5">Urutkan Berdasarkan</label>
                    <input type="hidden" name="sort" id="filter-sort" value="{{ $currentSort }}">
                    <button type="button" 
                            id="dropdown-sort-btn"
                            onclick="toggleDropdownPopover('sort')"
                            aria-haspopup="listbox"
                            aria-expanded="false"
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-300 rounded-2xl text-sm font-semibold text-[#1C1917] flex items-center justify-between text-left hover:border-stone-400 focus:outline-none focus:border-[#BD2000] transition-all cursor-pointer">
                        <span class="truncate font-semibold text-stone-800" id="dropdown-sort-label">{{ $selectedSortName }}</span>
                        <svg class="w-4 h-4 text-stone-400 transition-transform duration-200 shrink-0 ml-2" id="dropdown-sort-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    {{-- Popover Menu --}}
                    <div id="dropdown-sort-menu" 
                         class="hidden absolute top-full left-0 mt-1.5 w-full z-50 bg-white border border-slate-200 rounded-xl shadow-lg py-1 max-h-60 overflow-y-auto"
                         role="listbox">
                        @foreach($sortOptions as $key => $label)
                            <button type="button" 
                                    onclick="selectFilterSort('{{ $key }}', '{{ $label }}')"
                                    class="w-full text-left px-4 py-2.5 text-xs sm:text-sm font-semibold flex items-center justify-between transition-colors cursor-pointer {{ $currentSort === $key ? 'bg-[#BD2000]/10 text-[#BD2000] font-black' : 'text-stone-700 hover:bg-stone-50' }}">
                                <span>{{ $label }}</span>
                                @if($currentSort === $key)
                                    <svg class="w-4 h-4 text-[#BD2000]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- 3 Category Tabs Filter (Semua, Makanan, Minuman) --}}
            <div class="pt-4 border-t border-stone-100 flex items-center gap-3 overflow-x-auto scrollbar-none">
                
                {{-- Tab 1: Semua --}}
                @php $catType = request('category_type'); @endphp
                <a href="{{ route('menu.catalog', array_merge(request()->except('category_type'), ['category_type' => null])) }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-extrabold whitespace-nowrap transition-all border {{ !$catType ? 'bg-[#BD2000] text-white border-[#BD2000] shadow-sm' : 'bg-stone-100 text-stone-700 border-stone-200 hover:bg-stone-200' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    Semua
                </a>

                {{-- Tab 2: Makanan --}}
                <a href="{{ route('menu.catalog', array_merge(request()->except('category_type'), ['category_type' => 'makanan'])) }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-extrabold whitespace-nowrap transition-all border {{ $catType === 'makanan' ? 'bg-[#BD2000] text-white border-[#BD2000] shadow-sm' : 'bg-stone-100 text-stone-700 border-stone-200 hover:bg-stone-200' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Makanan
                </a>

                {{-- Tab 3: Minuman --}}
                <a href="{{ route('menu.catalog', array_merge(request()->except('category_type'), ['category_type' => 'minuman'])) }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-extrabold whitespace-nowrap transition-all border {{ $catType === 'minuman' ? 'bg-[#BD2000] text-white border-[#BD2000] shadow-sm' : 'bg-stone-100 text-stone-700 border-stone-200 hover:bg-stone-200' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.605 15.12a2 2 0 00-1.806.547M8 4h8l-1 8H9L8 4z"/>
                    </svg>
                    Minuman
                </a>

            </div>
        </form>
    </section>

    {{-- 4. DAFTAR KARTU MENU (KATALOG VIEW SAJA - TANPA TOMBOL PESAN) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-black text-[#8C0000]">
                Menampilkan {{ $menus->count() }} Menu
            </h2>
            @if(request()->anyFilled(['search', 'branch_id', 'category_type', 'sort']))
                <a href="{{ route('menu.catalog') }}" class="text-xs font-bold text-[#BD2000] hover:underline">
                    Atur Ulang Saringan
                </a>
            @endif
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
            @forelse($menus as $menu)
                <div class="bg-white border border-stone-200 rounded-2xl sm:rounded-3xl p-2.5 sm:p-5 shadow-xs sm:shadow-sm hover:shadow-xl hover:border-[#BD2000]/40 transition-all duration-300 flex flex-col justify-between group reveal-on-scroll reveal-scale">
                    <div>
                        {{-- Foto Makanan Kompak (Square di Mobile, 4/3 di Desktop) --}}
                        <div class="aspect-square sm:aspect-[4/3] rounded-xl sm:rounded-2xl overflow-hidden relative mb-2 sm:mb-4 bg-stone-100 border border-stone-200">
                            @if($menu->image_url)
                                <img src="{{ $menu->image_url }}" 
                                     alt="{{ $menu->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                     loading="lazy">
                            @else
                                <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80" 
                                     alt="{{ $menu->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                     loading="lazy">
                            @endif

                            {{-- Status Tag --}}
                            <div class="absolute top-2 left-2 sm:top-3 sm:left-3 z-10">
                                @if(($menu->status ?? 'available') === 'available')
                                    <span class="bg-emerald-600/95 backdrop-blur-2xs text-white px-2 py-0.5 sm:px-2.5 sm:py-1 text-[9px] sm:text-[10px] font-black rounded-full shadow-xs sm:shadow-md">
                                        Tersedia
                                    </span>
                                @else
                                    <span class="bg-red-600/95 backdrop-blur-2xs text-white px-2 py-0.5 sm:px-2.5 sm:py-1 text-[9px] sm:text-[10px] font-black rounded-full shadow-xs sm:shadow-md">
                                        Habis
                                    </span>
                                @endif
                            </div>

                            {{-- Branch Tag --}}
                            @if($menu->branch)
                                <div class="absolute bottom-2 right-2 sm:bottom-3 sm:right-3 z-10">
                                    <span class="bg-white/95 backdrop-blur-xs text-stone-800 px-1.5 py-0.5 sm:px-2.5 sm:py-1 text-[9px] sm:text-[10px] font-extrabold rounded-md sm:rounded-lg border border-stone-200 shadow-xs flex items-center gap-1 max-w-[85px] sm:max-w-none truncate">
                                        <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-[#BD2000] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span class="truncate">{{ $menu->branch->name }}</span>
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Kategori & Judul --}}
                        <div class="space-y-0.5 sm:space-y-1">
                            <span class="text-[9px] sm:text-[11px] font-bold text-[#BD2000] uppercase tracking-wider block truncate">
                                {{ $menu->category->name ?? 'Kuliner Nusantara' }}
                            </span>
                            <h3 class="font-bold sm:font-black text-xs sm:text-base lg:text-lg text-[#1C1917] leading-tight sm:leading-snug line-clamp-2 group-hover:text-[#BD2000] transition-colors mt-0.5 sm:mt-1">
                                {{ $menu->name }}
                            </h3>
                            <p class="hidden sm:block text-xs text-slate-500 line-clamp-2 mt-1 leading-relaxed font-medium">
                                {{ $menu->description ?? 'Olahan masakan segar pilihan khas SajiHUB dengan rasa bumbu rempah otentik meresap sempurna.' }}
                            </p>
                        </div>
                    </div>

                    {{-- Harga Menu --}}
                    <div class="pt-2 sm:pt-4 mt-2 sm:mt-4 border-t border-stone-100 flex items-center justify-between">
                        <div>
                            <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase hidden sm:block">Harga</span>
                            <span class="font-extrabold sm:font-black text-xs sm:text-lg lg:text-xl text-[#BD2000]">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white border border-stone-200 rounded-3xl p-8">
                    <div class="w-16 h-16 bg-stone-100 text-[#BD2000] rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-[#BD2000]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-black text-[#1C1917]">Tidak Ada Menu Ditemukan</h3>
                    <p class="text-slate-500 text-sm font-medium mt-1">Coba ubah kata kunci pencarian atau atur ulang saringan kategori & cabang.</p>
                    <a href="{{ route('menu.catalog') }}" class="inline-block mt-4 px-5 py-2.5 bg-[#BD2000] text-white font-extrabold text-xs rounded-xl hover:bg-[#8C0000] transition-colors">
                        Tampilkan Semua Menu
                    </a>
                </div>
            @endforelse
        </div>
    </section>

    {{-- 5. FOOTER (KAKI HALAMAN - WADAH MARUN GELAP #8C0000) --}}
    <footer class="w-full bg-[#8C0000] text-stone-100 py-10 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <div class="flex items-center justify-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" class="h-10 w-10 object-contain drop-shadow-sm">
                <span class="font-black text-2xl tracking-tight text-white">Saji<span class="text-[#FFBE0F]">HUB</span></span>
            </div>
            <p class="text-stone-200 text-xs sm:text-sm font-medium max-w-md mx-auto leading-relaxed">
                Restoran & Kuliner Otentik Nusantara. Menghidangkan rasa otentik dengan pelayanan terbaik.
            </p>
            <div class="text-xs text-stone-300 pt-4 border-t border-white/10 font-medium">
                &copy; {{ date('Y') }} SajiHUB Resto. Seluruh Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>

    {{-- MODAL SCANNER QR CODE --}}
    <div id="qr-scanner-modal" class="fixed inset-0 bg-stone-900/80 backdrop-blur-sm z-[9999] opacity-0 pointer-events-none transition-all duration-300 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl relative border border-stone-200 animate-fade-in-up space-y-4 text-center">
            <div class="flex justify-between items-center border-b border-stone-100 pb-3">
                <h3 class="text-base font-black text-[#8C0000] uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#BD2000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Pindai Kode QR Meja</span>
                </h3>
                <button type="button" onclick="closeQrScannerModal()" class="w-8 h-8 rounded-full bg-stone-100 text-stone-500 hover:text-stone-800 hover:bg-stone-200 flex items-center justify-center transition-colors cursor-pointer">
                    ✕
                </button>
            </div>

            <p class="text-xs text-slate-600 font-medium leading-relaxed">
                Arahkan kamera ke stiker kode QR yang menempel di meja makan Anda.
            </p>

            {{-- Camera Controls Bar (Flip Front / Back) --}}
            <div class="flex items-center justify-between bg-stone-50 px-3.5 py-2 rounded-2xl border border-stone-200">
                <span class="text-[11px] font-bold text-stone-600 flex items-center gap-1.5" id="customer-cam-label">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span id="customer-cam-mode-text">Kamera Depan</span>
                </span>
                <button type="button" onclick="toggleCustomerCameraFacing()" class="px-2.5 py-1 rounded-xl bg-white hover:bg-stone-100 text-stone-800 text-[11px] font-black transition-all border border-stone-300 flex items-center gap-1.5 cursor-pointer shadow-xs active:scale-95">
                    <svg class="w-3.5 h-3.5 text-[#BD2000]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Ganti Kamera</span>
                </button>
            </div>

            {{-- Camera Container with Viewfinder --}}
            <div id="qr-reader-container" class="w-full bg-stone-950 rounded-2xl p-2 border border-stone-300/80 flex flex-col items-center justify-center relative overflow-hidden shadow-inner" style="min-height: 270px; background-color: #0c0a09;">
                <div id="qr-reader" class="w-full rounded-xl overflow-hidden" style="width: 100%; min-height: 260px;"></div>
                
                {{-- Glowing Viewfinder Target Box Overlay --}}
                <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                    <div class="w-48 h-48 border-2 border-[#BD2000]/80 rounded-2xl relative shadow-[0_0_25px_rgba(189,32,0,0.4)]">
                        <div class="absolute -top-1 -left-1 w-5 h-5 border-t-4 border-l-4 border-[#FFBE0F] rounded-tl-lg"></div>
                        <div class="absolute -top-1 -right-1 w-5 h-5 border-t-4 border-r-4 border-[#FFBE0F] rounded-tr-lg"></div>
                        <div class="absolute -bottom-1 -left-1 w-5 h-5 border-b-4 border-l-4 border-[#FFBE0F] rounded-bl-lg"></div>
                        <div class="absolute -bottom-1 -right-1 w-5 h-5 border-b-4 border-r-4 border-[#FFBE0F] rounded-br-lg"></div>
                    </div>
                </div>
            </div>

            {{-- Close Button --}}
            <div class="pt-1">
                <button type="button" onclick="closeQrScannerModal()" class="w-full py-3.5 bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold text-xs uppercase tracking-wider rounded-2xl transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>Tutup Kamera</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        let html5QrcodeScanner = null;
        let isScanLocked = false;

        function playSuccessBeep() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(880, audioCtx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(1760, audioCtx.currentTime + 0.12);
                gain.gain.setValueAtTime(0.25, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.12);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.13);
            } catch(e) {}
        }

        function playErrorBeep() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(250, audioCtx.currentTime);
                osc.frequency.setValueAtTime(180, audioCtx.currentTime + 0.15);
                gain.gain.setValueAtTime(0.25, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.3);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.31);
            } catch(e) {}
        }

        const isMobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        // Default to 'user' on laptop/PC, 'environment' on smartphone
        let currentFacingMode = isMobileDevice ? "environment" : "user";

        function updateCustomerCamLabel() {
            const el = document.getElementById('customer-cam-mode-text');
            if (el) {
                el.innerText = currentFacingMode === "user" ? "Kamera Depan" : "Kamera Belakang";
            }
        }

        async function toggleCustomerCameraFacing() {
            currentFacingMode = currentFacingMode === "user" ? "environment" : "user";
            updateCustomerCamLabel();

            if (html5QrcodeScanner && html5QrcodeScanner.isScanning) {
                try {
                    await html5QrcodeScanner.stop();
                    html5QrcodeScanner.clear();
                } catch(e) {}
                startCustomerCamera();
            }
        }

        function openQrScannerModal() {
            const modal = document.getElementById('qr-scanner-modal');
            modal.classList.remove('opacity-0', 'pointer-events-none', 'hidden');
            modal.classList.add('opacity-100');
            isScanLocked = false;
            updateCustomerCamLabel();

            setTimeout(() => {
                startCustomerCamera();
            }, 150);
        }

        function startCustomerCamera() {
            updateCustomerCamLabel();

            if (!html5QrcodeScanner) {
                html5QrcodeScanner = new Html5Qrcode("qr-reader", {
                    verbose: false
                });
            }

            const scanConfig = {
                fps: 20,
                qrbox: function(viewfinderWidth, viewfinderHeight) {
                    const edge = Math.min(viewfinderWidth, viewfinderHeight);
                    const boxSize = Math.max(Math.floor(edge * 0.85), 200);
                    return { width: boxSize, height: boxSize };
                },
                aspectRatio: 1.0
            };

            const cameraConfig = {
                facingMode: currentFacingMode
            };

            html5QrcodeScanner.start(
                cameraConfig,
                scanConfig,
                onScanSuccess,
                (errorMessage) => {}
            ).then(() => {
                const videoEl = document.querySelector('#qr-reader video');
                if (videoEl) {
                    videoEl.style.width = '100%';
                    videoEl.style.height = '100%';
                    videoEl.style.minHeight = '260px';
                    videoEl.style.objectFit = 'cover';
                    videoEl.style.display = 'block';
                    videoEl.style.transform = 'scaleX(1)';
                    videoEl.style.webkitTransform = 'scaleX(1)';
                }
            }).catch(err => {
                console.warn("Primary camera start failed for", currentFacingMode, err);
                const fallbackMode = currentFacingMode === "user" ? "environment" : "user";
                html5QrcodeScanner.start(
                    { facingMode: fallbackMode },
                    scanConfig,
                    onScanSuccess,
                    () => {}
                ).then(() => {
                    currentFacingMode = fallbackMode;
                    updateCustomerCamLabel();
                    const videoEl = document.querySelector('#qr-reader video');
                    if (videoEl) {
                        videoEl.style.width = '100%';
                        videoEl.style.height = '100%';
                        videoEl.style.minHeight = '260px';
                        videoEl.style.objectFit = 'cover';
                        videoEl.style.display = 'block';
                        videoEl.style.transform = 'scaleX(1)';
                        videoEl.style.webkitTransform = 'scaleX(1)';
                    }
                }).catch(e => {
                    // Final fallback: unconstrained any camera device
                    html5QrcodeScanner.start(
                        {},
                        scanConfig,
                        onScanSuccess,
                        () => {}
                    ).then(() => {
                        const videoEl = document.querySelector('#qr-reader video');
                        if (videoEl) {
                            videoEl.style.width = '100%';
                            videoEl.style.height = '100%';
                            videoEl.style.minHeight = '260px';
                            videoEl.style.objectFit = 'cover';
                            videoEl.style.display = 'block';
                            videoEl.style.transform = 'scaleX(1)';
                            videoEl.style.webkitTransform = 'scaleX(1)';
                        }
                    }).catch(e2 => {
                        console.error("Camera error:", e2);
                        Swal.fire({
                            icon: 'warning',
                            title: 'Kamera Tidak Dapat Dibuka',
                            text: 'Pastikan Anda telah memberikan izin akses kamera pada browser.',
                            confirmButtonColor: '#BD2000'
                        });
                        closeQrScannerModal();
                    });
                });
            });
        }

        function onScanSuccess(decodedText) {
            if (isScanLocked) return;
            isScanLocked = true;

            if (html5QrcodeScanner && html5QrcodeScanner.isScanning) {
                try { html5QrcodeScanner.pause(true); } catch(e) {}
            }

            const text = (decodedText || '').trim();

            // 1. Format resmi SajiHub: SAJIHUB-TABLE-{branch_id}-{tableIdentifier}
            if (text.startsWith('SAJIHUB-TABLE-')) {
                const parts = text.split('-');
                if (parts.length >= 4) {
                    const branchId = parts[2];
                    const tableIdent = parts.slice(3).join('-');
                    playSuccessBeep();
                    closeQrScannerModal();
                    window.location.href = `{{ url('/pesan') }}?branch_id=${encodeURIComponent(branchId)}&table=${encodeURIComponent(tableIdent)}`;
                    return;
                }
            }

            // 2. URL internal SajiHub (/pesan atau /order)
            if (text.includes('/pesan') || text.includes('/order')) {
                try {
                    const urlObj = new URL(text, window.location.origin);
                    if (urlObj.searchParams.has('branch_id') || urlObj.searchParams.has('table')) {
                        playSuccessBeep();
                        closeQrScannerModal();
                        window.location.href = urlObj.href;
                        return;
                    }
                } catch(e) {}
            }

            // 3. QR tidak dikenali / bukan QR SajiHub
            playErrorBeep();
            Swal.fire({
                icon: 'error',
                title: 'QR Code Tidak Dikenali',
                text: 'Kode QR ini bukan QR Meja resmi SajiHUB. Silakan scan stiker QR yang terpasang di meja Anda.',
                confirmButtonColor: '#BD2000',
                confirmButtonText: 'Coba Lagi',
                customClass: { popup: 'rounded-3xl shadow-2xl font-sans' }
            }).then(() => {
                isScanLocked = false;
                if (html5QrcodeScanner && html5QrcodeScanner.isScanning) {
                    try { html5QrcodeScanner.resume(); } catch(e) {}
                }
            });
        }

        function closeQrScannerModal() {
            isScanLocked = false;
            const modal = document.getElementById('qr-scanner-modal');
            if (modal) {
                modal.classList.remove('opacity-100');
                modal.classList.add('opacity-0', 'pointer-events-none');
            }
            if (html5QrcodeScanner && html5QrcodeScanner.isScanning) {
                html5QrcodeScanner.stop().then(() => {
                    html5QrcodeScanner.clear();
                    html5QrcodeScanner = null;
                }).catch(() => {
                    html5QrcodeScanner = null;
                });
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const observerOptions = {
                root: null,
                rootMargin: '0px 0px -50px 0px',
                threshold: 0.08
            };

            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    } else {
                        entry.target.classList.remove('is-visible');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.reveal-on-scroll').forEach(el => revealObserver.observe(el));
        });

        // Custom Dropdown Popover Filters
        function toggleDropdownPopover(type) {
            const branchMenu = document.getElementById('dropdown-branch-menu');
            const branchChevron = document.getElementById('dropdown-branch-chevron');
            const sortMenu = document.getElementById('dropdown-sort-menu');
            const sortChevron = document.getElementById('dropdown-sort-chevron');

            if (type === 'branch') {
                if (sortMenu) {
                    sortMenu.classList.add('hidden');
                    if (sortChevron) sortChevron.classList.remove('rotate-180');
                }
                if (branchMenu) {
                    const isClosed = branchMenu.classList.contains('hidden');
                    if (isClosed) {
                        branchMenu.classList.remove('hidden');
                        if (branchChevron) branchChevron.classList.add('rotate-180');
                    } else {
                        branchMenu.classList.add('hidden');
                        if (branchChevron) branchChevron.classList.remove('rotate-180');
                    }
                }
            } else if (type === 'sort') {
                if (branchMenu) {
                    branchMenu.classList.add('hidden');
                    if (branchChevron) branchChevron.classList.remove('rotate-180');
                }
                if (sortMenu) {
                    const isClosed = sortMenu.classList.contains('hidden');
                    if (isClosed) {
                        sortMenu.classList.remove('hidden');
                        if (sortChevron) sortChevron.classList.add('rotate-180');
                    } else {
                        sortMenu.classList.add('hidden');
                        if (sortChevron) sortChevron.classList.remove('rotate-180');
                    }
                }
            }
        }

        function selectFilterBranch(branchId, branchName) {
            const input = document.getElementById('filter-branch-id');
            const label = document.getElementById('dropdown-branch-label');
            const menu = document.getElementById('dropdown-branch-menu');
            const chevron = document.getElementById('dropdown-branch-chevron');
            if (input) input.value = branchId;
            if (label) label.innerText = branchName;
            if (menu) menu.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180');
            document.getElementById('catalog-filter-form').submit();
        }

        function selectFilterSort(sortKey, sortName) {
            const input = document.getElementById('filter-sort');
            const label = document.getElementById('dropdown-sort-label');
            const menu = document.getElementById('dropdown-sort-menu');
            const chevron = document.getElementById('dropdown-sort-chevron');
            if (input) input.value = sortKey;
            if (label) label.innerText = sortName;
            if (menu) menu.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180');
            document.getElementById('catalog-filter-form').submit();
        }

        document.addEventListener('click', function(e) {
            const branchWrapper = document.getElementById('dropdown-branch-wrapper');
            const sortWrapper = document.getElementById('dropdown-sort-wrapper');
            
            if (branchWrapper && !branchWrapper.contains(e.target)) {
                const menu = document.getElementById('dropdown-branch-menu');
                const chevron = document.getElementById('dropdown-branch-chevron');
                if (menu && !menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                    if (chevron) chevron.classList.remove('rotate-180');
                }
            }
            if (sortWrapper && !sortWrapper.contains(e.target)) {
                const menu = document.getElementById('dropdown-sort-menu');
                const chevron = document.getElementById('dropdown-sort-chevron');
                if (menu && !menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                    if (chevron) chevron.classList.remove('rotate-180');
                }
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const branchMenu = document.getElementById('dropdown-branch-menu');
                const branchChevron = document.getElementById('dropdown-branch-chevron');
                const sortMenu = document.getElementById('dropdown-sort-menu');
                const sortChevron = document.getElementById('dropdown-sort-chevron');
                if (branchMenu) branchMenu.classList.add('hidden');
                if (branchChevron) branchChevron.classList.remove('rotate-180');
                if (sortMenu) sortMenu.classList.add('hidden');
                if (sortChevron) sortChevron.classList.remove('rotate-180');
            }
        });
    </script>
</body>
</html>
