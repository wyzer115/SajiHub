<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Login - SajiHUB Enterprise</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-[#FAF8F5] text-[#1C1917] font-sans antialiased min-h-screen flex items-center justify-center relative overflow-y-auto py-8">
    
    <!-- Decorative background elements -->
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-[#BD2000]/5 blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-[#FFBE0F]/10 blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-md px-6 py-4 z-10 animate-fade-in-up">
        
        <!-- Logo / Branding -->
        <div class="text-center mb-6">
            <a href="{{ route('landing') }}" class="inline-block">
                <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" class="h-14 w-auto object-contain drop-shadow-md mx-auto mb-1">
            </a>
            <p class="text-stone-500 font-semibold text-xs mt-0.5">Manajemen Kuliner Enterprise</p>
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

            <h2 class="text-xl font-black text-[#8C0000] mb-6 text-center">Masuk ke Akun Anda</h2>
            
            @if($errors->any())
                <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-600 px-4 py-3 rounded-2xl text-xs font-bold flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5" id="login-form" autocomplete="off">
                @csrf
                
                <div>
                    <label for="login" class="block text-xs font-extrabold text-stone-700 uppercase tracking-wider mb-1.5">Email / Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </div>
                        <input type="text" id="login" name="login" value="" required autofocus autocomplete="off" placeholder="Masukkan email atau username"
                            class="block w-full pl-11 pr-4 py-3 bg-stone-50/80 border border-stone-300 rounded-2xl text-[#1C1917] placeholder-stone-400 focus:outline-none focus:border-[#BD2000] focus:ring-2 focus:ring-[#BD2000]/20 transition-all font-medium text-sm">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-extrabold text-stone-700 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="••••••••"
                            class="block w-full pl-11 pr-11 py-3 bg-stone-50/80 border border-stone-300 rounded-2xl text-[#1C1917] placeholder-stone-400 focus:outline-none focus:border-[#BD2000] focus:ring-2 focus:ring-[#BD2000]/20 transition-all font-medium text-sm">
                        <button type="button" onclick="togglePassword('password', 'eye-icon')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-stone-400 hover:text-stone-600 transition-colors">
                            <svg id="eye-icon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-2xl shadow-lg text-sm font-extrabold text-white bg-[#BD2000] hover:bg-[#8C0000] focus:outline-none focus:ring-2 focus:ring-[#BD2000] transition-all duration-200 transform hover:scale-[1.01] active:scale-[0.99] cursor-pointer">
                        <span>Masuk</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </form>
            
        </div>

        <script>
            function togglePassword(inputId, iconId) {
                const input = document.getElementById(inputId);
                const icon = document.getElementById(iconId);
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a8.97 8.97 0 013.122-.563c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m-4.09-4.09a3 3 0 00-4.243-4.243m4.242 4.242L9.88 9.88m-4.242 4.242L3 3l18 18"/>';
                } else {
                    input.type = 'password';
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
                }
            }
        </script>
        
        <div class="text-center mt-6 text-xs text-stone-400 font-medium">
            &copy; {{ date('Y') }} SajiHUB Resto. Seluruh Hak Cipta Dilindungi.
        </div>
    </div>

</body>
</html>
