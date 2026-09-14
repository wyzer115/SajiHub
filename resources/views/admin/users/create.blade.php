@extends('layouts.app')
@section('title', 'Tambah Karyawan Baru')
@section('page-title', 'Tambah Karyawan')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 animate-fade-in-up">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.users.index') }}" class="p-2.5 bg-white border border-stone-200 text-stone-700 hover:text-[#BD2000] hover:bg-stone-100 rounded-xl transition-all shadow-sm">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-xl font-black text-[#8C0000]">Akun Karyawan Baru</h2>
            <p class="text-slate-600 text-xs font-medium">Daftarkan akun Kasir, Dapur (Koki), atau Waiter untuk operasional cabang restoran Anda.</p>
        </div>
    </div>

    <div class="bg-white border border-stone-200 rounded-3xl p-8 shadow-sm">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label for="role" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Peran / Tugas Staf <span class="text-red-500">*</span></label>
                <select name="role" id="role" required
                    class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all">
                    <option value="">-- Pilih Peran / Posisi --</option>
                    <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir (Front Office POS)</option>
                    <option value="koki" {{ old('role') == 'koki' ? 'selected' : '' }}>Dapur / Kitchen (Koki)</option>
                    <option value="waiter" {{ old('role') == 'waiter' ? 'selected' : '' }}>Waiter / Pelayan</option>
                </select>
                @error('role')
                    <p class="mt-1.5 text-xs font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all" 
                    placeholder="Contoh: Ahmad Kasiri">
                @error('name')
                    <p class="mt-1.5 text-xs font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="email" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Alamat Email <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all" 
                        placeholder="Contoh: ahmad@sajihub.com">
                    @error('email')
                        <p class="mt-1.5 text-xs font-bold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="username" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Username <span class="text-red-500">*</span></label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required
                        class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all" 
                        placeholder="Contoh: ahmad_kasir">
                    @error('username')
                        <p class="mt-1.5 text-xs font-bold text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Password <span class="text-red-500">*</span></label>
                <input type="password" id="password" name="password" required
                    class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all" 
                    placeholder="Masukkan password akun karyawan...">
                @error('password')
                    <p class="mt-1.5 text-xs font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="pt-4 border-t border-stone-200 flex justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-stone-100 hover:bg-stone-200 text-stone-700 border border-stone-300 transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-extrabold bg-[#BD2000] hover:bg-[#8C0000] text-white transition-all shadow-md cursor-pointer">
                    Buat Akun Karyawan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
