@extends('layouts.app')
@section('title', 'Tambah Karyawan Baru')
@section('page-title', 'Tambah Karyawan')

@section('content')
<div class="mb-6 animate-fade-in-up delay-100">
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-sm text-dark-400 hover:text-white transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Daftar Karyawan
    </a>
</div>

<div class="bg-dark-900 rounded-2xl border border-dark-700 overflow-hidden animate-fade-in-up delay-200 shadow-sm max-w-3xl">
    <div class="p-6 border-b border-dark-700">
        <h2 class="text-lg font-semibold text-white">Akun Karyawan Baru</h2>
        <p class="text-sm text-dark-400 mt-1">Daftarkan akun Kasir, Dapur (Koki), atau Waiter untuk operasional cabang restoran Anda.</p>
    </div>
    
    <div class="p-6">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label for="role" class="block text-sm font-medium text-dark-300 mb-1.5">Peran / Tugas Staf <span class="text-brand-500">*</span></label>
                <select name="role" id="role" required
                    class="block w-full px-4 py-3 bg-dark-800 border border-dark-600 rounded-xl text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/50 transition-colors shadow-inner">
                    <option value="">-- Pilih Peran / Posisi --</option>
                    <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>💵 Kasir (Front Office)</option>
                    <option value="koki" {{ old('role') == 'koki' ? 'selected' : '' }}>🍳 Dapur / Kitchen (Koki)</option>
                    <option value="waiter" {{ old('role') == 'waiter' ? 'selected' : '' }}>💁 Waiter / Pelayan</option>
                </select>
                @error('role')
                    <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-dark-300 mb-1.5">Nama Lengkap <span class="text-brand-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="block w-full px-4 py-3 bg-dark-800 border border-dark-600 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/50 transition-colors shadow-inner" 
                    placeholder="Contoh: Ahmad Kasiri">
                @error('name')
                    <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-dark-300 mb-1.5">Alamat Email <span class="text-brand-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="block w-full px-4 py-3 bg-dark-800 border border-dark-600 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/50 transition-colors shadow-inner" 
                        placeholder="Contoh: ahmad@sajihub.com">
                    @error('email')
                        <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium text-dark-300 mb-1.5">Username <span class="text-brand-500">*</span></label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required
                        class="block w-full px-4 py-3 bg-dark-800 border border-dark-600 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/50 transition-colors shadow-inner" 
                        placeholder="Contoh: ahmad_kasir">
                    @error('username')
                        <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-dark-300 mb-1.5">Password <span class="text-brand-500">*</span></label>
                <input type="password" id="password" name="password" required
                    class="block w-full px-4 py-3 bg-dark-800 border border-dark-600 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/50 transition-colors shadow-inner" 
                    placeholder="Masukkan password akun karyawan...">
                @error('password')
                    <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="pt-4 border-t border-dark-700 flex justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-medium bg-dark-700 hover:bg-dark-600 text-dark-200 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-medium bg-brand-500 hover:bg-brand-600 text-white transition-colors shadow-lg shadow-brand-500/20">
                    Buat Akun Karyawan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
