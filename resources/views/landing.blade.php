<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waroeng SajiHUB — Sambal Asli Rakyat Indonesia</title>
    <meta name="description" content="Selamat datang di Waroeng SajiHUB. Spesialisasi Sambal Bakar khas Nusantara dengan berbagai pilihan lauk pauk lezat.">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-dark-950 text-dark-300 font-sans antialiased overflow-x-hidden">

    @if(session('success'))
        <div class="fixed top-24 left-1/2 -translate-x-1/2 z-50 w-full max-w-md px-6 animate-fade-in-up">
            <div class="glass border-emerald-500/20 bg-emerald-500/10 text-emerald-400 p-4 rounded-2xl flex items-center gap-3 shadow-2xl">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="text-sm font-semibold">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════
         NAVBAR — Sticky Glassmorphism
    ═══════════════════════════════════════════════════════════════ --}}
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-500">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                {{-- Logo --}}
                <a href="#beranda" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-brand-500/20 text-brand-500 flex items-center justify-center border border-brand-500/30 group-hover:bg-brand-500/30 transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.559-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-white tracking-tight">Waroeng Saji<span class="text-brand-500">HUB</span></span>
                </a>

                {{-- Desktop Nav Links --}}
                <div class="hidden lg:flex items-center gap-1">
                    <a href="#beranda" class="nav-link active px-4 py-2 rounded-lg text-sm font-medium text-dark-300 hover:text-white hover:bg-white/5 transition-all">Welcome</a>
                    <a href="#tentang" class="nav-link px-4 py-2 rounded-lg text-sm font-medium text-dark-300 hover:text-white hover:bg-white/5 transition-all">Tentang Kami</a>
                    <a href="#keunggulan" class="nav-link px-4 py-2 rounded-lg text-sm font-medium text-dark-300 hover:text-white hover:bg-white/5 transition-all">Keunggulan</a>
                    <a href="#menu" class="nav-link px-4 py-2 rounded-lg text-sm font-medium text-dark-300 hover:text-white hover:bg-white/5 transition-all">Menu Spesial</a>
                    <a href="#cabang" class="nav-link px-4 py-2 rounded-lg text-sm font-medium text-dark-300 hover:text-white hover:bg-white/5 transition-all">Cabang Resto</a>
                </div>

                {{-- CTA + Mobile Toggle --}}
                <div class="flex items-center gap-4">
                    @auth
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-brand-600 to-brand-500 text-white text-sm font-semibold hover:from-brand-500 hover:to-brand-400 transition-all shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-105 transform cursor-pointer">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-brand-600 to-brand-500 text-white text-sm font-semibold hover:from-brand-500 hover:to-brand-400 transition-all shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-105 transform">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                            Masuk
                        </a>
                    @endauth
                    <button id="mobile-menu-btn" class="lg:hidden text-white p-2 rounded-lg hover:bg-white/10 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="lg:hidden hidden">
            <div class="px-6 py-4 border-t border-white/10 bg-dark-900/95 backdrop-blur-xl space-y-1">
                <a href="#beranda" class="block px-4 py-3 rounded-lg text-sm font-medium text-dark-300 hover:text-white hover:bg-white/5 transition-all">Welcome</a>
                <a href="#tentang" class="block px-4 py-3 rounded-lg text-sm font-medium text-dark-300 hover:text-white hover:bg-white/5 transition-all">Tentang Kami</a>
                <a href="#keunggulan" class="block px-4 py-3 rounded-lg text-sm font-medium text-dark-300 hover:text-white hover:bg-white/5 transition-all">Keunggulan</a>
                <a href="#menu" class="block px-4 py-3 rounded-lg text-sm font-medium text-dark-300 hover:text-white hover:bg-white/5 transition-all">Menu Spesial</a>
                <a href="#cabang" class="block px-4 py-3 rounded-lg text-sm font-medium text-dark-300 hover:text-white hover:bg-white/5 transition-all">Cabang Resto</a>
                @auth
                    <form action="{{ route('logout') }}" method="POST" class="block pt-2">
                        @csrf
                        <button type="submit" class="flex w-full items-center justify-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-brand-600 to-brand-500 text-white text-sm font-semibold cursor-pointer">
                            Keluar
                        </button>
                    </form>
                @else
                    <div class="pt-2">
                        <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-brand-600 to-brand-500 text-white text-sm font-semibold">
                            Masuk
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ═══════════════════════════════════════════════════════════════
         HERO SECTION
    ═══════════════════════════════════════════════════════════════ --}}
    <section id="beranda" class="relative min-h-screen flex items-center justify-center overflow-hidden pt-20">
        {{-- Decorative Background --}}
        <div class="absolute inset-0">
            <div class="absolute top-[-20%] left-[-10%] w-[50%] h-[50%] rounded-full bg-brand-500/8 blur-[120px]"></div>
            <div class="absolute bottom-[-20%] right-[-10%] w-[50%] h-[50%] rounded-full bg-amber-500/6 blur-[120px]"></div>
        </div>

        {{-- Grid Pattern Overlay --}}
        <div class="absolute inset-0 landing-grid-pattern opacity-[0.03]"></div>

        {{-- Hero Content --}}
        <div class="relative z-10 text-center px-6 max-w-5xl mx-auto flex flex-col items-center">
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand-500/10 border border-brand-500/20 text-brand-400 text-sm font-medium mb-8 landing-scroll-reveal">
                Selamat Datang di
            </div>

            {{-- Main Heading --}}
            <h1 class="text-4xl sm:text-5xl lg:text-7xl font-black text-white leading-tight mb-4 landing-scroll-reveal uppercase tracking-tight">
                Waroeng Saji<span class="text-brand-500">HUB</span>
            </h1>
            
            <p class="text-lg sm:text-xl font-bold tracking-widest text-brand-400 mb-6 uppercase landing-scroll-reveal">
                Sambal Asli Rakyat Indonesia
            </p>

            {{-- Photo Banner Placeholder --}}
            <div class="w-full max-w-4xl aspect-[21/9] rounded-2xl border-2 border-dashed border-dark-700/50 flex flex-col items-center justify-center bg-dark-900/30 text-dark-500 mb-8 landing-scroll-reveal group hover:border-brand-500/30 transition-colors">
                <svg class="w-12 h-12 mb-2 group-hover:text-brand-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-sm font-medium">[ Tempatkan Foto Banner Suasana Restoran di Sini ]</span>
            </div>

            {{-- CTA Buttons --}}
            <div class="flex flex-col items-center justify-center gap-4 landing-scroll-reveal">
                @auth
                    <div class="text-white text-lg font-semibold mb-2">
                        Selamat Datang Kembali, <span class="text-brand-500 font-bold">{{ auth()->user()->name }}</span>!
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="group inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-gradient-to-r from-brand-600 to-brand-500 text-white font-bold text-lg shadow-2xl shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-105 transition-all duration-300 cursor-pointer">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Keluar Akun (Logout)
                        </button>
                    </form>
                @else
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 w-full">
                        <a href="{{ route('login') }}" class="group inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-gradient-to-r from-brand-600 to-brand-500 text-white font-bold text-lg shadow-2xl shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-105 transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                            Sudah Punya Akun
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl border border-dark-700 text-dark-300 font-semibold text-lg hover:border-brand-500/50 hover:text-white hover:bg-white/5 transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            Belum Punya Akun
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════
         TENTANG SECTION
    ═══════════════════════════════════════════════════════════════ --}}
    <section id="tentang" class="relative py-24 lg:py-32">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">

                {{-- Left — Photo Restaurant Building Placeholder --}}
                <div class="relative landing-scroll-reveal">
                    <div class="relative bg-gradient-to-br from-dark-800/50 to-dark-900/50 rounded-3xl p-4 border border-dark-700/50 overflow-hidden aspect-video lg:aspect-[4/3] flex flex-col items-center justify-center text-dark-500 group hover:border-brand-500/30 transition-all">
                        <div class="absolute inset-0 bg-gradient-to-br from-brand-500/5 to-transparent opacity-100"></div>
                        <svg class="w-16 h-16 mb-2 group-hover:text-brand-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="text-sm font-semibold">[ Tempatkan Foto Gedung Restoran di Sini ]</span>
                    </div>
                </div>

                {{-- Right — Text Content --}}
                <div class="landing-scroll-reveal">
                    <h2 class="text-3xl lg:text-5xl font-extrabold text-white mb-6 uppercase tracking-tight">
                        Tentang Waroeng <span class="landing-gradient-text">SajiHUB</span>
                    </h2>
                    <p class="text-dark-400 text-lg leading-relaxed mb-6">
                        Waroeng SajiHUB adalah Restoran dengan spesialisasi Sambal Bakar yang menyediakan berbagai macam pilihan sambal legendaris dengan berbagai hidangan lauk pauk nusantara.
                    </p>
                    <p class="text-dark-400 text-lg leading-relaxed mb-8">
                        Yang menjadi spesial dan primadona dari sajian sambal-sambal di Waroeng SajiHUB adalah sambal-sambal yang menggunakan minyak kelapa pilihan membuat cita rasa lebih gurih, wangi, dan nikmat tiada tara ketika dibakar langsung di atas piring tanah liat cobek panas.
                    </p>                    
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════
         KEUNGGULAN SECTION
    ═══════════════════════════════════════════════════════════════ --}}
    <section id="keunggulan" class="relative py-24 lg:py-32">
        <div class="absolute inset-0 bg-gradient-to-b from-dark-900/50 via-dark-950 to-dark-900/50"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="text-center max-w-3xl mx-auto mb-16 landing-scroll-reveal">
                <h2 class="text-3xl lg:text-5xl font-extrabold text-white mb-6 uppercase tracking-tight">
                    Apa yang Bikin Happy di <span class="landing-gradient-text">Waroeng SajiHUB?</span>
                </h2>
                <p class="text-dark-400 text-lg">
                    Waroeng SajiHUB menyediakan berbagai macam hidangan lezat dan pengalaman menyenangkan yang bisa kamu nikmati bersama teman, keluarga, dan orang tersayang.
                </p>
            </div>

            {{-- 5 Circular Item Highlights --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 items-start justify-center">
                {{-- Highlight 1 --}}
                <div class="flex flex-col items-center text-center group landing-scroll-reveal">
                    <div class="w-32 h-32 rounded-full border-2 border-dashed border-dark-700 flex flex-col items-center justify-center bg-dark-900/30 text-dark-500 mb-4 group-hover:border-brand-500/40 group-hover:text-brand-500 transition-all overflow-hidden p-2">
                        <span class="text-xs font-semibold">[ Foto Sambal ]</span>
                    </div>
                    <h3 class="text-white font-bold text-sm tracking-wide">18 Jenis Sambal Spesial</h3>
                </div>

                {{-- Highlight 2 --}}
                <div class="flex flex-col items-center text-center group landing-scroll-reveal">
                    <div class="w-32 h-32 rounded-full border-2 border-dashed border-dark-700 flex flex-col items-center justify-center bg-dark-900/30 text-dark-500 mb-4 group-hover:border-brand-500/40 group-hover:text-brand-500 transition-all overflow-hidden p-2">
                        <span class="text-xs font-semibold">[ Foto Lauk ]</span>
                    </div>
                    <h3 class="text-white font-bold text-sm tracking-wide">Berbagai hidangan lauk Pauk Nusantara</h3>
                </div>

                {{-- Highlight 3 --}}
                <div class="flex flex-col items-center text-center group landing-scroll-reveal">
                    <div class="w-32 h-32 rounded-full border-2 border-dashed border-dark-700 flex flex-col items-center justify-center bg-dark-900/30 text-dark-500 mb-4 group-hover:border-brand-500/40 group-hover:text-brand-500 transition-all overflow-hidden p-2">
                        <span class="text-xs font-semibold">[ Foto Menu Anak ]</span>
                    </div>
                    <h3 class="text-white font-bold text-sm tracking-wide">Menu Paket Anak</h3>
                </div>

                {{-- Highlight 4 --}}
                <div class="flex flex-col items-center text-center group landing-scroll-reveal">
                    <div class="w-32 h-32 rounded-full border-2 border-dashed border-dark-700 flex flex-col items-center justify-center bg-dark-900/30 text-dark-500 mb-4 group-hover:border-brand-500/40 group-hover:text-brand-500 transition-all overflow-hidden p-2">
                        <span class="text-xs font-semibold">[ Foto Event ]</span>
                    </div>
                    <h3 class="text-white font-bold text-sm tracking-wide">Bisa Reservasi Segala Kebutuhan Acara Anda</h3>
                </div>

                {{-- Highlight 5 --}}
                <div class="flex flex-col items-center text-center group landing-scroll-reveal">
                    <div class="w-32 h-32 rounded-full border-2 border-dashed border-dark-700 flex flex-col items-center justify-center bg-dark-900/30 text-dark-500 mb-4 group-hover:border-brand-500/40 group-hover:text-brand-500 transition-all overflow-hidden p-2">
                        <span class="text-xs font-semibold">[ Foto Parkiran ]</span>
                    </div>
                    <h3 class="text-white font-bold text-sm tracking-wide">Parkir Mobil & Motor Luas</h3>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════
         MENU SECTION
    ═══════════════════════════════════════════════════════════════ --}}
    <section id="menu" class="relative py-24 lg:py-32">
        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="text-center max-w-3xl mx-auto mb-16 landing-scroll-reveal">
                <h2 class="text-3xl lg:text-5xl font-extrabold text-white mb-6 uppercase tracking-tight">
                    Menu Terpopuler <span class="landing-gradient-text">Pilihan</span>
                </h2>
                <p class="text-dark-400 text-lg">
                    Sajian lauk pauk bakar lezat dipadukan dengan pedasnya sambal racikan segar khas Nusantara.
                </p>
            </div>

            {{-- Menu Grid --}}
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
                {{-- Menu Item 1 --}}
                <div class="bg-dark-900/40 border border-dark-800 rounded-3xl p-5 hover:border-brand-500/20 hover:scale-[1.02] transition-all group landing-scroll-reveal">
                    <div class="aspect-square rounded-2xl border-2 border-dashed border-dark-700/50 flex items-center justify-center text-dark-500 mb-5 group-hover:border-brand-500/20 transition-all">
                        <span class="text-xs font-semibold">[ Foto Ayam Bakar ]</span>
                    </div>
                    <h3 class="text-white font-bold text-lg mb-2">Ayam Goreng Bakar Cobek</h3>
                    <p class="text-dark-500 text-sm leading-relaxed mb-4">Ayam goreng empuk khas rempah nusantara yang disajikan dibakar dengan pilihan sambal bakar favorit.</p>
                    <div class="text-brand-500 font-extrabold">Rp 22.000</div>
                </div>

                {{-- Menu Item 2 --}}
                <div class="bg-dark-900/40 border border-dark-800 rounded-3xl p-5 hover:border-brand-500/20 hover:scale-[1.02] transition-all group landing-scroll-reveal">
                    <div class="aspect-square rounded-2xl border-2 border-dashed border-dark-700/50 flex items-center justify-center text-dark-500 mb-5 group-hover:border-brand-500/20 transition-all">
                        <span class="text-xs font-semibold">[ Foto Iga Bakar ]</span>
                    </div>
                    <h3 class="text-white font-bold text-lg mb-2">Iga Bakar Cobek Panas</h3>
                    <p class="text-dark-500 text-sm leading-relaxed mb-4">Iga sapi premium bertekstur empuk berpadu dengan bumbu kecap madu khas dan sambal bakar super pedas.</p>
                    <div class="text-brand-500 font-extrabold">Rp 45.000</div>
                </div>

                {{-- Menu Item 3 --}}
                <div class="bg-dark-900/40 border border-dark-800 rounded-3xl p-5 hover:border-brand-500/20 hover:scale-[1.02] transition-all group landing-scroll-reveal">
                    <div class="aspect-square rounded-2xl border-2 border-dashed border-dark-700/50 flex items-center justify-center text-dark-500 mb-5 group-hover:border-brand-500/20 transition-all">
                        <span class="text-xs font-semibold">[ Foto Bebek Goreng ]</span>
                    </div>
                    <h3 class="text-white font-bold text-lg mb-2">Bebek Goreng Sambal Bakar</h3>
                    <p class="text-dark-500 text-sm leading-relaxed mb-4">Bebek goreng dengan kulit yang renyah (crispy) dan daging lembut disiram sambal racikan pedas wangi.</p>
                    <div class="text-brand-500 font-extrabold">Rp 32.000</div>
                </div>

                {{-- Menu Item 4 --}}
                <div class="bg-dark-900/40 border border-dark-800 rounded-3xl p-5 hover:border-brand-500/20 hover:scale-[1.02] transition-all group landing-scroll-reveal">
                    <div class="aspect-square rounded-2xl border-2 border-dashed border-dark-700/50 flex items-center justify-center text-dark-500 mb-5 group-hover:border-brand-500/20 transition-all">
                        <span class="text-xs font-semibold">[ Foto Nasi Goreng ]</span>
                    </div>
                    <h3 class="text-white font-bold text-lg mb-2">Nasi Goreng Cobek SajiHUB</h3>
                    <p class="text-dark-500 text-sm leading-relaxed mb-4">Nasi goreng bumbu kencur gurih dengan aroma khas arang disajikan panas mengepul di piring cobek.</p>
                    <div class="text-brand-500 font-extrabold">Rp 18.000</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════
         CABANG SECTION
    ═══════════════════════════════════════════════════════════════ --}}
    <section id="cabang" class="relative py-24 lg:py-32 overflow-hidden">
        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="text-center max-w-3xl mx-auto mb-16 landing-scroll-reveal">
                <h2 class="text-3xl lg:text-5xl font-extrabold text-white mb-6 uppercase tracking-tight">
                    Resto Waroeng SajiHUB - <span class="landing-gradient-text">JABODETABEK</span>
                </h2>
                <p class="text-dark-400 text-lg">
                    Kunjungi cabang-cabang resto kami terdekat di wilayah Anda. Nikmati sajian sambal bakar langsung dari dapur kami.
                </p>
            </div>

            {{-- Branch List Cards --}}
            <div class="max-w-4xl mx-auto space-y-6">
                {{-- Cabang 1 --}}
                <a href="https://www.google.com/maps/search/?api=1&query=Waroeng+Sambal+Bakar+Juanda+Bekasi" target="_blank" class="block bg-red-900/10 border-l-4 border-brand-500 bg-dark-900/60 rounded-r-3xl rounded-l-lg p-6 lg:p-8 flex flex-col md:flex-row items-center gap-6 shadow-xl landing-scroll-reveal group hover:bg-dark-900/90 transition-all hover:scale-[1.01]">
                    {{-- Circular branch image placeholder --}}
                    <div class="w-24 h-24 rounded-full border-2 border-dashed border-dark-700 flex flex-col items-center justify-center bg-dark-900/30 text-dark-500 flex-shrink-0 group-hover:border-brand-500/30 transition-colors">
                        <span class="text-[10px] font-semibold text-center">[ Foto Resto ]</span>
                    </div>
                    <div class="flex-grow text-center md:text-left">
                        <h3 class="text-xl lg:text-2xl font-black text-white mb-2 tracking-wide uppercase group-hover:text-brand-500 transition-colors">Waroeng SajiHUB - Juanda, Bekasi</h3>
                        <p class="text-dark-400 text-sm leading-relaxed mb-1">Jl. Insinyur H. Juanda No. 84-86, Margahayu, Kec. Bekasi Timur, Kota Bekasi</p>
                        <div class="text-brand-400 font-bold text-sm">Contact: 0895 4209 2725</div>
                    </div>
                    <div class="text-5xl lg:text-7xl font-black text-brand-500/20 select-none hidden md:block">01</div>
                </a>

                {{-- Cabang 2 --}}
                <a href="https://www.google.com/maps/search/?api=1&query=Waroeng+Sambal+Bakar+Cempaka+Putih" target="_blank" class="block border-l-4 border-brand-500 bg-dark-900/60 rounded-r-3xl rounded-l-lg p-6 lg:p-8 flex flex-col md:flex-row items-center gap-6 shadow-xl landing-scroll-reveal group hover:bg-dark-900/90 transition-all hover:scale-[1.01]">
                    {{-- Circular branch image placeholder --}}
                    <div class="w-24 h-24 rounded-full border-2 border-dashed border-dark-700 flex flex-col items-center justify-center bg-dark-900/30 text-dark-500 flex-shrink-0 group-hover:border-brand-500/30 transition-colors">
                        <span class="text-[10px] font-semibold text-center">[ Foto Resto ]</span>
                    </div>
                    <div class="flex-grow text-center md:text-left">
                        <h3 class="text-xl lg:text-2xl font-black text-white mb-2 tracking-wide uppercase group-hover:text-brand-500 transition-colors">Waroeng SajiHUB - Cempaka Putih</h3>
                        <p class="text-dark-400 text-sm leading-relaxed mb-1">Jl. Cempaka Putih Raya No. 27, Cempaka Putih Timur, Kec. Cempaka Putih, Kota Jakarta Pusat</p>
                        <div class="text-brand-400 font-bold text-sm">Contact: 0897 8837 203</div>
                    </div>
                    <div class="text-5xl lg:text-7xl font-black text-brand-500/20 select-none hidden md:block">02</div>
                </a>
            </div>
        </div>
    </section>



    {{-- ═══════════════════════════════════════════════════════════════
         FOOTER
    ═══════════════════════════════════════════════════════════════ --}}
    <footer id="kontak" class="relative border-t border-dark-800/50">
        <div class="absolute inset-0 bg-gradient-to-t from-dark-950 to-dark-900/30"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8 py-16 lg:py-20 text-center flex flex-col items-center">
            {{-- Social Handles --}}
            <div class="flex items-center gap-6 mb-8 justify-center">
                <a href="#" class="w-12 h-12 rounded-full bg-dark-800 text-dark-400 flex items-center justify-center hover:bg-brand-500/20 hover:text-brand-500 transition-all border border-dark-700/50">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                </a>
                <a href="#" class="w-12 h-12 rounded-full bg-dark-800 text-dark-400 flex items-center justify-center hover:bg-red-500/20 hover:text-red-500 transition-all border border-dark-700/50">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                </a>
                <a href="#" class="w-12 h-12 rounded-full bg-dark-800 text-dark-400 flex items-center justify-center hover:bg-dark-600 hover:text-white transition-all border border-dark-700/50">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                </a>
            </div>

            {{-- Text Info --}}
            <p class="text-sm font-semibold tracking-widest text-brand-500 uppercase mb-4">waroengsajihub</p>
            
            <div class="text-dark-400 text-sm space-y-2 mb-10">
                <div>Kritik & Saran (WA) <span class="text-white font-bold">0813 9889 7488</span></div>
                <div>Booking & Reservasi (WA) <span class="text-white font-bold">0813 9889 7488</span></div>
                <div>Informasi Peluang Kemitraan (WA) <span class="text-white font-bold">0811 8400 499</span></div>
            </div>

            <div class="text-white font-black text-sm tracking-widest mb-6">PT. DAVIN GALUH PARTNER</div>

            {{-- Bottom Bar --}}
            <div class="pt-8 border-t border-dark-800/50 w-full flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-dark-500 text-xs">Privacy Policy &copy; {{ date('Y') }} by Waroeng SajiHUB</p>
                <p class="text-dark-600 text-xs">Seluruh Hak Cipta Dilindungi</p>
            </div>
        </div>
    </footer>

    {{-- ═══════════════════════════════════════════════════════════════
         JAVASCRIPT — Navbar, Scroll, Counter, Mobile Menu
    ═══════════════════════════════════════════════════════════════ --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {

        // ── Sticky Navbar with Glassmorphism on Scroll ──
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('landing-navbar-scrolled');
            } else {
                navbar.classList.remove('landing-navbar-scrolled');
            }
        });

        // ── Mobile Menu Toggle ──
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
        // Close mobile menu on link click
        mobileMenu.querySelectorAll('a[href^="#"]').forEach(link => {
            link.addEventListener('click', () => mobileMenu.classList.add('hidden'));
        });

        // ── Smooth Scroll for Anchor Links ──
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetEl = document.querySelector(targetId);
                if (targetEl) {
                    const offset = 80;
                    const top = targetEl.getBoundingClientRect().top + window.pageYOffset - offset;
                    window.scrollTo({ top, behavior: 'smooth' });
                }
            });
        });

        // ── Active Nav Link on Scroll ──
        const sections = document.querySelectorAll('section[id], footer[id]');
        const navLinks = document.querySelectorAll('.nav-link');
        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop - 100;
                if (window.pageYOffset >= sectionTop) {
                    current = section.getAttribute('id');
                }
            });
            navLinks.forEach(link => {
                link.classList.remove('text-white', 'bg-white/10');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('text-white', 'bg-white/10');
                }
            });
        });

        // ── Scroll Reveal Animation ──
        const revealElements = document.querySelectorAll('.landing-scroll-reveal');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('landing-revealed');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
        revealElements.forEach(el => revealObserver.observe(el));

    });
    </script>
</body>
</html>
