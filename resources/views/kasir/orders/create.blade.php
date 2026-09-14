@extends('layouts.app')
@section('title', 'Buat Pesanan - POS Kasir')
@section('page-title', 'Mesin Kasir (POS)')

@section('content')
<div class="grid lg:grid-cols-3 gap-6 animate-fade-in-up">
    <!-- Left: Menus -->
    <div class="lg:col-span-2 space-y-4">
        <!-- Categories -->
        <div class="flex overflow-x-auto space-x-2 pb-2 scrollbar-hide">
            <button type="button" class="px-4 py-2 rounded-xl text-xs font-extrabold whitespace-nowrap bg-[#BD2000] text-white category-filter shadow-sm cursor-pointer" data-category="all">Semua</button>
            <button type="button" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap bg-white border border-stone-200 text-stone-700 hover:bg-stone-100 category-filter cursor-pointer" data-category="makanan">Makanan</button>
            <button type="button" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap bg-white border border-stone-200 text-stone-700 hover:bg-stone-100 category-filter cursor-pointer" data-category="minuman">Minuman</button>
        </div>

        <!-- Menu Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4" id="menu-grid">
            @foreach($menus as $menu)
            @php
                $isSoldOut = $menu->status == 'sold_out' || $menu->ingredients->contains(fn($ing) => optional($ing->inventory)->stock <= 0);
                $catNameLower = strtolower($menu->category->name ?? '');
                $catType = str_contains($catNameLower, 'minuman') ? 'minuman' : 'makanan';
            @endphp
            <div class="bg-white border {{ $isSoldOut ? 'border-red-300 bg-red-50/50' : 'border-stone-200 hover:border-[#BD2000]/40' }} rounded-2xl overflow-hidden flex flex-col group menu-item shadow-sm transition-all" data-category="{{ $catType }}">
                <div class="h-32 bg-stone-100 relative border-b border-stone-200">
                    @if($menu->image)
                        <img src="{{ asset('storage/' . $menu->image) }}" class="w-full h-full object-cover {{ $isSoldOut ? 'grayscale opacity-60' : '' }}">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-stone-400">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path></svg>
                        </div>
                    @endif
                    @if($isSoldOut)
                        <div class="absolute inset-0 bg-white/80 backdrop-blur-xs flex items-center justify-center">
                            <span class="bg-[#FA1E0E] text-white text-[11px] font-extrabold px-3 py-1 rounded-full shadow-md tracking-wider">🚫 HABIS (SOLD OUT)</span>
                        </div>
                    @endif
                </div>
                <div class="p-4 flex-1 flex flex-col">
                    <h4 class="text-[#1C1917] font-black text-sm mb-1 leading-tight">{{ $menu->name }}</h4>
                    <p class="text-[#BD2000] font-black text-sm mt-auto mb-3">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                    
                    @if($isSoldOut)
                        <button type="button" disabled class="w-full bg-red-100 border border-red-200 text-red-600 font-bold py-2 rounded-xl text-xs flex items-center justify-center cursor-not-allowed">
                            🚫 HABIS
                        </button>
                    @else
                        <button type="button" onclick="addToCart({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }})" class="w-full bg-stone-100 hover:bg-[#BD2000] text-stone-700 hover:text-white py-2 rounded-xl transition-all text-xs font-extrabold flex items-center justify-center cursor-pointer border border-stone-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg> Tambah
                        </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Right: Order Summary -->
    <div class="lg:col-span-1">
        <form action="{{ route('kasir.orders.store') }}" method="POST" id="order-form" class="bg-white border border-stone-200 rounded-3xl p-5 sticky top-2 h-[calc(100vh-6.5rem)] flex flex-col justify-between shadow-xl overflow-hidden">
            @csrf
            
            <!-- Fixed Card Header -->
            <h3 class="text-base font-black text-[#8C0000] pb-3 border-b border-stone-200 shrink-0 flex items-center justify-between">
                <span>Ringkasan Pesanan Kasir</span>
                <span class="text-[10px] font-extrabold bg-[#BD2000]/10 text-[#BD2000] px-2.5 py-0.5 rounded-full border border-[#BD2000]/20">Kasir</span>
            </h3>

            @if($errors->any())
                <div class="my-2 p-2.5 bg-red-500/10 border border-red-500/20 text-red-600 text-xs rounded-xl font-bold shrink-0">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Scrollable Body (Inputs + Cart Items) -->
            <div class="flex-1 overflow-y-auto pr-1 my-3 space-y-3.5 scrollbar-thin">
                <div>
                    <label for="customer_name" class="block text-stone-700 text-[11px] font-bold uppercase tracking-wider mb-1.5">Nama Pelanggan <span class="text-red-500">*</span></label>
                    <input type="text" name="customer_name" id="customer_name" required
                        class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-3.5 py-2 text-xs focus:border-[#BD2000] focus:outline-none transition-all" placeholder="Nama pemesan">
                </div>

                <div>
                    <label class="block text-stone-700 text-[11px] font-bold uppercase tracking-wider mb-1.5">Metode Pembayaran <span class="text-red-500">*</span></label>
                    <input type="hidden" name="payment_method" id="kasir_payment_method_input" value="cash">
                    <div class="grid grid-cols-2 gap-2.5">
                        <button type="button" id="kasir-pay-cash" onclick="setKasirPaymentMethod('cash')"
                            class="py-2 px-3 rounded-xl border text-xs font-bold text-center transition-all bg-[#BD2000] text-white border-[#BD2000] cursor-pointer flex items-center justify-center gap-1.5 shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>Tunai</span>
                        </button>
                        <button type="button" id="kasir-pay-qris" onclick="setKasirPaymentMethod('qris')"
                            class="py-2 px-3 rounded-xl border border-stone-300 text-xs font-bold text-center text-stone-600 hover:text-[#BD2000] transition-all cursor-pointer flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            <span>QRIS</span>
                        </button>
                    </div>

                    <!-- Compact QRIS Preview Box for Kasir -->
                    <div id="kasir-qris-guide" class="hidden p-2.5 rounded-xl bg-[#BD2000]/5 border border-[#BD2000]/20 text-xs text-stone-600 mt-2 space-y-1 animate-fade-in">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="font-black text-[#1C1917] uppercase text-[10px]">QRIS Warung Akid</span>
                        </div>
                        <div class="bg-white p-1.5 rounded-xl mx-auto border border-stone-200 flex flex-col items-center justify-center max-w-[150px] shadow-xs">
                            <img src="{{ asset('images/qris.jpg') }}" alt="QRIS Resmi Warung Akid" class="w-full h-auto rounded-lg object-contain max-h-[140px]">
                            <p class="text-[8px] text-stone-800 font-extrabold mt-1 text-center uppercase tracking-tight">NMID: ID1026528881513</p>
                        </div>
                    </div>
                </div>

                <!-- Tipe Pesanan: Dine In vs Takeaway -->
                <div>
                    <label class="block text-stone-700 text-[11px] font-bold uppercase tracking-wider mb-1.5">Tipe Pesanan <span class="text-red-500">*</span></label>
                    <input type="hidden" name="order_type" id="order_type_input" value="dine_in">
                    <input type="hidden" name="table_id" id="selected_table_id" value="">
                    
                    <div class="grid grid-cols-2 gap-2.5 mb-2.5">
                        <button type="button" id="type-dine-in" onclick="setOrderType('dine_in')"
                            class="py-2.5 px-3 rounded-xl border text-xs font-bold text-center transition-all bg-[#BD2000] text-white border-[#BD2000] cursor-pointer flex items-center justify-center gap-1.5 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span>Makan di Tempat</span>
                        </button>
                        <button type="button" id="type-takeaway" onclick="setOrderType('takeaway')"
                            class="py-2.5 px-3 rounded-xl border border-stone-300 text-xs font-bold text-center text-stone-600 hover:text-[#BD2000] transition-all cursor-pointer flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span>Bawa Pulang</span>
                        </button>
                    </div>

                    <!-- Wrapper: Pilih Meja (Dine In) -->
                    <div id="table-selection-wrapper" class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-stone-700 uppercase tracking-wider">Pilih Meja Restoran:</span>
                            <a href="{{ route('kasir.tables.index') }}" class="text-[11px] font-extrabold text-[#BD2000] hover:underline flex items-center gap-1">
                                + Kelola / Tambah Meja
                            </a>
                        </div>

                        <div class="grid grid-cols-3 gap-2 max-h-40 overflow-y-auto pr-1 scrollbar-thin">
                            @forelse($tables as $t)
                                <div onclick="selectTable(this)" 
                                     data-id="{{ $t->id }}" 
                                     data-number="{{ $t->table_number }}" 
                                     data-status="{{ $t->status }}"
                                     class="table-box p-2 rounded-xl border text-center transition-all cursor-pointer text-xs font-bold flex flex-col justify-center items-center gap-0.5 {{ $t->status === 'occupied' ? 'bg-red-50 border-red-200 text-red-500 cursor-not-allowed opacity-75' : 'bg-emerald-50 border-emerald-300 text-emerald-800 hover:bg-emerald-100' }}">
                                    <span>{{ $t->table_number }}</span>
                                    <span class="text-[9px] font-medium opacity-80">
                                        {{ $t->status === 'occupied' ? 'Terisi' : 'Tersedia (' . ($t->capacity ?? 4) . ' krs)' }}
                                    </span>
                                </div>
                            @empty
                                <div class="col-span-3 text-center py-3 text-stone-400 text-xs font-medium">
                                    Belum ada meja. <a href="{{ route('kasir.tables.index') }}" class="text-[#BD2000] font-bold underline">+ Tambah Meja</a>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Wrapper: Info Bawa Pulang (Takeaway) -->
                    <div id="takeaway-info-wrapper" class="hidden p-2.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-[11px] font-semibold flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 rounded-lg bg-amber-100 text-amber-800 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                            <div>
                                <p class="font-extrabold text-amber-950">Pesanan Bawa Pulang</p>
                                <p class="text-[10px] text-amber-800 font-medium">Otomatis tanpa meja. Dibungkus oleh Dapur.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cart Items -->
                <div id="cart-container" class="space-y-2 pt-2 border-t border-stone-200">
                    <div class="text-center text-stone-400 py-4 text-xs font-medium" id="empty-cart">
                        Belum ada item di keranjang
                    </div>
                </div>
            </div>

            <!-- Fixed Footer: Total & Submit Button ALWAYS VISIBLE -->
            <div class="pt-3 border-t border-stone-200 shrink-0 space-y-2.5 bg-white">
                <div class="flex justify-between items-center text-sm font-bold">
                    <span class="text-stone-700">Total Belanja:</span>
                    <span class="text-[#BD2000] font-black text-lg font-mono" id="cart-total">Rp 0</span>
                </div>
                
                <div id="hidden-inputs"></div>

                <button type="submit" id="submit-btn" disabled 
                        class="w-full bg-[#BD2000] hover:bg-[#8C0000] disabled:opacity-40 disabled:cursor-not-allowed text-white font-extrabold py-3 text-xs rounded-xl transition-all shadow-md cursor-pointer uppercase tracking-wider">
                    Pesan Sekarang & Buka Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let cart = [];

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    function addToCart(id, name, price) {
        const existing = cart.find(i => i.id === id);
        if (existing) {
            existing.qty++;
        } else {
            cart.push({ id, name, price, qty: 1, notes: '' });
        }
        renderCart();
    }

    function updateQty(id, delta) {
        const item = cart.find(i => i.id === id);
        if (item) {
            item.qty += delta;
            if (item.qty <= 0) {
                cart = cart.filter(i => i.id !== id);
            }
        }
        renderCart();
    }

    function updateNotes(id, value) {
        const item = cart.find(i => i.id === id);
        if (item) {
            item.notes = value;
        }
    }

    function renderCart() {
        const container = document.getElementById('cart-container');
        const hiddenInputs = document.getElementById('hidden-inputs');
        const submitBtn = document.getElementById('submit-btn');
        const totalEl = document.getElementById('cart-total');

        if (cart.length === 0) {
            container.innerHTML = '<div class="text-center text-slate-400 py-6 text-xs font-medium" id="empty-cart">Belum ada item ditambahkan ke keranjang</div>';
            submitBtn.disabled = true;
            totalEl.innerText = 'Rp 0';
            hiddenInputs.innerHTML = '';
            return;
        }

        submitBtn.disabled = false;
        container.innerHTML = '';
        hiddenInputs.innerHTML = '';

        let total = 0;

        cart.forEach((item, index) => {
            const subtotal = item.price * item.qty;
            total += subtotal;

            container.innerHTML += `
                <div class="bg-stone-50 p-3 rounded-2xl border border-stone-200 flex flex-col gap-1.5 text-xs shadow-xs">
                    <div class="flex justify-between items-center">
                        <span class="font-extrabold text-[#1C1917]">${item.name}</span>
                        <span class="font-black text-[#BD2000]">Rp ${formatRupiah(subtotal)}</span>
                    </div>
                    <div class="flex justify-between items-center mt-1 gap-2">
                        <input type="text" placeholder="Catatan (misal: pedas)" value="${item.notes}" onchange="updateNotes(${item.id}, this.value)"
                            class="bg-white border border-stone-300 text-[#1C1917] rounded-xl px-2.5 py-1 text-[11px] flex-1 focus:outline-none focus:border-[#BD2000] font-medium">
                        <div class="flex items-center gap-1.5 bg-white border border-stone-200 rounded-xl p-1 shadow-xs">
                            <button type="button" onclick="updateQty(${item.id}, -1)" class="w-6 h-6 flex items-center justify-center text-stone-600 hover:text-[#BD2000] hover:bg-stone-100 rounded-lg font-black transition-colors cursor-pointer">-</button>
                            <span class="font-black text-[#1C1917] px-1 text-xs min-w-[16px] text-center select-none">${item.qty}</span>
                            <button type="button" onclick="updateQty(${item.id}, 1)" class="w-6 h-6 flex items-center justify-center text-stone-600 hover:text-[#BD2000] hover:bg-stone-100 rounded-lg font-black transition-colors cursor-pointer">+</button>
                        </div>
                    </div>
                </div>
            `;

            hiddenInputs.innerHTML += `
                <input type="hidden" name="items[${index}][menu_id]" value="${item.id}">
                <input type="hidden" name="items[${index}][quantity]" value="${item.qty}">
                <input type="hidden" name="items[${index}][notes]" value="${item.notes}">
            `;
        });

        totalEl.innerText = 'Rp ' + formatRupiah(total);
    }

    // Category Filter
    document.querySelectorAll('.category-filter').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.category-filter').forEach(b => {
                b.classList.remove('bg-[#BD2000]', 'text-white', 'font-extrabold', 'shadow-sm');
                b.classList.add('bg-white', 'text-stone-700', 'border', 'border-stone-200', 'font-bold');
            });
            this.classList.remove('bg-white', 'text-stone-700', 'border', 'border-stone-200', 'font-bold');
            this.classList.add('bg-[#BD2000]', 'text-white', 'font-extrabold', 'shadow-sm');

            const cat = this.getAttribute('data-category');
            document.querySelectorAll('.menu-item').forEach(item => {
                if (cat === 'all' || item.getAttribute('data-category') === cat) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Form Submit Interception: Save pending draft order & trigger checkout payment modal
    document.getElementById('order-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        e.stopPropagation();

        if (cart.length === 0) {
            if (window.showToast) window.showToast('Keranjang belanja masih kosong!', 'warning');
            else alert('Keranjang belanja masih kosong!');
            return;
        }

        const customerName = document.getElementById('customer_name').value.trim();
        if (!customerName) {
            if (window.showToast) window.showToast('Silakan isi nama pelanggan terlebih dahulu!', 'warning');
            else alert('Silakan isi nama pelanggan terlebih dahulu!');
            return;
        }

        const orderTypeInput = document.getElementById('order_type_input');
        const orderType = orderTypeInput ? orderTypeInput.value : 'dine_in';
        const tableId = document.getElementById('selected_table_id').value;

        if (orderType === 'dine_in' && !tableId) {
            alert('Silakan pilih nomor meja restoran terlebih dahulu untuk pesanan Makan di Tempat!');
            return;
        }

        const paymentMethodInput = document.getElementById('kasir_payment_method_input');
        const paymentMethod = paymentMethodInput ? paymentMethodInput.value : 'cash';
        const submitBtn = document.getElementById('submit-btn');
        submitBtn.disabled = true;
        submitBtn.innerText = '⏳ Menyimpan Draft Pesanan...';

        try {
            const payload = {
                customer_name: customerName,
                order_type: orderType,
                table_id: orderType === 'dine_in' ? tableId : null,
                payment_method: paymentMethod,
                items: cart.map(item => ({
                    menu_id: item.id,
                    quantity: item.qty,
                    notes: item.notes || ''
                }))
            };

            const response = await fetch('{{ route("kasir.orders.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // MUST TRIGGER OPEN CHECKOUT PAYMENT MODAL IMMEDIATELY
                window.dispatchEvent(new CustomEvent('open-checkout', {
                    detail: {
                        orderId: data.order.id,
                        customerName: data.order.customer_name,
                        tableNumber: data.order.table_number,
                        totalAmount: data.order.total_price,
                        paymentMethod: paymentMethod,
                        items: data.order.items
                    }
                }));
            } else {
                if (window.showToast) window.showToast('Gagal menyimpan pesanan draft: ' + (data.message || 'Silakan cek kembali inputan.'), 'error');
                else alert('Gagal menyimpan pesanan draft: ' + (data.message || 'Silakan cek kembali inputan.'));
            }
        } catch (err) {
            if (window.showToast) window.showToast('Terjadi kesalahan jaringan saat mengirim pesanan.', 'error');
            else alert('Terjadi kesalahan jaringan saat mengirim pesanan.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerText = 'Pesan Sekarang & Buka Pembayaran';
        }
    });

    function setOrderType(type) {
        const btnDineIn = document.getElementById('type-dine-in');
        const btnTakeaway = document.getElementById('type-takeaway');
        const inputType = document.getElementById('order_type_input');
        const tableWrapper = document.getElementById('table-selection-wrapper');
        const takeawayWrapper = document.getElementById('takeaway-info-wrapper');
        const selectedTableInput = document.getElementById('selected_table_id');

        if (inputType) inputType.value = type;

        if (type === 'dine_in') {
            if (btnDineIn) btnDineIn.className = "py-2.5 px-3 rounded-xl border text-xs font-bold text-center transition-all bg-[#BD2000] text-white border-[#BD2000] cursor-pointer flex items-center justify-center gap-1.5 shadow-sm";
            if (btnTakeaway) btnTakeaway.className = "py-2.5 px-3 rounded-xl border border-stone-300 text-xs font-bold text-center text-stone-600 hover:text-[#BD2000] transition-all cursor-pointer flex items-center justify-center gap-1.5";
            if (tableWrapper) tableWrapper.classList.remove('hidden');
            if (takeawayWrapper) takeawayWrapper.classList.add('hidden');
        } else {
            if (btnTakeaway) btnTakeaway.className = "py-2.5 px-3 rounded-xl border text-xs font-bold text-center transition-all bg-[#BD2000] text-white border-[#BD2000] cursor-pointer flex items-center justify-center gap-1.5 shadow-sm";
            if (btnDineIn) btnDineIn.className = "py-2.5 px-3 rounded-xl border border-stone-300 text-xs font-bold text-center text-stone-600 hover:text-[#BD2000] transition-all cursor-pointer flex items-center justify-center gap-1.5";
            if (tableWrapper) tableWrapper.classList.add('hidden');
            if (takeawayWrapper) takeawayWrapper.classList.remove('hidden');
            if (selectedTableInput) selectedTableInput.value = '';

            document.querySelectorAll('.table-box').forEach(box => {
                if (box.getAttribute('data-status') === 'empty') {
                    box.className = 'table-box py-3 px-2 rounded-xl border text-center transition-all cursor-pointer border-emerald-300 bg-emerald-50 text-emerald-700 hover:bg-emerald-100';
                }
            });
        }
    }

    function setKasirPaymentMethod(method) {
        const btnCash = document.getElementById('kasir-pay-cash');
        const btnQris = document.getElementById('kasir-pay-qris');
        const inputVal = document.getElementById('kasir_payment_method_input');
        const qrisGuide = document.getElementById('kasir-qris-guide');

        if (inputVal) inputVal.value = method;

        [btnCash, btnQris].forEach(btn => {
            if (btn) btn.className = "py-2.5 px-3 rounded-xl border border-stone-300 text-xs font-bold text-center text-stone-600 hover:text-[#BD2000] transition-all cursor-pointer flex items-center justify-center gap-1.5";
        });

        if (method === 'cash') {
            if (btnCash) btnCash.className = "py-2.5 px-3 rounded-xl border text-xs font-bold text-center transition-all bg-[#BD2000] text-white border-[#BD2000] cursor-pointer flex items-center justify-center gap-1.5 shadow-sm";
            if (qrisGuide) qrisGuide.classList.add('hidden');
        } else {
            if (btnQris) btnQris.className = "py-2.5 px-3 rounded-xl border text-xs font-bold text-center transition-all bg-[#BD2000] text-white border-[#BD2000] cursor-pointer flex items-center justify-center gap-1.5 shadow-sm";
            if (qrisGuide) qrisGuide.classList.remove('hidden');
        }
    }

    function selectTable(el) {
        if (el.getAttribute('data-status') === 'occupied') {
            if (window.showToast) {
                window.showToast('Meja ' + el.getAttribute('data-number') + ' sedang TERISI dan tidak dapat dipilih!', 'error');
            } else {
                alert('Meja ' + el.getAttribute('data-number') + ' sedang TERISI dan tidak dapat dipilih!');
            }
            return;
        }
        
        document.querySelectorAll('.table-box').forEach(box => {
            if (box.getAttribute('data-status') === 'empty') {
                box.className = 'table-box py-3 px-2 rounded-xl border text-center transition-all cursor-pointer border-emerald-300 bg-emerald-50 text-emerald-700 hover:bg-emerald-100';
            }
        });
        
        el.className = 'table-box py-3 px-2 rounded-xl border text-center transition-all cursor-pointer border-[#BD2000] bg-[#BD2000] text-white shadow-md ring-2 ring-[#BD2000]/30';
        
        document.getElementById('selected_table_id').value = el.getAttribute('data-id');
    }
</script>
@endpush
