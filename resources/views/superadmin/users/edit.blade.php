@extends('layouts.app')
<<<<<<< HEAD
@section('title', 'Edit Akun - SajiHUB')
@section('page-title', 'Edit Akun Pengguna')

@section('content')

<div class="max-w-2xl mx-auto animate-fade-in-up">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('superadmin.users.index') }}" class="p-2 bg-dark-800 hover:bg-dark-700 text-dark-300 hover:text-white rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-xl font-bold text-white">Edit Akun: {{ $user->name }}</h2>
            <p class="text-dark-400 text-sm">Ubah data pengguna. Kosongkan password jika tidak ingin menggantinya.</p>
        </div>
    </div>

    <div class="bg-dark-900 border border-dark-700 rounded-2xl p-6 shadow-sm">
        @if($errors->any())
        <div class="mb-5 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl text-sm">
            <p class="font-semibold mb-1">Terdapat kesalahan input:</p>
            <ul class="list-disc list-inside space-y-0.5 text-xs">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('superadmin.users.update', $user->id) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Nama Lengkap <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full bg-dark-800 border border-dark-600 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-brand-500 placeholder-dark-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Username <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-dark-500 text-sm">@</span>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                               class="w-full bg-dark-800 border border-dark-600 text-white rounded-lg pl-7 pr-4 py-2.5 text-sm focus:outline-none focus:border-brand-500 placeholder-dark-500 transition-colors">
                    </div>
=======
@section('title', 'Edit Admin Cabang')
@section('page-title', 'Edit Akun Admin')

@section('content')
<div class="mb-6 animate-fade-in-up delay-100">
    <a href="{{ route('superadmin.users.index') }}" class="inline-flex items-center gap-2 text-sm text-dark-400 hover:text-white transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Daftar Admin
    </a>
</div>

<div class="bg-dark-900 rounded-2xl border border-dark-700 overflow-hidden animate-fade-in-up delay-200 shadow-sm max-w-3xl">
    <div class="p-6 border-b border-dark-700">
        <h2 class="text-lg font-semibold text-white">Edit Informasi Admin</h2>
        <p class="text-sm text-dark-400 mt-1">Perbarui detail akun admin dan penugasan cabangnya. Kosongkan field password jika tidak ingin mengubahnya.</p>
    </div>
    
    <div class="p-6">
        <form action="{{ route('superadmin.users.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label for="branch_id" class="block text-sm font-medium text-dark-300 mb-1.5">Penugasan Cabang <span class="text-brand-500">*</span></label>
                <select name="branch_id" id="branch_id" required
                    class="block w-full px-4 py-3 bg-dark-800 border border-dark-600 rounded-xl text-white focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/50 transition-colors shadow-inner">
                    <option value="">-- Pilih Cabang Penempatan --</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id', $user->branch_id) == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
                @error('branch_id')
                    <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-dark-300 mb-1.5">Nama Lengkap <span class="text-brand-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                    class="block w-full px-4 py-3 bg-dark-800 border border-dark-600 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/50 transition-colors shadow-inner" 
                    placeholder="Contoh: Budi Santoso">
                @error('name')
                    <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-dark-300 mb-1.5">Alamat Email <span class="text-brand-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="block w-full px-4 py-3 bg-dark-800 border border-dark-600 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/50 transition-colors shadow-inner" 
                        placeholder="Contoh: budi@sajihub.com">
                    @error('email')
                        <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium text-dark-300 mb-1.5">Username <span class="text-brand-500">*</span></label>
                    <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required
                        class="block w-full px-4 py-3 bg-dark-800 border border-dark-600 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/50 transition-colors shadow-inner" 
                        placeholder="Contoh: budi_saji">
                    @error('username')
                        <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                    @enderror
>>>>>>> a471717247185442b2b06268d4e157d25322f3c7
                </div>
            </div>

            <div>
<<<<<<< HEAD
                <label class="block text-sm font-medium text-dark-300 mb-1.5">Email <span class="text-red-400">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full bg-dark-800 border border-dark-600 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-brand-500 placeholder-dark-500 transition-colors">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Role / Jabatan <span class="text-red-400">*</span></label>
                    <select name="role" required class="w-full bg-dark-800 border border-dark-600 text-dark-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-brand-500 transition-colors">
                        <option value="admin_cabang" {{ old('role', $user->role) == 'admin_cabang' ? 'selected' : '' }}>Admin Cabang / Manager</option>
                        <option value="kasir" {{ old('role', $user->role) == 'kasir' ? 'selected' : '' }}>Kasir (Front Office)</option>
                        <option value="koki" {{ old('role', $user->role) == 'koki' ? 'selected' : '' }}>Koki / Dapur (Back Office)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Cabang Penugasan <span class="text-red-400">*</span></label>
                    <select name="branch_id" required class="w-full bg-dark-800 border border-dark-600 text-dark-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-brand-500 transition-colors">
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $user->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="h-px bg-dark-700 my-1"></div>
            <p class="text-xs text-dark-500">Kosongkan field password jika tidak ingin mengubah password.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Password Baru</label>
                    <input type="password" name="password" minlength="6"
                           class="w-full bg-dark-800 border border-dark-600 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-brand-500 placeholder-dark-500 transition-colors"
                           placeholder="Biarkan kosong jika tidak diubah">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation"
                           class="w-full bg-dark-800 border border-dark-600 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-brand-500 placeholder-dark-500 transition-colors"
                           placeholder="Ulangi password baru">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('superadmin.users.index') }}"
                   class="px-5 py-2.5 bg-dark-700 hover:bg-dark-600 text-dark-200 hover:text-white rounded-xl text-sm font-medium transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-brand-500 hover:bg-brand-600 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-brand-500/20 hover:scale-105">
=======
                <label for="password" class="block text-sm font-medium text-dark-300 mb-1.5">Password Baru (Opsional)</label>
                <input type="password" id="password" name="password"
                    class="block w-full px-4 py-3 bg-dark-800 border border-dark-600 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/50 transition-colors shadow-inner" 
                    placeholder="Kosongkan jika tidak ingin mengubah password...">
                @error('password')
                    <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="pt-4 border-t border-dark-700 flex justify-end gap-3">
                <a href="{{ route('superadmin.users.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-medium bg-dark-700 hover:bg-dark-600 text-dark-200 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-medium bg-brand-500 hover:bg-brand-600 text-white transition-colors shadow-lg shadow-brand-500/20">
>>>>>>> a471717247185442b2b06268d4e157d25322f3c7
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
<<<<<<< HEAD

=======
>>>>>>> a471717247185442b2b06268d4e157d25322f3c7
@endsection
