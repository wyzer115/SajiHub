<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Daftar Akun - SajiHUB Enterprise</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8F5] text-[#1C1917] font-sans antialiased min-h-screen flex items-center justify-center relative overflow-y-auto py-12">
    
    <!-- Decorative background elements -->
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-[#BD2000]/5 blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-[#FFBE0F]/10 blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-md px-6 z-10 animate-fade-in-up">
        
        <!-- Logo / Branding -->
        <div class="text-center mb-8">
            <a href="{{ route('landing') }}" class="inline-block">
                <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" class="h-20 w-20 object-contain drop-shadow-md mx-auto mb-2">
            </a>
            <h1 class="font-black text-2xl tracking-tight text-[#1C1917]">Saji<span class="text-[#BD2000]">HUB</span></h1>
            <p class="text-stone-500 font-semibold text-xs mt-0.5">Manajemen Kuliner Enterprise</p>
        </div>

        {{-- Back Button --}}
        <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 text-stone-500 hover:text-[#BD2000] transition-colors text-xs font-bold mb-4 group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Beranda
        </a>

        <!-- Register Card -->
        <div class="bg-white border border-stone-200 rounded-3xl p-8 shadow-xl relative">
            <h2 class="text-xl font-black text-[#8C0000] mb-6 text-center">Daftar Akun Baru</h2>
            
            @if($errors->any())
                <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-600 px-4 py-3 rounded-xl text-xs font-bold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" class="space-y-5" autocomplete="off">
                @csrf

                {{-- Role Selection Tab --}}
                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Pilih Tipe Akun</label>
                    <div class="grid grid-cols-2 gap-2 bg-stone-100 p-1.5 rounded-xl border border-stone-200">
                        <button type="button" id="tab-pelanggan" onclick="setRole('pelanggan')"
                            class="py-2.5 px-4 rounded-lg text-xs font-extrabold text-center transition-all duration-300 bg-[#BD2000] text-white shadow-md cursor-pointer">
                            Akun Biasa
                        </button>
                        <button type="button" id="tab-member" onclick="setRole('member')"
                            class="py-2.5 px-4 rounded-lg text-xs font-bold text-center transition-all duration-300 text-stone-600 hover:text-[#BD2000] cursor-pointer">
                            Akun Member
                        </button>
                    </div>
                    <input type="hidden" name="role" id="role-input" value="pelanggan">
                </div>

                {{-- Role Description Box --}}
                <div id="role-desc" class="p-4 rounded-xl bg-[#BD2000]/5 border border-[#BD2000]/20 text-xs text-stone-600 leading-relaxed font-medium transition-all duration-300">
                    <span class="text-[#BD2000] font-extrabold block mb-1">Benefit Akun Biasa:</span>
                    Akses pemesanan menu online standar, simpan riwayat transaksi kuliner, dan lacak status pesanan langsung dari meja Anda.
                </div>

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Masukkan Nama Lengkap"
                            class="block w-full pl-11 pr-4 py-3 bg-stone-50 border border-stone-300 rounded-xl text-[#1C1917] placeholder-stone-400 focus:outline-none focus:border-[#BD2000] focus:ring-1 focus:ring-[#BD2000] transition-colors font-medium">
                    </div>
                </div>

                {{-- Email / Username --}}
                <div>
                    <label for="username_or_email" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Email / Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input type="text" id="username_or_email" name="username_or_email" value="{{ old('username_or_email') }}" required placeholder="Masukkan Email atau Username" autocomplete="off"
                            class="block w-full pl-11 pr-4 py-3 bg-stone-50 border border-stone-300 rounded-xl text-[#1C1917] placeholder-stone-400 focus:outline-none focus:border-[#BD2000] focus:ring-1 focus:ring-[#BD2000] transition-colors font-medium">
                    </div>
                </div>

                {{-- WhatsApp / Phone --}}
                <div>
                    <label for="phone" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Nomor WhatsApp / Telepon</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="08xxxxxxxxxx"
                            class="block w-full pl-11 pr-4 py-3 bg-stone-50 border border-stone-300 rounded-xl text-[#1C1917] placeholder-stone-400 focus:outline-none focus:border-[#BD2000] focus:ring-1 focus:ring-[#BD2000] transition-colors font-medium">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" required placeholder="Minimal 6 karakter" autocomplete="new-password"
                            class="block w-full pl-11 pr-4 py-3 bg-stone-50 border border-stone-300 rounded-xl text-[#1C1917] placeholder-stone-400 focus:outline-none focus:border-[#BD2000] focus:ring-1 focus:ring-[#BD2000] transition-colors font-medium">
                    </div>
                </div>

                {{-- Password Confirmation --}}
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Konfirmasi Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi kata sandi"
                            class="block w-full pl-11 pr-4 py-3 bg-stone-50 border border-stone-300 rounded-xl text-[#1C1917] placeholder-stone-400 focus:outline-none focus:border-[#BD2000] focus:ring-1 focus:ring-[#BD2000] transition-colors font-medium">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 rounded-xl shadow-lg text-sm font-extrabold text-white bg-[#BD2000] hover:bg-[#8C0000] transition-all duration-200 transform hover:scale-[1.01] focus:outline-none focus:ring-2 focus:ring-[#BD2000] cursor-pointer">
                        Daftar Sekarang
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-xs text-stone-500 font-medium">
                    Sudah memiliki akun? 
                    <a href="{{ route('login') }}" class="text-[#BD2000] hover:text-[#8C0000] font-bold underline underline-offset-4 ml-1">Masuk di sini</a>
                </p>
            </div>
        </div>
        
        <div class="text-center mt-6 text-xs text-stone-400 font-medium">
            &copy; 2026 SajiHUB Resto. Seluruh Hak Cipta Dilindungi.
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
                tabPelanggan.className = "py-2.5 px-4 rounded-lg text-xs font-extrabold text-center transition-all duration-300 bg-[#BD2000] text-white shadow-md cursor-pointer";
                tabMember.className = "py-2.5 px-4 rounded-lg text-xs font-bold text-center transition-all duration-300 text-stone-600 hover:text-[#BD2000] cursor-pointer";
                
                roleDesc.innerHTML = `
                    <span class="text-[#BD2000] font-extrabold block mb-1">Benefit Akun Biasa:</span>
                    Akses pemesanan menu online standar, simpan riwayat transaksi kuliner, dan lacak status pesanan langsung dari meja Anda.
                `;
            } else {
                tabMember.className = "py-2.5 px-4 rounded-lg text-xs font-extrabold text-center transition-all duration-300 bg-[#BD2000] text-white shadow-md cursor-pointer";
                tabPelanggan.className = "py-2.5 px-4 rounded-lg text-xs font-bold text-center transition-all duration-300 text-stone-600 hover:text-[#BD2000] cursor-pointer";
                
                roleDesc.innerHTML = `
                    <span class="text-[#BD2000] font-extrabold block mb-1">👑 Benefit Akun Member (VIP):</span>
                    Kumpulkan poin loyalitas setiap pembelian untuk ditukar hidangan gratis, dapatkan diskon eksklusif member 10%, akses promo hari spesial, dan prioritas antrean pesanan!
                `;
            }
        }
    </script>

</body>
</html>
