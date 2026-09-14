@extends('layouts.app')

@section('title', 'Tambah Admin Cabang - SajiHUB')
@section('page-title', 'Tambah Admin Cabang')

@section('content')

<div class="max-w-2xl mx-auto animate-fade-in-up space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('superadmin.users.index') }}" class="p-2.5 bg-white border border-stone-200 text-stone-700 hover:text-[#BD2000] hover:bg-stone-100 rounded-xl transition-all shadow-sm">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-xl font-black text-[#8C0000]">Tambah Admin Cabang Baru</h2>
            <p class="text-slate-600 text-xs font-medium">Daftarkan akun Admin Cabang (Manager / Penanggung Jawab Cabang)</p>
        </div>
    </div>

    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm">
        @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-2xl text-sm">
            <p class="font-bold mb-1">Terdapat kesalahan input:</p>
            <ul class="list-disc list-inside space-y-0.5 text-xs font-semibold">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('superadmin.users.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Penugasan Cabang Restoran <span class="text-red-500">*</span></label>
                <select name="branch_id" required class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all">
                    <option value="">-- Pilih Cabang Penempatan --</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-2.5 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                           placeholder="Contoh: Budi Santoso">
                </div>
                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Username <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-stone-400 font-bold text-sm">@</span>
                        <input type="text" name="username" value="{{ old('username') }}" required
                               class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl pl-8 pr-4 py-2.5 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                               placeholder="admin_cabang">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Alamat Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-2.5 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                       placeholder="admin@sajihub.com">
            </div>

            <div class="h-px bg-stone-200 my-1"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required minlength="6"
                           class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-2.5 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                           placeholder="Minimal 6 karakter">
                </div>
                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" required
                           class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-2.5 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                           placeholder="Ulangi password">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('superadmin.users.index') }}"
                   class="px-5 py-2.5 bg-stone-100 hover:bg-stone-200 text-stone-700 border border-stone-300 rounded-xl text-sm font-bold transition-all">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-[#BD2000] hover:bg-[#8C0000] text-white rounded-xl text-sm font-extrabold transition-all shadow-md cursor-pointer">
                    Buat Akun Admin Cabang
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
