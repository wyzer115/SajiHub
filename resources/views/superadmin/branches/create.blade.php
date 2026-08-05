@extends('layouts.app')
@section('title', 'Tambah Cabang Baru')
@section('page-title', 'Tambah Cabang Baru')

@section('content')
<div class="mb-6 animate-fade-in-up delay-100">
    <a href="{{ route('superadmin.branches.index') }}" class="inline-flex items-center gap-2 text-sm text-dark-400 hover:text-white transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Daftar Cabang
    </a>
</div>

<div class="bg-dark-900 rounded-2xl border border-dark-700 overflow-hidden animate-fade-in-up delay-200 shadow-sm max-w-3xl">
    <div class="p-6 border-b border-dark-700">
        <h2 class="text-lg font-semibold text-white">Informasi Cabang</h2>
        <p class="text-sm text-dark-400 mt-1">Isi form di bawah ini untuk menambahkan cabang baru ke dalam sistem.</p>
    </div>
    
    <div class="p-6">
        <form action="{{ route('superadmin.branches.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-medium text-dark-300 mb-1.5">Nama Cabang <span class="text-brand-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="block w-full px-4 py-3 bg-dark-800 border border-dark-600 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/50 transition-colors shadow-inner" 
                    placeholder="Contoh: Cabang Jakarta Pusat">
                @error('name')
                    <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-dark-300 mb-1.5">Nomor Telepon</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                    class="block w-full px-4 py-3 bg-dark-800 border border-dark-600 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/50 transition-colors shadow-inner" 
                    placeholder="Contoh: 081234567890">
                @error('phone')
                    <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-dark-300 mb-1.5">Alamat Lengkap <span class="text-brand-500">*</span></label>
                <textarea id="address" name="address" rows="4" required
                    class="block w-full px-4 py-3 bg-dark-800 border border-dark-600 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/50 transition-colors shadow-inner resize-none" 
                    placeholder="Masukkan alamat lengkap cabang...">{{ old('address') }}</textarea>
                @error('address')
                    <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="pt-4 border-t border-dark-700 flex justify-end gap-3">
                <a href="{{ route('superadmin.branches.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-medium bg-dark-700 hover:bg-dark-600 text-dark-200 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-medium bg-brand-500 hover:bg-brand-600 text-white transition-colors shadow-lg shadow-brand-500/20">
                    Simpan Cabang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
