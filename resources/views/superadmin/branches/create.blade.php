@extends('layouts.app')
@section('title', 'Tambah Cabang Baru')
@section('page-title', 'Tambah Cabang Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 animate-fade-in-up">
    <div class="flex items-center gap-3">
        <a href="{{ route('superadmin.branches.index') }}" class="p-2.5 bg-white border border-stone-200 text-stone-700 hover:text-[#BD2000] hover:bg-stone-100 rounded-xl transition-all shadow-sm">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-xl font-black text-[#8C0000]">Tambah Cabang Restoran Baru</h2>
            <p class="text-slate-600 text-xs font-medium">Isi form di bawah ini untuk menambahkan cabang baru ke dalam sistem SajiHUB.</p>
        </div>
    </div>

    <div class="bg-white border border-stone-200 rounded-3xl p-8 shadow-sm">
        <form action="{{ route('superadmin.branches.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label for="name" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Nama Cabang <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all" 
                    placeholder="Contoh: SajiHub Cabang Jakarta Pusat">
                @error('name')
                    <p class="mt-1.5 text-xs font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Nomor Telepon Operational</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                    class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all" 
                    placeholder="Contoh: 081234567890">
                @error('phone')
                    <p class="mt-1.5 text-xs font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="address" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Alamat Lengkap Cabang <span class="text-red-500">*</span></label>
                <textarea id="address" name="address" rows="4" required
                    class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all resize-none" 
                    placeholder="Masukkan alamat lengkap lokasi cabang...">{{ old('address') }}</textarea>
                @error('address')
                    <p class="mt-1.5 text-xs font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="pt-4 border-t border-stone-200 flex justify-end gap-3">
                <a href="{{ route('superadmin.branches.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-stone-100 hover:bg-stone-200 text-stone-700 border border-stone-300 transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-extrabold bg-[#BD2000] hover:bg-[#8C0000] text-white transition-all shadow-md cursor-pointer">
                    Simpan Cabang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
