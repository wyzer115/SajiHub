@extends('layouts.app')
@section('title', 'Cek & Sesuaikan Stok Bahan')
@section('page-title', 'Cek & Sesuaikan Stok Bahan Dapur (Stock Opname)')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 animate-fade-in-up">

    <!-- Header Info Banner -->
    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-[#8C0000] flex items-center gap-2 mb-1">
                <svg class="w-6 h-6 text-[#BD2000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                <span>Cek & Sesuaikan Stok Bahan Dapur</span>
            </h2>
            <p class="text-stone-600 text-xs font-medium">Cocokkan jumlah stok di sistem dengan kenyataan barang asli di dapur (misal: koreksi fisik, belanjaan baru masuk, atau barang basi/rusak).</p>
        </div>
        <form method="GET" action="{{ route('supervisor.opname.index') }}" class="flex items-center gap-2 shrink-0">
            <label for="date" class="text-xs font-bold text-stone-600">Tanggal:</label>
            <input type="date" name="date" id="date" value="{{ $date }}" onchange="this.form.submit()"
                class="bg-stone-50 border border-stone-300 text-stone-800 text-xs font-bold rounded-xl px-3 py-2 focus:border-[#BD2000] focus:outline-none">
        </form>
    </div>

    <!-- Main Navigation Tabs -->
    <div class="bg-white border border-stone-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
        
        <div class="flex flex-wrap items-center gap-2 p-1.5 bg-stone-100 rounded-2xl w-fit">
            <button type="button" id="tab-quick-adjust" onclick="switchTab('quick-adjust')"
                class="px-5 py-2.5 rounded-xl text-xs font-extrabold transition-all shadow-sm bg-white text-[#BD2000]">
                ⚡ Sesuaikan Stok
            </button>
            <button type="button" id="tab-add-item" onclick="switchTab('add-item')"
                class="px-5 py-2.5 rounded-xl text-xs font-bold text-stone-600 hover:text-stone-900 transition-all">
                ➕ Tambah Bahan Baru
            </button>
            <button type="button" id="tab-log-history" onclick="switchTab('log-history')"
                class="px-5 py-2.5 rounded-xl text-xs font-bold text-stone-600 hover:text-stone-900 transition-all">
                📜 Riwayat Perubahan Stok
            </button>
        </div>

        <!-- TAB 1: FORM SESUAIKAN STOK PRAKTIS -->
        <div id="section-quick-adjust" class="space-y-6">
            <form action="{{ route('supervisor.opname.store') }}" method="POST" id="form-adjust" class="space-y-6">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">
                <input type="hidden" name="mode" value="existing">
                <input type="hidden" name="action_type" id="input_action_type" value="set_actual">

                <!-- 1. PILIH BAHAN BAKU -->
                <div>
                    <label for="select_inventory_id" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                        1. Pilih Bahan Baku yang Ingin Disesuaikan *
                    </label>
                    <select name="inventory_id" id="select_inventory_id" onchange="onItemChange()" required
                        class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-2xl px-4 py-3.5 text-sm focus:border-[#BD2000] focus:outline-none transition-all">
                        <option value="">-- Ketuk untuk memilih bahan baku --</option>
                        @foreach($inventories as $inv)
                            <option value="{{ $inv->id }}"
                                data-stock="{{ (float)$inv->stock }}"
                                data-unit="{{ $inv->unit }}"
                                data-price="{{ $inv->unit_price }}"
                                data-min="{{ $inv->min_stock }}"
                                data-name="{{ $inv->name }}"
                                data-cat="{{ $inv->category }}"
                                {{ old('inventory_id') == $inv->id ? 'selected' : '' }}>
                                {{ $inv->name }} (Stok Sistem: {{ (float)$inv->stock }} {{ $inv->unit }})
                            </option>
                        @endforeach
                    </select>
                    @error('inventory_id')
                        <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- CARD PREVIEW INFORMASI BARANG (MUNCUL OTOMATIS) -->
                <div id="item-preview-card" class="hidden p-5 bg-gradient-to-r from-stone-50 to-stone-100/60 border border-stone-200 rounded-2xl animate-fade-in-up">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <span id="preview-cat-badge" class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full bg-stone-200 text-stone-700"></span>
                            <h3 id="preview-name" class="text-lg font-black text-[#1C1917] mt-1"></h3>
                            <p class="text-xs text-stone-500 font-medium mt-0.5">
                                Harga HPP: <span id="preview-price" class="font-bold text-stone-700"></span>
                            </p>
                        </div>
                        <div class="bg-white border border-stone-200 rounded-xl px-5 py-3 text-right shrink-0 shadow-xs">
                            <span class="text-[10px] font-bold text-stone-500 uppercase tracking-wider block">Stok Sistem Saat Ini</span>
                            <div class="text-2xl font-black text-[#BD2000]">
                                <span id="preview-stock">0</span> <span id="preview-unit" class="text-sm font-bold text-stone-600"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. PILIHAN CARA PENYESUAIAN (3 PILIHAN PRAKTIS) -->
                <div class="space-y-3">
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider">
                        2. Pilih Jenis Penyesuaian *
                    </label>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <!-- Opsi A: Set Fisik Nyata (Paling sering) -->
                        <div onclick="selectActionType('set_actual')" id="card-act-set_actual"
                            class="cursor-pointer border-2 border-[#BD2000] bg-[#BD2000]/5 rounded-2xl p-4 transition-all flex items-start gap-3">
                            <input type="radio" name="radio_action" id="radio_set_actual" checked class="mt-1 text-[#BD2000]">
                            <div>
                                <h4 class="text-sm font-extrabold text-[#1C1917] flex items-center gap-1.5">
                                    <span>🎯</span>
                                    <span>Koreksi Stok Fisik Dapur</span>
                                </h4>
                                <p class="text-[11px] text-stone-600 mt-1 leading-snug">Cukup ketik berapa jumlah riil barang yang dihitung di dapur saat ini.</p>
                            </div>
                        </div>

                        <!-- Opsi B: Tambah Stok Masuk -->
                        <div onclick="selectActionType('add')" id="card-act-add"
                            class="cursor-pointer border border-stone-200 bg-white hover:border-emerald-400 rounded-2xl p-4 transition-all flex items-start gap-3">
                            <input type="radio" name="radio_action" id="radio_add" class="mt-1 text-emerald-600">
                            <div>
                                <h4 class="text-sm font-extrabold text-[#1C1917] flex items-center gap-1.5">
                                    <span>📥</span>
                                    <span>Tambah Stok (Restok)</span>
                                </h4>
                                <p class="text-[11px] text-stone-600 mt-1 leading-snug">Ada barang baru dibeli atau pasokan baru masuk ke dapur.</p>
                            </div>
                        </div>

                        <!-- Opsi C: Kurangi Stok Rusak/Basi -->
                        <div onclick="selectActionType('reduce')" id="card-act-reduce"
                            class="cursor-pointer border border-stone-200 bg-white hover:border-red-400 rounded-2xl p-4 transition-all flex items-start gap-3">
                            <input type="radio" name="radio_action" id="radio_reduce" class="mt-1 text-red-600">
                            <div>
                                <h4 class="text-sm font-extrabold text-[#1C1917] flex items-center gap-1.5">
                                    <span>🗑️</span>
                                    <span>Kurangi (Rusak / Basi)</span>
                                </h4>
                                <p class="text-[11px] text-stone-600 mt-1 leading-snug">Ada bahan yang basi, tumpah, atau dibuang (waste).</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. INPUT JUMLAH & KALKULASI PRAKTIS -->
                <div class="bg-stone-50 border border-stone-200 rounded-2xl p-5 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 items-center">
                        <div>
                            <label for="input_stock_value" id="label-stock-value" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                                Masukkan Jumlah Stok Fisik di Dapur Sekarang *
                            </label>
                            <div class="relative">
                                <input type="number" step="0.001" min="0" required name="actual_stock" id="input_stock_value"
                                    oninput="calculateLivePreview()"
                                    placeholder="Contoh: 45"
                                    class="w-full bg-white border border-stone-300 text-[#1C1917] font-black text-xl rounded-xl px-4 py-3.5 focus:border-[#BD2000] focus:outline-none transition-all">
                                <span id="input-unit-badge" class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-extrabold text-stone-500 bg-stone-100 px-2.5 py-1 rounded-md">unit</span>
                            </div>
                        </div>

                        <!-- Ringkasan Live Perubahan -->
                        <div id="live-calc-box" class="bg-white border border-stone-200 rounded-xl p-4 space-y-1.5">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-stone-400">Hasil Penyesuaian:</span>
                            <div id="live-calc-text" class="text-sm font-extrabold text-stone-700">
                                Silakan pilih barang dan masukkan jumlah di sebelah kiri.
                            </div>
                        </div>
                    </div>

                    <!-- Alasan & Catatan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-stone-200">
                        <div>
                            <label for="reason" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Alasan Penyesuaian *</label>
                            <select name="reason" id="reason" required 
                                class="w-full bg-white border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all">
                                <option value="koreksi_stok">Pengecekan Rutin Fisik Dapur</option>
                                <option value="rusak_basi">Bahan Basi / Rusak / Expired</option>
                                <option value="selisih_hitung">Selisih Hitungan / Koreksi Manual</option>
                                <option value="lost_hilang">Bahan Hilang / Tumpah</option>
                            </select>
                        </div>
                        <div>
                            <label for="notes" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Keterangan / Catatan (Opsional)</label>
                            <input type="text" name="notes" id="notes" placeholder="Contoh: Daging ayam sisa semalam di chiller..."
                                class="w-full bg-white border border-stone-300 text-[#1C1917] font-medium rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all">
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi Simpan -->
                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold text-sm px-8 py-3.5 rounded-2xl transition-all shadow-md hover:shadow-lg flex items-center gap-2 cursor-pointer">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        <span>Simpan & Perbarui Stok Sekarang</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- TAB 2: FORM TAMBAH BARANG BARU -->
        <div id="section-add-item" class="hidden space-y-6">
            <form action="{{ route('supervisor.opname.store') }}" method="POST" class="space-y-5 bg-stone-50 border border-stone-200 p-6 rounded-2xl">
                @csrf
                <input type="hidden" name="mode" value="new">
                <input type="hidden" name="date" value="{{ $date }}">

                <h3 class="text-sm font-black text-[#8C0000] uppercase tracking-wider flex items-center gap-2">
                    <span>✨ Tambah Bahan Baku Baru ke Inventaris Cabang</span>
                </h3>

                <div>
                    <label for="item_name" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Nama Bahan Baku Baru *</label>
                    <input type="text" name="item_name" id="item_name" required value="{{ old('item_name') }}"
                        class="w-full bg-white border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                        placeholder="Contoh: Bawang Merah Kupas, Sirup Karamel, Susu UHT...">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="category" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Kategori Bahan *</label>
                        <select name="category" id="category"
                            class="w-full bg-white border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all">
                            <option value="bahan_makanan">Bahan Makanan</option>
                            <option value="bahan_minuman">Bahan Minuman</option>
                            <option value="peralatan">Peralatan / Perlengkapan</option>
                        </select>
                    </div>

                    <div>
                        <label for="unit" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Satuan (UOM) *</label>
                        <input type="text" name="unit" id="unit" list="unit-suggestions" required value="{{ old('unit', 'kg') }}"
                            class="w-full bg-white border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                            placeholder="kg, liter, gram, pcs, botol...">
                        <datalist id="unit-suggestions">
                            <option value="kg"></option>
                            <option value="gram"></option>
                            <option value="liter"></option>
                            <option value="ml"></option>
                            <option value="pcs"></option>
                            <option value="pack"></option>
                            <option value="botol"></option>
                        </datalist>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="new_stock" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Stok Awal Masuk *</label>
                        <input type="number" step="0.001" name="stock" id="new_stock" value="{{ old('stock', 10) }}" min="0" required
                            class="w-full bg-white border border-stone-300 text-[#1C1917] font-black rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all">
                    </div>
                    <div>
                        <label for="new_unit_price" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Harga Beli / Satuan (Rp) *</label>
                        <input type="number" name="unit_price" id="new_unit_price" value="{{ old('unit_price', 25000) }}" min="0" step="500" required
                            class="w-full bg-white border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all">
                    </div>
                    <div>
                        <label for="new_min_stock" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Peringatan Stok Menipis</label>
                        <input type="number" step="0.001" name="min_stock" id="new_min_stock" value="{{ old('min_stock', 5) }}" min="0"
                            class="w-full bg-white border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all">
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold text-xs px-6 py-3 rounded-xl transition-all shadow-md">
                        Simpan Bahan Baru ke Inventaris
                    </button>
                </div>
            </form>
        </div>

        <!-- TAB 3: RIWAYAT PERUBAHAN STOK -->
        <div id="section-log-history" class="hidden space-y-4">
            <h3 class="text-sm font-black text-stone-800 uppercase tracking-wider">Catatan Riwayat Penyesuaian Stok</h3>
            
            <div class="overflow-x-auto border border-stone-200 rounded-2xl shadow-xs">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-stone-100 text-stone-700 font-extrabold uppercase tracking-wider border-b border-stone-200">
                            <th class="py-3.5 px-4">Tanggal</th>
                            <th class="py-3.5 px-4">Nama Bahan</th>
                            <th class="py-3.5 px-4 text-center">Stok Sebelum</th>
                            <th class="py-3.5 px-4 text-center">Stok Akhir</th>
                            <th class="py-3.5 px-4 text-center">Selisih</th>
                            <th class="py-3.5 px-4">Alasan & Catatan</th>
                            <th class="py-3.5 px-4">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 font-medium">
                        @forelse($recentReconciliations as $recon)
                            <tr class="hover:bg-stone-50 transition-colors">
                                <td class="py-3 px-4 font-bold text-stone-700">{{ $recon->date->format('d/m/Y') }}</td>
                                <td class="py-3 px-4 font-black text-[#1C1917]">{{ $recon->inventory->name ?? '-' }}</td>
                                <td class="py-3 px-4 text-center text-stone-600">{{ (float)$recon->opening_stock }} {{ $recon->inventory->unit ?? '' }}</td>
                                <td class="py-3 px-4 text-center font-bold text-[#BD2000]">{{ (float)$recon->closing_stock }} {{ $recon->inventory->unit ?? '' }}</td>
                                <td class="py-3 px-4 text-center">
                                    @php
                                        $diffVal = (float)$recon->closing_stock - (float)$recon->opening_stock;
                                    @endphp
                                    @if($diffVal > 0)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">+{{ $diffVal }}</span>
                                    @elseif($diffVal < 0)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-red-100 text-red-800">{{ $diffVal }}</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-stone-100 text-stone-600">0 (Sesuai)</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-stone-600">
                                    <span class="font-bold text-stone-800 capitalize">{{ str_replace('_', ' ', $recon->reason ?? '-') }}</span>
                                    @if($recon->notes)
                                        <p class="text-[11px] text-stone-500 mt-0.5">{{ $recon->notes }}</p>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-stone-600 font-semibold">{{ $recon->user->name ?? 'Supervisor' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-stone-400 font-bold">
                                    Belum ada catatan riwayat penyesuaian stok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $recentReconciliations->links() }}
            </div>
        </div>

    </div>

    <!-- TABEL MONITOR KONTROL SELURUH BAHAN BAKU CABANG -->
    <div class="bg-white border border-stone-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-stone-100 pb-4">
            <div>
                <h3 class="text-base font-black text-[#1C1917] flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#BD2000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    <span>Daftar Stok Bahan Saat Ini</span>
                </h3>
                <p class="text-xs text-stone-500">Klik tombol <strong>"⚡ Sesuaikan"</strong> pada salah satu baris untuk langsung mengisi form di atas.</p>
            </div>
            <span class="text-xs font-bold bg-stone-100 text-stone-700 px-3 py-1.5 rounded-xl w-fit">
                Total: {{ $inventories->count() }} Bahan Baku
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-stone-50 text-stone-700 font-extrabold uppercase tracking-wider border-b border-stone-200">
                        <th class="py-3 px-4">Nama Bahan</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4 text-center">Stok Tersedia</th>
                        <th class="py-3 px-4 text-center">Batas Minimum</th>
                        <th class="py-3 px-4 text-right">Harga HPP</th>
                        <th class="py-3 px-4 text-center">Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 font-medium">
                    @foreach($inventories as $inv)
                        @php
                            $isWarning = $inv->stock <= $inv->min_stock;
                            $isZero = $inv->stock <= 0;
                        @endphp
                        <tr class="hover:bg-stone-50/80 transition-colors">
                            <td class="py-3 px-4 font-black text-[#1C1917] text-sm">{{ $inv->name }}</td>
                            <td class="py-3 px-4">
                                <span class="text-[10px] font-extrabold px-2.5 py-0.5 rounded-full bg-stone-100 text-stone-700 capitalize">
                                    {{ str_replace('_', ' ', $inv->category) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($isZero)
                                    <span class="px-3 py-1 rounded-full text-xs font-black bg-red-100 text-red-700 border border-red-200">
                                        0 {{ $inv->unit }} (HABIS)
                                    </span>
                                @elseif($isWarning)
                                    <span class="px-3 py-1 rounded-full text-xs font-black bg-amber-100 text-amber-800 border border-amber-300">
                                        {{ (float)$inv->stock }} {{ $inv->unit }} (MENIPIS)
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-black bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        {{ (float)$inv->stock }} {{ $inv->unit }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center text-stone-500 font-semibold">{{ (float)$inv->min_stock }} {{ $inv->unit }}</td>
                            <td class="py-3 px-4 text-right font-bold text-stone-700">Rp {{ number_format($inv->unit_price, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-center">
                                <button type="button" onclick="selectFromTable({{ $inv->id }})"
                                    class="bg-stone-100 hover:bg-[#BD2000] hover:text-white text-[#BD2000] font-extrabold text-[11px] px-3.5 py-1.5 rounded-xl border border-stone-200 hover:border-transparent transition-all shadow-2xs cursor-pointer">
                                    ⚡ Sesuaikan
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    let currentActionType = 'set_actual';
    let currentSelectedStock = 0;
    let currentSelectedUnit = '';

    function switchTab(tab) {
        document.getElementById('section-quick-adjust').classList.toggle('hidden', tab !== 'quick-adjust');
        document.getElementById('section-add-item').classList.toggle('hidden', tab !== 'add-item');
        document.getElementById('section-log-history').classList.toggle('hidden', tab !== 'log-history');

        const btnAdjust = document.getElementById('tab-quick-adjust');
        const btnAdd = document.getElementById('tab-add-item');
        const btnLog = document.getElementById('tab-log-history');

        [btnAdjust, btnAdd, btnLog].forEach(btn => {
            btn.className = "px-5 py-2.5 rounded-xl text-xs font-bold text-stone-600 hover:text-stone-900 transition-all";
        });

        if (tab === 'quick-adjust') {
            btnAdjust.className = "px-5 py-2.5 rounded-xl text-xs font-extrabold transition-all shadow-sm bg-white text-[#BD2000]";
        } else if (tab === 'add-item') {
            btnAdd.className = "px-5 py-2.5 rounded-xl text-xs font-extrabold transition-all shadow-sm bg-white text-[#BD2000]";
        } else {
            btnLog.className = "px-5 py-2.5 rounded-xl text-xs font-extrabold transition-all shadow-sm bg-white text-[#BD2000]";
        }
    }

    function onItemChange() {
        const select = document.getElementById('select_inventory_id');
        const previewCard = document.getElementById('item-preview-card');
        const opt = select.options[select.selectedIndex];

        if (!opt || !opt.value) {
            previewCard.classList.add('hidden');
            currentSelectedStock = 0;
            currentSelectedUnit = '';
            document.getElementById('input-unit-badge').innerText = 'unit';
            calculateLivePreview();
            return;
        }

        currentSelectedStock = parseFloat(opt.dataset.stock) || 0;
        currentSelectedUnit = opt.dataset.unit || 'unit';
        const price = parseFloat(opt.dataset.price) || 0;
        const name = opt.dataset.name || '';
        const cat = opt.dataset.cat ? opt.dataset.cat.replace('_', ' ') : 'Bahan';

        document.getElementById('preview-name').innerText = name;
        document.getElementById('preview-cat-badge').innerText = cat;
        document.getElementById('preview-stock').innerText = currentSelectedStock;
        document.getElementById('preview-unit').innerText = currentSelectedUnit;
        document.getElementById('preview-price').innerText = 'Rp ' + Number(price).toLocaleString('id-ID') + ' / ' + currentSelectedUnit;
        document.getElementById('input-unit-badge').innerText = currentSelectedUnit;

        previewCard.classList.remove('hidden');

        // Pre-fill input with current stock if set_actual mode
        const inputVal = document.getElementById('input_stock_value');
        if (currentActionType === 'set_actual' && (!inputVal.value || inputVal.value == 0)) {
            inputVal.value = currentSelectedStock;
        }
        calculateLivePreview();
    }

    function selectActionType(type) {
        currentActionType = type;
        document.getElementById('input_action_type').value = type;

        const cardSet = document.getElementById('card-act-set_actual');
        const cardAdd = document.getElementById('card-act-add');
        const cardReduce = document.getElementById('card-act-reduce');

        [cardSet, cardAdd, cardReduce].forEach(c => {
            c.className = "cursor-pointer border border-stone-200 bg-white hover:border-stone-400 rounded-2xl p-4 transition-all flex items-start gap-3";
        });

        document.getElementById('radio_set_actual').checked = (type === 'set_actual');
        document.getElementById('radio_add').checked = (type === 'add');
        document.getElementById('radio_reduce').checked = (type === 'reduce');

        const label = document.getElementById('label-stock-value');
        const input = document.getElementById('input_stock_value');
        const reasonSelect = document.getElementById('reason');

        if (type === 'set_actual') {
            cardSet.className = "cursor-pointer border-2 border-[#BD2000] bg-[#BD2000]/5 rounded-2xl p-4 transition-all flex items-start gap-3";
            label.innerText = "Masukkan Jumlah Stok Fisik di Dapur Sekarang *";
            input.name = "actual_stock";
            input.placeholder = "Contoh: 45";
            if (currentSelectedStock > 0 && !input.value) {
                input.value = currentSelectedStock;
            }
            reasonSelect.value = "koreksi_stok";
        } else if (type === 'add') {
            cardAdd.className = "cursor-pointer border-2 border-emerald-600 bg-emerald-50/50 rounded-2xl p-4 transition-all flex items-start gap-3";
            label.innerText = "Jumlah Stok Baru Masuk / Dibeli *";
            input.name = "qty_change";
            input.placeholder = "Contoh: 10";
            input.value = '';
            reasonSelect.value = "koreksi_stok";
        } else if (type === 'reduce') {
            cardReduce.className = "cursor-pointer border-2 border-red-600 bg-red-50/50 rounded-2xl p-4 transition-all flex items-start gap-3";
            label.innerText = "Jumlah Stok yang Berkurang / Basi / Rusak *";
            input.name = "qty_change";
            input.placeholder = "Contoh: 3";
            input.value = '';
            reasonSelect.value = "rusak_basi";
        }

        calculateLivePreview();
    }

    function calculateLivePreview() {
        const inputVal = parseFloat(document.getElementById('input_stock_value').value);
        const resultBox = document.getElementById('live-calc-text');

        if (isNaN(inputVal)) {
            resultBox.innerHTML = '<span class="text-stone-400">Masukkan angka pada kolom input untuk melihat hasil perhitungan.</span>';
            return;
        }

        if (currentActionType === 'set_actual') {
            const diff = inputVal - currentSelectedStock;
            if (diff === 0) {
                resultBox.innerHTML = `<span class="text-emerald-700">✅ Stok dikonfirmasi sama dengan sistem: <strong>${inputVal} ${currentSelectedUnit}</strong> (Tidak ada selisih).</span>`;
            } else if (diff > 0) {
                resultBox.innerHTML = `<span class="text-emerald-700">📈 Stok bertambah dari <strong>${currentSelectedStock}</strong> menjadi <strong>${inputVal} ${currentSelectedUnit}</strong> (+${diff.toFixed(2)} ${currentSelectedUnit}).</span>`;
            } else {
                resultBox.innerHTML = `<span class="text-red-700">📉 Stok berkurang dari <strong>${currentSelectedStock}</strong> menjadi <strong>${inputVal} ${currentSelectedUnit}</strong> (${diff.toFixed(2)} ${currentSelectedUnit}).</span>`;
            }
        } else if (currentActionType === 'add') {
            const newTotal = currentSelectedStock + inputVal;
            resultBox.innerHTML = `<span class="text-emerald-700">📦 Stok bertambah: <strong>${currentSelectedStock} + ${inputVal} = ${newTotal.toFixed(2)} ${currentSelectedUnit}</strong>.</span>`;
        } else if (currentActionType === 'reduce') {
            const newTotal = Math.max(0, currentSelectedStock - inputVal);
            resultBox.innerHTML = `<span class="text-red-700">🗑️ Stok berkurang: <strong>${currentSelectedStock} - ${inputVal} = ${newTotal.toFixed(2)} ${currentSelectedUnit}</strong>.</span>`;
        }
    }

    function selectFromTable(inventoryId) {
        switchTab('quick-adjust');
        const select = document.getElementById('select_inventory_id');
        select.value = inventoryId;
        onItemChange();
        window.scrollTo({ top: document.getElementById('form-adjust').offsetTop - 80, behavior: 'smooth' });
    }

    // Auto-trigger if old input was present
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('select_inventory_id');
        if (select && select.value) {
            onItemChange();
        }
    });
</script>
@endsection
