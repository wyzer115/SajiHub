@extends('layouts.app')
@section('title', 'Tambah Staff - SajiHUB')
@section('page-title', 'Tambah Staff Baru')

@section('content')

<div class="max-w-xl mx-auto animate-fade-in-up">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.staff.index') }}" class="p-2 bg-dark-800 hover:bg-dark-700 text-dark-300 hover:text-white rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-xl font-bold text-white">Tambah Staff Baru</h2>
            <p class="text-dark-400 text-sm">Cabang: <span class="text-brand-400 font-semibold">{{ auth()->user()->branch->name }}</span></p>
        </div>
    </div>

    {{-- Role Info Cards --}}
    <div class="grid grid-cols-2 gap-3 mb-6">
        <div class="bg-green-500/5 border border-green-500/20 rounded-xl p-3">
            <div class="text-xs font-semibold text-green-400 mb-1">Kasir (Front Office)</div>
            <p class="text-xs text-dark-400">Input pesanan POS, terima pembayaran, cetak struk, pilih meja</p>
        </div>
        <div class="bg-purple-500/5 border border-purple-500/20 rounded-xl p-3">
            <div class="text-xs font-semibold text-purple-400 mb-1">Koki / Dapur</div>
            <p class="text-xs text-dark-400">Monitor pesanan masuk real-time, ubah status masak & siap saji</p>
        </div>
    </div>

    <div class="bg-dark-900 border border-dark-700 rounded-2xl p-6 shadow-sm">
        @if($errors->any())
        <div class="mb-5 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl text-sm">
            <p class="font-semibold mb-1">Terdapat kesalahan:</p>
            <ul class="list-disc list-inside space-y-0.5 text-xs">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.staff.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1.5">Jabatan / Role <span class="text-red-400">*</span></label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="kasir" class="sr-only peer" {{ old('role', 'kasir') === 'kasir' ? 'checked' : '' }}>
                        <div class="border border-dark-600 peer-checked:border-green-500 peer-checked:bg-green-500/10 rounded-xl p-3 transition-all text-center">
                            <div class="text-sm font-semibold text-dark-200 peer-checked:text-green-400">Kasir</div>
                            <div class="text-xs text-dark-500">Front Office</div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="koki" class="sr-only peer" {{ old('role') === 'koki' ? 'checked' : '' }}>
                        <div class="border border-dark-600 peer-checked:border-purple-500 peer-checked:bg-purple-500/10 rounded-xl p-3 transition-all text-center">
                            <div class="text-sm font-semibold text-dark-200 peer-checked:text-purple-400">Koki / Dapur</div>
                            <div class="text-xs text-dark-500">Back Office</div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Nama Lengkap <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full bg-dark-800 border border-dark-600 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-brand-500 placeholder-dark-500 transition-colors"
                           placeholder="Nama lengkap">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Username <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-dark-500 text-sm">@</span>
                        <input type="text" name="username" value="{{ old('username') }}" required
                               class="w-full bg-dark-800 border border-dark-600 text-white rounded-lg pl-7 pr-4 py-2.5 text-sm focus:outline-none focus:border-brand-500 placeholder-dark-500 transition-colors"
                               placeholder="nama_staff">
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
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Password <span class="text-red-400">*</span></label>
                    <input type="password" name="password" required minlength="6"
                           class="w-full bg-dark-800 border border-dark-600 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-brand-500 placeholder-dark-500 transition-colors"
                           placeholder="Min. 6 karakter">
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1.5">Konfirmasi Password <span class="text-red-400">*</span></label>
                    <input type="password" name="password_confirmation" required
                           class="w-full bg-dark-800 border border-dark-600 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-brand-500 placeholder-dark-500 transition-colors"
                           placeholder="Ulangi password">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.staff.index') }}"
                   class="px-5 py-2.5 bg-dark-700 hover:bg-dark-600 text-dark-200 hover:text-white rounded-xl text-sm font-medium transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-brand-500 hover:bg-brand-600 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-brand-500/20 hover:scale-105">
                    Buat Akun Staff
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
