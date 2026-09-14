@extends('layouts.app')
@section('title', 'Tambah Staff - SajiHUB')
@section('page-title', 'Tambah Staff Baru')

@section('content')

<div class="max-w-3xl mx-auto space-y-6 animate-fade-in-up">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.staff.index') }}" class="p-2.5 bg-white border border-stone-200 text-stone-700 hover:text-[#BD2000] hover:bg-stone-100 rounded-xl transition-all shadow-sm">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-xl font-black text-[#8C0000]">Tambah Staff / Karyawan Baru</h2>
            <p class="text-slate-600 text-xs font-medium">Cabang: <span class="text-[#BD2000] font-bold">{{ auth()->user()->branch->name ?? 'Cabang' }}</span></p>
        </div>
    </div>

    {{-- Role Info Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-3.5">
            <div class="text-xs font-bold text-blue-700 mb-1">Owner</div>
            <p class="text-[11px] text-slate-600 font-medium">Pantau omzet, laba bersih & stok barang</p>
        </div>
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-3.5">
            <div class="text-xs font-bold text-amber-700 mb-1">Supervisor</div>
            <p class="text-[11px] text-slate-600 font-medium">Kelola & restok bahan makanan & alat</p>
        </div>
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-3.5">
            <div class="text-xs font-bold text-emerald-700 mb-1">Kasir</div>
            <p class="text-[11px] text-slate-600 font-medium">Input POS, pilih meja & bayar</p>
        </div>
        <div class="bg-purple-50 border border-purple-200 rounded-2xl p-3.5">
            <div class="text-xs font-bold text-purple-700 mb-1">Dapur</div>
            <p class="text-[11px] text-slate-600 font-medium">Monitor pesanan real-time dapur</p>
        </div>
    </div>

    <div class="bg-white border border-stone-200 rounded-3xl p-8 shadow-sm">
        @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-2xl text-sm">
            <p class="font-bold mb-1">Terdapat kesalahan:</p>
            <ul class="list-disc list-inside space-y-0.5 text-xs font-semibold">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.staff.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Jabatan / Role <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="owner" class="sr-only peer" {{ old('role') === 'owner' ? 'checked' : '' }}>
                        <div class="border border-stone-300 bg-stone-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 rounded-2xl p-3.5 transition-all text-center">
                            <div class="text-xs font-black text-stone-700 peer-checked:text-blue-700">Owner</div>
                            <div class="text-[10px] text-slate-500 font-bold">Pemilik</div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="supervisor" class="sr-only peer" {{ old('role') === 'supervisor' ? 'checked' : '' }}>
                        <div class="border border-stone-300 bg-stone-50 peer-checked:border-amber-600 peer-checked:bg-amber-50 rounded-2xl p-3.5 transition-all text-center">
                            <div class="text-xs font-black text-stone-700 peer-checked:text-amber-700">Supervisor</div>
                            <div class="text-[10px] text-slate-500 font-bold">Stok & Alat</div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="kasir" class="sr-only peer" {{ old('role', 'kasir') === 'kasir' ? 'checked' : '' }}>
                        <div class="border border-stone-300 bg-stone-50 peer-checked:border-emerald-600 peer-checked:bg-emerald-50 rounded-2xl p-3.5 transition-all text-center">
                            <div class="text-xs font-black text-stone-700 peer-checked:text-emerald-700">Kasir</div>
                            <div class="text-[10px] text-slate-500 font-bold">POS Kasir</div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="dapur" class="sr-only peer" {{ old('role') === 'dapur' || old('role') === 'koki' ? 'checked' : '' }}>
                        <div class="border border-stone-300 bg-stone-50 peer-checked:border-purple-600 peer-checked:bg-purple-50 rounded-2xl p-3.5 transition-all text-center">
                            <div class="text-xs font-black text-stone-700 peer-checked:text-purple-700">Dapur</div>
                            <div class="text-[10px] text-slate-500 font-bold">Kitchen</div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                           placeholder="Nama lengkap">
                </div>
                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Username <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-stone-400 font-bold text-sm">@</span>
                        <input type="text" name="username" value="{{ old('username') }}" required
                               class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl pl-8 pr-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                               placeholder="nama_staff">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Alamat Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                       placeholder="email@sajihub.com">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required minlength="6"
                           class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                           placeholder="Min. 6 karakter">
                </div>
                <div>
                    <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" required
                           class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                           placeholder="Ulangi password">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.staff.index') }}"
                   class="px-5 py-2.5 bg-stone-100 hover:bg-stone-200 text-stone-700 border border-stone-300 rounded-xl text-sm font-bold transition-all">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-[#BD2000] hover:bg-[#8C0000] text-white rounded-xl text-sm font-extrabold transition-all shadow-md cursor-pointer">
                    Buat Akun Staff
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
