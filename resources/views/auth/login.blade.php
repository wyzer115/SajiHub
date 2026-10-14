<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Masuk Portal Staf - SajiHUB Enterprise</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('partials.head-assets')
    <!-- Alpine.js -->
    <script defer src="{{ asset('js/alpine.min.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        /* Disable native browser password reveal & clear icons (Edge/Chrome/Chromium) */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none !important;
        }
    </style>
</head>
<body class="bg-[#FAF8F5] text-[#1C1917] font-sans antialiased min-h-screen flex items-center justify-center relative overflow-y-auto py-8">
    
    <!-- Decorative background elements -->
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-[#BD2000]/5 blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-[#FFBE0F]/10 blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-md px-6 py-4 z-10 animate-fade-in-up">
        
        <!-- Logo / Branding -->
        <div class="text-center mb-6">
            <a href="{{ route('landing') }}" class="inline-flex items-center justify-center gap-2.5 flex-shrink-0 group">
                <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" class="h-10 w-auto object-contain flex-shrink-0 drop-shadow-md group-hover:scale-105 transition-transform duration-300">
                <span class="font-black text-2xl sm:text-3xl tracking-tight text-[#8C0000] leading-none select-none flex-shrink-0">
                    Saji<span class="text-[#FFBE0F]">HUB</span>
                </span>
            </a>
            <p class="text-stone-500 font-semibold text-xs mt-2">Manajemen Kuliner Enterprise</p>
        </div>

        {{-- Back Button --}}
        <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 text-stone-500 hover:text-[#BD2000] transition-colors text-xs font-bold mb-4 group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Beranda
        </a>

        <!-- Login Card -->
        <div class="bg-white/95 backdrop-blur-md border border-stone-200/80 rounded-3xl p-8 shadow-2xl relative">
            <div class="w-12 h-12 rounded-2xl bg-[#BD2000]/10 border border-[#BD2000]/20 flex items-center justify-center text-[#BD2000] mx-auto mb-4">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
            </div>

            <div class="text-center mb-6">
                <h2 class="text-xl font-black text-[#8C0000] tracking-tight">Portal Staf & Manajemen</h2>
                <p class="text-xs text-stone-500 font-medium mt-1 leading-relaxed">Akses terpadu operasional dan pengelolaan cabang SajiHUB</p>
            </div>
            
            @php
                $isSysMaint = \App\Models\SystemSetting::isMaintenanceMode();
            @endphp

            @if($isSysMaint)
                <div class="mb-5 bg-amber-50 border border-amber-300 text-amber-800 px-4 py-3 rounded-2xl text-xs flex items-start gap-2.5 shadow-sm">
                    <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <span class="block font-black text-amber-900">Mode Pemeliharaan Sedang Aktif</span>
                        <span class="font-semibold text-amber-700">Akses publik & staf dinonaktifkan sementara. Hanya Super Admin yang diizinkan masuk.</span>
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-5 bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-2xl text-xs font-bold flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('warning') }}</span>
                </div>
            @endif



            <form action="{{ route('login.post') }}" method="POST" class="space-y-5" id="login-form" autocomplete="off">
                @csrf
                
                {{-- Kolom Input Email --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="email" class="block text-xs font-extrabold {{ $errors->has('email') ? 'text-red-600' : 'text-stone-700' }} uppercase tracking-wider">Alamat Email</label>
                        @error('email')
                            <span class="text-red-600 font-bold text-[11px] normal-case flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $message }}</span>
                            </span>
                        @enderror
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none {{ $errors->has('email') ? 'text-red-500' : 'text-stone-400' }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="Masukkan alamat email staf"
                            class="block w-full pl-11 pr-4 py-3 border rounded-2xl text-sm font-medium transition-all focus:outline-none {{ $errors->has('email') ? 'bg-red-50/70 border-red-500 text-red-950 placeholder-red-300 ring-2 ring-red-500/20 focus:border-red-600 focus:ring-red-500/30' : 'bg-stone-50/80 border-stone-300 text-[#1C1917] placeholder-stone-400 focus:border-[#BD2000] focus:ring-2 focus:ring-[#BD2000]/20' }}">
                    </div>
                </div>

                {{-- Kolom Input Kata Sandi --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-extrabold {{ $errors->has('password') ? 'text-red-600' : 'text-stone-700' }} uppercase tracking-wider">Kata Sandi</label>
                        @error('password')
                            <span class="text-red-600 font-bold text-[11px] normal-case flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $message }}</span>
                            </span>
                        @enderror
                    </div>
                    <div class="relative" x-data="{ show: false }">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none {{ $errors->has('password') ? 'text-red-500' : 'text-stone-400' }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input :type="show ? 'text' : 'password'" id="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                            class="block w-full pl-11 pr-12 py-3 border rounded-2xl text-sm font-medium transition-all focus:outline-none {{ $errors->has('password') ? 'bg-red-50/70 border-red-500 text-red-950 placeholder-red-300 ring-2 ring-red-500/20 focus:border-red-600 focus:ring-red-500/30' : 'bg-stone-50/80 border-stone-300 text-[#1C1917] placeholder-stone-400 focus:border-[#BD2000] focus:ring-2 focus:ring-[#BD2000]/20' }}">
                        <button type="button" 
                                @click="show = !show" 
                                tabindex="-1" 
                                class="absolute top-1/2 right-3 -translate-y-1/2 flex items-center justify-center cursor-pointer focus:outline-none" 
                                aria-label="Tampilkan / Sembunyikan Kata Sandi">
                            <img src="{{ asset('images/eye-open.png') }}" 
                                 alt="Tampilkan Kata Sandi" 
                                 x-show="show" 
                                 class="h-5 w-5 block select-none pointer-events-none object-contain" />
                            <img src="{{ asset('images/eye-closed.png') }}" 
                                 alt="Sembunyikan Kata Sandi" 
                                 x-show="!show" 
                                 x-cloak 
                                 class="h-5 w-5 block select-none pointer-events-none object-contain" />
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-2xl shadow-lg text-sm font-extrabold text-white bg-[#BD2000] hover:bg-[#8C0000] focus:outline-none focus:ring-2 focus:ring-[#BD2000] transition-all duration-200 transform hover:scale-[1.01] active:scale-[0.99] cursor-pointer">
                        <span>Masuk ke Portal</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </form>
            
        </div>
        
        <div class="text-center mt-6 text-xs text-stone-400 font-medium">
            &copy; {{ date('Y') }} SajiHUB Resto. Seluruh Hak Cipta Dilindungi.
        </div>
    </div>

</body>
</html>
