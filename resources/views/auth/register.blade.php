<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Waroeng SajiHUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-dark-950 font-sans antialiased min-h-screen flex items-center justify-center relative overflow-y-auto py-12">
    
    <!-- Decorative background elements -->
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-brand-500/10 blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-amber-500/10 blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-lg px-6 z-10 animate-fade-in-up">
        
        <!-- Logo / Branding -->
        <div class="text-center mb-8">
            <a href="{{ route('landing') }}" class="inline-block">
                <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" class="h-20 w-auto mx-auto mb-2">
            </a>
            <p class="text-dark-400 font-medium text-xs tracking-wider uppercase">Sambal Asli Rakyat Indonesia</p>
        </div>

        {{-- Back Button --}}
        <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 text-dark-400 hover:text-brand-500 transition-colors text-sm font-medium mb-4 group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Beranda
        </a>

        <!-- Register Card -->
        <div class="glass rounded-3xl p-8 shadow-2xl relative">
            <h2 class="text-xl font-bold text-white mb-6 text-center">Buat Akun Baru</h2>
            
            @if($errors->any())
                <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-xl text-sm font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" class="space-y-5" autocomplete="off">
                @csrf

                {{-- Role Selection Tab --}}
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Pilih Tipe Akun</label>
                    <div class="grid grid-cols-2 gap-2 bg-dark-900 p-1.5 rounded-xl border border-dark-800">
                        <button type="button" id="tab-pelanggan" onclick="setRole('pelanggan')"
                            class="py-2.5 px-4 rounded-lg text-sm font-bold text-center transition-all duration-300 bg-brand-500 text-white shadow-md">
                            Akun Biasa
                        </button>
                        <button type="button" id="tab-member" onclick="setRole('member')"
                            class="py-2.5 px-4 rounded-lg text-sm font-bold text-center transition-all duration-300 text-dark-400 hover:text-white">
                            Akun Member
                        </button>
                    </div>
                    <input type="hidden" name="role" id="role-input" value="pelanggan">
                </div>

                {{-- Role Description Box --}}
                <div id="role-desc" class="p-4 rounded-2xl bg-brand-500/5 border border-brand-500/10 text-xs text-dark-400 leading-relaxed transition-all duration-300">
                    <span class="text-brand-400 font-bold block mb-1">Benefit Akun Biasa:</span>
                    Akses pemesanan menu online standar, simpan riwayat transaksi kuliner, dan lacak status pesanan langsung dari meja Anda.
                </div>

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-dark-300 mb-1.5">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-dark-500">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Masukkan Nama Lengkap"
                            class="block w-full pl-11 pr-4 py-3 bg-dark-900 border border-dark-700 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors shadow-inner">
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-dark-300 mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-dark-500">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="contoh@email.com"
                            class="block w-full pl-11 pr-4 py-3 bg-dark-900 border border-dark-700 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors shadow-inner">
                    </div>
                </div>

                {{-- Username --}}
                <div>
                    <label for="username" class="block text-sm font-medium text-dark-300 mb-1.5">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-dark-500">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required placeholder="username_kamu" autocomplete="off"
                            class="block w-full pl-11 pr-4 py-3 bg-dark-900 border border-dark-700 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors shadow-inner">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-dark-300 mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-dark-500">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input type="password" id="password" name="password" required placeholder="Minimal 6 karakter" autocomplete="new-password"
                            class="block w-full pl-11 pr-4 py-3 bg-dark-900 border border-dark-700 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors shadow-inner">
                    </div>
                </div>

                {{-- Password Confirmation --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-dark-300 mb-1.5">Konfirmasi Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-dark-500">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi kata sandi"
                            class="block w-full pl-11 pr-4 py-3 bg-dark-900 border border-dark-700 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors shadow-inner">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-500 hover:to-brand-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-dark-950 focus:ring-brand-500 transition-all duration-200 transform hover:scale-[1.02]">
                        Daftar Akun
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-dark-400">
                    Sudah memiliki akun? 
                    <a href="{{ route('login') }}" class="text-brand-500 hover:text-brand-400 font-bold underline underline-offset-4 ml-1">Masuk di sini</a>
                </p>
            </div>
        </div>
        
        <div class="text-center mt-6 text-xs text-dark-500">
            &copy; {{ date('Y') }} Waroeng SajiHUB. Seluruh Hak Cipta Dilindungi.
        </div>
    </div>

    <script>
        function setRole(role) {
            const tabPelanggan = document.getElementById('tab-pelanggan');
            const tabMember = document.getElementById('tab-member');
            const roleInput = document.getElementById('role-input');
            const roleDesc = document.getElementById('role-desc');

            roleInput.value = role;

            if (role === 'pelanggan') {
                tabPelanggan.className = "py-2.5 px-4 rounded-lg text-sm font-bold text-center transition-all duration-300 bg-brand-500 text-white shadow-md";
                tabMember.className = "py-2.5 px-4 rounded-lg text-sm font-bold text-center transition-all duration-300 text-dark-400 hover:text-white";
                
                roleDesc.innerHTML = `
                    <span class="text-brand-400 font-bold block mb-1">Benefit Akun Biasa:</span>
                    Akses pemesanan menu online standar, simpan riwayat transaksi kuliner, dan lacak status pesanan langsung dari meja Anda.
                `;
            } else {
                tabMember.className = "py-2.5 px-4 rounded-lg text-sm font-bold text-center transition-all duration-300 bg-brand-500 text-white shadow-md";
                tabPelanggan.className = "py-2.5 px-4 rounded-lg text-sm font-bold text-center transition-all duration-300 text-dark-400 hover:text-white";
                
                roleDesc.innerHTML = `
                    <span class="text-brand-400 font-bold block mb-1">👑 Benefit Akun Member (VIP):</span>
                    Kumpulkan poin loyalitas setiap pembelian untuk ditukar hidangan gratis, dapatkan diskon eksklusif member 10%, akses promo hari spesial, dan prioritas antrean pesanan!
                `;
            }
        }
    </script>

</body>
</html>
