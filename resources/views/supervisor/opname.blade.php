@extends('layouts.app')
@section('title', 'Stock Opname & Penyesuaian')
@section('page-title', 'Stock Opname & Audit Penyesuaian Stok')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">

    <!-- Header info banner -->
    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm">
        <h2 class="text-xl font-black text-[#8C0000] flex items-center gap-2 mb-1">
            <svg class="w-6 h-6 text-[#BD2000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            <span>Audit Stock Opname Fisik (Input Manual)</span>
        </h2>
        <p class="text-slate-600 text-xs font-medium">Masukkan data barang, satuan, harga, dan jumlah stok fisik secara manual. Jika barang sudah ada, stok & harga akan diperbarui otomatis.</p>
    </div>

    <!-- Opname Form Manual -->
    <div class="bg-white border border-stone-200 rounded-3xl p-8 shadow-sm">
        <form action="{{ route('supervisor.opname.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Nama Barang Inventaris (Manual Input) -->
            <div>
                <label for="item_name" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Nama Barang / Bahan Inventaris *</label>
                <input type="text" name="item_name" id="item_name" value="{{ old('item_name') }}" required
                    class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                    placeholder="Contoh: Daging Sapi, Beras Premium, Telur Ayam, Minyak Goreng...">
                @error('item_name')
                    <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Satuan & Harga Satuan (Manual Input) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="unit" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Satuan (kg, gram, liter, pcs, botol, dll) *</label>
                    <input type="text" name="unit" id="unit" list="unit-suggestions" value="{{ old('unit') }}" required
                        class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                        placeholder="Contoh: kg, gram, liter, pcs, ikat, pack...">
                    <datalist id="unit-suggestions">
                        <option value="kg"></option>
                        <option value="gram"></option>
                        <option value="liter"></option>
                        <option value="ml"></option>
                        <option value="pcs"></option>
                        <option value="pack"></option>
                        <option value="botol"></option>
                        <option value="ikat"></option>
                        <option value="dus"></option>
                    </datalist>
                    @error('unit')
                        <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="unit_price" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Harga Satuan (Rp) *</label>
                    <input type="number" name="unit_price" id="unit_price" value="{{ old('unit_price') }}" required min="0" step="100"
                        class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                        placeholder="Contoh: 15000">
                    @error('unit_price')
                        <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Stok Fisik & Alasan Penyesuaian -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="physical_stock" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Jumlah Stok Fisik Hasil Hitung Real *</label>
                    <input type="number" step="0.001" name="physical_stock" id="physical_stock" value="{{ old('physical_stock') }}" required min="0"
                        class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                        placeholder="Contoh: 45">
                    @error('physical_stock')
                        <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="reason" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Alasan Penyesuaian / Selisih *</label>
                    <select name="reason" id="reason" required 
                        class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all">
                        <option value="selisih_hitung">Selisih Hitung Rutin</option>
                        <option value="rusak_basi">Barang Basi / Rusak (Waste)</option>
                        <option value="lost_hilang">Lost / Hilang</option>
                        <option value="koreksi_stok">Koreksi Data Input Manual</option>
                    </select>
                    @error('reason')
                        <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="notes" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Catatan Audit Tambahan (Opsional)</label>
                <textarea name="notes" id="notes" rows="3" 
                    class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                    placeholder="Masukkan penjelasan penyebab selisih atau catatan tambahan..."></textarea>
                @error('notes')
                    <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 flex justify-end space-x-4">
                <a href="{{ route('supervisor.inventory.index') }}" class="bg-stone-100 hover:bg-stone-200 text-stone-700 border border-stone-300 px-6 py-3 rounded-xl transition-all font-bold">Batal</a>
                <button type="submit" class="bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold px-8 py-3 rounded-xl transition-all shadow-md cursor-pointer">
                    Simpan Stock Opname
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
