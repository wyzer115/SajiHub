@extends('layouts.app')
@section('title', 'Manajemen Mode Maintenance - Super Admin')
@section('page-title', 'Mode Pemeliharaan Website')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 animate-fade-in-up">

    <!-- Flash Notification -->
    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 font-bold">✓</div>
            <p class="text-sm font-bold">{{ session('success') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-700/60 hover:text-emerald-700 text-sm font-black">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-700 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-red-500 text-white flex items-center justify-center shrink-0 font-bold">!</div>
            <p class="text-sm font-bold">{{ session('error') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-red-700/60 hover:text-red-700 text-sm font-black">&times;</button>
    </div>
    @endif

    <!-- Status Hero Card -->
    <div class="relative overflow-hidden rounded-3xl p-8 sm:p-10 border transition-all shadow-sm
        {{ $maintenance['active'] ? 'bg-gradient-to-br from-red-500/10 via-amber-500/5 to-slate-900/40 border-red-500/30' : 'bg-gradient-to-br from-emerald-500/10 via-teal-500/5 to-slate-900/40 border-emerald-500/30' }}">
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-black tracking-wider uppercase
                    {{ $maintenance['active'] ? 'bg-red-500/20 text-red-700 border border-red-500/30' : 'bg-emerald-500/20 text-emerald-700 border border-emerald-500/30' }}">
                    <span class="w-2.5 h-2.5 rounded-full {{ $maintenance['active'] ? 'bg-red-500 animate-ping' : 'bg-emerald-500' }}"></span>
                    <span class="w-2.5 h-2.5 rounded-full {{ $maintenance['active'] ? 'bg-red-500 -ml-4.5' : 'bg-emerald-500 -ml-4.5' }}"></span>
                    <span>{{ $maintenance['active'] ? 'MODE MAINTENANCE AKTIF (WEBSITE NON-AKTIF)' : 'WEBSITE AKTIF & ONLINE NORMAL' }}</span>
                </div>

                <h2 class="text-2xl sm:text-3xl font-black text-stone-900 tracking-tight">
                    {{ $maintenance['active'] ? 'Sistem Terkunci untuk Publik' : 'Sistem Berjalan Normal' }}
                </h2>

                <p class="text-stone-600 text-sm sm:text-base max-w-xl leading-relaxed">
                    {{ $maintenance['active'] 
                        ? 'Pengunjung publik dan akun staf cabang diarahkan ke halaman 503 Maintenance. Hanya Super Admin yang memiliki akses ke dashboard dan sistem.' 
                        : 'Semua pengunjung dapat mengakses katalog menu, meja QR scan, dan seluruh staf cabang dapat memproses pesanan seperti biasa.' }}
                </p>

                <div class="flex flex-wrap items-center gap-4 text-xs font-semibold text-stone-500 pt-1">
                    <span>Terakhir diubah: <strong class="text-stone-700">{{ $maintenance['updated_at'] ? \Carbon\Carbon::parse($maintenance['updated_at'])->translatedFormat('d M Y, H:i') : '-' }}</strong></span>
                    <span>&bull;</span>
                    <span>Oleh: <strong class="text-stone-700">{{ $maintenance['updated_by'] ?? 'Sistem' }}</strong></span>
                </div>
            </div>

            <!-- Quick Action Toggle Button -->
            <div class="flex flex-col sm:flex-row md:flex-col gap-3 shrink-0">
                <form id="quick-toggle-form" action="{{ route('superadmin.maintenance.toggle') }}" method="POST">
                    @csrf
                    <button type="button" onclick="confirmQuickToggle({{ $maintenance['active'] ? 'false' : 'true' }})" 
                        class="w-full inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-2xl font-black text-sm text-white shadow-lg transition-all active:scale-95 cursor-pointer
                        {{ $maintenance['active'] ? 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-700/20' : 'bg-red-600 hover:bg-red-700 shadow-red-700/20' }}">
                        @if($maintenance['active'])
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Kembalikan Website Online</span>
                        @else
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <span>Aktifkan Mode Maintenance</span>
                        @endif
                    </button>
                </form>

                <a href="{{ route('superadmin.maintenance.preview') }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-white border border-stone-300 hover:border-stone-400 text-stone-700 font-extrabold text-sm shadow-sm transition-all hover:bg-stone-50">
                    <svg class="w-4 h-4 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span>Pratinjau Halaman 503</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Impact & Metrics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Total Cabang Sistem</p>
                <h3 class="text-2xl font-black text-stone-800">{{ $totalBranches }} <span class="text-sm font-semibold text-stone-400">Cabang Terdaftar</span></h3>
            </div>
            <div class="p-3 bg-stone-100 text-stone-600 rounded-2xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
        </div>

        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Pesanan Aktif Sedang Diproses</p>
                <h3 class="text-2xl font-black text-amber-600">{{ $activeOrders }} <span class="text-sm font-semibold text-stone-400">Pesanan</span></h3>
            </div>
            <div class="p-3 bg-amber-50 text-amber-600 rounded-2xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Detailed Configuration Form -->
    <div class="bg-white border border-stone-200 rounded-3xl p-8 shadow-sm">
        <div class="border-b border-stone-200 pb-5 mb-6">
            <h3 class="text-lg font-black text-[#8C0000]">Konfigurasi Pesan & Waktu Pemeliharaan</h3>
            <p class="text-stone-500 text-xs mt-1">Sesuaikan informasi yang akan dibaca oleh pengunjung ketika mengakses website dalam mode pemeliharaan.</p>
        </div>

        <form action="{{ route('superadmin.maintenance.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Toggle Switch in Form -->
            <div class="flex items-center justify-between p-4 rounded-2xl bg-stone-50 border border-stone-200">
                <div>
                    <label for="maintenance_mode_switch" class="text-sm font-bold text-stone-800 block cursor-pointer">
                        Status Mode Pemeliharaan
                    </label>
                    <span class="text-xs text-stone-500">Nyalakan untuk menutup akses umum, matikan untuk membuka kembali website.</span>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="maintenance_mode" id="maintenance_mode_switch" value="1" class="sr-only peer" {{ $maintenance['active'] ? 'checked' : '' }}>
                    <div class="w-14 h-8 bg-stone-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:border-stone-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#BD2000]"></div>
                </label>
            </div>

            <!-- Title Input -->
            <div>
                <label class="block text-xs font-black uppercase text-stone-600 mb-2">Judul Pemeliharaan (H1 Pengunjung)</label>
                <input type="text" name="maintenance_title" value="{{ old('maintenance_title', $maintenance['title']) }}" 
                    class="w-full px-4 py-3 rounded-2xl bg-stone-50 border border-stone-200 focus:border-[#BD2000] focus:bg-white focus:outline-none text-sm font-semibold text-stone-800 transition-all"
                    placeholder="Contoh: SajiHub Sedang Dalam Pemeliharaan">
                @error('maintenance_title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Message Textarea -->
            <div>
                <label class="block text-xs font-black uppercase text-stone-600 mb-2">Pesan Penjelasan untuk Pengunjung</label>
                <textarea name="maintenance_message" rows="4" 
                    class="w-full px-4 py-3 rounded-2xl bg-stone-50 border border-stone-200 focus:border-[#BD2000] focus:bg-white focus:outline-none text-sm font-semibold text-stone-800 transition-all leading-relaxed"
                    placeholder="Tuliskan keterangan detail pemeliharaan...">{{ old('maintenance_message', $maintenance['message']) }}</textarea>
                @error('maintenance_message')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- End Time (Optional Countdown) -->
            <div>
                <label class="block text-xs font-black uppercase text-stone-600 mb-2">
                    Estimasi Selesai (Opsional untuk Countdown Timer)
                </label>
                <input type="datetime-local" name="maintenance_end_time" 
                    value="{{ old('maintenance_end_time', $maintenance['end_time'] ? \Carbon\Carbon::parse($maintenance['end_time'])->format('Y-m-d\TH:i') : '') }}" 
                    class="w-full sm:w-80 px-4 py-3 rounded-2xl bg-stone-50 border border-stone-200 focus:border-[#BD2000] focus:bg-white focus:outline-none text-sm font-semibold text-stone-800 transition-all">
                <p class="text-stone-400 text-xs mt-1.5">Kosongkan jika waktu selesai belum dapat dipastikan.</p>
                @error('maintenance_end_time')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-stone-200 flex items-center justify-end gap-3">
                <button type="submit" class="inline-flex items-center gap-2 bg-[#BD2000] hover:bg-[#8C0000] text-white px-7 py-3 rounded-2xl font-black text-sm shadow-md transition-all active:scale-95 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Simpan Pengaturan</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Secret Bypass Key Box (Emergency Access) -->
    <div class="bg-white border border-stone-200 rounded-3xl p-8 shadow-sm">
        <div class="flex items-center gap-3 mb-3">
            <div class="p-2.5 bg-amber-50 text-amber-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-black text-stone-800">Tautan Akses Rahasia (Secret Bypass URL)</h3>
                <p class="text-xs text-stone-500">Gunakan tautan ini jika Anda ingin login dari perangkat atau browser baru saat mode maintenance aktif.</p>
            </div>
        </div>

        @php
            $bypassUrl = url('/maintenance/bypass?token=' . $maintenance['secret']);
        @endphp

        <div class="mt-4 p-4 rounded-2xl bg-stone-50 border border-stone-200 flex flex-col sm:flex-row items-center justify-between gap-3">
            <code class="text-xs font-mono font-bold text-stone-700 break-all select-all">{{ $bypassUrl }}</code>
            <div class="flex items-center gap-2 shrink-0">
                <button type="button" onclick="copyBypassUrl('{{ $bypassUrl }}')" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-stone-300 hover:border-stone-400 text-stone-700 text-xs font-bold transition-all shadow-sm cursor-pointer">
                    <svg class="w-4 h-4 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                    <span id="copy-btn-text">Salin URL</span>
                </button>
                <form action="{{ route('superadmin.maintenance.regenerate-secret') }}" method="POST">
                    @csrf
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin memperbarui token bypass? Token lama tidak akan berlaku lagi.')" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-stone-200 hover:bg-stone-300 text-stone-700 text-xs font-bold transition-all cursor-pointer">
                        <svg class="w-4 h-4 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        <span>Regenerasi</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    function confirmQuickToggle(willActivate) {
        const title = willActivate 
            ? 'Aktifkan Mode Maintenance?' 
            : 'Nonaktifkan Mode Maintenance?';
        const text = willActivate 
            ? 'Pengunjung publik dan staf cabang tidak akan bisa mengakses website sampai Anda mematikannya kembali.' 
            : 'Website akan kembali ONLINE dan dapat diakses publik seketika.';
        const confirmText = willActivate ? 'Ya, Aktifkan!' : 'Ya, Kembalikan Online!';
        const confirmColor = willActivate ? '#BD2000' : '#059669';

        Swal.fire({
            title: title,
            text: text,
            icon: willActivate ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: confirmColor,
            cancelButtonColor: '#6B7280',
            confirmButtonText: confirmText,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('quick-toggle-form').submit();
            }
        });
    }

    function copyBypassUrl(url) {
        navigator.clipboard.writeText(url).then(() => {
            const btnText = document.getElementById('copy-btn-text');
            btnText.textContent = 'Tersalin!';
            setTimeout(() => {
                btnText.textContent = 'Salin URL';
            }, 2000);
        });
    }
</script>
@endsection
