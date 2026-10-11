<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>500 Terjadi Kesalahan Server - SajiHUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @include('partials.head-assets')
</head>
<body class="bg-[#FAF8F5] font-sans antialiased min-h-screen flex items-center justify-center relative overflow-hidden">
    <!-- Top Left Brand Header -->
    <header class="absolute top-0 left-0 w-full p-6 sm:p-8 z-20 flex items-center justify-between">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 flex-shrink-0 group">
            <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" class="h-8 sm:h-9 w-auto object-contain flex-shrink-0 drop-shadow-sm group-hover:scale-105 transition-transform duration-200">
            <span class="font-black text-xl sm:text-2xl tracking-tight text-[#8C0000] leading-none select-none flex-shrink-0">
                Saji<span class="text-[#FFBE0F]">HUB</span>
            </span>
        </a>
    </header>

    <!-- Decorative background elements -->
    <div class="absolute top-[-10%] left-[-10%] w-[45%] h-[45%] rounded-full bg-[#BD2000]/5 blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[45%] h-[45%] rounded-full bg-[#FFBE0F]/10 blur-[120px] pointer-events-none"></div>

    <div class="z-10 text-center animate-fade-in-up px-6 pt-16 sm:pt-0 max-w-lg">
        <h1 class="text-8xl sm:text-9xl font-black tracking-tighter mb-2 text-transparent bg-clip-text bg-gradient-to-r from-[#8C0000] via-[#BD2000] to-[#FFBE0F] drop-shadow-sm">
            500
        </h1>
        <h2 class="text-2xl sm:text-3xl font-black text-[#1C1917] mb-3">Kendala Server Internal</h2>
        <p class="text-stone-500 font-semibold text-sm sm:text-base mb-8 leading-relaxed">
            Terjadi gangguan teknis sementara pada server. Tim kami sedang menanganinya. Silakan coba beberapa saat lagi.
        </p>
        
        <a href="{{ url('/') }}" class="inline-flex items-center justify-center py-3.5 px-8 rounded-2xl shadow-lg text-sm font-black text-white bg-[#BD2000] hover:bg-[#8C0000] transition-all duration-200 cursor-pointer">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>
