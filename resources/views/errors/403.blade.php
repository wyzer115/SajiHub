<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Akses Ditolak - SajiHUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-dark-950 font-sans antialiased min-h-screen flex items-center justify-center relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-[20%] left-[20%] w-[30%] h-[30%] rounded-full bg-red-500/10 blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[20%] right-[20%] w-[30%] h-[30%] rounded-full bg-brand-500/10 blur-[100px] pointer-events-none"></div>

    <div class="z-10 text-center animate-fade-in-up px-6">
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
        
        <div class="mt-16 flex items-center justify-center gap-2 text-dark-500">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.559-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
            </svg>
            <span class="font-bold tracking-tight text-white">Saji<span class="text-brand-500">HUB</span></span>
        </div>
    </div>
</body>
</html>
