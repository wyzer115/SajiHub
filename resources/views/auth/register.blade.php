<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - SajiHUB Enterprise</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0B0E14] font-sans antialiased min-h-screen flex items-center justify-center relative overflow-y-auto py-12">
    
    <!-- Decorative background elements -->
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-[#F96310]/10 blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-amber-500/10 blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-md px-6 z-10 animate-fade-in-up">
        
        <!-- Logo / Branding -->
        <div class="text-center mb-8">
            <a href="{{ route('landing') }}" class="inline-block">
                <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" class="h-20 w-auto mx-auto mb-2">
            </a>
            <p class="text-slate-400 font-medium text-sm">Manajemen Kuliner Enterprise</p>
        </div>

        {{-- Back Button --}}
        <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-[#F96310] transition-colors text-sm font-medium mb-4 group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Beranda
        </a>

        <!-- Register Card -->
        <div class="bg-[#131B2A] rounded-xl p-8 shadow-2xl relative border border-slate-800/50">
            <h2 class="text-xl font-bold text-white mb-6 text-center">Daftar Akun Baru</h2>
            
            @if($errors->any())
                <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-xl text-sm font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" class="space-y-5" autocomplete="off">
                @csrf

                {{-- Role Selection Tab --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Pilih Tipe Akun</label>
                    <div class="grid grid-cols-2 gap-2 bg-[#0F172A] p-1.5 rounded-xl border border-slate-800">
                        <button type="button" id="tab-pelanggan" onclick="setRole('pelanggan')"
                            class="py-2.5 px-4 rounded-lg text-sm font-bold text-center transition-all duration-300 bg-[#F96310] text-white shadow-md">
                            Akun Biasa
                        </button>
                        <button type="button" id="tab-member" onclick="setRole('member')"
                            class="py-2.5 px-4 rounded-lg text-sm font-bold text-center transition-all duration-300 text-slate-400 hover:text-white">
                            Akun Member
                        </button>
                    </div>
                    <input type="hidden" name="role" id="role-input" value="pelanggan">
                </div>

                {{-- Role Description Box --}}
                <div id="role-desc" class="p-4 rounded-xl bg-[#F96310]/5 border border-[#F96310]/10 text-xs text-slate-400 leading-relaxed transition-all duration-300">
                    <span class="text-[#F96310] font-bold block mb-1">Benefit Akun Biasa:</span>
                    Akses pemesanan menu online standar, simpan riwayat transaksi kuliner, dan lacak status pesanan langsung dari meja Anda.
                </div>

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-300 mb-1.5">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <!-- Icon User -->
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Masukkan Nama Lengkap"
                            class="block w-full pl-11 pr-4 py-3 bg-[#0F172A] border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-[#F96310] focus:ring-1 focus:ring-[#F96310] transition-colors shadow-inner">
                    </div>
                </div>

                {{-- Email / Username --}}
                <div>
                    <label for="username_or_email" class="block text-sm font-medium text-slate-300 mb-1.5">Email / Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <!-- Icon @ -->
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input type="text" id="username_or_email" name="username_or_email" value="{{ old('username_or_email') }}" required placeholder="Masukkan Email atau Username" autocomplete="off"
                            class="block w-full pl-11 pr-4 py-3 bg-[#0F172A] border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-[#F96310] focus:ring-1 focus:ring-[#F96310] transition-colors shadow-inner">
                    </div>
                </div>

                {{-- WhatsApp / Phone --}}
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-300 mb-1.5">Nomor WhatsApp / Telepon</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <!-- Icon Phone -->
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="08xxxxxxxxxx"
                            class="block w-full pl-11 pr-4 py-3 bg-[#0F172A] border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-[#F96310] focus:ring-1 focus:ring-[#F96310] transition-colors shadow-inner">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <!-- Icon Gembok -->
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" required placeholder="Minimal 6 karakter" autocomplete="new-password"
                            class="block w-full pl-11 pr-4 py-3 bg-[#0F172A] border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-[#F96310] focus:ring-1 focus:ring-[#F96310] transition-colors shadow-inner">
                    </div>
                </div>

                {{-- Password Confirmation --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-1.5">Konfirmasi Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <!-- Icon Gembok -->
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi kata sandi"
                            class="block w-full pl-11 pr-4 py-3 bg-[#0F172A] border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-[#F96310] focus:ring-1 focus:ring-[#F96310] transition-colors shadow-inner">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 rounded-lg shadow-lg text-sm font-bold text-white bg-[#F96310] hover:bg-[#e05307] transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-[#F96310] focus:ring-offset-2 focus:ring-offset-[#131B2A]">
                        Daftar Sekarang
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-slate-400">
                    Sudah memiliki akun? 
                    <a href="{{ route('login') }}" class="text-[#F96310] hover:text-[#e05307] font-bold underline underline-offset-4 ml-1">Masuk di sini</a>
                </p>
            </div>
        </div>
        
        <div class="text-center mt-6 text-xs text-slate-500">
            &copy; 2026 SajiHUB Enterprise. Versi 1.0.0
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
                tabPelanggan.className = "py-2.5 px-4 rounded-lg text-sm font-bold text-center transition-all duration-300 bg-[#F96310] text-white shadow-md";
                tabMember.className = "py-2.5 px-4 rounded-lg text-sm font-bold text-center transition-all duration-300 text-slate-400 hover:text-white";
                
                roleDesc.innerHTML = `
                    <span class="text-[#F96310] font-bold block mb-1">Benefit Akun Biasa:</span>
                    Akses pemesanan menu online standar, simpan riwayat transaksi kuliner, dan lacak status pesanan langsung dari meja Anda.
                `;
            } else {
                tabMember.className = "py-2.5 px-4 rounded-lg text-sm font-bold text-center transition-all duration-300 bg-[#F96310] text-white shadow-md";
                tabPelanggan.className = "py-2.5 px-4 rounded-lg text-sm font-bold text-center transition-all duration-300 text-slate-400 hover:text-white";
                
                roleDesc.innerHTML = `
                    <span class="text-[#F96310] font-bold block mb-1">👑 Benefit Akun Member (VIP):</span>
                    Kumpulkan poin loyalitas setiap pembelian untuk ditukar hidangan gratis, dapatkan diskon eksklusif member 10%, akses promo hari spesial, dan prioritas antrean pesanan!
                `;
            }
        }
    </script>

</body>
</html>
