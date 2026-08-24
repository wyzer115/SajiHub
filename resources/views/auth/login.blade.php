<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SajiHUB Enterprise</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-dark-950 font-sans antialiased min-h-screen flex items-center justify-center relative overflow-y-auto">
    
    <!-- Decorative background elements -->
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-brand-500/10 blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-amber-500/10 blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-md px-6 z-10 animate-fade-in-up">
        
        <!-- Logo / Branding -->
        <div class="text-center mb-10">
            <a href="{{ route('landing') }}" class="inline-block">
                <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" class="h-20 w-auto mx-auto mb-2">
            </a>
            <p class="text-dark-400 font-medium">Manajemen Kuliner Enterprise</p>
        </div>

        {{-- Back Button --}}
        <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 text-dark-400 hover:text-brand-500 transition-colors text-sm font-medium mb-6 group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Beranda
        </a>

        <!-- Login Card -->
        <div class="glass rounded-2xl p-8 shadow-2xl relative">
            <h2 class="text-xl font-semibold text-white mb-6 text-center">Masuk ke Akun Anda</h2>
            
            @if($errors->any())
                <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-xl text-sm font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5" autocomplete="off">
                @csrf
                
                <div>
                    <label for="login" class="block text-sm font-medium text-dark-300 mb-1.5">Email / Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-dark-500">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </div>
                        <input type="text" id="login" name="login" value="" required autofocus autocomplete="off"
                            class="block w-full pl-11 pr-4 py-3 bg-dark-900 border border-dark-700 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors shadow-inner">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-dark-300 mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-dark-500">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input type="password" id="password" name="password" required autocomplete="new-password"
                            class="block w-full pl-11 pr-4 py-3 bg-dark-900 border border-dark-700 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors shadow-inner">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-500 hover:to-brand-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-dark-950 focus:ring-brand-500 transition-all duration-200 transform hover:scale-[1.02]">
                        Masuk Sistem
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-dark-400">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-brand-500 hover:text-brand-400 font-bold underline underline-offset-4 ml-1">Daftar di sini</a>
                </p>
            </div>
        </div>
        
        <div class="text-center mt-8 text-sm text-dark-500">
            &copy; {{ date('Y') }} SajiHUB Enterprise. Versi 1.0.0
        </div>
    </div>

</body>
</html>
