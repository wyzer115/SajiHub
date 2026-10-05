@extends('layouts.app')
@section('title', 'Pindai QR Konfirmasi Pesanan')
@section('page-title', 'Pindai QR Pelanggan')

@section('breadcrumb')
    <a href="{{ route('kasir.orders.index') }}" class="hover:text-[#BD2000] transition-colors">Kasir</a>
    <svg class="w-3 h-3 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="text-stone-700 font-bold">Pindai QR</span>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-4 sm:space-y-6 animate-fade-in-up overflow-x-hidden">
    <!-- 1. Banner Header Atas -->
    <div class="bg-gradient-to-r from-[#BD2000] via-[#A01600] to-[#8C0000] rounded-2xl sm:rounded-3xl p-5 sm:p-8 text-white shadow-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-6 relative overflow-hidden">
        <div class="space-y-1.5 sm:space-y-2 z-10 max-w-xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] sm:text-xs font-bold uppercase tracking-wider bg-white/15 text-white backdrop-blur-sm border border-white/20">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                </span>
                <span>Kamera Siap</span>
            </div>
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-black tracking-tight leading-tight">Pindai QR Pelanggan</h2>
            <p class="text-xs sm:text-sm text-stone-100 font-medium leading-relaxed">
                Arahkan kamera ke kode QR pelanggan untuk memeriksa rincian pesanan dan menyelesaikan pembayaran.
            </p>
        </div>
        <div class="z-10 w-full sm:w-auto shrink-0 flex items-center">
            <a href="{{ route('kasir.orders.index') }}" class="w-full sm:w-auto justify-center px-4 py-3 sm:py-2.5 rounded-xl bg-white/15 hover:bg-white/25 active:scale-95 text-white text-xs sm:text-sm font-bold border border-white/25 transition-all flex items-center gap-2 shadow-xs min-h-[44px] sm:min-h-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Lihat Daftar Pesanan</span>
            </a>
        </div>
        <!-- Decorative subtle background glow -->
        <div class="absolute -right-12 -bottom-12 w-56 h-56 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-12 w-40 h-40 bg-black/10 rounded-full blur-xl pointer-events-none"></div>
    </div>

    <!-- 2. Area Utama: 2 Kartu Berdampingan yang Sejajar -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 sm:gap-6 items-stretch">
        <!-- Kartu Kiri: Kamera Scanner -->
        <div class="bg-white border border-stone-200 rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm h-full flex flex-col justify-between space-y-4 sm:space-y-5">
            <!-- Header Kartu Kiri -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 sm:pb-4 border-b border-stone-200">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-[#BD2000]/10 text-[#BD2000] flex items-center justify-center font-bold text-sm shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-[#1C1917]">Kamera Scanner</h3>
                        <p class="text-[11px] text-stone-500 font-medium">Pemindaian kode QR pelanggan secara langsung</p>
                    </div>
                </div>

                <!-- Controls & Action Button -->
                <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                    <select id="camera-select" onchange="changeSelectedCamera(this.value)" class="hidden text-xs bg-stone-100 hover:bg-stone-200 border border-stone-300 rounded-xl px-2.5 py-1.5 font-bold text-stone-700 focus:outline-none cursor-pointer">
                    </select>

                    <button type="button" id="torch-btn" onclick="toggleTorch()" class="hidden px-2.5 py-2 sm:py-1.5 rounded-xl bg-stone-100 hover:bg-amber-100 text-stone-700 hover:text-amber-800 text-xs font-bold transition-all border border-stone-200 flex items-center gap-1 cursor-pointer" title="Nyalakan Lampu Senter">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span>Senter</span>
                    </button>

                    <button type="button" id="zoom-btn" onclick="cycleZoom()" class="hidden px-2.5 py-2 sm:py-1.5 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold transition-all border border-stone-200 flex items-center gap-1 cursor-pointer" title="Zoom Kamera">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/>
                        </svg>
                        <span id="zoom-text">1x</span>
                    </button>

                    <button type="button" id="flip-cam-btn" onclick="toggleCameraFacing()" class="flex-1 sm:flex-none justify-center px-3 py-2.5 sm:py-1.5 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold transition-all border border-stone-200 flex items-center gap-1.5 cursor-pointer shadow-xs min-h-[44px] sm:min-h-0" title="Ganti Kamera Depan atau Belakang">
                        <svg class="w-3.5 h-3.5 text-[#BD2000]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span id="flip-cam-text">Kamera Depan</span>
                    </button>

                    <button type="button" onclick="startScanner()" id="start-btn" class="w-full sm:w-auto justify-center px-4 py-3 sm:py-1.5 rounded-xl bg-[#BD2000] hover:bg-[#8C0000] active:scale-95 text-white text-xs sm:text-sm font-bold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer min-h-[44px] sm:min-h-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                        <span>Nyalakan Kamera</span>
                    </button>

                    <button type="button" onclick="stopScanner()" id="stop-btn" class="hidden w-full sm:w-auto justify-center px-4 py-3 sm:py-1.5 rounded-xl bg-stone-200 hover:bg-stone-300 active:scale-95 text-stone-700 text-xs sm:text-sm font-bold transition-all flex items-center gap-1.5 cursor-pointer min-h-[44px] sm:min-h-0">
                        <span>Matikan Kamera</span>
                    </button>
                </div>
            </div>

            <!-- Viewfinder Area Container: Perfect Centering -->
            <div class="flex-1 flex flex-col justify-center">
                <div id="scanner-viewfinder-container" class="relative bg-stone-950 rounded-2xl overflow-hidden flex flex-col items-center justify-center text-center border border-stone-800 shadow-inner w-full min-h-[280px] sm:min-h-[380px]" style="background-color: #0c0a09;">
                    <div id="reader" style="width: 100%; min-height: 280px;"></div>

                    <!-- Laser Target Box Overlay & Reticle Guides -->
                    <div id="scanner-laser-overlay" class="hidden absolute inset-0 pointer-events-none flex items-center justify-center">
                        <div id="scanner-target-box" class="w-52 h-52 sm:w-64 sm:h-64 max-w-[75vw] max-h-[75vw] border-2 border-dashed border-emerald-400/80 rounded-2xl relative shadow-[0_0_25px_rgba(16,185,129,0.35)] transition-all duration-200">
                            <div class="absolute -top-1 -left-1 w-6 h-6 border-t-4 border-l-4 border-emerald-400 rounded-tl-lg"></div>
                            <div class="absolute -top-1 -right-1 w-6 h-6 border-t-4 border-r-4 border-emerald-400 rounded-tr-lg"></div>
                            <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-4 border-l-4 border-emerald-400 rounded-bl-lg"></div>
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 border-b-4 border-r-4 border-emerald-400 rounded-br-lg"></div>
                            <div class="scan-laser-beam"></div>
                        </div>
                    </div>

                    <!-- Idle Placeholder: Exact Center Positioned -->
                    <div id="scanner-placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-center p-4 sm:p-6 space-y-3 sm:space-y-3.5 text-stone-400 z-10">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-stone-900 border border-stone-800 text-stone-400 flex items-center justify-center mx-auto shadow-inner">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                        </div>
                        <div class="space-y-1 max-w-xs">
                            <h4 class="text-sm font-bold text-stone-200">Kamera Belum Aktif</h4>
                            <p class="text-xs text-stone-400 leading-relaxed">Tekan tombol Nyalakan Kamera untuk mulai memindai kode QR pelanggan.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan kecil di bawah kamera -->
            <div class="p-3 sm:p-3.5 bg-stone-50 border border-stone-200 rounded-xl sm:rounded-2xl flex items-center gap-2.5 sm:gap-3">
                <svg class="w-4 h-4 text-stone-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs text-stone-600 font-medium">Arahkan kode QR ke area pemindai.</span>
            </div>
        </div>

        <!-- Kartu Kanan: Input Manual Nomor Nota -->
        <div class="bg-white border border-stone-200 rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm h-full flex flex-col justify-between space-y-4 sm:space-y-5">
            <!-- Header Kartu Kanan -->
            <div class="flex items-center justify-between pb-3 sm:pb-4 border-b border-stone-200">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-sm shrink-0">
                        <svg class="w-5 h-5 text-amber-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-[#1C1917]">Input Manual Kode</h3>
                        <p class="text-[11px] text-stone-500 font-medium">Alternatif jika kode QR tidak terbaca kamera</p>
                    </div>
                </div>
                <span class="text-[11px] font-bold text-stone-500 bg-stone-100 px-2.5 py-1 rounded-lg border border-stone-200">Manual</span>
            </div>

            <!-- Form Input Manual yang mengisi ruang secara proporsional -->
            <form onsubmit="handleManualSearch(event)" class="flex-1 flex flex-col justify-between py-1 sm:py-2 space-y-5 sm:space-y-6">
                <div class="space-y-3 sm:space-y-4 my-auto">
                    <div class="space-y-1">
                        <label for="manual_code" class="block text-xs font-extrabold text-stone-700 uppercase tracking-wider">
                            Nomor Nota
                        </label>
                        <p class="text-xs text-stone-500 font-medium leading-relaxed">
                            Masukkan nomor nota jika kamera pemindai terkendala.
                        </p>
                    </div>

                    <div class="space-y-1.5 sm:space-y-2">
                        <input type="text" id="manual_code" name="manual_code" placeholder="Masukkan nomor nota..." required
                               class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-mono font-bold text-base sm:text-lg rounded-xl sm:rounded-2xl px-4 sm:px-5 py-3.5 sm:py-4 focus:border-[#BD2000] focus:bg-white focus:outline-none uppercase tracking-wide transition-all shadow-inner placeholder:normal-case placeholder:font-normal placeholder:text-stone-400 placeholder:text-xs sm:placeholder:text-sm">
                        <p class="text-xs text-stone-500 font-medium">
                            Dapat memasukkan kode nota lengkap atau nomor pesanan.
                        </p>
                    </div>
                </div>

                <!-- Tombol Submit Utama: Cari Pesanan -->
                <div>
                    <button type="submit" id="manual-btn"
                            class="w-full py-3.5 sm:py-4 px-6 bg-[#BD2000] hover:bg-[#8C0000] active:scale-[0.99] text-white font-extrabold text-sm sm:text-base rounded-xl sm:rounded-2xl transition-all shadow-md flex items-center justify-center gap-2.5 cursor-pointer min-h-[48px]">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span>Cari Pesanan</span>
                    </button>
                </div>
            </form>

            <!-- Catatan kecil di bawah kartu kanan sejajar dengan kartu kiri -->
            <div class="p-3 sm:p-3.5 bg-stone-50 border border-stone-200 rounded-xl sm:rounded-2xl flex items-center gap-2.5 sm:gap-3">
                <svg class="w-4 h-4 text-stone-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs text-stone-600 font-medium">Data pesanan akan diverifikasi secara langsung.</span>
            </div>
        </div>
    </div>
</div>

<!-- Modal Verifikasi dan Pembayaran Pesanan -->
<div id="verify-modal" class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-3 sm:p-4">
    <div class="bg-white border border-stone-200 w-full max-w-2xl rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xl transition-all my-auto sm:my-8 animate-fade-in">
        <!-- Modal Header -->
        <div class="px-4 sm:px-6 py-3.5 sm:py-4 border-b border-stone-200 flex justify-between items-center bg-stone-50">
            <div class="flex items-center gap-2.5 sm:gap-3">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#BD2000]/10 text-[#BD2000] flex items-center justify-center font-bold text-base sm:text-lg shrink-0">
                    <svg class="w-5 h-5 text-[#BD2000]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm sm:text-base font-black text-[#8C0000] leading-tight">Konfirmasi Pembayaran Pesanan</h3>
                    <p class="text-xs text-stone-500 font-medium">Nomor Nota: <span class="font-mono font-black text-stone-900" id="modal-order-code">-</span></p>
                </div>
            </div>
            <button onclick="closeVerifyModal()" class="p-2 text-stone-400 hover:text-stone-700 hover:bg-stone-100 rounded-xl transition-colors cursor-pointer" aria-label="Tutup">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-4 sm:p-6 space-y-4 sm:space-y-5 max-h-[calc(100vh-8rem)] sm:max-h-[calc(100vh-14rem)] overflow-y-auto scrollbar-thin">
            <!-- Order Meta Pill -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="p-3 bg-stone-50 rounded-2xl border border-stone-200 text-center">
                    <span class="block text-[10px] text-stone-400 font-bold uppercase tracking-wider">Pelanggan</span>
                    <span class="text-xs font-black text-stone-900" id="modal-customer-name">-</span>
                </div>
                <div class="p-3 bg-stone-50 rounded-2xl border border-stone-200 text-center">
                    <span class="block text-[10px] text-stone-400 font-bold uppercase tracking-wider">Meja</span>
                    <span class="text-xs font-black text-[#BD2000]" id="modal-table-number">-</span>
                </div>
                <div class="p-3 bg-stone-50 rounded-2xl border border-stone-200 text-center">
                    <span class="block text-[10px] text-stone-400 font-bold uppercase tracking-wider">Metode Bayar</span>
                    <span class="text-xs font-black uppercase text-stone-900" id="modal-pay-choice">-</span>
                </div>
                <div class="p-3 bg-stone-50 rounded-2xl border border-stone-200 text-center">
                    <span class="block text-[10px] text-stone-400 font-bold uppercase tracking-wider">Status</span>
                    <span class="text-xs font-black uppercase text-amber-700" id="modal-pay-status">-</span>
                </div>
            </div>

            <!-- Items List -->
            <div class="space-y-2">
                <h4 class="text-xs font-bold text-stone-600 uppercase tracking-wider">Rincian Pesanan</h4>
                <div class="bg-stone-50 rounded-2xl p-4 border border-stone-200 divide-y divide-stone-200 max-h-40 overflow-y-auto scrollbar-thin" id="modal-items-list">
                    <!-- Dynamic Items -->
                </div>
            </div>

            <!-- Total Price Highlight -->
            <div class="p-4 rounded-2xl bg-[#BD2000]/5 border border-[#BD2000]/20 flex items-center justify-between">
                <div>
                    <span class="text-xs text-stone-500 font-bold uppercase tracking-wider">Total Tagihan:</span>
                    <p class="text-xs text-stone-600 font-medium">Harus dibayarkan oleh pelanggan</p>
                </div>
                <span class="text-2xl font-black text-[#BD2000] font-mono" id="modal-total-amount">Rp 0</span>
            </div>

            <!-- Pelunasan Section -->
            <div class="space-y-3 pt-3 border-t border-stone-200">
                <div class="flex items-center justify-between pb-0.5">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0" id="modal-pay-indicator"></span>
                        <h4 class="text-xs font-bold text-stone-800 uppercase tracking-wider" id="modal-pay-section-title">
                            Pembayaran Tunai
                        </h4>
                    </div>
                    <span class="text-[11px] font-medium text-stone-500 bg-stone-100 px-2.5 py-0.5 rounded-lg border border-stone-200">
                        Pilihan Pelanggan
                    </span>
                </div>

                <!-- CASH CONTAINER -->
                <div id="cash-container" class="space-y-3 pt-2">
                    <div>
                        <label class="block text-[11px] text-stone-700 font-bold uppercase mb-1">Nominal Diterima</label>
                        <input type="number" id="modal_cash_paid" oninput="calculateModalChange()" placeholder="0" min="0"
                               class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] rounded-xl px-4 py-2.5 text-base font-black font-mono focus:border-[#BD2000] focus:outline-none">
                    </div>

                    <!-- Quick buttons -->
                    <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2">
                        <button type="button" onclick="setExactModalCash()" class="py-2 sm:py-1.5 px-3 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold rounded-xl transition-colors cursor-pointer">Uang Pas</button>
                        <button type="button" onclick="addModalCash(50000)" class="py-2 sm:py-1.5 px-3 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold rounded-xl transition-colors cursor-pointer">+50.000</button>
                        <button type="button" onclick="addModalCash(100000)" class="py-2 sm:py-1.5 px-3 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold rounded-xl transition-colors cursor-pointer">+100.000</button>
                        <button type="button" onclick="resetModalCash()" class="py-2 sm:py-1.5 px-3 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 text-xs font-bold rounded-xl transition-colors cursor-pointer">Hitung Ulang</button>
                    </div>

                    <!-- Change Display -->
                    <div id="change-box" class="p-3.5 rounded-xl border bg-stone-50 border-stone-200 flex items-center justify-between">
                        <span class="text-xs text-stone-600 font-bold uppercase">Kembalian:</span>
                        <span class="text-lg font-black font-mono text-emerald-600" id="modal-cash-change">Rp 0</span>
                    </div>

                    <button type="button" onclick="submitModalCashPayment()" id="btn-submit-cash"
                            class="w-full py-3.5 bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold text-sm rounded-xl transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>Konfirmasi Pembayaran Tunai</span>
                    </button>
                </div>

                <!-- QRIS CONTAINER -->
                <div id="qris-container" class="hidden space-y-4 pt-2">
                    <div class="bg-stone-50 border border-stone-200 rounded-2xl p-4 text-center space-y-2">
                        <p class="text-xs text-stone-600 font-bold">Tunjukkan kode QRIS ke pelanggan:</p>
                        <div class="bg-white p-2.5 rounded-xl inline-block shadow-md mx-auto border border-stone-200 max-w-[180px]">
                            <img src="{{ asset('images/qris.jpg') }}" alt="QRIS Resmi Warung Akid" class="w-full h-auto rounded-lg object-contain">
                            <p class="text-[8px] font-black text-stone-800 tracking-tighter mt-1 uppercase">warung akid • ID1026528881513</p>
                        </div>
                    </div>

                    <!-- Foto / Upload Bukti Transfer Manual -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider">
                            Foto Bukti Transfer
                        </label>
                        <input type="file" id="modal_qris_proof" accept="image/*" capture="environment" onchange="previewProofImage(event)" class="hidden">
                        
                        <div class="flex gap-2 items-center">
                            <button type="button" onclick="document.getElementById('modal_qris_proof').click()"
                                    class="py-2.5 px-4 bg-stone-100 hover:bg-stone-200 border border-stone-300 text-stone-800 font-bold text-xs rounded-xl transition-all flex items-center gap-2 cursor-pointer">
                                <svg class="w-4 h-4 text-stone-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Pilih Foto Bukti Transfer</span>
                            </button>
                            <span class="text-xs text-stone-500 font-medium" id="proof-filename">Belum ada foto</span>
                        </div>

                        <!-- Image Preview -->
                        <div id="proof-preview-wrapper" class="hidden p-2 bg-stone-50 rounded-2xl border border-stone-200 inline-block">
                            <img id="proof-preview-img" src="" alt="Bukti Transfer" class="max-h-40 rounded-xl object-contain border border-stone-200 shadow-xs">
                        </div>
                    </div>

                    <button type="button" onclick="submitModalQrisPayment()" id="btn-submit-qris"
                            class="w-full py-3.5 bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold text-sm rounded-xl transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>Konfirmasi Pembayaran QRIS</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<style>
    #scanner-viewfinder-container {
        min-height: 280px !important;
        background-color: #0c0a09 !important;
        position: relative !important;
    }
    @media (min-width: 640px) {
        #scanner-viewfinder-container {
            min-height: 380px !important;
        }
    }
    #reader {
        border: none !important;
        width: 100% !important;
        min-height: 280px !important;
        background: #0c0a09 !important;
        position: relative !important;
    }
    @media (min-width: 640px) {
        #reader {
            min-height: 380px !important;
        }
    }
    #reader video {
        border-radius: 1rem !important;
        object-fit: cover !important;
        width: 100% !important;
        height: 100% !important;
        min-height: 280px !important;
        max-height: 480px !important;
        display: block !important;
        transform: scaleX(1) !important;
        -webkit-transform: scaleX(1) !important;
    }
    @media (min-width: 640px) {
        #reader video {
            min-height: 380px !important;
        }
    }
    #reader__scan_region {
        border: none !important;
        width: 100% !important;
        min-height: 280px !important;
    }
    @media (min-width: 640px) {
        #reader__scan_region {
            min-height: 380px !important;
        }
    }
    #reader__dashboard { border: none !important; }
    #reader__dashboard_section_csr, #reader__dashboard_section_swaplink, #reader__status_span, #reader canvas {
        display: none !important;
    }
    @keyframes laserSweep {
        0% { top: 6%; opacity: 0.4; }
        50% { top: 90%; opacity: 1; }
        100% { top: 6%; opacity: 0.4; }
    }
    .scan-laser-beam {
        position: absolute;
        left: 6px;
        right: 6px;
        height: 3px;
        background: linear-gradient(90deg, transparent, #34D399, #10B981, #34D399, transparent);
        box-shadow: 0 0 14px #10B981;
        border-radius: 2px;
        animation: laserSweep 2s infinite ease-in-out;
    }
</style>
<script>
    let html5QrcodeScanner = null;
    let isScannerRunning = false;
    let isScanLocked = false;
    let currentOrder = null;
    let selectedModalMethod = 'cash';
    let availableCameras = [];
    let selectedCameraId = localStorage.getItem('sajihub_kasir_camera_id') || null;
    let isTorchActive = false;

    function formatRupiah(num) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(num || 0);
    }

    // Audio chime synth via Web Audio API (instant, no external audio file needed)
    function playSuccessBeep() {
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            const audioCtx = new AudioCtx();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(880, audioCtx.currentTime); // A5
            osc.frequency.exponentialRampToValueAtTime(1320, audioCtx.currentTime + 0.12); // E6
            gain.gain.setValueAtTime(0.3, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.18);
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start();
            osc.stop(audioCtx.currentTime + 0.18);
        } catch(e) {}
    }

    function flashTargetBox() {
        const box = document.getElementById('scanner-target-box');
        if (box) {
            box.style.borderColor = '#ffffff';
            box.style.backgroundColor = 'rgba(16, 185, 129, 0.4)';
            box.style.boxShadow = '0 0 35px rgba(255, 255, 255, 0.8)';
            setTimeout(() => {
                box.style.borderColor = '#34D399';
                box.style.backgroundColor = 'transparent';
                box.style.boxShadow = '0 0 25px rgba(16, 185, 129, 0.35)';
            }, 500);
        }
    }

    const isMobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    // On laptop/PC default to 'user' (front camera), on phone default to 'environment' (back camera)
    let currentFacingMode = localStorage.getItem('sajihub_kasir_facing_mode') || (isMobileDevice ? "environment" : "user");

    function updateFlipButtonText() {
        const textEl = document.getElementById('flip-cam-text');
        if (textEl) {
            textEl.innerText = currentFacingMode === "user" ? "Kamera Depan" : "Kamera Belakang";
        }
        const flipBtn = document.getElementById('flip-cam-btn');
        if (flipBtn) {
            flipBtn.classList.toggle('bg-amber-100', currentFacingMode === 'user');
            flipBtn.classList.toggle('border-amber-300', currentFacingMode === 'user');
        }
    }

    async function toggleCameraFacing() {
        if (availableCameras.length > 1) {
            const currentIdx = availableCameras.findIndex(c => c.id === selectedCameraId);
            const nextIdx = (currentIdx + 1) % availableCameras.length;
            selectedCameraId = availableCameras[nextIdx].id;
            const selectEl = document.getElementById('camera-select');
            if (selectEl) selectEl.value = selectedCameraId;
            localStorage.setItem('sajihub_kasir_camera_id', selectedCameraId);

            currentFacingMode = currentFacingMode === "user" ? "environment" : "user";
        } else {
            currentFacingMode = currentFacingMode === "user" ? "environment" : "user";
        }
        localStorage.setItem('sajihub_kasir_facing_mode', currentFacingMode);
        updateFlipButtonText();

        if (html5QrcodeScanner && isScannerRunning) {
            await stopScanner();
            setTimeout(() => {
                startScanner();
            }, 250);
        }
    }

    async function loadCameras() {
        try {
            const cameras = await Html5Qrcode.getCameras();
            availableCameras = cameras || [];
            const selectEl = document.getElementById('camera-select');
            if (!selectEl) return;
            selectEl.innerHTML = '';
            
            if (availableCameras.length > 1) {
                availableCameras.forEach((cam, idx) => {
                    const opt = document.createElement('option');
                    opt.value = cam.id;
                    opt.text = cam.label || ('Kamera ' + (idx + 1));
                    if (cam.id === selectedCameraId) opt.selected = true;
                    selectEl.appendChild(opt);
                });
                selectEl.classList.remove('hidden');
            } else {
                selectEl.classList.add('hidden');
            }

            const savedCamId = localStorage.getItem('sajihub_kasir_camera_id');
            if (savedCamId && availableCameras.some(c => c.id === savedCamId)) {
                selectedCameraId = savedCamId;
                selectEl.value = selectedCameraId;
            }
        } catch(e) {
            console.log('Error listing cameras:', e);
        }
    }

    async function startScanner() {
        await loadCameras();
        updateFlipButtonText();

        const placeholder = document.getElementById('scanner-placeholder');
        const laserOverlay = document.getElementById('scanner-laser-overlay');
        const startBtn = document.getElementById('start-btn');
        const stopBtn = document.getElementById('stop-btn');
        const torchBtn = document.getElementById('torch-btn');

        if (placeholder) placeholder.classList.add('hidden');
        if (laserOverlay) laserOverlay.classList.remove('hidden');
        if (startBtn) startBtn.classList.add('hidden');
        if (stopBtn) stopBtn.classList.remove('hidden');

        if (!html5QrcodeScanner) {
            html5QrcodeScanner = new Html5Qrcode("reader", {
                verbose: false
            });
        }

        const scanConfig = {
            fps: 20,
            qrbox: function(viewfinderWidth, viewfinderHeight) {
                const edge = Math.min(viewfinderWidth, viewfinderHeight);
                const boxSize = Math.max(Math.floor(edge * 0.90), 200);
                return { width: boxSize, height: boxSize };
            },
            aspectRatio: 1.0
        };

        let cameraConfig;
        if (selectedCameraId && availableCameras.some(c => c.id === selectedCameraId)) {
            cameraConfig = { deviceId: { exact: selectedCameraId } };
        } else {
            cameraConfig = { facingMode: currentFacingMode };
        }

        html5QrcodeScanner.start(
            cameraConfig,
            scanConfig,
            (decodedText, decodedResult) => {
                onScanSuccess(decodedText);
            },
            (errorMessage) => {
                // Ignore silent frame misses
            }
        ).then(() => {
            isScannerRunning = true;
            isScanLocked = false;
            const videoEl = document.querySelector('#reader video');
            if (videoEl) {
                videoEl.style.width = '100%';
                videoEl.style.height = '100%';
                videoEl.style.minHeight = window.innerWidth < 640 ? '280px' : '380px';
                videoEl.style.objectFit = 'cover';
                videoEl.style.display = 'block';
                videoEl.style.transform = 'scaleX(1)';
                videoEl.style.webkitTransform = 'scaleX(1)';
            }

            try {
                const track = html5QrcodeScanner.getRunningTrackCameraCapabilities();
                if (track && track.torchFeature().isSupported()) {
                    if (torchBtn) torchBtn.classList.remove('hidden');
                }
                if (track && track.zoomFeature && track.zoomFeature().isSupported()) {
                    hasZoom = true;
                    minZoom = track.zoomFeature().min();
                    maxZoom = track.zoomFeature().max();
                    const zoomBtn = document.getElementById('zoom-btn');
                    if (zoomBtn) zoomBtn.classList.remove('hidden');
                }
            } catch(e) {}
        }).catch(err => {
            console.warn("Primary camera start failed for", cameraConfig, err);
            // Fallback: try opposite facingMode or unconstrained
            const fallbackMode = currentFacingMode === "user" ? "environment" : "user";
            html5QrcodeScanner.start(
                { facingMode: fallbackMode },
                scanConfig,
                (decodedText) => onScanSuccess(decodedText),
                () => {}
            ).then(() => {
                currentFacingMode = fallbackMode;
                updateFlipButtonText();
                isScannerRunning = true;
                isScanLocked = false;
                const videoEl = document.querySelector('#reader video');
                if (videoEl) {
                    videoEl.style.width = '100%';
                    videoEl.style.height = '100%';
                    videoEl.style.minHeight = window.innerWidth < 640 ? '280px' : '380px';
                    videoEl.style.objectFit = 'cover';
                    videoEl.style.display = 'block';
                    videoEl.style.transform = 'scaleX(1)';
                    videoEl.style.webkitTransform = 'scaleX(1)';
                }
            }).catch(e2 => {
                // Final fallback: unconstrained any camera device
                html5QrcodeScanner.start(
                    {},
                    scanConfig,
                    (decodedText) => onScanSuccess(decodedText),
                    () => {}
                ).then(() => {
                    isScannerRunning = true;
                    isScanLocked = false;
                    const videoEl = document.querySelector('#reader video');
                    if (videoEl) {
                        videoEl.style.width = '100%';
                        videoEl.style.height = '100%';
                        videoEl.style.minHeight = window.innerWidth < 640 ? '280px' : '380px';
                        videoEl.style.objectFit = 'cover';
                        videoEl.style.display = 'block';
                        videoEl.style.transform = 'scaleX(1)';
                        videoEl.style.webkitTransform = 'scaleX(1)';
                    }
                }).catch(e3 => {
                    alert('Akses kamera belum diizinkan pada peramban ini.');
                    stopScanner();
                });
            });
        });
    }

    async function changeSelectedCamera(camId) {
        selectedCameraId = camId;
        localStorage.setItem('sajihub_kasir_camera_id', camId);
        if (isScannerRunning) {
            stopScanner();
            setTimeout(() => {
                startScanner();
            }, 300);
        }
    }

    async function toggleTorch() {
        if (!html5QrcodeScanner || !isScannerRunning) return;
        try {
            isTorchActive = !isTorchActive;
            await html5QrcodeScanner.applyVideoConstraints({
                advanced: [{ torch: isTorchActive }]
            });
            const torchBtn = document.getElementById('torch-btn');
            if (torchBtn) {
                torchBtn.classList.toggle('bg-amber-400', isTorchActive);
                torchBtn.classList.toggle('text-amber-950', isTorchActive);
            }
        } catch(e) {
            console.log('Torch not supported:', e);
        }
    }

    let currentZoom = 1.0;
    let minZoom = 1.0;
    let maxZoom = 1.0;
    let hasZoom = false;

    async function cycleZoom() {
        if (!html5QrcodeScanner || !isScannerRunning) return;
        try {
            if (currentZoom === 1.0 && maxZoom >= 1.5) {
                currentZoom = 1.5;
            } else if (currentZoom === 1.5 && maxZoom >= 2.0) {
                currentZoom = 2.0;
            } else if (currentZoom === 2.0 && maxZoom >= 3.0) {
                currentZoom = 3.0;
            } else {
                currentZoom = minZoom || 1.0;
            }
            await html5QrcodeScanner.applyVideoConstraints({
                advanced: [{ zoom: currentZoom }]
            });
            const zoomText = document.getElementById('zoom-text');
            if (zoomText) zoomText.innerText = `${currentZoom}x`;
            const zoomBtn = document.getElementById('zoom-btn');
            if (zoomBtn) {
                zoomBtn.classList.toggle('bg-amber-100', currentZoom > 1.0);
                zoomBtn.classList.toggle('text-amber-800', currentZoom > 1.0);
            }
        } catch(e) {
            console.log('Zoom not supported:', e);
        }
    }

    async function stopScanner() {
        const placeholder = document.getElementById('scanner-placeholder');
        const laserOverlay = document.getElementById('scanner-laser-overlay');
        const startBtn = document.getElementById('start-btn');
        const stopBtn = document.getElementById('stop-btn');
        const torchBtn = document.getElementById('torch-btn');
        const zoomBtn = document.getElementById('zoom-btn');

        if (html5QrcodeScanner && isScannerRunning) {
            try {
                await html5QrcodeScanner.stop();
                html5QrcodeScanner.clear();
            } catch(e) {
                console.log(e);
            }
            isScannerRunning = false;
            isScanLocked = false;
        }

        if (placeholder) placeholder.classList.remove('hidden');
        if (laserOverlay) laserOverlay.classList.add('hidden');
        if (startBtn) startBtn.classList.remove('hidden');
        if (stopBtn) stopBtn.classList.add('hidden');
        if (torchBtn) torchBtn.classList.add('hidden');
        if (zoomBtn) {
            zoomBtn.classList.add('hidden');
            currentZoom = 1.0;
            const zoomText = document.getElementById('zoom-text');
            if (zoomText) zoomText.innerText = '1x';
            zoomBtn.classList.remove('bg-amber-100', 'text-amber-800');
        }
    }

    function onScanSuccess(code) {
        if (isScanLocked) return;
        isScanLocked = true;

        playSuccessBeep();
        flashTargetBox();

        // Pause camera scanning while looking up and showing modal
        if (html5QrcodeScanner && isScannerRunning) {
            try { html5QrcodeScanner.pause(true); } catch(e) {}
        }

        lookupOrderCode(code);
    }

    function handleManualSearch(e) {
        e.preventDefault();
        const code = document.getElementById('manual_code').value;
        if (!code) return;
        lookupOrderCode(code);
    }

    async function lookupOrderCode(code) {
        const btnManual = document.getElementById('manual-btn');
        if (btnManual) {
            btnManual.disabled = true;
            btnManual.innerText = 'Mencari pesanan...';
        }

        try {
            const res = await fetch("{{ route('kasir.orders.lookup') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ code: code })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                currentOrder = data.order;
                openVerifyModal(data.order);
            } else {
                alert(data.message || 'Nomor nota tidak ditemukan. Periksa kembali kodenya.');
                // Unlock scanner so cashier can scan again
                isScanLocked = false;
                if (html5QrcodeScanner && isScannerRunning) {
                    try { html5QrcodeScanner.resume(); } catch(e) {}
                }
            }
        } catch (err) {
            alert('Terjadi kendala jaringan saat mencari pesanan.');
            isScanLocked = false;
            if (html5QrcodeScanner && isScannerRunning) {
                try { html5QrcodeScanner.resume(); } catch(e) {}
            }
        } finally {
            if (btnManual) {
                btnManual.disabled = false;
                btnManual.innerHTML = `
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>Cari Pesanan</span>
                `;
            }
        }
    }

    function openVerifyModal(order) {
        document.getElementById('modal-order-code').innerText = '#ORD-' + order.id;
        document.getElementById('modal-customer-name').innerText = order.customer_name;
        document.getElementById('modal-table-number').innerText = order.table_number;
        document.getElementById('modal-pay-choice').innerText = (order.payment_method === 'qris' ? 'QRIS' : 'Tunai');
        document.getElementById('modal-pay-status').innerText = (order.payment_status === 'paid' ? 'Lunas' : 'Menunggu');
        document.getElementById('modal-total-amount').innerText = formatRupiah(order.total_price);

        // Render items list
        const itemsList = document.getElementById('modal-items-list');
        itemsList.innerHTML = '';
        order.items.forEach(item => {
            const div = document.createElement('div');
            div.className = 'py-2 flex items-center justify-between text-xs';
            div.innerHTML = `
                <div>
                    <span class="font-bold text-stone-900">${item.name}</span>
                    <span class="text-stone-500 font-bold ml-1">x${item.quantity}</span>
                    ${item.notes ? `<p class="text-[10px] text-amber-700 italic">Catatan: ${item.notes}</p>` : ''}
                </div>
                <span class="font-mono font-bold text-stone-800">${formatRupiah(item.price * item.quantity)}</span>
            `;
            itemsList.appendChild(div);
        });

        // Kunci metode pembayaran sesuai dengan pilihan customer saat memesan
        applyOrderPaymentMethod(order.payment_method === 'qris' ? 'qris' : 'cash');
        if (order.payment_method !== 'qris') {
            setExactModalCash();
        }

        // Reset proof input
        document.getElementById('modal_qris_proof').value = '';
        document.getElementById('proof-filename').innerText = 'Belum ada foto';
        document.getElementById('proof-preview-wrapper').classList.add('hidden');

        document.getElementById('verify-modal').classList.remove('hidden');
    }

    function closeVerifyModal() {
        document.getElementById('verify-modal').classList.add('hidden');
        currentOrder = null;
        isScanLocked = false;
        if (html5QrcodeScanner && isScannerRunning) {
            try { html5QrcodeScanner.resume(); } catch(e) {}
        }
    }

    function applyOrderPaymentMethod(method) {
        selectedModalMethod = method;
        const titleEl = document.getElementById('modal-pay-section-title');
        const indicatorEl = document.getElementById('modal-pay-indicator');
        const cashCont = document.getElementById('cash-container');
        const qrisCont = document.getElementById('qris-container');

        if (method === 'cash') {
            if (titleEl) titleEl.innerText = 'Pembayaran Tunai';
            if (indicatorEl) indicatorEl.className = 'w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0';
            if (cashCont) cashCont.classList.remove('hidden');
            if (qrisCont) qrisCont.classList.add('hidden');
        } else {
            if (titleEl) titleEl.innerText = 'Pembayaran QRIS';
            if (indicatorEl) indicatorEl.className = 'w-2.5 h-2.5 rounded-full bg-[#BD2000] shrink-0';
            if (qrisCont) qrisCont.classList.remove('hidden');
            if (cashCont) cashCont.classList.add('hidden');
        }
    }

    function calculateModalChange() {
        if (!currentOrder) return;
        const total = parseFloat(currentOrder.total_price) || 0;
        const paid = parseFloat(document.getElementById('modal_cash_paid').value) || 0;
        const change = paid - total;
        const changeEl = document.getElementById('modal-cash-change');
        const btnSubmit = document.getElementById('btn-submit-cash');

        if (change >= 0) {
            changeEl.innerText = formatRupiah(change);
            changeEl.className = 'text-lg font-black font-mono text-emerald-600';
            btnSubmit.disabled = false;
        } else {
            changeEl.innerText = 'Kurang ' + formatRupiah(total - paid);
            changeEl.className = 'text-sm font-black font-mono text-red-600';
            btnSubmit.disabled = true;
        }
    }

    function setExactModalCash() {
        if (!currentOrder) return;
        document.getElementById('modal_cash_paid').value = currentOrder.total_price;
        calculateModalChange();
    }

    function addModalCash(amt) {
        const cur = parseFloat(document.getElementById('modal_cash_paid').value) || 0;
        document.getElementById('modal_cash_paid').value = cur + amt;
        calculateModalChange();
    }

    function resetModalCash() {
        document.getElementById('modal_cash_paid').value = 0;
        calculateModalChange();
    }

    function previewProofImage(e) {
        const file = e.target.files[0];
        if (file) {
            document.getElementById('proof-filename').innerText = file.name;
            const reader = new FileReader();
            reader.onload = function(evt) {
                document.getElementById('proof-preview-img').src = evt.target.result;
                document.getElementById('proof-preview-wrapper').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    async function submitModalCashPayment() {
        if (!currentOrder) return;
        const paid = parseFloat(document.getElementById('modal_cash_paid').value) || 0;
        if (paid < currentOrder.total_price) {
            alert('Nominal pembayaran tunai kurang dari total tagihan.');
            return;
        }

        const btn = document.getElementById('btn-submit-cash');
        btn.disabled = true;
        btn.innerText = 'Memproses pembayaran...';

        try {
            const formData = new FormData();
            formData.append('payment_method', 'cash');
            formData.append('cash_paid', paid);

            const res = await fetch(`/kasir/orders/${currentOrder.id}/confirm-payment`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const data = await res.json();
            if (res.ok && data.success) {
                Swal.fire({
                    title: 'Pembayaran Berhasil!',
                    text: 'Pesanan berhasil diverifikasi.',
                    icon: 'success',
                    confirmButtonColor: '#BD2000',
                    confirmButtonText: 'Cetak Struk',
                    showCancelButton: true,
                    cancelButtonText: 'Tutup'
                }).then(result => {
                    if (result.isConfirmed && data.receipt_url) {
                        window.open(data.receipt_url, '_blank');
                    }
                    closeVerifyModal();
                });
            } else {
                alert(data.message || 'Gagal mengonfirmasi pembayaran.');
            }
        } catch (err) {
            alert('Terjadi kendala jaringan saat memproses pembayaran.');
        } finally {
            btn.disabled = false;
            btn.innerText = 'Konfirmasi Pembayaran Tunai';
        }
    }

    async function submitModalQrisPayment() {
        if (!currentOrder) return;
        const fileInput = document.getElementById('modal_qris_proof');
        if (!fileInput.files || fileInput.files.length === 0) {
            alert('Pilih foto atau unggah bukti transfer QRIS terlebih dahulu.');
            return;
        }

        const btn = document.getElementById('btn-submit-qris');
        btn.disabled = true;
        btn.innerText = 'Mengunggah bukti pembayaran...';

        try {
            const formData = new FormData();
            formData.append('payment_method', 'qris');
            formData.append('payment_proof', fileInput.files[0]);

            const res = await fetch(`/kasir/orders/${currentOrder.id}/confirm-payment`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const data = await res.json();
            if (res.ok && data.success) {
                Swal.fire({
                    title: 'Pembayaran Berhasil!',
                    text: 'Pesanan berhasil diverifikasi.',
                    icon: 'success',
                    confirmButtonColor: '#BD2000',
                    confirmButtonText: 'Cetak Struk',
                    showCancelButton: true,
                    cancelButtonText: 'Tutup'
                }).then(result => {
                    if (result.isConfirmed && data.receipt_url) {
                        window.open(data.receipt_url, '_blank');
                    }
                    closeVerifyModal();
                });
            } else {
                alert(data.message || 'Gagal memproses verifikasi QRIS.');
            }
        } catch (err) {
            alert('Terjadi kendala jaringan saat mengunggah bukti.');
        } finally {
            btn.disabled = false;
            btn.innerText = 'Konfirmasi Pembayaran QRIS';
        }
    }
</script>
@endpush
