<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>{{ $maintenance['title'] ?? 'Pemeliharaan Sistem - SajiHUB' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN for guaranteed standalone styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        saji: {
                            maroon: '#8C0000',
                            rust: '#BD2000',
                            red: '#FA1E0E',
                            yellow: '#FFBE0F',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #FAF8F5;
            color: #1C1917;
        }
        @keyframes gentlePulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.06); opacity: 0.9; }
        }
        @keyframes slowSpin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .pulse-icon {
            animation: gentlePulse 3s ease-in-out infinite;
        }
        .gear-spin {
            animation: slowSpin 25s linear infinite;
        }
    </style>
</head>
<body class="bg-[#FAF8F5] text-[#1C1917] min-h-screen flex flex-col justify-between relative overflow-x-hidden {{ (isset($isPreview) && $isPreview) ? 'pt-14' : '' }}">

    @if(isset($isPreview) && $isPreview)
    <!-- Preview Banner for Super Admin -->
    <div class="fixed top-0 left-0 right-0 z-50 bg-[#8C0000] text-white px-4 py-2.5 shadow-lg flex items-center justify-between text-xs sm:text-sm font-bold">
        <div class="flex items-center justify-center gap-2 mx-auto flex-wrap">
            <span class="inline-block w-2.5 h-2.5 rounded-full bg-amber-400 animate-ping"></span>
            <span>Mode Pratinjau (Preview): Ini adalah simulasi tampilan pemeliharaan untuk pengunjung umum.</span>
            <a href="{{ route('superadmin.maintenance.index') }}" class="bg-white text-[#8C0000] hover:bg-amber-100 px-3 py-1 rounded-xl text-xs font-black shadow-sm transition-all ml-2">
                &larr; Kembali ke Panel Admin
            </a>
        </div>
    </div>
    @endif

    <!-- Decorative background elements -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-[-10%] left-[-10%] w-[45%] h-[45%] rounded-full bg-[#BD2000]/5 blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[45%] h-[45%] rounded-full bg-[#FFBE0F]/10 blur-[120px]"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[60%] h-[60%] rounded-full bg-[#8C0000]/3 blur-[140px]"></div>
    </div>

    <!-- Header Logo & Badge -->
    <header class="w-full max-w-4xl mx-auto px-6 pt-8 pb-4 relative z-10 flex items-center justify-between">
        <a href="{{ url('/') }}" class="inline-block">
            <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" class="h-10 w-auto object-contain drop-shadow-sm">
        </a>
        <div class="flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-red-50 border border-red-200 text-red-600 text-xs font-extrabold shadow-sm">
            <span class="w-2 h-2 rounded-full bg-red-600 animate-ping"></span>
            <span class="w-2 h-2 rounded-full bg-red-600 -ml-4"></span>
            <span class="uppercase tracking-wider">Mode Pemeliharaan</span>
        </div>
    </header>

    <!-- Main Content Card -->
    <main class="flex-1 flex items-center justify-center px-4 py-8 relative z-10">
        <div class="w-full max-w-xl bg-white/95 border border-stone-200/90 rounded-3xl p-8 sm:p-12 shadow-2xl backdrop-blur-md text-center relative overflow-hidden">
            
            <!-- Top Subtle Light Accent -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#BD2000] via-[#FFBE0F] to-[#8C0000]"></div>

            <!-- Icon Container -->
            <div class="relative w-24 h-24 mx-auto mb-6 flex items-center justify-center">
                <div class="absolute inset-0 rounded-3xl bg-[#BD2000]/10 border border-[#BD2000]/20 pulse-icon"></div>
                <div class="relative w-16 h-16 rounded-2xl bg-[#BD2000] text-white flex items-center justify-center shadow-lg shadow-red-900/20">
                    <svg class="w-8 h-8 gear-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-2xl sm:text-3xl font-black text-[#8C0000] tracking-tight mb-3">
                {{ $maintenance['title'] ?? 'SajiHub Sedang Dalam Pemeliharaan' }}
            </h1>
            
            <!-- Message -->
            <p class="text-stone-600 text-sm sm:text-base leading-relaxed max-w-md mx-auto mb-8">
                {{ $maintenance['message'] ?? 'Kami sedang melakukan pemeliharaan server dan optimasi performa berkala untuk memberikan pengalaman terbaik. Sistem akan segera aktif kembali.' }}
            </p>

            <!-- Countdown Timer if end_time exists -->
            @if(!empty($maintenance['end_time']))
            <div class="mb-8 p-4 rounded-2xl bg-stone-50 border border-stone-200 max-w-sm mx-auto" id="countdown-card">
                <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-2.5">Perkiraan Waktu Selesai</p>
                <div class="grid grid-cols-3 gap-2 text-center" id="countdown-timer" data-target="{{ $maintenance['end_time'] }}">
                    <div class="bg-white rounded-xl p-2.5 border border-stone-200 shadow-sm">
                        <span class="block text-2xl font-black text-[#BD2000]" id="hours">00</span>
                        <span class="text-[10px] font-bold text-stone-500 uppercase">Jam</span>
                    </div>
                    <div class="bg-white rounded-xl p-2.5 border border-stone-200 shadow-sm">
                        <span class="block text-2xl font-black text-[#BD2000]" id="minutes">00</span>
                        <span class="text-[10px] font-bold text-stone-500 uppercase">Menit</span>
                    </div>
                    <div class="bg-white rounded-xl p-2.5 border border-stone-200 shadow-sm">
                        <span class="block text-2xl font-black text-[#BD2000]" id="seconds">00</span>
                        <span class="text-[10px] font-bold text-stone-500 uppercase">Detik</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <button onclick="window.location.reload()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-[#BD2000] hover:bg-[#8C0000] text-white font-black text-sm shadow-md transition-all active:scale-95 cursor-pointer">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span>Muat Ulang Halaman</span>
                </button>
                <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-2xl bg-stone-100 hover:bg-stone-200 border border-stone-300 text-stone-700 font-bold text-sm transition-all shadow-sm">
                    <svg class="w-4 h-4 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Login Super Admin</span>
                </a>
            </div>

            <!-- Footer note inside card -->
            <div class="mt-8 pt-5 border-t border-stone-200/80 text-xs text-stone-400 font-medium">
                <p>Pesanan yang telah dibayar tetap tercatat aman di server database kami.</p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full max-w-4xl mx-auto px-6 py-6 relative z-10 text-center text-xs text-stone-400">
        <p>&copy; {{ date('Y') }} SajiHUB Enterprise &bull; Layanan Manajemen Restoran Multi-Cabang</p>
    </footer>

    @if(!empty($maintenance['end_time']))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const timerContainer = document.getElementById('countdown-timer');
            if (!timerContainer) return;

            const targetTime = new Date(timerContainer.getAttribute('data-target')).getTime();
            if (isNaN(targetTime)) return;

            const hoursEl = document.getElementById('hours');
            const minutesEl = document.getElementById('minutes');
            const secondsEl = document.getElementById('seconds');

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = targetTime - now;

                if (distance <= 0) {
                    hoursEl.textContent = '00';
                    minutesEl.textContent = '00';
                    secondsEl.textContent = '00';
                    return;
                }

                const hours = Math.floor(distance / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                hoursEl.textContent = String(hours).padStart(2, '0');
                minutesEl.textContent = String(minutes).padStart(2, '0');
                secondsEl.textContent = String(seconds).padStart(2, '0');
            }

            updateCountdown();
            setInterval(updateCountdown, 1000);
        });
    </script>
    @endif
</body>
</html>
