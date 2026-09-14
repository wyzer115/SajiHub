<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Login - SajiHUB Enterprise</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8F5] text-[#1C1917] font-sans antialiased min-h-screen flex items-center justify-center relative overflow-y-auto">
    
    <!-- Decorative background elements -->
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-[#BD2000]/5 blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-[#FFBE0F]/10 blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-md px-6 py-8 z-10 animate-fade-in-up">
        
        <!-- Logo / Branding -->
        <div class="text-center mb-8">
            <a href="{{ route('landing') }}" class="inline-block">
                <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" class="h-16 w-auto object-contain drop-shadow-md mx-auto mb-1">
            </a>
            <p class="text-stone-500 font-semibold text-xs mt-0.5">Manajemen Kuliner Enterprise</p>
        </div>

        {{-- Back Button --}}
        <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 text-stone-500 hover:text-[#BD2000] transition-colors text-xs font-bold mb-4 group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Beranda
        </a>

        <!-- Login Card -->
        <div class="bg-white border border-stone-200 rounded-3xl p-8 shadow-xl relative">
            <h2 class="text-xl font-black text-[#8C0000] mb-6 text-center">Masuk ke Akun Anda</h2>
            
            @if($errors->any())
                <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-600 px-4 py-3 rounded-xl text-xs font-bold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5" autocomplete="off">
                @csrf
                
                <div>
                    <label for="login" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Email / Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </div>
                        <input type="text" id="login" name="login" value="" required autofocus autocomplete="off"
                            class="block w-full pl-11 pr-4 py-3 bg-stone-50 border border-stone-300 rounded-xl text-[#1C1917] placeholder-stone-400 focus:outline-none focus:border-[#BD2000] focus:ring-1 focus:ring-[#BD2000] transition-colors font-medium">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input type="password" id="password" name="password" required autocomplete="new-password"
                            class="block w-full pl-11 pr-4 py-3 bg-stone-50 border border-stone-300 rounded-xl text-[#1C1917] placeholder-stone-400 focus:outline-none focus:border-[#BD2000] focus:ring-1 focus:ring-[#BD2000] transition-colors font-medium">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 rounded-xl shadow-lg text-sm font-extrabold text-white bg-[#BD2000] hover:bg-[#8C0000] focus:outline-none focus:ring-2 focus:ring-[#BD2000] transition-all duration-200 transform hover:scale-[1.01] cursor-pointer">
                        Masuk Sistem
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center border-t border-stone-100 pt-4">
                <p class="text-xs text-stone-500 font-medium">
                    Akses khusus staf & pengelola. Akun dibuat oleh Administrator Cabang / Pusat.
                </p>
            </div>
        </div>
        
        <div class="text-center mt-8 text-xs text-stone-400 font-medium">
            &copy; {{ date('Y') }} SajiHUB Resto. Seluruh Hak Cipta Dilindungi.
        </div>
    </div>

</body>
</html>
