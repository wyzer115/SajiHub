<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Pesan Menu - Waroeng SajiHUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.alert = function(message, title = 'Pemberitahuan') {
            Swal.fire({
                title: title,
                text: message,
                icon: 'warning',
                confirmButtonColor: '#BD2000',
                confirmButtonText: 'Mengerti',
                customClass: { popup: 'rounded-3xl shadow-2xl font-sans' }
            });
        };
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8F5] text-[#1C1917] font-sans antialiased overflow-y-auto pb-24">

    {{-- Header --}}
    <header class="border-b border-stone-200 bg-white sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center group">
                <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" class="h-10 w-auto object-contain drop-shadow-sm group-hover:scale-105 transition-transform">
            </a>
            
            <div class="flex items-center gap-3">
                <button type="button" onclick="openQrScannerModal()" class="px-3.5 py-1.5 text-xs font-bold text-[#BD2000] bg-white border border-[#BD2000] hover:bg-[#BD2000]/10 rounded-xl transition-all flex items-center gap-1.5 cursor-pointer shadow-sm">
                    <svg class="w-4 h-4 text-[#BD2000]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Scan QR Meja
                </button>
                @auth
                    <span class="text-xs font-bold text-stone-700 hidden sm:inline">Halo, <span class="text-[#BD2000] font-black">{{ auth()->user()->name }}</span></span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 text-xs font-bold text-stone-700 bg-stone-100 hover:bg-stone-200 rounded-xl transition-all cursor-pointer">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-bold text-white bg-[#BD2000] hover:bg-[#8C0000] rounded-xl transition-all shadow-md">
                        Masuk Akun
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
        
        {{-- Flash Notification --}}
        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 rounded-2xl flex items-center gap-3 font-semibold text-sm">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        {{-- 1. IF NO BRANCH SELECTED --}}
        @if(!$selectedBranch)
            <div class="text-center max-w-2xl mx-auto py-16">
                <h1 class="text-3xl font-black text-[#8C0000] mb-3">Mau Makan di Cabang Mana?</h1>
                <p class="text-slate-600 mb-8 font-medium">Pilih lokasi cabang Restoran SajiHUB tempat Anda berada sekarang untuk mulai memesan makanan.</p>
                
                <div class="grid md:grid-cols-2 gap-6 text-left">
                    @foreach($branches as $branch)
                        <a href="?branch_id={{ $branch->id }}" class="p-6 bg-white border border-stone-200 rounded-3xl shadow-sm hover:shadow-xl hover:border-[#BD2000]/40 transition-all block group">
                            <h3 class="text-xl font-extrabold text-[#1C1917] group-hover:text-[#BD2000] transition-colors mb-2">{{ $branch->name }}</h3>
                            <p class="text-slate-600 text-sm mb-4 font-medium">{{ $branch->address }}</p>
                            <span class="text-xs font-extrabold text-[#BD2000] flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                                Pilih Cabang Ini &rightarrow;
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            {{-- 2. BRANCH SELECTED — SHOW ORDER SYSTEM --}}
            <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 pb-6 border-b border-stone-200">
                <div>
                    <span class="text-xs font-bold text-[#BD2000] uppercase tracking-widest bg-[#BD2000]/10 px-3 py-1 rounded-full border border-[#BD2000]/20">Cabang Pilihan</span>
                    <h1 class="text-2xl lg:text-3xl font-black text-[#8C0000] uppercase mt-2">{{ $selectedBranch->name }}</h1>
                    <p class="text-slate-600 text-sm mt-1 font-medium">{{ $selectedBranch->address }}</p>
                </div>
                @if(!isset($selectedTable))
                    <a href="{{ route('pesan') }}" class="text-xs font-bold text-stone-700 hover:text-[#BD2000] px-4 py-2 border border-stone-300 bg-white hover:bg-stone-50 rounded-xl transition-all shadow-sm">
                        &larr; Ganti Cabang
                    </a>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-[#BD2000]/10 text-[#BD2000] border border-[#BD2000]/20">
                        <svg class="w-4 h-4 text-[#BD2000]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Cabang Terkunci (Hasil Scan QR)
                    </span>
                @endif
            </div>

            {{-- Ordering System Layout --}}
            <div class="grid lg:grid-cols-3 gap-8">
                
                {{-- Column Left/Middle — Menu & Meja Selector --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- Form Meja & Items --}}
                    <form id="order-form" action="{{ route('pesan.store') }}" method="POST" class="space-y-8">
                        @csrf
                        <input type="hidden" name="branch_id" value="{{ $selectedBranch->id }}">
                        <input type="hidden" name="payment_method" id="payment_method_input" value="cash">

                        {{-- Table Selector (Locked if QR Scanned) --}}
                        @if(isset($selectedTable))
                            <div class="p-6 bg-white border-2 border-[#BD2000]/40 rounded-3xl shadow-md relative overflow-hidden">
                                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-[#BD2000]/10 border border-[#BD2000]/20 flex items-center justify-center text-[#BD2000] flex-shrink-0">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </div>
                                        <div>
                                            <span class="text-[10px] font-extrabold text-[#BD2000] uppercase tracking-widest block">Nomor Meja Terkunci (Hasil Scan QR)</span>
                                            <h3 class="text-2xl font-black text-[#1C1917]">
                                                {{ Str::startsWith($selectedTable->table_number, 'Meja') ? $selectedTable->table_number : 'Meja ' . $selectedTable->table_number }}
                                                <span class="text-xs font-bold text-slate-500 ml-2">(Kapasitas: {{ $selectedTable->capacity ?? 4 }} Kursi)</span>
                                            </h3>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        Meja Terkunci (Dine-In)
                                    </span>
                                </div>
                                <input type="hidden" name="table_id" id="table_id" value="{{ $selectedTable->id }}">
                            </div>
                        @else
                            <div class="p-6 bg-white border border-stone-200 rounded-3xl shadow-sm">
                                <h3 class="text-lg font-black text-[#8C0000] mb-4 flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#BD2000]"></span>
                                    Pilih Nomor Meja Anda
                                </h3>
                                <div class="grid sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="table_id" class="block text-xs font-bold text-stone-700 mb-1.5 uppercase">Nomor Meja *</label>
                                        <select name="table_id" id="table_id" required class="block w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-[#1C1917] text-sm font-semibold focus:outline-none focus:border-[#BD2000]">
                                            <option value="">-- Pilih Nomor Meja --</option>
                                            @foreach($tables as $table)
                                                <option value="{{ $table->id }}" 
                                                    {{ old('table_id') == $table->id ? 'selected' : '' }}
                                                    {{ $table->status == 'occupied' ? 'disabled class=text-stone-400' : '' }}>
                                                    {{ Str::startsWith($table->table_number, 'Meja') ? $table->table_number : 'Meja ' . $table->table_number }} ({{ $table->status == 'occupied' ? 'Sedang Digunakan' : 'Tersedia' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                     <div class="flex items-center gap-2.5 text-xs text-slate-600 font-medium leading-relaxed bg-stone-50 p-4 border border-stone-200 rounded-xl">
                                         <svg class="w-5 h-5 text-[#BD2000] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                         <span>Silakan periksa nomor yang tertera di meja fisik restoran tempat Anda duduk sekarang.</span>
                                     </div>
                                </div>
                            </div>
                        @endif

                        {{-- Menu Grid --}}
                        <div>
                            <h3 class="text-xl font-black text-[#8C0000] mb-6 uppercase tracking-tight">Menu Makanan & Minuman</h3>
                            
                            @if($menus->isEmpty())
                                <p class="text-slate-500 text-sm font-medium">Tidak ada menu yang tersedia untuk cabang ini saat ini.</p>
                            @else
                                <div class="grid sm:grid-cols-2 gap-6">
                                    @foreach($menus as $menu)
                                        @php
                                            $isSoldOut = $menu->status == 'sold_out' || $menu->ingredients->contains(fn($ing) => optional($ing->inventory)->stock <= 0);
                                        @endphp
                                        <div class="p-4 bg-white border {{ $isSoldOut ? 'border-red-300 bg-red-50/50' : 'border-stone-200 hover:border-[#BD2000]/40' }} rounded-2xl flex gap-4 items-start group transition-all shadow-sm relative overflow-hidden">
                                             {{-- Menu Image --}}
                                             @if($menu->image)
                                                 <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-20 h-20 rounded-xl object-cover flex-shrink-0 border border-stone-200 {{ $isSoldOut ? 'grayscale opacity-60' : '' }}">
                                             @else
                                                 <div class="w-20 h-20 rounded-xl border border-stone-200 flex flex-col items-center justify-center bg-stone-100 text-stone-400 flex-shrink-0">
                                                    <svg class="w-6 h-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    <span class="text-[8px] font-bold text-center">NO PHOTO</span>
                                                 </div>
                                             @endif
                                            <div class="flex-grow">
                                                <div class="flex justify-between items-start">
                                                    <span class="text-[10px] font-extrabold bg-[#BD2000]/10 text-[#BD2000] px-2 py-0.5 rounded">{{ $menu->category->name ?? 'Menu' }}</span>
                                                    @if($isSoldOut)
                                                        <span class="text-[10px] font-bold bg-red-100 text-red-700 px-2 py-0.5 rounded-full border border-red-200">SOLD OUT</span>
                                                    @endif
                                                </div>
                                                <h4 class="text-[#1C1917] font-black text-sm mt-1.5">{{ $menu->name }}</h4>
                                                <div class="text-[#BD2000] font-black text-sm mt-1">Rp {{ number_format($menu->price, 0, ',', '.') }}</div>
                                                
                                                {{-- Quantity Selector --}}
                                                <div class="mt-3 flex items-center justify-between">
                                                    @if($isSoldOut)
                                                        <span class="text-xs font-bold text-red-600 bg-red-100 px-3 py-1 rounded-lg border border-red-200">Stok Menu Habis</span>
                                                    @else
                                                        <div class="flex items-center border border-stone-300 bg-stone-50 rounded-lg overflow-hidden">
                                                            <button type="button" onclick="decrementQty({{ $menu->id }})" class="px-2.5 py-1 text-stone-600 hover:text-[#BD2000] font-black text-sm select-none cursor-pointer">-</button>
                                                            <span id="qty-display-{{ $menu->id }}" class="px-3 py-1 text-xs text-[#1C1917] font-black min-w-[20px] text-center select-none">0</span>
                                                            <button type="button" onclick="incrementQty({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }})" class="px-2.5 py-1 text-stone-600 hover:text-[#BD2000] font-black text-sm select-none cursor-pointer">+</button>
                                                        </div>
                                                        <div id="hidden-inputs-{{ $menu->id }}"></div>
                                                    @endif
                                                </div>
                                                
                                                {{-- Note Input --}}
                                                @if(!$isSoldOut)
                                                <div class="mt-2 hidden" id="note-container-{{ $menu->id }}">
                                                    <input type="text" placeholder="Catatan (misal: pedas sekali)" onchange="updateNote({{ $menu->id }}, this.value)"
                                                        class="w-full bg-stone-50 border border-stone-300 rounded-lg px-2.5 py-1 text-[11px] text-[#1C1917] focus:outline-none focus:border-[#BD2000] font-medium">
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Column Right — Cart Summary --}}
                <div>
                    <div class="p-6 bg-white border border-stone-200 rounded-3xl shadow-md space-y-5">
                        <h3 class="text-lg font-black text-[#8C0000] flex items-center justify-between border-b border-stone-200 pb-4">
                            <span>Keranjang</span>
                            <span id="cart-count" class="text-xs font-extrabold bg-[#BD2000]/10 text-[#BD2000] px-3 py-1 rounded-full">0 Item</span>
                        </h3>
                        
                        {{-- Cart Items List --}}
                        <div id="cart-items" class="space-y-3 max-h-52 overflow-y-auto text-sm text-stone-600">
                            <div id="cart-empty" class="text-center py-6 text-slate-400 font-medium text-xs">
                                Keranjang kosong. Silakan tambah menu di sebelah kiri.
                            </div>
                        </div>

                        {{-- Card Field: Nama Pemesan & Catatan --}}
                        <div class="border-t border-stone-200 pt-4 space-y-3">
                            <div>
                                <label for="customer_name" class="block text-xs font-bold text-stone-700 uppercase mb-1.5">Nama Pemesan *</label>
                                <input type="text" form="order-form" name="customer_name" id="customer_name" required placeholder="Masukkan Nama Pemesan..."
                                    class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] rounded-xl px-4 py-2.5 text-xs font-semibold focus:border-[#BD2000] focus:outline-none transition-all">
                            </div>
                            <div>
                                <label for="order_notes" class="block text-xs font-bold text-stone-700 uppercase mb-1.5">Catatan Pesanan (Opsional)</label>
                                <textarea form="order-form" name="order_notes" id="order_notes" rows="2" placeholder="Catatan tambahan (misal: piring terpisah)..."
                                    class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] rounded-xl px-4 py-2.5 text-xs font-medium focus:border-[#BD2000] focus:outline-none resize-none transition-all"></textarea>
                            </div>
                        </div>

                        {{-- Payment Method Selector (Tunai & QRIS Only) --}}
                        <div class="border-t border-stone-200 pt-4">
                            <h4 class="text-xs font-bold text-stone-700 uppercase mb-2">Metode Pembayaran</h4>
                            <div class="grid grid-cols-2 gap-3">
                                <button type="button" id="pay-cash" onclick="setPaymentMethod('cash')"
                                    class="py-3 px-2 rounded-xl border text-xs font-bold text-center transition-all bg-[#BD2000] text-white border-[#BD2000] cursor-pointer flex items-center justify-center gap-1.5 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    Tunai
                                </button>
                                <button type="button" id="pay-qris" onclick="setPaymentMethod('qris')"
                                    class="py-3 px-2 rounded-xl border border-stone-300 text-xs font-bold text-center text-stone-600 hover:text-[#BD2000] transition-all cursor-pointer flex items-center justify-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    QRIS
                                </button>
                            </div>
                        </div>

                        {{-- Instant Payment Visual Guide (Dynamic QRIS Image) --}}
                        <div id="payment-guide" class="hidden p-4 rounded-2xl bg-[#BD2000]/5 border border-[#BD2000]/20 text-xs text-stone-600 space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span class="font-black text-[#1C1917] uppercase" id="guide-title">Bayar Langsung</span>
                            </div>
                            <p id="guide-text" class="text-[11px] leading-relaxed font-medium">Pesanan otomatis lunas dan langsung masuk ke dapur untuk dimasak.</p>
                            
                            <!-- Real Official QRIS Image -->
                            <div id="qris-qr" class="hidden flex flex-col items-center justify-center p-3 bg-white rounded-2xl mx-auto border border-stone-200 mt-2 animate-fade-in shadow-md max-w-[240px]">
                                <img src="{{ asset('images/qris.jpg') }}" alt="QRIS Resmi Warung Akid" class="w-full h-auto rounded-xl object-contain">
                                <p class="text-[9px] text-stone-800 font-extrabold mt-2 text-center uppercase tracking-tight">NMID: ID1026528881513 (WARUNG AKID)</p>
                            </div>
                        </div>

                        {{-- Total Price --}}
                        <div class="border-t border-stone-200 pt-4 flex items-center justify-between">
                            <span class="text-sm font-bold text-stone-700">Total Harga:</span>
                            <span id="cart-total" class="text-xl font-black text-[#BD2000]">Rp 0</span>
                        </div>

                        {{-- Order Submit --}}
                        <button type="button" onclick="submitOrder()" id="btn-submit" disabled
                            class="w-full py-4 rounded-xl bg-[#BD2000] text-white font-extrabold shadow-lg hover:bg-[#8C0000] transition-all duration-200 opacity-50 cursor-not-allowed">
                            Kirim Pesanan ke Kasir
                        </button>
                        
                        <p class="text-[10px] text-slate-500 text-center leading-relaxed font-medium flex items-center justify-center gap-1">
                            <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>Setelah mengirim pesanan, silakan tunjukkan nama Anda ke Kasir untuk konfirmasi / pembayaran.</span>
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </main>

    <script>
        // Simple state-management for cart
        const cart = {};

        function setPaymentMethod(method) {
            const btnCash = document.getElementById('pay-cash');
            const btnQris = document.getElementById('pay-qris');
            const inputVal = document.getElementById('payment_method_input');
            const guide = document.getElementById('payment-guide');
            const guideTitle = document.getElementById('guide-title');
            const guideText = document.getElementById('guide-text');
            const qrisQr = document.getElementById('qris-qr');
            const btnSubmit = document.getElementById('btn-submit');

            // Reset classes
            [btnCash, btnQris].forEach(btn => {
                if (btn) btn.className = "py-3 px-2 rounded-xl border border-stone-300 text-xs font-bold text-center text-stone-600 hover:text-[#BD2000] transition-all cursor-pointer flex items-center justify-center gap-1.5";
            });

            inputVal.value = method;

            if (method === 'cash') {
                btnCash.className = "py-3 px-2 rounded-xl border text-xs font-bold text-center transition-all bg-[#BD2000] text-white border-[#BD2000] cursor-pointer flex items-center justify-center gap-1.5 shadow-sm";
                guide.classList.add('hidden');
                btnSubmit.textContent = "Kirim Pesanan ke Kasir";
            } else {
                guide.classList.remove('hidden');
                btnSubmit.textContent = "Bayar QRIS & Kirim ke Dapur";
                btnQris.className = "py-3 px-2 rounded-xl border text-xs font-bold text-center transition-all bg-[#BD2000] text-white border-[#BD2000] cursor-pointer flex items-center justify-center gap-1.5 shadow-sm";
                guideTitle.textContent = "PEMBAYARAN QRIS RESMI";
                guideText.textContent = "Scan kode QRIS di bawah ini dengan aplikasi m-banking / e-wallet Anda. Setelah scan & bayar, pesanan otomatis langsung dimasak koki di Dapur!";
                qrisQr.classList.remove('hidden');
            }
        }

        function incrementQty(menuId, name, price) {
            if (!cart[menuId]) {
                cart[menuId] = { name, price, quantity: 0, notes: '' };
            }
            cart[menuId].quantity += 1;
            
            // Show note container
            document.getElementById(`note-container-${menuId}`).classList.remove('hidden');
            
            updateUI(menuId);
        }

        function decrementQty(menuId) {
            if (cart[menuId] && cart[menuId].quantity > 0) {
                cart[menuId].quantity -= 1;
                
                if (cart[menuId].quantity === 0) {
                    // Hide note container
                    document.getElementById(`note-container-${menuId}`).classList.add('hidden');
                    delete cart[menuId];
                }
                
                updateUI(menuId);
            }
        }

        function updateNote(menuId, value) {
            if (cart[menuId]) {
                cart[menuId].notes = value;
                updateInputs(menuId);
            }
        }

        function updateUI(menuId) {
            const qty = cart[menuId] ? cart[menuId].quantity : 0;
            document.getElementById(`qty-display-${menuId}`).textContent = qty;
            
            updateInputs(menuId);
            renderCart();
        }

        function updateInputs(menuId) {
            const container = document.getElementById(`hidden-inputs-${menuId}`);
            container.innerHTML = '';
            
            if (cart[menuId]) {
                const item = cart[menuId];
                container.innerHTML = `
                    <input type="hidden" name="items[${menuId}][menu_id]" value="${menuId}">
                    <input type="hidden" name="items[${menuId}][quantity]" value="${item.quantity}">
                    <input type="hidden" name="items[${menuId}][notes]" value="${item.notes}">
                `;
            }
        }

        function renderCart() {
            const cartItemsContainer = document.getElementById('cart-items');
            const cartEmpty = document.getElementById('cart-empty');
            const cartCount = document.getElementById('cart-count');
            const cartTotal = document.getElementById('cart-total');
            const btnSubmit = document.getElementById('btn-submit');
            
            cartItemsContainer.innerHTML = '';
            
            let total = 0;
            let count = 0;
            const items = Object.entries(cart);
            
            if (items.length === 0) {
                cartItemsContainer.appendChild(cartEmpty);
                btnSubmit.disabled = true;
                btnSubmit.className = "w-full py-4 rounded-xl bg-[#BD2000] text-white font-extrabold shadow-lg opacity-50 cursor-not-allowed";
            } else {
                btnSubmit.disabled = false;
                btnSubmit.className = "w-full py-4 rounded-xl bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold shadow-lg transition-all duration-200 cursor-pointer transform hover:scale-[1.01]";
                
                items.forEach(([menuId, item]) => {
                    const row = document.createElement('div');
                    row.className = "flex justify-between items-center border-b border-stone-200 pb-3";
                    row.innerHTML = `
                        <div class="flex-grow pr-3">
                            <div class="font-black text-[#1C1917] text-sm">${item.name} <span class="text-[#BD2000] text-xs ml-1 font-extrabold">x${item.quantity}</span></div>
                            ${item.notes ? `<div class="text-[11px] text-slate-500 italic mt-0.5 font-medium">"${item.notes}"</div>` : ''}
                        </div>
                        <div class="text-[#BD2000] font-black flex-shrink-0 text-sm">
                            Rp ${(item.price * item.quantity).toLocaleString('id-ID')}
                        </div>
                    `;
                    cartItemsContainer.appendChild(row);
                    total += item.price * item.quantity;
                    count += item.quantity;
                });
            }
            
            cartCount.textContent = count + ' Item';
            cartTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        function submitOrder() {
            const tableInput = document.getElementById('table_id');
            const nameInput = document.getElementById('customer_name');

            if (!tableInput || !tableInput.value) {
                alert('Silakan pilih nomor meja Anda terlebih dahulu!');
                if (tableInput) tableInput.focus();
                return;
            }

            if (!nameInput || !nameInput.value.trim()) {
                alert('Silakan masukkan Nama Pemesan Anda terlebih dahulu!');
                if (nameInput) nameInput.focus();
                return;
            }
            
            document.getElementById('order-form').submit();
        }
    </script>

    <!-- Camera QR Scanner Modal -->
    <div id="qr-scanner-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">
        <div class="w-full max-w-md bg-white border border-stone-200 rounded-3xl p-6 shadow-2xl space-y-5 text-center relative">
            <div class="flex justify-between items-center border-b border-stone-200 pb-3">
                <h3 class="text-base font-black text-[#8C0000] uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#BD2000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Pindai Kode QR Meja</span>
                </h3>
                <button type="button" onclick="closeQrScannerModal()" class="text-stone-400 hover:text-stone-700 bg-stone-100 p-1.5 rounded-full border border-stone-200 transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <p class="text-xs text-slate-600 font-medium leading-relaxed">
                Arahkan kamera HP Anda ke stiker QR Code yang tertera di meja restoran untuk langsung mengunci meja & memilih menu.
            </p>

            <div id="qr-reader-container" class="w-full bg-stone-50 rounded-2xl p-2 border border-stone-200 min-h-[250px] flex flex-col items-center justify-center relative overflow-hidden">
                <div id="qr-reader" class="w-full"></div>
            </div>

            <div class="pt-2 flex flex-col gap-2">
                <button type="button" onclick="closeQrScannerModal()" class="w-full py-3 bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold text-xs uppercase rounded-xl border border-stone-300 transition-all cursor-pointer">
                    Tutup Kamera
                </button>
            </div>
        </div>
    </div>

    <script>
        let html5QrCodeScannerInstance = null;

        function openQrScannerModal() {
            const modal = document.getElementById('qr-scanner-modal');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100');

            if (!html5QrCodeScannerInstance) {
                html5QrCodeScannerInstance = new Html5QrcodeScanner("qr-reader", { 
                    fps: 10, 
                    qrbox: { width: 220, height: 220 },
                    rememberLastUsedCamera: true
                });
                
                html5QrCodeScannerInstance.render(onScanSuccess, onScanError);
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            console.log("QR Code Scanned:", decodedText);
            if (decodedText) {
                if (html5QrCodeScannerInstance) {
                    html5QrCodeScannerInstance.clear();
                    html5QrCodeScannerInstance = null;
                }
                window.location.href = decodedText;
            }
        }

        function onScanError(errorMessage) {
            // silent continuous polling scan error
        }

        function closeQrScannerModal() {
            const modal = document.getElementById('qr-scanner-modal');
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0', 'pointer-events-none');

            if (html5QrCodeScannerInstance) {
                html5QrCodeScannerInstance.clear().then(() => {
                    html5QrCodeScannerInstance = null;
                }).catch(err => {
                    html5QrCodeScannerInstance = null;
                });
            }
        }
    </script>
</body>
</html>
