<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Akses Ditolak - SajiHUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('partials.head-assets')
</head>
<body class="bg-dark-950 font-sans antialiased min-h-screen flex items-center justify-center relative overflow-hidden">
    <!-- Top Left Brand Header -->
    <header class="absolute top-0 left-0 w-full p-6 sm:p-8 z-20 flex items-center justify-between">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 flex-shrink-0 group">
            <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" class="h-8 sm:h-9 w-auto object-contain flex-shrink-0 drop-shadow-sm group-hover:scale-105 transition-transform duration-200">
            <span class="font-black text-xl sm:text-2xl tracking-tight text-white leading-none select-none flex-shrink-0">
                Saji<span class="text-[#FFBE0F]">HUB</span>
            </span>
        </a>
    </header>

    <!-- Decorative background elements -->
    <div class="absolute top-[20%] left-[20%] w-[30%] h-[30%] rounded-full bg-red-500/10 blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[20%] right-[20%] w-[30%] h-[30%] rounded-full bg-brand-500/10 blur-[100px] pointer-events-none"></div>

    <div class="z-10 text-center animate-fade-in-up px-6 pt-16 sm:pt-0">
        <h1 class="text-9xl font-extrabold tracking-tighter mb-4 text-transparent bg-clip-text bg-gradient-to-r from-brand-500 to-red-500 drop-shadow-lg">
            403
        </h1>
        <h2 class="text-3xl font-bold text-white mb-4">Akses Ditolak</h2>
        <p class="text-dark-400 text-lg mb-8 max-w-md mx-auto">
            Anda tidak memiliki izin untuk mengakses halaman ini. Silakan kembali ke halaman sebelumnya.
        </p>
        
        <a href="{{ url('/') }}" class="inline-flex items-center justify-center py-3 px-8 border border-transparent rounded-xl shadow-lg text-base font-medium text-white bg-dark-800 hover:bg-dark-700 border-dark-700 hover:border-dark-600 transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Dashboard
        </a>
    </div>
</body>
</html>
