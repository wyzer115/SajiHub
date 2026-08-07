@extends('layouts.app')
@section('title', 'Tambah Akun - SajiHUB')
@section('page-title', 'Tambah Akun Baru')

@section('content')

<div class="max-w-2xl mx-auto animate-fade-in-up">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('superadmin.users.index') }}" class="p-2 bg-dark-800 hover:bg-dark-700 text-dark-300 hover:text-white rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-xl font-bold text-white">Tambah Akun Baru</h2>
            <p class="text-dark-400 text-sm">Buat akun Admin Cabang, Kasir, atau Koki</p>
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

        <form action="{{ route('superadmin.users.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Nama Lengkap <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full bg-dark-800 border border-dark-600 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-brand-500 placeholder-dark-500 transition-colors"
                           placeholder="Nama lengkap pengguna">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Username <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-dark-500 text-sm">@</span>
                        <input type="text" name="username" value="{{ old('username') }}" required
                               class="w-full bg-dark-800 border border-dark-600 text-white rounded-lg pl-7 pr-4 py-2.5 text-sm focus:outline-none focus:border-brand-500 placeholder-dark-500 transition-colors"
                               placeholder="nama_pengguna">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1.5">Email <span class="text-red-400">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full bg-dark-800 border border-dark-600 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-brand-500 placeholder-dark-500 transition-colors"
                       placeholder="email@sajihub.com">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Role / Jabatan <span class="text-red-400">*</span></label>
                    <select name="role" required class="w-full bg-dark-800 border border-dark-600 text-dark-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-brand-500 transition-colors">
                        <option value="">-- Pilih Role --</option>
                        <option value="admin_cabang" {{ old('role') == 'admin_cabang' ? 'selected' : '' }}>Admin Cabang / Manager</option>
                        <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir (Front Office)</option>
                        <option value="koki" {{ old('role') == 'koki' ? 'selected' : '' }}>Koki / Dapur (Back Office)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Cabang Penugasan <span class="text-red-400">*</span></label>
                    <select name="branch_id" required class="w-full bg-dark-800 border border-dark-600 text-dark-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-brand-500 transition-colors">
                        <option value="">-- Pilih Cabang --</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="h-px bg-dark-700 my-1"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Password <span class="text-red-400">*</span></label>
                    <input type="password" name="password" required minlength="6"
                           class="w-full bg-dark-800 border border-dark-600 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-brand-500 placeholder-dark-500 transition-colors"
                           placeholder="Minimal 6 karakter">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Konfirmasi Password <span class="text-red-400">*</span></label>
                    <input type="password" name="password_confirmation" required
                           class="w-full bg-dark-800 border border-dark-600 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-brand-500 placeholder-dark-500 transition-colors"
                           placeholder="Ulangi password">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('superadmin.users.index') }}"
                   class="px-5 py-2.5 bg-dark-700 hover:bg-dark-600 text-dark-200 hover:text-white rounded-xl text-sm font-medium transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-brand-500 hover:bg-brand-600 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-brand-500/20 hover:scale-105">
                    Buat Akun
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
