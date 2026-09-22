<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>SajiHUB — Restoran & Kuliner Otentik Nusantara</title>
    <meta name="description" content="SajiHUB — Nikmati sajian kuliner khas Nusantara dengan bumbu meresap, bahan segar, dan pelayanan cepat di berbagai cabang restoran kami.">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #FAF8F5;
            color: #1C1917;
        }
        .scrollbar-none::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-none {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Scroll Reveal Animations */
        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(35px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }
        .reveal-left {
            transform: translateX(-40px);
        }
        .reveal-right {
            transform: translateX(40px);
        }
        .reveal-scale {
            transform: scale(0.93);
        }
        .reveal-on-scroll.is-visible {
            opacity: 1;
            transform: translate(0) scale(1);
        }
        .delay-100 { transition-delay: 0.1s; }
        .delay-200 { transition-delay: 0.2s; }
        .delay-300 { transition-delay: 0.3s; }

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
    </style>
</head>
<body class="antialiased overflow-x-hidden bg-[#FAF8F5] text-[#1C1917]">

    {{-- 1. NAVIGASI ATAS (HEADER) --}}
    <nav class="w-full bg-white border-b border-stone-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            {{-- Logo Merk --}}
            <a href="#beranda" class="flex items-center group">
                <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" class="h-10 sm:h-11 w-auto object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
            </a>

            {{-- Menu Navigasi Tengah --}}
            <div class="hidden lg:flex items-center gap-8 text-sm font-bold tracking-wide">
                <a href="#beranda" class="nav-link text-[#BD2000] border-[#BD2000] py-1 border-b-2 transition-all">Beranda</a>
                <a href="#menu-terlaris" class="nav-link text-stone-600 border-transparent py-1 border-b-2 hover:text-[#BD2000] transition-all">Menu Terlaris</a>
                <a href="#testimoni" class="nav-link text-stone-600 border-transparent py-1 border-b-2 hover:text-[#BD2000] transition-all">Testimoni</a>
                <a href="{{ route('menu.catalog') }}" class="nav-link text-stone-600 border-transparent py-1 border-b-2 hover:text-[#BD2000] transition-all">Lihat Menu</a>
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

    {{-- 2. BAGIAN UTAMA (HERO SECTION - 2 KOLOM SEIMBANG) --}}
    <section id="beranda" class="w-full bg-[#FAF8F5]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center py-16 w-full">
                
                {{-- Sisi Kiri (Teks) --}}
                <div class="w-full text-left reveal-on-scroll reveal-left">
                    <div class="inline-flex items-center gap-2 bg-[#BD2000]/10 border border-[#BD2000]/20 text-[#BD2000] px-3.5 py-1.5 rounded-full text-xs font-bold mb-4">
                        <svg class="w-4 h-4 text-[#BD2000]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Restoran Kuliner Otentik & Pelayanan Cepat</span>
                    </div>

                    <h1 class="text-4xl lg:text-5xl font-extrabold text-[#8C0000] leading-tight">
                        Nikmati Kelezatan Kuliner Otentik SajiHUB
                    </h1>

                    <p class="text-slate-600 text-lg leading-relaxed mt-4 font-medium">
                        Sajikan kehangatan cita rasa bumbu rempah pilihan khas Nusantara di meja makan Anda. Dibuat dari bahan baku segar berkualitas, dimasak higienis, dan disajikan dengan pelayanan prima super cepat.
                    </p>

                    <div class="flex flex-wrap gap-4 mt-8">
                        <button type="button" onclick="openOrderTypeModal()" class="px-7 py-3.5 rounded-xl bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold text-base transition-all shadow-lg hover:shadow-xl hover:scale-105 transform inline-flex items-center gap-2 cursor-pointer">
                            Pesan Sekarang
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>

                        <button type="button" onclick="openQrScannerModal()" class="px-6 py-3.5 rounded-xl bg-white border-2 border-stone-300 hover:border-[#BD2000] text-[#1C1917] hover:text-[#BD2000] font-bold text-base transition-all shadow-sm inline-flex items-center gap-2 cursor-pointer">
                            <svg class="w-5 h-5 text-[#BD2000]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 116 0z"/>
                            </svg>
                            Pindai QR Meja
                        </button>
                    </div>

                    {{-- Pembatas Garis Gradasi Statistik --}}
                    <div class="w-full h-[1.5px] bg-gradient-to-r from-transparent via-[#BD2000]/30 to-transparent mt-8 mb-6 reveal-on-scroll reveal-scale delay-150"></div>

                    {{-- Ringkasan Statistik --}}
                    <div class="grid grid-cols-3 gap-4 w-full reveal-on-scroll reveal-scale delay-200">
                        <div>
                            <div class="text-2xl lg:text-3xl font-black text-[#8C0000] flex items-center gap-1.5">
                                <svg class="w-6 h-6 text-[#FFBE0F] fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                <span>4.9/5</span>
                            </div>
                            <div class="text-xs sm:text-sm text-slate-600 font-bold mt-0.5">Rating Pelanggan</div>
                        </div>
                        <div>
                            <div class="text-2xl lg:text-3xl font-black text-[#8C0000]">100%</div>
                            <div class="text-xs sm:text-sm text-slate-600 font-bold mt-0.5">Bahan Rempah Segar</div>
                        </div>
                        <div>
                            <div class="text-2xl lg:text-3xl font-black text-[#8C0000]">Cepat</div>
                            <div class="text-xs sm:text-sm text-slate-600 font-bold mt-0.5">Sajikan Meja</div>
                        </div>
                    </div>
                </div>

                {{-- Sisi Kanan (Carousel 3 Menu Terlaris Interaktif) --}}
                @php
                    $heroTopMenus = \App\Models\Menu::with('category')
                        ->withSum('orderItems as count', 'quantity')
                        ->orderByDesc('count')
                        ->take(3)
                        ->get();

                    if ($heroTopMenus->isEmpty()) {
                        $heroTopMenus = \App\Models\Menu::with('category')->take(3)->get();
                    }

                    $fallbackImages = [
                        'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=1000&q=80',
                        'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?auto=format&fit=crop&w=1000&q=80',
                        'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=1000&q=80'
                    ];

                    $slidesData = $heroTopMenus->map(function($menu, $idx) use ($fallbackImages) {
                        $img = $menu->image ? asset('storage/' . $menu->image) : ($menu->image_url ?? $fallbackImages[$idx % 3]);
                        return [
                            'name' => $menu->name,
                            'category' => $menu->category->name ?? 'Kuliner Otentik',
                            'price' => 'Rp ' . number_format($menu->price, 0, ',', '.'),
                            'image' => $img,
                        ];
                    })->values()->all();

                    if (empty($slidesData)) {
                        $slidesData = [
                            ['name' => 'Ayam Bakar Madu', 'category' => 'Spesial Ayam', 'price' => 'Rp 42.000', 'image' => $fallbackImages[0]],
                            ['name' => 'Nasi Goreng Rempah', 'category' => 'Olahan Nasi', 'price' => 'Rp 35.000', 'image' => $fallbackImages[1]],
                            ['name' => 'Es Teh Manis Jumbo', 'category' => 'Minuman', 'price' => 'Rp 8.000', 'image' => $fallbackImages[2]],
                        ];
                    }
                @endphp

                <div class="w-full reveal-on-scroll reveal-right">
                    <div id="hero-carousel-container" class="w-full rounded-3xl overflow-hidden shadow-2xl border border-stone-200 relative bg-stone-900 group" style="height: 450px; min-height: 420px;">
                        
                        {{-- Render Slides directly in HTML for Instant Server-side Load --}}
                        @foreach($slidesData as $index => $slide)
                        <div class="hero-slide absolute inset-0 w-full h-full transition-all duration-700 ease-in-out" 
                             style="{{ $index === 0 ? 'opacity: 1; pointer-events: auto; transform: scale(1); z-index: 10;' : 'opacity: 0; pointer-events: none; transform: scale(0.95); z-index: 0;' }}" 
                             data-slide-index="{{ $index }}">
                            <img src="{{ $slide['image'] }}" alt="{{ $slide['name'] }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>

                            <div class="absolute bottom-6 left-6 right-6 bg-white/95 backdrop-blur-md p-4 rounded-2xl border border-stone-200 shadow-xl flex items-center justify-between z-10">
                                <div class="flex items-center gap-3 pr-2 min-w-0">
                                    <div class="w-10 h-10 rounded-2xl bg-[#BD2000] text-white flex items-center justify-center font-black text-sm shrink-0 shadow-md">
                                        #{{ $index + 1 }}
                                    </div>
                                    <div class="truncate">
                                        <div class="text-[10px] font-extrabold text-[#BD2000] uppercase tracking-wider flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-[#BD2000]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                                            <span>Menu Terlaris</span>
                                            <span>•</span>
                                            <span>{{ $slide['category'] }}</span>
                                        </div>
                                        <div class="text-base font-black text-[#1C1917] truncate">{{ $slide['name'] }}</div>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-xs font-bold text-slate-400 block uppercase text-[9px]">Harga</span>
                                    <span class="text-base font-black text-[#BD2000]">{{ $slide['price'] }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        {{-- Tombol Navigasi Kiri & Kanan (< >) --}}
                        <button type="button" onclick="prevHeroSlide()" title="Menu Sebelumya"
                                style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); z-index: 30;"
                                class="w-11 h-11 rounded-full bg-black/60 hover:bg-[#BD2000] text-white backdrop-blur-md border border-white/30 flex items-center justify-center transition-all shadow-lg cursor-pointer">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                        </button>

                        <button type="button" onclick="nextHeroSlide()" title="Menu Selanjutnya"
                                style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); z-index: 30;"
                                class="w-11 h-11 rounded-full bg-black/60 hover:bg-[#BD2000] text-white backdrop-blur-md border border-white/30 flex items-center justify-center transition-all shadow-lg cursor-pointer">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </button>

                        {{-- Slide Indicator Dots --}}
                        <div style="position: absolute; top: 20px; right: 20px; z-index: 30;" class="flex items-center gap-2 bg-black/60 backdrop-blur-md px-3 py-1.5 rounded-full border border-white/30">
                            @foreach($slidesData as $index => $slide)
                            <button type="button" onclick="goToHeroSlide({{ $index }})" 
                                    class="hero-dot h-2.5 rounded-full transition-all cursor-pointer" 
                                    style="{{ $index === 0 ? 'width: 24px; background-color: #BD2000;' : 'width: 10px; background-color: rgba(255, 255, 255, 0.6);' }}" 
                                    data-dot-index="{{ $index }}"></button>
                            @endforeach
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Pembatas Garis Gradasi Beranimasi --}}
    <div class="w-full bg-[#FAF8F5] py-2 flex items-center justify-center overflow-hidden reveal-on-scroll">
        <div class="w-3/4 max-w-4xl h-[1.5px] bg-gradient-to-r from-transparent via-[#BD2000]/35 to-transparent relative flex items-center justify-center">
            <div class="w-2.5 h-2.5 rounded-full bg-[#BD2000]/40 animate-ping absolute"></div>
            <div class="w-2 h-2 rounded-full bg-[#BD2000] relative"></div>
        </div>
    </div>

    {{-- 3. BAGIAN MENU TERLARIS (DYNAMIC TOP 3 MENU) --}}
    <section id="menu-terlaris" class="w-full py-16 sm:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            
            {{-- Header Judul Bagian --}}
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3 reveal-on-scroll">
                <div class="inline-flex items-center gap-1.5 text-xs font-bold text-[#BD2000] uppercase tracking-wider bg-[#BD2000]/10 px-3.5 py-1.5 rounded-full border border-[#BD2000]/20">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                    Rekomendasi Utama
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-[#8C0000] tracking-tight">
                    Menu Terlaris SajiHUB
                </h2>
                <p class="text-slate-600 text-base font-medium">
                    Pilihan paling favorit dan paling banyak dipesan oleh pelanggan setia kami setiap harinya.
                </p>
            </div>

            {{-- Ambil Top Menu Dinamis (3 Menu Terlaris) --}}
            @php
                $topMenus = \App\Models\Menu::with('category')
                    ->withSum('orderItems as count', 'quantity')
                    ->orderByDesc('count')
                    ->take(3)
                    ->get();

                if ($topMenus->isEmpty()) {
                    $topMenus = \App\Models\Menu::with('category')->take(3)->get();
                }
            @endphp

            {{-- Grid 3 Kolom Lapang (6 Menu Card) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full">
                @foreach($topMenus as $index => $menu)
                    <div class="bg-white border border-stone-200 rounded-2xl p-5 shadow-sm hover:shadow-xl hover:border-[#BD2000]/40 transition-all duration-300 flex flex-col justify-between group w-full reveal-on-scroll reveal-scale delay-{{ ($index + 1) * 100 }}">
                        
                        <div>
                            {{-- Container Foto Makanan (Rasio 4:3) --}}
                            <div class="aspect-[4/3] rounded-xl overflow-hidden relative mb-4 bg-stone-100 border border-stone-200 w-full">
                                @if($menu->image_url || $menu->image)
                                    <img src="{{ $menu->image_url ?? asset('storage/' . $menu->image) }}" 
                                         alt="{{ $menu->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80" 
                                         alt="{{ $menu->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @endif

                                {{-- Lencana Terlaris --}}
                                <div class="absolute top-3 left-3 z-10">
                                    <span class="bg-[#FA1E0E] text-white px-3 py-1 text-xs font-extrabold rounded-full shadow-md flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                                        <span>Terlaris</span>
                                    </span>
                                </div>
                            </div>

                            {{-- Informasi Menu --}}
                            <div class="space-y-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    {{ $menu->category->name ?? 'Kuliner Nusantara' }}
                                </span>
                                <h3 class="font-extrabold text-xl text-[#1C1917] group-hover:text-[#BD2000] transition-colors leading-snug">
                                    {{ $menu->name }}
                                </h3>
                                <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed font-medium">
                                    {{ $menu->description ?? 'Olahan masakan segar pilihan khas SajiHUB dengan rasa bumbu rempah otentik meresap sempurna.' }}
                                </p>
                            </div>
                        </div>

                        {{-- Harga & Tombol Pesan --}}
                        <div class="pt-5 mt-4 border-t border-stone-100 flex items-center justify-between gap-4 w-full">
                            <div>
                                <span class="text-[11px] font-bold text-slate-400 uppercase block">Harga Porsi</span>
                                <span class="font-black text-2xl text-[#BD2000]">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                            </div>

                            <button type="button" onclick="openOrderTypeModal()" class="px-5 py-2.5 rounded-xl bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold text-xs sm:text-sm transition-all shadow-md hover:shadow-lg flex items-center gap-1.5 cursor-pointer">
                                Pesan
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            </button>
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- Tombol Lihat Seluruh Menu (Redirect to /menu Catalog) --}}
            <div class="mt-12 text-center reveal-on-scroll reveal-scale">
                <a href="{{ route('menu.catalog') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-stone-100 hover:bg-[#BD2000] text-[#1C1917] hover:text-white font-extrabold text-sm transition-all border border-stone-300 hover:border-[#BD2000] shadow-sm cursor-pointer">
                    Lihat Seluruh Daftar Menu SajiHUB
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

        </div>
    </section>

    {{-- Pembatas Garis Gradasi Beranimasi --}}
    <div class="w-full bg-white py-2 flex items-center justify-center overflow-hidden reveal-on-scroll">
        <div class="w-3/4 max-w-4xl h-[1.5px] bg-gradient-to-r from-transparent via-[#BD2000]/35 to-transparent relative flex items-center justify-center">
            <div class="w-2.5 h-2.5 rounded-full bg-[#BD2000]/40 animate-ping absolute"></div>
            <div class="w-2 h-2 rounded-full bg-[#BD2000] relative"></div>
        </div>
    </div>

    {{-- 4. BAGIAN TESTIMONI / ULASAN PELANGGAN --}}
    <section id="testimoni" class="w-full py-16 sm:py-24 bg-[#FAF8F5]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            
            {{-- Header Judul --}}
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3 reveal-on-scroll">
                <div class="inline-flex items-center gap-1.5 text-xs font-bold text-[#BD2000] uppercase tracking-wider bg-[#BD2000]/10 px-3.5 py-1.5 rounded-full border border-[#BD2000]/20">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Ulasan Jujur Pelanggan
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-[#8C0000] tracking-tight">
                    Apa Kata Pelanggan Setia Kami?
                </h2>
                <p class="text-slate-600 text-base font-medium">
                    Ribuan pengalaman santap puas dari pecinta kuliner yang telah membuktikan cita rasa otentik SajiHUB.
                </p>
            </div>

            {{-- Grid Kartu Ulasan --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full">
                
                {{-- Kartu Ulasan 1 --}}
                <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between w-full reveal-on-scroll reveal-left delay-100">
                    <div>
                        <div class="text-[#FFBE0F] text-lg font-bold flex gap-1 mb-3">
                            ★★★★★
                        </div>
                        <p class="text-[#1C1917] text-sm leading-relaxed font-medium italic mb-6">
                            "Rasa bumbu rasanya pas banget di lidah, ayam bakarnya empuk dan bumbunya meresap sampai ke tulang. Pelayanannya cepat dan tempatnya sangat bersih!"
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-stone-100">
                        <div class="w-11 h-11 rounded-full bg-[#BD2000]/10 border border-[#BD2000]/20 flex items-center justify-center font-black text-[#BD2000] text-sm">
                            B
                        </div>
                        <div>
                            <div class="font-bold text-[#1C1917] text-sm">Bambang S.</div>
                            <div class="text-xs text-slate-500 font-semibold">Pelanggan Setia — Cabang Pusat</div>
                        </div>
                    </div>
                </div>

                {{-- Kartu Ulasan 2 --}}
                <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between w-full reveal-on-scroll reveal-scale delay-200">
                    <div>
                        <div class="text-[#FFBE0F] text-lg font-bold flex gap-1 mb-3">
                            ★★★★★
                        </div>
                        <p class="text-[#1C1917] text-sm leading-relaxed font-medium italic mb-6">
                            "Sistem scan QR meja nya praktis banget! Nggak perlu antre lama di kasir, tinggal pesan dari meja langsung diantar panas-panas. Sangat direkomendasikan."
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-stone-100">
                        <div class="w-11 h-11 rounded-full bg-[#BD2000]/10 border border-[#BD2000]/20 flex items-center justify-center font-black text-[#BD2000] text-sm">
                            R
                        </div>
                        <div>
                            <div class="font-bold text-[#1C1917] text-sm">Rina Rahmawati</div>
                            <div class="text-xs text-slate-500 font-semibold">Pengunjung Keluarga</div>
                        </div>
                    </div>
                </div>

                {{-- Kartu Ulasan 3 --}}
                <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between w-full reveal-on-scroll reveal-right delay-300">
                    <div>
                        <div class="text-[#FFBE0F] text-lg font-bold flex gap-1 mb-3">
                            ★★★★★
                        </div>
                        <p class="text-[#1C1917] text-sm leading-relaxed font-medium italic mb-6">
                            "Harga porsinya sangat bersahabat dibanding kualitas rasanya yang bintang lima. Nasi goreng rempahnya bikin nagih!"
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-stone-100">
                        <div class="w-11 h-11 rounded-full bg-[#BD2000]/10 border border-[#BD2000]/20 flex items-center justify-center font-black text-[#BD2000] text-sm">
                            A
                        </div>
                        <div>
                            <div class="font-bold text-[#1C1917] text-sm">Agung Pratama</div>
                            <div class="text-xs text-slate-500 font-semibold">Pecinta Kuliner Nusantara</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    {{-- Pembatas Garis Gradasi Emas-Marun --}}
    <div class="w-full bg-[#FAF8F5] py-2 flex items-center justify-center overflow-hidden reveal-on-scroll">
        <div class="w-3/4 max-w-4xl h-[1.5px] bg-gradient-to-r from-transparent via-[#FFBE0F]/50 to-transparent relative flex items-center justify-center">
            <div class="w-2.5 h-2.5 rounded-full bg-[#FFBE0F]/40 animate-ping absolute"></div>
            <div class="w-2 h-2 rounded-full bg-[#FFBE0F] relative"></div>
        </div>
    </div>

    {{-- 5. FOOTER (KAKI HALAMAN - WADAH MARUN GELAP #8C0000) --}}
    <footer class="w-full bg-[#8C0000] text-stone-100 pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-12 pb-12 border-b border-white/10 w-full reveal-on-scroll">
                
                {{-- Kolom 1: Tentang SajiHUB & Langganan Promo --}}
                <div class="lg:col-span-4 space-y-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" class="h-10 w-10 object-contain">
                        <span class="font-black text-2xl tracking-tight text-white">Saji<span class="text-[#FFBE0F]">HUB</span></span>
                    </div>
                    
                    <p class="text-stone-200 text-sm leading-relaxed font-medium">
                        SajiHUB berkomitmen menghadirkan pengalaman kuliner khas Nusantara terbaik dengan bumbu rempah otentik meresap, bahan segar, serta pelayanan prima di setiap restoran kami.
                    </p>

                    {{-- Form Langganan Email --}}
                    <div class="pt-2 space-y-2">
                        <label for="newsletter-email" class="block text-xs font-bold uppercase tracking-wider text-stone-200">
                            Langganan Promo Terbaru
                        </label>
                        <form onsubmit="event.preventDefault(); alert('Terima kasih telah berlangganan promo SajiHUB!');" class="flex gap-2">
                            <input type="email" id="newsletter-email" placeholder="Masukkan alamat email Anda" required
                                   class="w-full bg-white/10 border border-white/20 text-white placeholder-stone-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#FFBE0F]">
                            <button type="submit" class="bg-[#FFBE0F] hover:bg-[#e5ab0e] text-[#1C1917] font-black px-4 py-2.5 rounded-xl transition-all shadow-md text-sm cursor-pointer whitespace-nowrap">
                                Kirim
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Kolom 2: Navigasi Cepat --}}
                <div class="lg:col-span-2 space-y-4">
                    <h4 class="font-extrabold text-white text-base uppercase tracking-wider">Navigasi Cepat</h4>
                    <ul class="space-y-2.5 text-sm font-medium text-stone-200">
                        <li><a href="#beranda" class="hover:text-[#FFBE0F] transition-colors">Beranda</a></li>
                        <li><a href="#menu-terlaris" class="hover:text-[#FFBE0F] transition-colors">Menu Terlaris</a></li>
                        <li><a href="#testimoni" class="hover:text-[#FFBE0F] transition-colors">Testimoni</a></li>
                        <li><a href="{{ route('pesan') }}" class="hover:text-[#FFBE0F] transition-colors">Pesan Online</a></li>
                    </ul>
                </div>

                {{-- Kolom 3: Layanan Kontak --}}
                <div class="lg:col-span-3 space-y-4">
                    <h4 class="font-extrabold text-white text-base uppercase tracking-wider">Layanan Pelanggan</h4>
                    <ul class="space-y-2.5 text-sm font-medium text-stone-200">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#FFBE0F]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span>0813-9889-7488 (WhatsApp)</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#FFBE0F]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>@sajihub.resto (Instagram)</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#FFBE0F]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Setiap Hari (09.00 - 22.00 WIB)</span>
                        </li>
                    </ul>
                </div>

                {{-- Kolom 4: Alamat Resto --}}
                <div class="lg:col-span-3 space-y-4">
                    <h4 class="font-extrabold text-white text-base uppercase tracking-wider">Alamat Restoran</h4>
                    <p class="text-sm text-stone-200 leading-relaxed font-medium">
                        Jl. Raya Kuliner Nusantara No. 88, Jakarta Selatan, Indonesia.
                    </p>
                </div>

            </div>

            {{-- Copyright Text --}}
            <div class="pt-8 text-center text-xs text-stone-300 font-medium">
                © 2026 SajiHUB Resto. Seluruh Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>

    {{-- MODAL PILIH LAYANAN PEMESANAN --}}
    <div id="order-type-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">
        <div class="bg-white border border-stone-200 w-full max-w-3xl rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6 relative text-left">
            <div class="flex justify-between items-start border-b border-stone-200 pb-4">
                <div>
                    <h3 class="font-black text-xl text-[#8C0000]">Pilih Metode Pemesanan</h3>
                    <p class="text-xs sm:text-sm text-slate-600 font-medium mt-0.5">Silakan pilih opsi layanan pemesanan sesuai keinginan Anda.</p>
                </div>
                <button type="button" onclick="closeOrderTypeModal()" class="text-stone-400 hover:text-stone-700 bg-stone-100 p-2 rounded-full border border-stone-200 transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- Opsi 1: Makan di Tempat (Dine-In) --}}
                <div class="bg-stone-50 border border-stone-200 rounded-3xl p-6 flex flex-col justify-between space-y-4 hover:border-[#BD2000] hover:shadow-md transition-all group">
                    <div class="space-y-3">
                        <div class="p-3.5 bg-[#BD2000]/10 text-[#BD2000] rounded-2xl border border-[#BD2000]/20 w-max">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold uppercase tracking-wider text-[#BD2000] bg-[#BD2000]/10 px-3 py-1 rounded-full border border-[#BD2000]/20">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Makan di Tempat (Dine-In)
                        </span>
                        <h4 class="text-lg font-black text-[#1C1917]">Pesan dari Meja Makan</h4>
                        <p class="text-xs text-slate-600 font-medium leading-relaxed">
                            Silakan <strong>tempati meja kosong</strong> di restoran, kemudian <strong>pindai (scan) Kode QR</strong> yang tertera di meja Anda untuk langsung memilih menu & memesan dari smartphone.
                        </p>
                    </div>

                    <div class="pt-2">
                        <button type="button" onclick="openQrFromOrderType()" class="w-full bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold py-3 px-4 rounded-xl text-xs uppercase tracking-wider transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 116 0z"/>
                            </svg>
                            <span>Pindai QR Code Meja</span>
                        </button>
                    </div>
                </div>

                {{-- Opsi 2: Bawa Pulang (Takeaway) --}}
                <div class="bg-stone-50 border border-stone-200 rounded-3xl p-6 flex flex-col justify-between space-y-4 hover:border-amber-500 hover:shadow-md transition-all group">
                    <div class="space-y-3">
                        <div class="p-3.5 bg-amber-100 text-amber-700 rounded-2xl border border-amber-300 w-max">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold uppercase tracking-wider text-amber-800 bg-amber-100 px-3 py-1 rounded-full border border-amber-300">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            Bawa Pulang (Takeaway)
                        </span>
                        <h4 class="text-lg font-black text-[#1C1917]">Pesan Langsung di Kasir</h4>
                        <p class="text-xs text-slate-600 font-medium leading-relaxed">
                            Untuk pesanan bawa pulang / dibungkus, silakan <strong>langsung menuju ke area Kasir</strong> restoran kami. Petugas kasir kami siap mencatat & melayani pesanan Anda secara cepat.
                        </p>
                    </div>

                    <div class="pt-2 space-y-2">
                        <div class="w-full bg-amber-500 text-white font-extrabold py-3 px-4 rounded-xl text-xs uppercase tracking-wider text-center flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Silakan Pesan di Kasir</span>
                        </div>
                        <a href="{{ route('menu.catalog') }}" class="block text-center text-[11px] text-[#BD2000] hover:underline font-extrabold">
                            Lihat Menu & Harga →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL KAMERA SCANNER QR MEJA --}}
    <div id="qr-scanner-modal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/80 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-300">
        <div class="bg-white border border-stone-200/80 w-full max-w-md rounded-3xl p-6 shadow-2xl space-y-4 text-center relative overflow-hidden">
            {{-- Header --}}
            <div class="flex justify-between items-center border-b border-stone-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-[#BD2000]/10 border border-[#BD2000]/20 flex items-center justify-center text-[#BD2000]">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 116 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-black text-base text-[#8C0000]">Pindai Kode QR Meja</h3>
                </div>
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
        let html5QrCodeInstance = null;

        function openOrderTypeModal() {
            const modal = document.getElementById('order-type-modal');
            if (modal) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modal.classList.add('opacity-100');
            }
        }

        function closeOrderTypeModal() {
            const modal = document.getElementById('order-type-modal');
            if (modal) {
                modal.classList.remove('opacity-100');
                modal.classList.add('opacity-0', 'pointer-events-none');
            }
        }

        function openQrFromOrderType() {
            closeOrderTypeModal();
            setTimeout(() => {
                openQrScannerModal();
            }, 200);
        }

        function openQrScannerModal() {
            const modal = document.getElementById('qr-scanner-modal');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100');

            if (!html5QrCodeInstance) {
                html5QrCodeInstance = new Html5Qrcode("qr-reader");
            }

            const config = { fps: 15 };

            html5QrCodeInstance.start(
                { facingMode: "environment" },
                config,
                onScanSuccess,
                onScanError
            ).catch(err => {
                html5QrCodeInstance.start({ facingMode: "user" }, config, onScanSuccess, onScanError).catch(e => console.log(e));
            });
        }

        function onScanSuccess(decodedText, decodedResult) {
            console.log("QR Code Scanned:", decodedText);
            if (decodedText) {
                closeQrScannerModal();
                window.location.href = decodedText;
            }
        }

        function onScanError(errorMessage) {
            // silent continuous scan error
        }

        function closeQrScannerModal() {
            const modal = document.getElementById('qr-scanner-modal');
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0', 'pointer-events-none');

            if (html5QrCodeInstance && html5QrCodeInstance.isScanning) {
                html5QrCodeInstance.stop().then(() => {
                    html5QrCodeInstance.clear();
                    html5QrCodeInstance = null;
                }).catch(err => {
                    html5QrCodeInstance = null;
                });
            }
        }

        // ScrollSpy Navbar Link Highlighter
        const sections = document.querySelectorAll('section');
        const navLinks = document.querySelectorAll('.nav-link');

        function activateScrollSpy() {
            let current = 'beranda';
            const scrollPosition = window.scrollY + 100;

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.offsetHeight;
                const sectionId = section.getAttribute('id');
                if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                    if (sectionId) {
                        current = sectionId;
                    }
                }
            });

            if (window.scrollY < 50) {
                current = 'beranda';
            }

            navLinks.forEach(link => {
                link.classList.remove('text-[#BD2000]', 'border-[#BD2000]');
                link.classList.add('text-stone-600', 'border-transparent');
                
                const href = link.getAttribute('href');
                if (href === `#${current}`) {
                    link.classList.remove('text-stone-600', 'border-transparent');
                    link.classList.add('text-[#BD2000]', 'border-[#BD2000]');
                }
            });
        }

        let currentHeroIndex = 0;
        let heroSlideTimer = null;

        function showHeroSlide(index) {
            const slides = document.querySelectorAll('.hero-slide');
            const dots = document.querySelectorAll('.hero-dot');
            if (!slides.length) return;

            currentHeroIndex = (index + slides.length) % slides.length;

            slides.forEach((slide, idx) => {
                if (idx === currentHeroIndex) {
                    slide.style.opacity = '1';
                    slide.style.pointerEvents = 'auto';
                    slide.style.transform = 'scale(1)';
                    slide.style.zIndex = '10';
                } else {
                    slide.style.opacity = '0';
                    slide.style.pointerEvents = 'none';
                    slide.style.transform = 'scale(0.95)';
                    slide.style.zIndex = '0';
                }
            });

            dots.forEach((dot, idx) => {
                if (idx === currentHeroIndex) {
                    dot.style.width = '24px';
                    dot.style.backgroundColor = '#BD2000';
                } else {
                    dot.style.width = '10px';
                    dot.style.backgroundColor = 'rgba(255, 255, 255, 0.6)';
                }
            });
        }

        function nextHeroSlide() {
            showHeroSlide(currentHeroIndex + 1);
            resetHeroTimer();
        }

        function prevHeroSlide() {
            showHeroSlide(currentHeroIndex - 1);
            resetHeroTimer();
        }

        function goToHeroSlide(index) {
            showHeroSlide(index);
            resetHeroTimer();
        }

        function resetHeroTimer() {
            if (heroSlideTimer) clearInterval(heroSlideTimer);
            heroSlideTimer = setInterval(() => {
                showHeroSlide(currentHeroIndex + 1);
            }, 4000);
        }

        document.addEventListener('DOMContentLoaded', () => {
            showHeroSlide(0);
            resetHeroTimer();

            // Scroll Reveal Observer
            const observerOptions = {
                root: null,
                rootMargin: '0px 0px -50px 0px',
                threshold: 0.1
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

        window.addEventListener('scroll', activateScrollSpy);
        activateScrollSpy();
    </script>
</body>
</html>
