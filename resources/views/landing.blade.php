<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SajiHUB — Portal Restoran Multi-Branch</title>
    <meta name="description" content="SajiHUB — Tempat makan favorit keluarga dengan berbagai pilihan menu lezat khas Nusantara. Tersedia di berbagai cabang terdekat.">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #020617;
            color: #cbd5e1;
        }
    </style>
</head>
<body class="antialiased overflow-x-hidden bg-dark-950 text-dark-300">

    {{-- 1. TOP PREMIUM BANNER --}}
    <div class="bg-dark-950 text-dark-400 text-xs py-2.5 px-6 flex justify-between items-center z-50 relative font-medium border-b border-dark-800/80">
        <div>SajiHUB — Portal Sistem Manajemen Restoran Multi-Branch</div>
        <div class="hidden sm:block text-dark-500">★★★★★ Review Kualitas Pelayanan 9.8 / 10</div>
    </div>

    {{-- 2. CENTERED NAVBAR --}}
    <nav class="bg-dark-950/90 backdrop-blur-md border-b border-dark-800 sticky top-0 z-40 shadow-lg">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                {{-- Left Logo (SajiHUB Flame emblem style) --}}
                <a href="#" class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-brand-500/10 text-brand-500 flex items-center justify-center border border-brand-500/20 shadow-sm">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.559-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xl font-extrabold text-white tracking-tight">Saji<span class="text-brand-500">HUB</span></span>
                </a>

                {{-- Center links --}}
                <div class="hidden lg:flex items-center gap-8 text-xs font-bold text-dark-300 uppercase tracking-wider">
                    <a href="#beranda" class="nav-link text-brand-500 border-brand-500 py-1 border-b-2 transition-all">Beranda</a>
                    <a href="#menu-favorit" class="nav-link border-transparent py-1 border-b-2 hover:text-brand-500 transition-all">Menu Favorit</a>
                    <a href="#testimoni" class="nav-link border-transparent py-1 border-b-2 hover:text-brand-500 transition-all">Testimoni</a>
                </div>

                {{-- Right Actions --}}
                <div class="flex items-center gap-4">
                    @auth
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-semibold text-dark-400 hidden sm:inline">Halo, <span class="text-brand-500 font-bold">{{ auth()->user()->name }}</span></span>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-brand-500 hover:bg-brand-600 rounded-xl transition-colors cursor-pointer">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-brand-500 hover:bg-brand-600 transition-all shadow-md">
                            MASUK PORTAL
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- 3. HERO SLIDER BANNER (SajiHUB Dark Branding with Gradient Overlay) --}}
    <section id="beranda" class="relative bg-dark-950 overflow-hidden aspect-[21/9] min-h-[400px] flex items-end border-b border-dark-900">
        {{-- Hero Background Image (Dimly lit cozy warm restaurant) --}}
        <div id="hero-bg" class="absolute inset-0 bg-cover bg-center bg-no-repeat transition-all duration-500 ease-in-out" style="background-image: url('https://images.unsplash.com/photo-1514933651103-005eec06c04b?auto=format&fit=crop&w=1920&q=80');"></div>
        {{-- Smooth diagonal dark gradient overlay for organic text blending and color preservation on the right --}}
        <div class="absolute inset-0 bg-gradient-to-tr from-black/95 via-black/45 to-transparent z-10"></div>

        {{-- Left & Right Arrow Navigation (Cyan color matching reference photo) --}}
        <button id="hero-btn-prev" class="absolute left-6 top-1/2 -translate-y-1/2 z-20 text-[#00c5ff] hover:scale-110 transition-transform bg-black/40 p-2 rounded-full border border-dark-800 cursor-pointer">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <button id="hero-btn-next" class="absolute right-6 top-1/2 -translate-y-1/2 z-20 text-[#00c5ff] hover:scale-110 transition-transform bg-black/40 p-2 rounded-full border border-dark-800 cursor-pointer">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
        </button>

        {{-- Hero Content: Positioned at the very bottom-left (aligned directly to the viewport edge for maximum left alignment) --}}
        <div class="w-full px-4 sm:px-8 lg:px-12 pb-8 pt-20 relative z-20">
            <div class="w-full max-w-none drop-shadow-[0_4px_16px_rgba(0,0,0,0.95)]">
                <h1 class="font-black text-white tracking-tight uppercase mb-4" style="line-height: 0.85;">
                    <span id="hero-heading-1" class="block whitespace-nowrap animate-fade-in-up transition-all duration-300" style="font-size: clamp(2.5rem, 6vw, 5.5rem);">LOKASI</span>
                    <span id="hero-heading-2" class="block whitespace-nowrap animate-fade-in-up delay-100 transition-all duration-300" style="font-size: clamp(3.2rem, 8vw, 7.2rem);">SAJIHUB RESTO</span>
                </h1>
                <!-- Two-line description matching reference photo, enlarged and bolded -->
                <div class="text-white text-sm sm:text-base lg:text-lg leading-relaxed drop-shadow-md font-bold">
                    <div id="hero-sub" class="font-black text-brand-400 uppercase tracking-widest mb-1 transition-all duration-300">Cabang Resmi Pilihan Keluarga!</div>
                    <div id="hero-desc" class="text-gray-255 max-w-5xl text-gray-200 transition-all duration-300">Nikmati menu terpopuler khas Nusantara, harga ramah kantong, dengan suasana makan nyaman dan pelayanan cepat!</div>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. FAVORITE MENUS SECTION (SajiHUB Dark Theme) --}}
    <section id="menu-favorit" class="py-20 bg-dark-950">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center mb-12">
                <h2 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight">
                    Menu Favorit SajiHUB
                </h2>
                <div class="flex items-center gap-2">
                    <button class="w-8 h-8 rounded-full border border-dark-800 text-brand-500 hover:bg-dark-900 flex items-center justify-center font-bold">&lt;</button>
                    <button class="w-8 h-8 rounded-full border border-dark-800 text-brand-500 hover:bg-dark-900 flex items-center justify-center font-bold">&gt;</button>
                </div>
            </div>

            <!-- Grid Menu -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                @php
                    $menuFavorites = [
                        ['name' => 'NASI GORENG SPESIAL', 'price' => 35000, 'category' => 'Makanan Utama'],
                        ['name' => 'MIE GORENG SEAFOOD', 'price' => 38000, 'category' => 'Makanan Utama'],
                        ['name' => 'AYAM BAKAR MADU', 'price' => 42000, 'category' => 'Makanan Utama'],
                        ['name' => 'ES TEH MANIS', 'price' => 8000, 'category' => 'Minuman'],
                        ['name' => 'JUS ALPUKAT', 'price' => 18000, 'category' => 'Minuman'],
                        ['name' => 'ES CAMPUR', 'price' => 20000, 'category' => 'Dessert'],
                    ];
                @endphp

                @foreach($menuFavorites as $menu)
                    <div class="bg-dark-900 border border-dark-800 rounded-2xl p-4 hover:border-brand-500/20 hover:scale-[1.02] transition-all flex flex-col justify-between group">
                        <!-- Image Container: Empty Placeholder (As requested: "gw aja yang ngisi") -->
                        <div class="aspect-square rounded-xl border border-dashed border-dark-700 bg-dark-950 flex flex-col items-center justify-center p-3 relative overflow-hidden group-hover:border-brand-500 transition-colors">
                            <span class="text-[9px] font-bold text-dark-500 uppercase tracking-widest text-center">Tarik Foto Ke Sini</span>
                            <span class="text-[8px] text-dark-600 text-center mt-1 font-mono italic">{{ $menu['category'] }}</span>

                            <!-- "LIHAT MENU" Button Overlay -->
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center z-10">
                                <a href="#simulator" class="px-4 py-2 bg-brand-500 text-white text-[10px] font-bold uppercase rounded-lg shadow-md hover:scale-105 transform transition-transform">
                                    LIHAT MENU
                                </a>
                            </div>
                        </div>

                        <!-- Menu details -->
                        <div class="mt-4">
                            <h3 class="font-extrabold text-sm text-brand-500 leading-tight truncate uppercase mb-1">{{ $menu['name'] }}</h3>
                            <p class="text-xs text-white font-bold">MULAI DARI RP {{ number_format($menu['price'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 5. TESTIMONIAL BANNER (SajiHUB Dark Branding with Gradient Overlay) --}}
    <section id="testimoni" class="relative bg-dark-950 overflow-hidden aspect-[21/9] min-h-[300px] flex items-end border-t border-b border-dark-800">
        {{-- Background Image --}}
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('https://images.unsplash.com/photo-1543007630-9710e4a00a20?auto=format&fit=crop&w=1920&q=80');"></div>
        {{-- Smooth diagonal dark gradient overlay for organic text blending and color preservation on the right --}}
        <div class="absolute inset-0 bg-gradient-to-tr from-black/95 via-black/45 to-transparent z-10"></div>

        <div class="w-full px-4 sm:px-8 lg:px-12 pb-8 pt-20 relative z-20">
            <div class="w-full max-w-none drop-shadow-[0_4px_16px_rgba(0,0,0,0.95)]">
                <!-- Top: Rating block matching Photo 1 -->
                <div class="text-white text-sm sm:text-base lg:text-[17px] font-bold tracking-wide mb-4 drop-shadow-sm">
                    <div class="font-extrabold text-white">★★★★★ Rating Kepuasan Pelanggan 4.9</div>
                    <div class="text-gray-300 font-medium">Ribuan Pelanggan Setia</div>
                </div>

                <!-- Middle: Heading matching Photo 1 -->
                <h2 class="font-black text-white uppercase tracking-tight mb-5 whitespace-nowrap" style="font-size: clamp(2.8rem, 6.5vw, 5.8rem); line-height: 0.95;">
                    ULASAN PELANGGAN
                </h2>

                <!-- Bottom: Two-line details matching Photo 1 -->
                <div class="text-white text-sm sm:text-base lg:text-lg leading-relaxed drop-shadow-md font-bold max-w-5xl">
                    <div class="font-black text-brand-400 uppercase tracking-widest mb-1.5">Ribuan testimoni positif dari pecinta kuliner SajiHUB setiap harinya.</div>
                    <div class="text-gray-150 text-gray-100">"Cita rasa bumbunya meresap sempurna, sajian menu bervariasi, porsi mengenyangkan, dengan harga bersahabat dan tempat makan yang bersih dan nyaman."</div>
                </div>
            </div>
        </div>
    </section>

    {{-- 6. PREMIUM FOUR-COLUMN FOOTER (SajiHUB Dark Branding - Gacoan Structured) --}}
    <footer class="bg-dark-950 text-dark-300 text-lg py-20 border-t border-dark-900">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-14 pb-12 border-b border-dark-900">
                
                {{-- Column 1: Newsletter signup (4 cols) --}}
                <div class="lg:col-span-4 space-y-6">
                    <h3 class="font-black text-white text-lg sm:text-[21px] uppercase tracking-wider mb-2">BERGABUNG BERSAMA SAJIHUB</h3>
                    <p class="text-gray-300 leading-relaxed text-[15px] sm:text-[16px]">Masukkan email Anda untuk berlangganan info promo terbaru dan penawaran khusus dari SajiHUB Resto.</p>
                    <div class="relative w-full max-w-[340px]">
                        <input type="email" placeholder="Enter Email Address" class="w-full bg-dark-900 border border-dark-800 text-white rounded-none px-4 py-4 text-base focus:outline-none focus:border-brand-500 transition-colors">
                        <button class="absolute right-0 top-0 bottom-0 px-5 bg-brand-500 hover:bg-brand-600 text-white flex items-center justify-center transition-colors">
                            &rarr;
                        </button>
                    </div>
                </div>

                {{-- Column 2: Navigation Links (2 cols) --}}
                <div class="lg:col-span-2 space-y-6">
                    <h3 class="font-black text-white text-lg sm:text-[21px] uppercase tracking-wider mb-2">Navigasi SajiHUB</h3>
                    <ul class="space-y-4 text-[15px] sm:text-[16px] font-bold">
                        <li><a href="#beranda" class="hover:text-brand-500 transition-colors">Beranda</a></li>
                        <li><a href="#menu-favorit" class="hover:text-brand-500 transition-colors">Menu Favorit</a></li>
                        <li><a href="#testimoni" class="hover:text-brand-500 transition-colors">Testimoni</a></li>
                        <li><a href="#" class="hover:text-brand-500 transition-colors">Karir</a></li>
                        <li><a href="#" class="hover:text-brand-500 transition-colors">About Us</a></li>
                    </ul>
                </div>

                {{-- Column 3: Partner Links (2 cols) --}}
                <div class="lg:col-span-2 space-y-6">
                    <h3 class="font-black text-white text-lg sm:text-[21px] uppercase tracking-wider mb-2">Kemitraan</h3>
                    <ul class="space-y-4 text-[15px] sm:text-[16px] font-bold">
                        <li><a href="#" class="hover:text-brand-500 transition-colors">Informasi Kemitraan</a></li>
                        <li><a href="#" class="hover:text-brand-500 transition-colors">Hubungi Kemitraan</a></li>
                        <li><a href="#" class="hover:text-brand-500 transition-colors">Kontak Kami</a></li>
                        <li><a href="#" class="hover:text-brand-500 transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-brand-500 transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>

                {{-- Column 4: Description, CS and Office (4 cols) --}}
                <div class="lg:col-span-4 space-y-6">
                    <p class="leading-relaxed text-[15px] sm:text-[16px] text-gray-300">
                        SajiHUB berkomitmen menghadirkan pengalaman kuliner Nusantara terbaik dengan cita rasa otentik bumbu meresap, harga ramah di kantong, serta mengutamakan kebersihan dan pelayanan prima di setiap cabang kami.
                    </p>
                    <div class="space-y-1 text-[15px] sm:text-[16px]">
                        <h4 class="font-black text-white uppercase tracking-wider">CUSTOMER SUPPORT:</h4>
                        <p class="text-brand-400 font-extrabold hover:underline cursor-pointer">@sajihub.resto</p>
                        <p class="text-gray-300 font-semibold">0813-9889-7488 (WhatsApp)</p>
                    </div>
                    <div class="space-y-1 text-[15px] sm:text-[16px]">
                        <h4 class="font-black text-white uppercase tracking-wider">ALAMAT KANTOR PUSAT:</h4>
                        <p class="text-gray-300 font-semibold">Jakarta, Indonesia</p>
                    </div>
                </div>

            </div>

            {{-- Bottom info: copyright (PT. DAVIN GALUH PARTNER) --}}
            <div class="pt-8 text-center space-y-3">
                <div class="font-bold text-base sm:text-lg text-white tracking-widest uppercase">PT. DAVIN GALUH PARTNER</div>
                <p class="text-xs sm:text-sm text-dark-500 font-medium">Privacy Policy &copy; {{ date('Y') }} SajiHUB Restaurant Systems. Seluruh Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        // Interactive Hero Banner Slider
        const heroSlides = [
            {
                image: "https://images.unsplash.com/photo-1514933651103-005eec06c04b?auto=format&fit=crop&w=1920&q=80",
                heading1: "LOKASI",
                heading2: "SAJIHUB RESTO",
                sub: "Cabang Resmi Pilihan Keluarga!",
                desc: "Nikmati menu terpopuler khas Nusantara, harga ramah kantong, dengan suasana makan nyaman dan pelayanan cepat!"
            },
            {
                image: "https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1920&q=80",
                heading1: "CITA RASA",
                heading2: "MENU ANDALAN",
                sub: "Kuliner Lezat & Higienis!",
                desc: "Pilihan bahan segar berkualitas premium yang dimasak secara higienis oleh chef andalan kami khusus untuk Anda."
            },
            {
                image: "https://images.unsplash.com/photo-1543007630-9710e4a00a20?auto=format&fit=crop&w=1920&q=80",
                heading1: "ULASAN",
                heading2: "PELANGGAN SETIA",
                sub: "Rating Kepuasan Bintang 5!",
                desc: "Ribuan testimoni puas dari pecinta kuliner yang menyukai rasa bumbu otentik meresap dan harga ramah dompet."
            }
        ];

        let currentHeroSlide = 0;
        const heroBg = document.getElementById('hero-bg');
        const heroH1 = document.getElementById('hero-heading-1');
        const heroH2 = document.getElementById('hero-heading-2');
        const heroSub = document.getElementById('hero-sub');
        const heroDesc = document.getElementById('hero-desc');

        function updateHeroSlide(index) {
            // Apply fade-out effect
            heroBg.style.opacity = '0.5';
            heroH1.style.opacity = '0';
            heroH2.style.opacity = '0';
            heroSub.style.opacity = '0';
            heroDesc.style.opacity = '0';

            setTimeout(() => {
                const slide = heroSlides[index];
                heroBg.style.backgroundImage = `url('${slide.image}')`;
                heroH1.textContent = slide.heading1;
                heroH2.textContent = slide.heading2;
                heroSub.textContent = slide.sub;
                heroDesc.textContent = slide.desc;

                // Fade back in
                heroBg.style.opacity = '1';
                heroH1.style.opacity = '1';
                heroH2.style.opacity = '1';
                heroSub.style.opacity = '1';
                heroDesc.style.opacity = '1';
            }, 300);
        }

        document.getElementById('hero-btn-prev').addEventListener('click', () => {
            currentHeroSlide = (currentHeroSlide - 1 + heroSlides.length) % heroSlides.length;
            updateHeroSlide(currentHeroSlide);
        });

        document.getElementById('hero-btn-next').addEventListener('click', () => {
            currentHeroSlide = (currentHeroSlide + 1) % heroSlides.length;
            updateHeroSlide(currentHeroSlide);
        });

        // Dynamic ScrollSpy for Navbar Tautan Aktif
        const sections = document.querySelectorAll('section');
        const navLinks = document.querySelectorAll('.nav-link');

        function activateScrollSpy() {
            let current = 'beranda';
            const scrollPosition = window.scrollY + 120; // 120px offset for navbar height and margins

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.offsetHeight;
                const sectionId = section.getAttribute('id');
                if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                    if (sectionId) {
                        current = sectionId;
                    }
                }
            });

            // If we are at the top of the page, default to beranda
            if (window.scrollY < 50) {
                current = 'beranda';
            }

            navLinks.forEach(link => {
                link.classList.remove('text-brand-500', 'border-brand-500');
                link.classList.add('border-transparent');
                
                const href = link.getAttribute('href');
                if (href === `#${current}`) {
                    link.classList.remove('border-transparent');
                    link.classList.add('text-brand-500', 'border-brand-500');
                }
            });
        }

        window.addEventListener('scroll', activateScrollSpy);
        // Run immediately on page load
        activateScrollSpy();
    </script>
</body>
</html>
