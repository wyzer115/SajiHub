@extends('layouts.app')
@section('title', 'Edit Karyawan')
@section('page-title', 'Edit Detail Karyawan')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 animate-fade-in-up">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.users.index') }}" class="p-2.5 bg-white border border-stone-200 text-stone-700 hover:text-[#BD2000] hover:bg-stone-100 rounded-xl transition-all shadow-sm">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-xl font-black text-[#8C0000]">Edit Informasi Karyawan: {{ $user->name }}</h2>
            <p class="text-slate-600 text-xs font-medium">Perbarui detail akun staf cabang. Kosongkan field password jika tidak ingin mengubahnya.</p>
        </div>
    </div>

    <div class="bg-white border border-stone-200 rounded-3xl p-8 shadow-sm">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label for="role" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Peran / Tugas Staf <span class="text-red-500">*</span></label>
                <select name="role" id="role" required
                    class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all">
                    <option value="kasir" {{ old('role', $user->role) == 'kasir' ? 'selected' : '' }}>Kasir</option>
                    <option value="koki" {{ old('role', $user->role) == 'koki' ? 'selected' : '' }}>Staf Dapur</option>
                    <option value="waiter" {{ old('role', $user->role) == 'waiter' ? 'selected' : '' }}>Pelayan</option>
                </select>
                @error('role')
                    <p class="mt-1.5 text-xs font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all" 
                    placeholder="Contoh: Ahmad Kasiri">
                @error('name')
                    <p class="mt-1.5 text-xs font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="email" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Alamat Email <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all" 
                        placeholder="Contoh: ahmad@sajihub.com">
                    @error('email')
                        <p class="mt-1.5 text-xs font-bold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="username" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Username <span class="text-red-500">*</span></label>
                    <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required
                        class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all" 
                        placeholder="Contoh: ahmad_kasir">
                    @error('username')
                        <p class="mt-1.5 text-xs font-bold text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="edit-user-password" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Password Baru (Opsional)</label>
                <div class="relative">
                    <input type="password" id="edit-user-password" name="password"
                        class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl pl-4 pr-11 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all" 
                        placeholder="Kosongkan jika tidak ingin mengubah password...">
                    <button type="button" onclick="toggleInputPassword('edit-user-password', this)" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-stone-400 hover:text-stone-600 cursor-pointer focus:outline-none" aria-label="Lihat Kata Sandi">
                        <svg class="w-5 h-5 eye-open-svg block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg class="w-5 h-5 eye-closed-svg hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a8.97 8.97 0 013.122-.563c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m-4.09-4.09a3 3 0 00-4.243-4.243m4.242 4.242L9.88 9.88m-4.242 4.242L3 3l18 18"/></svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1.5 text-xs font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="pt-4 border-t border-stone-200 flex justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-stone-100 hover:bg-stone-200 text-stone-700 border border-stone-300 transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-extrabold bg-[#BD2000] hover:bg-[#8C0000] text-white transition-all shadow-md cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
