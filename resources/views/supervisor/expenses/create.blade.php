@extends('layouts.app')
@section('title', 'Tambah Pengeluaran Operasional')
@section('page-title', 'Tambah Pengeluaran Operasional Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 animate-fade-in-up">
    <a href="{{ route('supervisor.expenses.index') }}" class="inline-flex items-center text-slate-600 hover:text-[#BD2000] font-bold text-sm transition-colors mb-4">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Daftar Pengeluaran
    </a>

    <div class="bg-white border border-stone-200 rounded-3xl p-8 shadow-sm">
        <form action="{{ route('supervisor.expenses.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Keterangan / Nama Pengeluaran</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required 
                    class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 focus:border-[#BD2000] focus:outline-none transition-all"
                    placeholder="Contoh: Pembayaran Tagihan Listrik & Air Bulan Ini">
                @error('title')
                    <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="category" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Kategori Biaya</label>
                    <select name="category" id="category" required 
                        class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 focus:border-[#BD2000] focus:outline-none transition-all">
                        <option value="operasional">Operasional (Utilitas/Listrik/Gas)</option>
                        <option value="pembelian_bahan">Pembelian Bahan Darurat</option>
                        <option value="peralatan">Peralatan / Servis Mesin</option>
                        <option value="gaji_harian">Gaji / Insentif Harian</option>
                        <option value="lain_lain">Lain-lain</option>
                    </select>
                    @error('category')
                        <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="amount" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Nominal Pengeluaran (Rp)</label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required min="1" step="500"
                        class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 focus:border-[#BD2000] focus:outline-none transition-all"
                        placeholder="Contoh: 350000">
                    @error('amount')
                        <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="date" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Tanggal Pengeluaran</label>
                <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required 
                    class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 focus:border-[#BD2000] focus:outline-none transition-all">
                @error('date')
                    <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="notes" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Catatan Detail (Opsional)</label>
                <textarea name="notes" id="notes" rows="3" 
                    class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 focus:border-[#BD2000] focus:outline-none transition-all"
                    placeholder="Contoh: Penggantian tabung gas 12kg dan selang regulator..."></textarea>
                @error('notes')
                    <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 flex justify-end space-x-4">
                <a href="{{ route('supervisor.expenses.index') }}" class="bg-stone-100 hover:bg-stone-200 text-stone-700 border border-stone-300 px-6 py-3 rounded-xl transition-all font-bold">Batal</a>
                <button type="submit" class="bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold px-8 py-3 rounded-xl transition-all shadow-md cursor-pointer">
                    Simpan Pengeluaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
