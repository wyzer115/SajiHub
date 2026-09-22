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
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #FAF8F5;
            color: #1C1917;
        }
        /* QR Code Scanner Custom Styles */
        #qr-reader {
            border: none !important;
            background: transparent !important;
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
        }
        #qr-reader__scan_region video {
            border-radius: 1rem !important;
            object-fit: cover !important;
            width: 100% !important;
            max-height: 320px !important;
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            {{-- Logo Merk --}}
            <a href="{{ route('landing') }}" class="flex items-center group">
                <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" class="h-10 sm:h-11 w-auto object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
            </a>

            {{-- Menu Navigasi Tengah --}}
            <div class="hidden lg:flex items-center gap-8 text-sm font-bold tracking-wide">
                <a href="{{ route('landing') }}#beranda" class="nav-link text-stone-600 border-transparent py-1 border-b-2 hover:text-[#BD2000] transition-all">Beranda</a>
                <a href="{{ route('landing') }}#menu-terlaris" class="nav-link text-stone-600 border-transparent py-1 border-b-2 hover:text-[#BD2000] transition-all">Menu Terlaris</a>
                <a href="{{ route('landing') }}#testimoni" class="nav-link text-stone-600 border-transparent py-1 border-b-2 hover:text-[#BD2000] transition-all">Testimoni</a>
                <a href="{{ route('menu.catalog') }}" class="nav-link text-[#BD2000] border-[#BD2000] py-1 border-b-2 font-black transition-all">Lihat Menu</a>
            </div>

            {{-- Tombol Aksi Kanan --}}
            <div class="flex items-center gap-3">
                <button type="button" onclick="openQrScannerModal()" class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-[#BD2000] bg-white border border-[#BD2000] hover:bg-[#BD2000]/10 transition-all shadow-sm flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-[#BD2000]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 116 0z"/>
                    </svg>
                    Pindai QR Meja
                </button>

                @auth
                    <div class="flex items-center gap-3 border-l border-stone-200 pl-4">
                        <span class="text-sm font-bold text-stone-700 hidden sm:inline">Halo, <span class="text-[#BD2000] font-black">{{ auth()->user()->name }}</span></span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-stone-700 bg-stone-100 hover:bg-stone-200 transition-colors cursor-pointer">
                                Keluar
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="bg-[#BD2000] text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-[#8C0000] transition shadow-md inline-block">
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
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form method="GET" action="{{ route('menu.catalog') }}" class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm space-y-6">
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

                {{-- Filter Cabang --}}
                <div>
                    <label class="block text-xs font-bold text-stone-500 uppercase mb-1.5">Cabang Restoran</label>
                    <select name="branch_id" onchange="this.form.submit()" class="w-full px-4 py-3 bg-stone-50 border border-stone-300 rounded-2xl text-sm font-semibold text-[#1C1917] focus:outline-none focus:border-[#BD2000] transition-all">
                        <option value="">Semua Cabang</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Urutan --}}
                <div>
                    <label class="block text-xs font-bold text-stone-500 uppercase mb-1.5">Urutkan Berdasarkan</label>
                    <select name="sort" onchange="this.form.submit()" class="w-full px-4 py-3 bg-stone-50 border border-stone-300 rounded-2xl text-sm font-semibold text-[#1C1917] focus:outline-none focus:border-[#BD2000] transition-all">
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga: Terendah</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga: Tertinggi</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama (A - Z)</option>
                    </select>
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
                    Reset Filter
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($menus as $menu)
                <div class="bg-white border border-stone-200 rounded-3xl p-5 shadow-sm hover:shadow-xl hover:border-[#BD2000]/40 transition-all duration-300 flex flex-col justify-between group reveal-on-scroll reveal-scale">
                    <div>
                        {{-- Foto Makanan --}}
                        <div class="aspect-[4/3] rounded-2xl overflow-hidden relative mb-4 bg-stone-100 border border-stone-200">
                            @if($menu->image_url || $menu->image)
                                <img src="{{ $menu->image_url ?? asset('storage/' . $menu->image) }}" 
                                     alt="{{ $menu->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80" 
                                     alt="{{ $menu->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @endif

                            {{-- Status Tag --}}
                            <div class="absolute top-3 left-3 z-10">
                                @if(($menu->status ?? 'available') === 'available')
                                    <span class="bg-emerald-600 text-white px-2.5 py-1 text-[10px] font-black rounded-full shadow-md">
                                        Tersedia
                                    </span>
                                @else
                                    <span class="bg-red-600 text-white px-2.5 py-1 text-[10px] font-black rounded-full shadow-md">
                                        Habis
                                    </span>
                                @endif
                            </div>

                            {{-- Branch Tag --}}
                            @if($menu->branch)
                                <div class="absolute bottom-3 right-3 z-10">
                                    <span class="bg-white/90 backdrop-blur-xs text-stone-800 px-2.5 py-1 text-[10px] font-extrabold rounded-lg border border-stone-200 shadow-xs flex items-center gap-1">
                                        <svg class="w-3 h-3 text-[#BD2000]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $menu->branch->name }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Kategori & Judul --}}
                        <div class="space-y-1">
                            <span class="text-[11px] font-bold text-[#BD2000] uppercase tracking-wider">
                                {{ $menu->category->name ?? 'Kuliner Nusantara' }}
                            </span>
                            <h3 class="font-black text-lg text-[#1C1917] leading-snug group-hover:text-[#BD2000] transition-colors">
                                {{ $menu->name }}
                            </h3>
                            <p class="text-xs text-slate-500 line-clamp-2 mt-1 leading-relaxed font-medium">
                                {{ $menu->description ?? 'Olahan masakan segar pilihan khas SajiHUB dengan rasa bumbu rempah otentik meresap sempurna.' }}
                            </p>
                        </div>
                    </div>

                    {{-- Harga Menu --}}
                    <div class="pt-4 mt-4 border-t border-stone-100 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Harga</span>
                            <span class="font-black text-xl text-[#BD2000]">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
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
                    <p class="text-slate-500 text-sm font-medium mt-1">Coba ubah kata kunci pencarian atau reset filter kategori & cabang.</p>
                    <a href="{{ route('menu.catalog') }}" class="inline-block mt-4 px-5 py-2.5 bg-[#BD2000] text-white font-extrabold text-xs rounded-xl hover:bg-[#8C0000] transition-colors">
                        Tampilkan Semua Menu
                    </a>
                </div>
            @endforelse
        </div>
    </section>

    {{-- 5. FOOTER --}}
    <footer class="w-full bg-[#1C1917] text-white py-12 border-t border-stone-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <div class="flex items-center justify-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" class="h-10 w-10 object-contain">
                <span class="font-black text-xl tracking-tight text-white">Saji<span class="text-[#BD2000]">HUB</span></span>
            </div>
            <p class="text-stone-400 text-xs font-medium max-w-md mx-auto">
                Restoran & Kuliner Otentik Nusantara. Menghidangkan rasa otentik dengan pelayanan terbaik.
            </p>
            <div class="text-xs text-stone-500 pt-4 border-t border-stone-800">
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
                Arahkan kamera ponsel Anda ke stiker kode QR yang menempel di meja makan.
            </p>

            {{-- Camera Container with Viewfinder --}}
            <div id="qr-reader-container" class="w-full bg-stone-950 rounded-2xl p-2 border border-stone-300/80 min-h-[260px] flex flex-col items-center justify-center relative overflow-hidden shadow-inner">
                <div id="qr-reader" class="w-full rounded-xl overflow-hidden"></div>
                
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

        function openQrScannerModal() {
            const modal = document.getElementById('qr-scanner-modal');
            modal.classList.remove('opacity-0', 'pointer-events-none', 'hidden');
            modal.classList.add('opacity-100');

            if (!html5QrcodeScanner) {
                html5QrcodeScanner = new Html5Qrcode("qr-reader");
            }
            const config = { fps: 15 };

            html5QrcodeScanner.start(
                { facingMode: "environment" },
                config,
                (decodedText) => {
                    closeQrScannerModal();
                    window.location.href = decodedText;
                },
                (errorMessage) => {}
            ).catch(err => {
                html5QrcodeScanner.start({ facingMode: "user" }, config, (decodedText) => {
                    closeQrScannerModal();
                    window.location.href = decodedText;
                }, () => {}).catch(e => console.log(e));
            });
        }

        function closeQrScannerModal() {
            const modal = document.getElementById('qr-scanner-modal');
            if (modal) {
                modal.classList.remove('opacity-100');
                modal.classList.add('opacity-0', 'pointer-events-none');
            }
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    console.log("Camera stopped.");
                }).catch(() => {});
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
    </script>
</body>
</html>
