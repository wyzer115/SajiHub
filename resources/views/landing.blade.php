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
        .scrollbar-none::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-none {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="antialiased overflow-x-hidden bg-dark-950 text-dark-300">


    {{-- 2. CENTERED NAVBAR --}}
    <nav class="bg-dark-950/90 backdrop-blur-md border-b border-dark-800 sticky top-0 z-40 shadow-lg">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-24">
                {{-- Left Logo --}}
                <a href="#" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" class="h-16 w-auto group-hover:scale-105 transition-transform duration-300">
                </a>

                {{-- Center links --}}
                <div class="hidden lg:flex items-center gap-10 text-[13.5px] sm:text-[14.5px] font-black uppercase tracking-widest text-dark-300">
                    <a href="#beranda" class="nav-link text-brand-500 border-brand-500 py-1.5 border-b-2 hover:text-brand-500 transition-all">Beranda</a>
                    <a href="#menu-favorit" class="nav-link border-transparent py-1.5 border-b-2 hover:text-brand-500 transition-all">Menu Favorit</a>
                    <a href="#testimoni" class="nav-link border-transparent py-1.5 border-b-2 hover:text-brand-500 transition-all">Testimoni</a>
                </div>

                {{-- Right Actions --}}
                <div class="flex items-center gap-4">
                    @auth
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-extrabold text-dark-300 hidden sm:inline">Halo, <span class="text-brand-500 font-black">{{ auth()->user()->name }}</span></span>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-6 py-3 text-xs sm:text-[13.5px] font-black uppercase tracking-wider text-white bg-brand-500 hover:bg-brand-600 rounded-xl transition-colors cursor-pointer">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-3 rounded-xl text-xs sm:text-[13.5px] font-black text-white bg-brand-500 hover:bg-brand-600 transition-all shadow-md hover:shadow-brand-500/20 hover:scale-105 transform">
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

        {{-- Left & Right Arrow Navigation (Orange branding color and z-30 clickable index) --}}
        <button id="hero-btn-prev" class="absolute left-6 top-1/2 -translate-y-1/2 z-30 text-brand-500 hover:scale-110 transition-transform bg-black/40 p-2.5 rounded-full border border-dark-800 cursor-pointer">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <button id="hero-btn-next" class="absolute right-6 top-1/2 -translate-y-1/2 z-30 text-brand-500 hover:scale-110 transition-transform bg-black/40 p-2.5 rounded-full border border-dark-800 cursor-pointer">
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
                <div class="flex items-center gap-2 relative z-20">
                    <button id="favorit-btn-prev" class="w-8 h-8 rounded-full border border-dark-800 text-brand-500 hover:bg-dark-900 flex items-center justify-center font-bold cursor-pointer transition-colors">&lt;</button>
                    <button id="favorit-btn-next" class="w-8 h-8 rounded-full border border-dark-800 text-brand-500 hover:bg-dark-900 flex items-center justify-center font-bold cursor-pointer transition-colors">&gt;</button>
                </div>
            </div>

            <!-- Slider Menu Container -->
            <div id="favorit-scroll-container" class="flex gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory scrollbar-none pb-4">
                @php
                    $menuFavorites = [
                        ['name' => 'NASI GORENG SPESIAL', 'price' => 35000, 'category' => 'Makanan Utama', 'image' => 'nasi-goreng.jpg'],
                        ['name' => 'MIE GORENG SEAFOOD', 'price' => 38000, 'category' => 'Makanan Utama', 'image' => 'seafood.jpg'],
                        ['name' => 'AYAM BAKAR MADU', 'price' => 42000, 'category' => 'Makanan Utama', 'image' => 'ayam-bakar.jpg'],
                        ['name' => 'ES TEH MANIS', 'price' => 8000, 'category' => 'Minuman', 'image' => 'es-teh.jpg'],
                        ['name' => 'JUS ALPUKAT', 'price' => 18000, 'category' => 'Minuman', 'image' => 'jus-alpukat.jpg'],
                        ['name' => 'ES CAMPUR', 'price' => 20000, 'category' => 'Dessert', 'image' => 'es-campur.jpg'],
                    ];
                @endphp

                @foreach($menuFavorites as $menu)
                    <div class="w-[85%] sm:w-[45%] md:w-[30%] lg:w-[23.5%] flex-shrink-0 bg-dark-900 border border-dark-800 rounded-2xl p-4 hover:border-brand-500/20 hover:scale-[1.02] transition-all flex flex-col justify-between group snap-start">
                        <!-- Image Container -->
                        <div class="aspect-square rounded-xl border border-dark-800 bg-dark-950 flex items-center justify-center relative overflow-hidden group-hover:border-brand-500 transition-colors mb-4">
                            <img src="{{ asset('images/landing/' . $menu['image']) }}" alt="{{ $menu['name'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            
                            <!-- Category Badge -->
                            <div class="absolute top-2 left-2 z-10">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-extrabold uppercase tracking-wider bg-dark-950/80 text-dark-400 border border-dark-800/80 backdrop-blur-sm">
                                    {{ $menu['category'] }}
                                </span>
                            </div>

                            <!-- "LIHAT MENU" Button Overlay -->
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center z-10">
                                <a href="#simulator" class="px-4 py-2 bg-brand-500 text-white text-[10px] font-bold uppercase rounded-lg shadow-md hover:scale-105 transform transition-transform">
                                    LIHAT MENU
                                </a>
                            </div>
                        </div>

                        <!-- Menu details -->
                        <div>
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-24 pb-12 border-b border-dark-900">
                
                {{-- Column 1: Newsletter signup (4 cols) --}}
                <div class="lg:col-span-4 space-y-6">
                    <h3 class="font-black text-white text-lg sm:text-[21px] uppercase tracking-wider mb-2">BERGABUNG BERSAMA SAJIHUB</h3>
                    <p class="text-gray-300 leading-relaxed text-[15px] sm:text-[16px]">Masukkan email Anda untuk berlangganan info promo terbaru dan penawaran khusus dari SajiHUB Resto.</p>
                    <div class="relative w-full max-w-[300px]">
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

        // Favorite Menu Slider Navigation (Scroll behavior)
        const favoritContainer = document.getElementById('favorit-scroll-container');
        const favoritBtnPrev = document.getElementById('favorit-btn-prev');
        const favoritBtnNext = document.getElementById('favorit-btn-next');

        if (favoritContainer && favoritBtnPrev && favoritBtnNext) {
            favoritBtnPrev.addEventListener('click', () => {
                const card = favoritContainer.querySelector('.snap-start');
                const scrollAmount = card ? card.offsetWidth + 24 : 320; // card width + flex gap
                favoritContainer.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            });

            favoritBtnNext.addEventListener('click', () => {
                const card = favoritContainer.querySelector('.snap-start');
                const scrollAmount = card ? card.offsetWidth + 24 : 320; // card width + flex gap
                favoritContainer.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });
        }

        window.addEventListener('scroll', activateScrollSpy);
        // Run immediately on page load
        activateScrollSpy();
    </script>
</body>
</html>
