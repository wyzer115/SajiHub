@extends('layouts.app')
@section('title', 'Buat Pesanan')
@section('page-title', 'Mesin Kasir (POS)')

@section('content')
<div class="grid lg:grid-cols-3 gap-6 animate-fade-in-up">
    <!-- Left: Menus -->
    <div class="lg:col-span-2 space-y-4">
        <!-- Categories -->
        <div class="flex overflow-x-auto space-x-2 pb-2 scrollbar-hide">
            <button type="button" class="px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap bg-brand-500 text-white category-filter" data-category="all">Semua</button>
            @foreach($menus->groupBy(fn($m) => $m->category->name ?? 'Tanpa Kategori')->keys() as $catName)
                <button type="button" class="px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap bg-dark-800 text-dark-300 hover:bg-dark-700 category-filter" data-category="{{ Str::slug($catName) }}">{{ $catName }}</button>
            @endforeach
        </div>

        <!-- Menu Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4" id="menu-grid">
            @foreach($menus as $menu)
            <div class="bg-dark-900 border border-dark-700 rounded-2xl overflow-hidden flex flex-col group menu-item {{ $menu->status == 'sold_out' ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer' }}" data-category="{{ Str::slug($menu->category->name ?? 'Tanpa Kategori') }}">
                <div class="h-32 bg-dark-700 relative">
                    @if($menu->image)
                        <img src="{{ asset('storage/' . $menu->image) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-dark-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path></svg>
                        </div>
                    @endif
                    @if($menu->status == 'sold_out')
                        <div class="absolute inset-0 bg-dark-900/60 flex items-center justify-center">
                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">HABIS</span>
                        </div>
                    @endif
                </div>
                <div class="p-4 flex-1 flex flex-col">
                    <h4 class="text-white font-medium text-sm mb-1 leading-tight">{{ $menu->name }}</h4>
                    <p class="text-brand-400 font-bold text-sm mt-auto mb-3">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                    
                    <button type="button" @if($menu->status == 'available') onclick="addToCart({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }})" @else disabled @endif class="w-full bg-dark-700 hover:bg-dark-600 text-dark-200 hover:text-white py-2 rounded-xl transition-all text-sm font-medium flex items-center justify-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Tambah
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Right: Order Summary -->
    <div class="lg:col-span-1">
        <form action="{{ route('kasir.orders.store') }}" method="POST" id="order-form" class="bg-dark-800 border border-dark-700 rounded-2xl p-6 sticky top-24 max-h-[calc(100vh-8rem)] flex flex-col">
            @csrf
            
            <h3 class="text-lg font-bold text-white mb-4 border-b border-dark-700 pb-3">Ringkasan Pesanan</h3>

            <div class="space-y-4 mb-4 flex-shrink-0">
                <div>
                    <label for="customer_name" class="block text-dark-300 text-xs font-semibold uppercase tracking-wider mb-2">Nama Pelanggan</label>
                    <input type="text" name="customer_name" id="customer_name" required
                        class="w-full bg-dark-900 border border-dark-600 text-white rounded-xl px-4 py-3 text-base focus:border-brand-500 focus:outline-none transition-all" placeholder="Nama pemesan">
                </div>
                <div>
                    <label class="block text-dark-300 text-xs font-semibold uppercase tracking-wider mb-2">Pilih Meja</label>
                    <input type="hidden" name="table_id" id="selected_table_id" required>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 max-h-[220px] overflow-y-auto pr-1 scrollbar-thin">
                        @foreach($tables ?? [] as $table)
                            <div class="table-box py-4 px-3 rounded-xl border text-center transition-all cursor-pointer 
                                {{ $table->status == 'empty' ? 'border-green-500/30 bg-green-500/5 hover:bg-green-500/10 text-green-400' : 'border-red-500/20 bg-dark-900/60 opacity-55 text-dark-500 cursor-not-allowed' }}"
                                data-id="{{ $table->id }}"
                                data-status="{{ $table->status }}"
                                onclick="selectTable(this)">
                                <p class="font-extrabold text-base">Meja {{ str_replace('Table ', '', $table->table_number) }}</p>
                                <span class="text-xs block mt-1 uppercase font-bold">
                                    {{ $table->status == 'empty' ? 'Kosong' : 'Terisi' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Cart Items -->
            <div id="cart-container" class="flex-1 overflow-y-auto space-y-3 mb-4 pr-1 min-h-[180px]">
                <div class="text-center text-dark-400 py-8 text-sm" id="empty-cart">
                    Belum ada item ditambahkan
                </div>
            </div>

            <!-- Total & Submit -->
            <div class="mt-auto border-t border-dark-700 pt-4 flex-shrink-0 space-y-4">
                <div class="flex justify-between items-center text-xl font-bold">
                    <span class="text-dark-200">Total:</span>
                    <span class="text-brand-400 font-black text-2xl" id="cart-total">Rp 0</span>
                </div>
                
                <div id="hidden-inputs"></div>

                <button type="submit" id="submit-btn" disabled class="w-full bg-brand-500 hover:bg-brand-600 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold py-4 text-base rounded-xl transition-all shadow-lg shadow-brand-500/20">
                    Bayar & Kirim Pesanan
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

    function updateQuantity(index, delta) {
        cart[index].qty += delta;
        if (cart[index].qty < 1) cart.splice(index, 1);
        renderCart();
    }

    function updateNotes(index, value) {
        cart[index].notes = value;
    }

    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cart-container');
        const hiddenInputs = document.getElementById('hidden-inputs');
        const emptyMsg = document.getElementById('empty-cart');
        const totalEl = document.getElementById('cart-total');
        const submitBtn = document.getElementById('submit-btn');

        if (cart.length === 0) {
            container.innerHTML = '<div class="text-center text-dark-400 py-8 text-sm">Belum ada item ditambahkan</div>';
            totalEl.innerText = 'Rp 0';
            hiddenInputs.innerHTML = '';
            submitBtn.disabled = true;
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
                <div class="bg-dark-900 border border-dark-700 p-4 rounded-xl flex flex-col gap-3">
                    <div class="flex justify-between items-start">
                        <div class="font-bold text-white text-base flex-grow pr-2 leading-snug">${item.name}</div>
                        <div class="text-brand-400 font-extrabold text-base whitespace-nowrap">Rp ${formatRupiah(subtotal)}</div>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <div class="flex items-center space-x-2 bg-dark-800 rounded-lg p-1.5">
                            <button type="button" onclick="updateQuantity(${index}, -1)" class="w-8 h-8 flex items-center justify-center text-dark-300 hover:text-white bg-dark-700 rounded-md font-extrabold text-sm">-</button>
                            <span class="text-white text-sm w-6 text-center font-black">${item.qty}</span>
                            <button type="button" onclick="updateQuantity(${index}, 1)" class="w-8 h-8 flex items-center justify-center text-dark-300 hover:text-white bg-dark-700 rounded-md font-extrabold text-sm">+</button>
                        </div>
                        <button type="button" onclick="removeItem(${index})" class="text-red-400 hover:text-red-300 p-1.5 rounded-lg hover:bg-red-500/10 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                    <input type="text" placeholder="Catatan (opsional)" class="w-full bg-dark-800 border border-dark-700/60 focus:border-brand-500 text-white text-sm rounded-lg px-3 py-2 mt-1 focus:ring-1 focus:ring-brand-500" value="${item.notes}" onchange="updateNotes(${index}, this.value)">
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
            // Update active state
            document.querySelectorAll('.category-filter').forEach(b => {
                b.classList.remove('bg-brand-500', 'text-white');
                b.classList.add('bg-dark-800', 'text-dark-300');
            });
            this.classList.remove('bg-dark-800', 'text-dark-300');
            this.classList.add('bg-brand-500', 'text-white');

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

    // Form Submit verification
    document.getElementById('order-form').addEventListener('submit', function(e) {
        if (cart.length === 0) {
            e.preventDefault();
            alert('Keranjang belanja masih kosong!');
            return;
        }

        const tableId = document.getElementById('selected_table_id').value;
        if (!tableId) {
            e.preventDefault();
            alert('Silakan pilih meja makan terlebih dahulu!');
            return;
        }

        // Catatan values are captured via onchange before submit, but let's make sure
        cart.forEach((item, index) => {
            const notesInput = document.querySelector(`input[name="items[${index}][notes]"]`);
            if(notesInput) {
                // Ensure hidden input matches the actual cart data
                notesInput.value = item.notes;
            }
        });
    });

    function selectTable(el) {
        if (el.getAttribute('data-status') === 'occupied') {
            return;
        }
        
        document.querySelectorAll('.table-box').forEach(box => {
            if (box.getAttribute('data-status') === 'empty') {
                box.className = 'table-box py-4 px-3 rounded-xl border text-center transition-all cursor-pointer border-green-500/30 bg-green-500/5 hover:bg-green-500/10 text-green-400';
            }
        });
        
        el.className = 'table-box py-4 px-3 rounded-xl border text-center transition-all cursor-pointer border-brand-500 bg-brand-500/20 text-white ring-2 ring-brand-500/50';
        
        document.getElementById('selected_table_id').value = el.getAttribute('data-id');
    }
</script>
@endpush
