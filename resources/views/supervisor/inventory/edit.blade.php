@extends('layouts.app')

@section('title', 'Edit Barang Inventaris - SajiHUB')
@section('page-title', 'Edit & Restok Barang')

@section('content')
<div class="max-w-2xl mx-auto animate-fade-in-up">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('supervisor.inventory.index') }}" class="p-2 bg-white border border-stone-200 hover:bg-stone-100 text-stone-700 rounded-xl transition-colors shadow-xs">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-xl font-black text-[#8C0000]">Edit / Restok: {{ $inventory->name }}</h2>
            <p class="text-slate-600 text-sm font-medium">Ubah rincian barang atau perbarui jumlah stok saat ini</p>
        </div>
    </div>

    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm">
        @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-2xl text-sm font-bold">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('supervisor.inventory.update', $inventory->id) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Nama Barang / Bahan <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $inventory->name) }}" required
                           class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#BD2000]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Kategori Barang <span class="text-red-500">*</span></label>
                    <select name="category" required class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#BD2000]">
                        <option value="bahan_makanan" {{ old('category', $inventory->category) == 'bahan_makanan' ? 'selected' : '' }}>Bahan Makanan</option>
                        <option value="bahan_minuman" {{ old('category', $inventory->category) == 'bahan_minuman' ? 'selected' : '' }}>Bahan Minuman</option>
                        <option value="peralatan" {{ old('category', $inventory->category) == 'peralatan' ? 'selected' : '' }}>Peralatan / Alat Masak</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Jumlah Stok <span class="text-red-500">*</span></label>
                    <input type="number" step="any" name="stock" value="{{ old('stock', (float)$inventory->stock) }}" required min="0"
                           class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#BD2000]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Satuan (Unit) <span class="text-red-500">*</span></label>
                    <input type="text" name="unit" value="{{ old('unit', $inventory->unit) }}" required
                           class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#BD2000]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Batas Min. Stok Warning <span class="text-red-500">*</span></label>
                    <input type="number" step="any" name="min_stock" value="{{ old('min_stock', (float)$inventory->min_stock) }}" required min="0"
                           class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#BD2000]">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Harga Beli Per Unit (Rp)</label>
                <input type="number" name="unit_price" value="{{ old('unit_price', $inventory->unit_price) }}" min="0" step="100"
                       class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#BD2000]">
            </div>

            <div>
                <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Catatan Tambahan (Opsional)</label>
                <textarea name="notes" rows="3"
                          class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#BD2000]">{{ old('notes', $inventory->notes) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('supervisor.inventory.index') }}"
                   class="px-5 py-2.5 bg-stone-100 hover:bg-stone-200 text-stone-700 border border-stone-300 rounded-xl text-sm font-bold transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-[#BD2000] hover:bg-[#8C0000] text-white rounded-xl text-sm font-extrabold transition-all shadow-md cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
