<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Menu - Waroeng SajiHUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-dark-950 text-dark-300 font-sans antialiased overflow-y-auto pb-24">

    {{-- Header --}}
    <header class="border-b border-dark-800 bg-dark-900/50 backdrop-blur-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center gap-2 group">
                <div class="w-8 h-8 rounded-lg bg-brand-500/20 text-brand-500 flex items-center justify-center border border-brand-500/30">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.559-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-lg font-bold text-white uppercase tracking-tight">Waroeng Saji<span class="text-brand-500">HUB</span></span>
            </a>
            
            <div class="flex items-center gap-4">
                <span class="text-sm font-semibold text-dark-400">Halo, <span class="text-brand-400">{{ auth()->user()->name }}</span></span>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white bg-dark-800 hover:bg-red-500/20 hover:text-red-400 border border-dark-700 rounded-lg transition-all cursor-pointer">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
        
        {{-- Flash Notification --}}
        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl flex items-center gap-3">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="text-sm font-semibold">{{ session('success') }}</div>
            </div>
        @endif

        {{-- 1. IF NO BRANCH SELECTED --}}
        @if(!$selectedBranch)
            <div class="text-center max-w-2xl mx-auto py-16">
                <h1 class="text-3xl font-extrabold text-white mb-3">Mau Makan di Cabang Mana?</h1>
                <p class="text-dark-400 mb-8">Pilih lokasi cabang Waroeng SajiHUB tempat Anda berada sekarang untuk mulai memesan makanan.</p>
                
                <div class="grid md:grid-cols-2 gap-6 text-left">
                    @foreach($branches as $branch)
                        <a href="?branch_id={{ $branch->id }}" class="p-6 bg-dark-900 border border-dark-800 rounded-2xl hover:border-brand-500/30 transition-all block group">
                            <h3 class="text-xl font-bold text-white group-hover:text-brand-500 transition-colors mb-2">{{ $branch->name }}</h3>
                            <p class="text-dark-400 text-sm mb-4">{{ $branch->address }}</p>
                            <span class="text-xs font-semibold text-brand-500 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                                Pilih Cabang Ini &rightarrow;
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            {{-- 2. BRANCH SELECTED — SHOW ORDER SYSTEM --}}
            <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 pb-6 border-b border-dark-800">
                <div>
                    <span class="text-xs font-semibold text-brand-500 uppercase tracking-widest">Cabang Pilihan</span>
                    <h1 class="text-2xl lg:text-3xl font-black text-white uppercase">{{ $selectedBranch->name }}</h1>
                    <p class="text-dark-400 text-sm mt-1">{{ $selectedBranch->address }}</p>
                </div>
                <a href="{{ route('pesan') }}" class="text-xs font-bold text-dark-400 hover:text-white px-4 py-2 border border-dark-800 hover:border-dark-700 bg-dark-900/50 rounded-xl transition-all">
                    &larr; Ganti Cabang
                </a>
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

                        {{-- Table Selector --}}
                        <div class="p-6 bg-dark-900 border border-dark-800 rounded-3xl">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                                Pilih Nomor Meja Anda
                            </h3>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="table_id" class="block text-xs font-medium text-dark-400 mb-1.5 uppercase">Nomor Meja</label>
                                    <select name="table_id" id="table_id" required class="block w-full bg-dark-950 border border-dark-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500">
                                        <option value="">-- Pilih Nomor Meja --</option>
                                        @foreach($tables as $table)
                                            <option value="{{ $table->id }}" 
                                                {{ (isset($selectedTable) && $selectedTable->id == $table->id) || old('table_id') == $table->id ? 'selected' : '' }}
                                                {{ $table->status == 'occupied' && (!isset($selectedTable) || $selectedTable->id != $table->id) ? 'disabled class=text-dark-600' : '' }}>
                                                Meja {{ $table->table_number }} ({{ $table->status == 'occupied' ? 'Sedang Digunakan' : 'Tersedia' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex items-center text-xs text-dark-400 leading-relaxed bg-dark-950 p-4 border border-dark-800 rounded-xl">
                                    ℹ️ Silakan periksa nomor yang tertera di cobek/meja fisik restoran tempat Anda duduk sekarang.
                                </div>
                            </div>
                        </div>

                        {{-- Menu Grid --}}
                        <div>
                            <h3 class="text-xl font-bold text-white mb-6 uppercase tracking-tight">Menu Makanan & Minuman</h3>
                            
                            @if($menus->isEmpty())
                                <p class="text-dark-500 text-sm">Tidak ada menu yang tersedia untuk cabang ini saat ini.</p>
                            @else
                                <div class="grid sm:grid-cols-2 gap-6">
                                    @foreach($menus as $menu)
                                        <div class="p-4 bg-dark-900 border border-dark-800 rounded-2xl flex gap-4 items-start group hover:border-brand-500/10 transition-colors">
                                            {{-- Menu Image Placeholder --}}
                                            <div class="w-20 h-20 rounded-xl border border-dashed border-dark-700 flex flex-col items-center justify-center bg-dark-950 text-dark-500 flex-shrink-0">
                                                <span class="text-[8px] font-semibold text-center">[ Foto ]</span>
                                            </div>
                                            <div class="flex-grow">
                                                <span class="text-[10px] font-semibold bg-brand-500/10 text-brand-400 px-2 py-0.5 rounded">{{ $menu->category->name ?? 'Menu' }}</span>
                                                <h4 class="text-white font-bold text-sm mt-1.5">{{ $menu->name }}</h4>
                                                <div class="text-brand-500 font-extrabold text-sm mt-1">Rp {{ number_format($menu->price, 0, ',', '.') }}</div>
                                                
                                                {{-- Quantity Selector --}}
                                                <div class="mt-3 flex items-center justify-between">
                                                    <div class="flex items-center border border-dark-700 bg-dark-950 rounded-lg overflow-hidden">
                                                        <button type="button" onclick="decrementQty({{ $menu->id }})" class="px-2 py-1 text-dark-400 hover:text-white transition-colors font-bold text-sm select-none">-</button>
                                                        <span id="qty-display-{{ $menu->id }}" class="px-3 py-1 text-xs text-white font-bold min-w-[20px] text-center select-none">0</span>
                                                        <button type="button" onclick="incrementQty({{ $menu->id }}, '{{ $menu->name }}', {{ $menu->price }})" class="px-2 py-1 text-dark-400 hover:text-white transition-colors font-bold text-sm select-none">+</button>
                                                    </div>
                                                    
                                                    {{-- Hidden form elements populated dynamically via JS --}}
                                                    <div id="hidden-inputs-{{ $menu->id }}"></div>
                                                </div>
                                                
                                                {{-- Note Input --}}
                                                <div class="mt-2 hidden" id="note-container-{{ $menu->id }}">
                                                    <input type="text" placeholder="Catatan (misal: pedas sekali)" onchange="updateNote({{ $menu->id }}, this.value)"
                                                        class="w-full bg-dark-950 border border-dark-800 rounded-lg px-2.5 py-1 text-[11px] text-white focus:outline-none focus:border-brand-500">
                                                </div>
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
                    <div class="sticky top-24 p-6 bg-dark-900 border border-dark-800 rounded-3xl space-y-6">
                        <h3 class="text-lg font-bold text-white flex items-center justify-between border-b border-dark-800 pb-4">
                            <span>Keranjang</span>
                            <span id="cart-count" class="text-xs font-semibold bg-brand-500/20 text-brand-500 px-2.5 py-1 rounded-full">0 Item</span>
                        </h3>
                        
                        {{-- Cart Items List --}}
                        <div id="cart-items" class="space-y-4 max-h-60 overflow-y-auto text-sm text-dark-400">
                            <div id="cart-empty" class="text-center py-6 text-dark-500">
                                Keranjang kosong. Silakan tambah menu di sebelah kiri.
                            </div>
                        </div>

                        {{-- Payment Method Selector --}}
                        <div class="border-t border-dark-800 pt-4">
                            <h4 class="text-xs font-bold text-dark-400 uppercase mb-2">Metode Pembayaran</h4>
                            <div class="grid grid-cols-3 gap-2">
                                <button type="button" id="pay-cash" onclick="setPaymentMethod('cash')"
                                    class="py-2.5 px-1 rounded-xl border text-[10px] font-bold text-center transition-all bg-brand-500 text-white border-brand-500 cursor-pointer">
                                    💵 Tunai
                                </button>
                                <button type="button" id="pay-qris" onclick="setPaymentMethod('qris')"
                                    class="py-2.5 px-1 rounded-xl border border-dark-800 text-[10px] font-bold text-center text-dark-400 hover:text-white transition-all cursor-pointer">
                                    📱 QRIS
                                </button>
                                <button type="button" id="pay-transfer" onclick="setPaymentMethod('transfer')"
                                    class="py-2.5 px-1 rounded-xl border border-dark-800 text-[10px] font-bold text-center text-dark-400 hover:text-white transition-all cursor-pointer">
                                    🏦 Transfer
                                </button>
                            </div>
                        </div>

                        {{-- Instant Payment Visual Guide (Dynamic) --}}
                        <div id="payment-guide" class="hidden p-4 rounded-2xl bg-brand-500/5 border border-brand-500/10 text-xs text-dark-400 space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span class="font-bold text-white uppercase" id="guide-title">Bayar Langsung</span>
                            </div>
                            <p id="guide-text" class="text-[11px] leading-relaxed">Pesanan otomatis lunas dan langsung masuk ke dapur untuk dimasak.</p>
                            
                            <!-- Mock QR Code for QRIS -->
                            <div id="qris-qr" class="hidden flex flex-col items-center justify-center p-2.5 bg-white rounded-xl mx-auto w-28 h-28 border border-dark-800 mt-2 animate-fade-in">
                                <div class="w-24 h-24 border-2 border-dashed border-dark-300 flex items-center justify-center text-[8px] text-dark-800 font-bold">
                                    [ SCAN QRIS ]
                                </div>
                            </div>
                            <!-- Mock Bank account for Transfer -->
                            <div id="bank-info" class="hidden bg-dark-950 p-2.5 border border-dark-800 rounded-xl space-y-1 font-mono text-[9px] text-dark-300 mt-2 animate-fade-in">
                                <div>BCA: <span class="text-white font-bold">8490-3888-29</span></div>
                                <div>a/n PT Davin Galuh Partner</div>
                            </div>
                        </div>

                        {{-- Total Price --}}
                        <div class="border-t border-dark-800 pt-4 flex items-center justify-between">
                            <span class="text-sm font-semibold text-dark-400">Total Harga:</span>
                            <span id="cart-total" class="text-xl font-black text-brand-500">Rp 0</span>
                        </div>

                        {{-- Order Submit --}}
                        <button type="button" onclick="submitOrder()" id="btn-submit" disabled
                            class="w-full py-4 rounded-xl bg-gradient-to-r from-brand-600 to-brand-500 text-white font-bold shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all duration-200 opacity-50 cursor-not-allowed">
                            Kirim Pesanan ke Kasir
                        </button>
                        
                        <p class="text-[10px] text-dark-500 text-center leading-relaxed">
                            ⚠️ Setelah mengirim pesanan, silakan tunjukkan ID Pesanan ke Kasir untuk validasi / pembayaran agar pesanan segera disajikan.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- 3. PAST ORDERS LIST --}}
        <div class="mt-16 bg-dark-900 border border-dark-800 rounded-3xl p-6 lg:p-8">
            <h3 class="text-xl font-bold text-white mb-6 uppercase tracking-tight">Riwayat Pesanan Anda</h3>
            
            @if($myOrders->isEmpty())
                <p class="text-dark-500 text-sm">Anda belum pernah melakukan pemesanan.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-dark-400">
                        <thead class="bg-dark-950 text-white uppercase text-[11px] tracking-wider border-b border-dark-800">
                            <tr>
                                <th class="p-4">ID Pesanan</th>
                                <th class="p-4">Cabang</th>
                                <th class="p-4">Meja</th>
                                <th class="p-4">Total Harga</th>
                                <th class="p-4">Status Pesanan</th>
                                <th class="p-4">Pembayaran</th>
                                <th class="p-4">Tanggal / Jam</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-dark-800/50">
                            @foreach($myOrders as $order)
                                <tr class="hover:bg-dark-800/10 transition-colors">
                                    <td class="p-4 font-bold text-white">#{{ $order->id }}</td>
                                    <td class="p-4">{{ $order->branch->name }}</td>
                                    <td class="p-4 font-medium text-dark-200">Meja {{ $order->table->table_number ?? '-' }}</td>
                                    <td class="p-4 font-bold text-brand-500">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td class="p-4">
                                        @if($order->order_status == 'pending')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-500">
                                                ⏱️ Antrean
                                            </span>
                                        @elseif($order->order_status == 'cooking')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-500/10 text-blue-500">
                                                🍳 Dimasak
                                            </span>
                                        @elseif($order->order_status == 'served')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-500">
                                                🍽️ Disajikan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-dark-700 text-dark-400">
                                                ✔️ Selesai
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        @if($order->payment_status == 'paid')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-bold bg-emerald-500/10 text-emerald-500">PAID</span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-bold bg-red-500/10 text-red-500">UNPAID</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-xs text-dark-500">{{ $order->created_at->format('d M Y, H:i') }} WIB</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $myOrders->links() }}
                </div>
            @endif
        </div>
    </main>

    <script>
        // Simple state-management for cart
        const cart = {};

        function setPaymentMethod(method) {
            const btnCash = document.getElementById('pay-cash');
            const btnQris = document.getElementById('pay-qris');
            const btnTransfer = document.getElementById('pay-transfer');
            const inputVal = document.getElementById('payment_method_input');
            const guide = document.getElementById('payment-guide');
            const guideTitle = document.getElementById('guide-title');
            const guideText = document.getElementById('guide-text');
            const qrisQr = document.getElementById('qris-qr');
            const bankInfo = document.getElementById('bank-info');
            const btnSubmit = document.getElementById('btn-submit');

            // Reset classes
            [btnCash, btnQris, btnTransfer].forEach(btn => {
                btn.className = "py-2.5 px-1 rounded-xl border border-dark-800 text-[10px] font-bold text-center text-dark-400 hover:text-white transition-all cursor-pointer";
            });

            inputVal.value = method;

            if (method === 'cash') {
                btnCash.className = "py-2.5 px-1 rounded-xl border text-[10px] font-bold text-center transition-all bg-brand-500 text-white border-brand-500 cursor-pointer";
                guide.classList.add('hidden');
                btnSubmit.textContent = "Kirim Pesanan ke Kasir";
            } else {
                guide.classList.remove('hidden');
                btnSubmit.textContent = "Bayar & Kirim ke Dapur";
                
                if (method === 'qris') {
                    btnQris.className = "py-2.5 px-1 rounded-xl border text-[10px] font-bold text-center transition-all bg-brand-500 text-white border-brand-500 cursor-pointer";
                    guideTitle.textContent = "QRIS / BARCODE";
                    guideText.textContent = "Pindai kode QRIS di bawah ini untuk membayar instan. Setelah pembayaran berhasil, pesanan langsung masuk ke dapur.";
                    qrisQr.classList.remove('hidden');
                    bankInfo.classList.add('hidden');
                } else {
                    btnTransfer.className = "py-2.5 px-1 rounded-xl border text-[10px] font-bold text-center transition-all bg-brand-500 text-white border-brand-500 cursor-pointer";
                    guideTitle.textContent = "BANK TRANSFER";
                    guideText.textContent = "Transfer ke rekening bank di bawah ini. Setelah transfer berhasil, pesanan otomatis langsung masuk ke dapur.";
                    qrisQr.classList.add('hidden');
                    bankInfo.classList.remove('hidden');
                }
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
                btnSubmit.className = "w-full py-4 rounded-xl bg-gradient-to-r from-brand-600 to-brand-500 text-white font-bold shadow-lg shadow-brand-500/25 opacity-50 cursor-not-allowed";
            } else {
                btnSubmit.disabled = false;
                btnSubmit.className = "w-full py-4 rounded-xl bg-gradient-to-r from-brand-600 to-brand-500 text-white font-bold shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all duration-200 cursor-pointer transform hover:scale-[1.02]";
                
                items.forEach(([menuId, item]) => {
                    const row = document.createElement('div');
                    row.className = "flex justify-between items-start border-b border-dark-800/40 pb-3";
                    row.innerHTML = `
                        <div class="flex-grow pr-3">
                            <div class="font-bold text-white">${item.name} <span class="text-brand-500 text-xs ml-1">x${item.quantity}</span></div>
                            ${item.notes ? `<div class="text-[11px] text-dark-500 italic mt-0.5">"${item.notes}"</div>` : ''}
                        </div>
                        <div class="text-white font-semibold flex-shrink-0">
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
            const tableSelect = document.getElementById('table_id');
            if (!tableSelect.value) {
                alert('Silakan pilih nomor meja Anda terlebih dahulu!');
                tableSelect.focus();
                return;
            }
            
            document.getElementById('order-form').submit();
        }
    </script>
</body>
</html>
