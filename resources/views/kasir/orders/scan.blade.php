@extends('layouts.app')
@section('title', 'Scan QR Konfirmasi Pesanan')
@section('page-title', 'Scan QR Konfirmasi Customer')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-[#BD2000] to-[#8C0000] rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col sm:flex-row items-center justify-between gap-6 relative overflow-hidden">
        <div class="space-y-2 z-10 text-center sm:text-left">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-white/20 text-white backdrop-blur-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Scanner Aktif
            </span>
            <h2 class="text-2xl font-black tracking-tight">Scan Tiket QR Pelanggan</h2>
            <p class="text-xs sm:text-sm text-stone-200 max-w-md font-medium">
                Arahkan kamera ke QR Code di layar HP pelanggan (pesanan makan di tempat) untuk membaca detail menu dan memproses pembayaran.
            </p>
        </div>
        <div class="z-10 shrink-0">
            <a href="{{ route('kasir.orders.index') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Daftar Pesanan</span>
            </a>
        </div>
        <!-- Background decoration -->
        <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-white/5 rounded-full pointer-events-none"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Camera Scanner (2 cols) -->
        <div class="lg:col-span-2 bg-white border border-stone-200 rounded-3xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-stone-200">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-[#BD2000]/10 text-[#BD2000] flex items-center justify-center font-bold text-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-sm font-black text-[#1C1917]">Kamera Scanner Realtime</h3>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="startScanner()" id="start-btn" class="px-3.5 py-1.5 rounded-xl bg-[#BD2000] hover:bg-[#8C0000] text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                        <span>Mulai Kamera</span>
                    </button>
                    <button type="button" onclick="stopScanner()" id="stop-btn" class="hidden px-3.5 py-1.5 rounded-xl bg-stone-200 hover:bg-stone-300 text-stone-700 text-xs font-bold transition-all flex items-center gap-1 cursor-pointer">
                        <span>Matikan</span>
                    </button>
                </div>
            </div>

            <!-- Viewfinder Area -->
            <div class="relative bg-stone-900 rounded-2xl overflow-hidden min-h-[300px] flex items-center justify-center">
                <div id="reader" class="w-full"></div>
                <div id="scanner-placeholder" class="text-center p-6 space-y-3 text-stone-400">
                    <svg class="w-16 h-16 mx-auto text-stone-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <p class="text-xs font-semibold">Klik "Mulai Kamera" di atas untuk mengaktifkan pemindaian QR Code dari HP pelanggan.</p>
                </div>
            </div>

            <!-- Scanner Status Info -->
            <div class="flex items-center justify-between text-xs text-stone-500 pt-2 font-medium">
                <span>Tips: Arahkan layar HP customer tepat ke dalam kotak bidik scanner.</span>
            </div>
        </div>

        <!-- Right: Manual Input & Quick Guide (1 col) -->
        <div class="space-y-6">
            <!-- Manual Code Input Form -->
            <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-stone-200">
                    <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-sm font-black text-[#1C1917]">Input Manual Kode</h3>
                </div>

                <p class="text-xs text-stone-600 font-medium leading-relaxed">
                    Gunakan jika kamera scanner tidak dapat membaca layar pelanggan:
                </p>

                <form onsubmit="handleManualSearch(event)" class="space-y-3">
                    <div>
                        <label class="block text-[11px] font-bold text-stone-700 uppercase tracking-wider mb-1.5">Kode Pesanan / No. Nota</label>
                        <input type="text" id="manual_code" placeholder="Contoh: SAJI-ORD-12 atau 12" required
                               class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-3.5 py-2.5 text-xs focus:border-[#BD2000] focus:outline-none uppercase">
                    </div>

                    <button type="submit" id="manual-btn"
                            class="w-full py-2.5 px-4 bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold text-xs rounded-xl transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>Cari Pesanan</span>
                    </button>
                </form>
            </div>

            <!-- Workflow Guide Card -->
            <div class="bg-stone-50 border border-stone-200 rounded-3xl p-5 space-y-3">
                <h4 class="text-xs font-black text-[#8C0000] uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#8C0000]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    <span>Panduan Alur Kasir</span>
                </h4>
                <ol class="text-xs text-stone-600 space-y-2 list-decimal list-inside font-medium leading-relaxed">
                    <li>Scan tiket QR pelanggan yang memesan di meja.</li>
                    <li>Rincian pesanan dan total tagihan akan langsung muncul.</li>
                    <li><strong>Jika Tunai:</strong> Terima uang cash, hitung kembalian, konfirmasi lunas.</li>
                    <li><strong>Jika QRIS:</strong> Tunjukkan kode QRIS SajiHub, minta bukti transfer, foto/upload bukti, lalu konfirmasi lunas.</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Modal Verifikasi & Pembayaran Pesanan -->
<div id="verify-modal" class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white border border-stone-200 w-full max-w-2xl rounded-3xl overflow-hidden shadow-2xl transition-all my-8 animate-fade-in">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-stone-200 flex justify-between items-center bg-stone-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#BD2000]/10 text-[#BD2000] flex items-center justify-center font-bold text-lg">
                    🧾
                </div>
                <div>
                    <h3 class="text-base font-black text-[#8C0000] leading-tight">Konfirmasi Pembayaran Pesanan</h3>
                    <p class="text-xs text-stone-500 font-medium">No. Nota: <span class="font-mono font-black text-stone-900" id="modal-order-code">-</span></p>
                </div>
            </div>
            <button onclick="closeVerifyModal()" class="p-2 text-stone-400 hover:text-stone-700 hover:bg-stone-100 rounded-xl transition-colors cursor-pointer">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-5 max-h-[calc(100vh-14rem)] overflow-y-auto scrollbar-thin">
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
                    <span class="block text-[10px] text-stone-400 font-bold uppercase tracking-wider">Pilihan Bayar</span>
                    <span class="text-xs font-black uppercase text-stone-900" id="modal-pay-choice">-</span>
                </div>
                <div class="p-3 bg-stone-50 rounded-2xl border border-stone-200 text-center">
                    <span class="block text-[10px] text-stone-400 font-bold uppercase tracking-wider">Status</span>
                    <span class="text-xs font-black uppercase text-amber-700" id="modal-pay-status">-</span>
                </div>
            </div>

            <!-- Items List -->
            <div class="space-y-2">
                <h4 class="text-xs font-bold text-stone-600 uppercase tracking-wider">Daftar Menu yang Dipesan</h4>
                <div class="bg-stone-50 rounded-2xl p-4 border border-stone-200 divide-y divide-stone-200 max-h-40 overflow-y-auto scrollbar-thin" id="modal-items-list">
                    <!-- Dynamic Items -->
                </div>
            </div>

            <!-- Total Price Highlight -->
            <div class="p-4 rounded-2xl bg-[#BD2000]/5 border border-[#BD2000]/20 flex items-center justify-between">
                <div>
                    <span class="text-xs text-stone-500 font-bold uppercase tracking-wider">Total Tagihan:</span>
                    <p class="text-xs text-stone-600 font-medium">Harus dibayarkan oleh customer</p>
                </div>
                <span class="text-2xl font-black text-[#BD2000] font-mono" id="modal-total-amount">Rp 0</span>
            </div>

            <!-- Payment Action Tabs: Tunai vs QRIS -->
            <div class="space-y-3 pt-2 border-t border-stone-200">
                <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider">Pilih Metode Pelunasan di Kasir:</label>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="switchModalPaymentMethod('cash')" id="tab-cash"
                            class="py-2.5 px-3 rounded-xl border text-xs font-bold text-center transition-all bg-[#BD2000] text-white border-[#BD2000] cursor-pointer flex items-center justify-center gap-1.5 shadow-xs">
                        <span>💵 Bayar Tunai (Cash)</span>
                    </button>
                    <button type="button" onclick="switchModalPaymentMethod('qris')" id="tab-qris"
                            class="py-2.5 px-3 rounded-xl border border-stone-300 text-xs font-bold text-center text-stone-600 hover:text-[#BD2000] transition-all cursor-pointer flex items-center justify-center gap-1.5">
                        <span>📲 Bayar QRIS (Foto Bukti)</span>
                    </button>
                </div>

                <!-- CASH CONTAINER -->
                <div id="cash-container" class="space-y-3 pt-2">
                    <div>
                        <label class="block text-[11px] text-stone-700 font-bold uppercase mb-1">Nominal Diterima (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" id="modal_cash_paid" oninput="calculateModalChange()" placeholder="0" min="0"
                               class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] rounded-xl px-4 py-2.5 text-base font-black font-mono focus:border-[#BD2000] focus:outline-none">
                    </div>

                    <!-- Quick buttons -->
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="setExactModalCash()" class="py-1.5 px-3 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold rounded-xl transition-colors cursor-pointer">Uang Pas</button>
                        <button type="button" onclick="addModalCash(50000)" class="py-1.5 px-3 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold rounded-xl transition-colors cursor-pointer">+50.000</button>
                        <button type="button" onclick="addModalCash(100000)" class="py-1.5 px-3 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold rounded-xl transition-colors cursor-pointer">+100.000</button>
                        <button type="button" onclick="resetModalCash()" class="py-1.5 px-3 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 text-xs font-bold rounded-xl transition-colors cursor-pointer">Reset</button>
                    </div>

                    <!-- Change Display -->
                    <div id="change-box" class="p-3.5 rounded-xl border bg-stone-50 border-stone-200 flex items-center justify-between">
                        <span class="text-xs text-stone-600 font-bold uppercase">Kembalian:</span>
                        <span class="text-lg font-black font-mono text-emerald-600" id="modal-cash-change">Rp 0</span>
                    </div>

                    <button type="button" onclick="submitModalCashPayment()" id="btn-submit-cash"
                            class="w-full py-3.5 bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold text-sm rounded-xl transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>Konfirmasi & Terima Pembayaran Tunai</span>
                    </button>
                </div>

                <!-- QRIS CONTAINER -->
                <div id="qris-container" class="hidden space-y-4 pt-2">
                    <div class="bg-stone-50 border border-stone-200 rounded-2xl p-4 text-center space-y-2">
                        <p class="text-xs text-stone-600 font-bold">Tunjukkan QRIS Resmi ke Customer:</p>
                        <div class="bg-white p-2.5 rounded-xl inline-block shadow-md mx-auto border border-stone-200 max-w-[180px]">
                            <img src="{{ asset('images/qris.jpg') }}" alt="QRIS Resmi Warung Akid" class="w-full h-auto rounded-lg object-contain">
                            <p class="text-[8px] font-black text-stone-800 tracking-tighter mt-1 uppercase">warung akid • ID1026528881513</p>
                        </div>
                    </div>

                    <!-- Foto / Upload Bukti Transfer Manual -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider">
                            Foto Bukti Transfer Customer <span class="text-red-500">*</span>
                        </label>
                        <input type="file" id="modal_qris_proof" accept="image/*" capture="environment" onchange="previewProofImage(event)" class="hidden">
                        
                        <div class="flex gap-2 items-center">
                            <button type="button" onclick="document.getElementById('modal_qris_proof').click()"
                                    class="py-2.5 px-4 bg-stone-100 hover:bg-stone-200 border border-stone-300 text-stone-800 font-bold text-xs rounded-xl transition-all flex items-center gap-2 cursor-pointer">
                                <svg class="w-4 h-4 text-stone-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Ambil Foto / Upload Bukti Transfer</span>
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
                        <span>Konfirmasi Pembayaran QRIS Lunas</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    let html5QrcodeScanner = null;
    let isScannerRunning = false;
    let currentOrder = null;
    let selectedModalMethod = 'cash';

    function formatRupiah(num) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(num || 0);
    }

    function startScanner() {
        const placeholder = document.getElementById('scanner-placeholder');
        const startBtn = document.getElementById('start-btn');
        const stopBtn = document.getElementById('stop-btn');

        if (placeholder) placeholder.classList.add('hidden');
        if (startBtn) startBtn.classList.add('hidden');
        if (stopBtn) stopBtn.classList.remove('hidden');

        html5QrcodeScanner = new Html5Qrcode("reader");

        Html5Qrcode.getCameras().then(cameras => {
            if (cameras && cameras.length) {
                // Prefer back camera if available
                let cameraId = cameras[0].id;
                for (let cam of cameras) {
                    if (cam.label.toLowerCase().includes('back') || cam.label.toLowerCase().includes('belakang')) {
                        cameraId = cam.id;
                        break;
                    }
                }

                html5QrcodeScanner.start(
                    cameraId,
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 }
                    },
                    (decodedText, decodedResult) => {
                        // Successfully scanned
                        onScanSuccess(decodedText);
                    },
                    (errorMessage) => {
                        // ignore parse errors
                    }
                ).then(() => {
                    isScannerRunning = true;
                }).catch(err => {
                    alert('Gagal membuka kamera: ' + err);
                    stopScanner();
                });
            } else {
                alert('Tidak ada kamera yang terdeteksi pada perangkat ini.');
                stopScanner();
            }
        }).catch(err => {
            alert('Izin kamera ditolak atau tidak didukung: ' + err);
            stopScanner();
        });
    }

    function stopScanner() {
        const placeholder = document.getElementById('scanner-placeholder');
        const startBtn = document.getElementById('start-btn');
        const stopBtn = document.getElementById('stop-btn');

        if (html5QrcodeScanner && isScannerRunning) {
            html5QrcodeScanner.stop().then(() => {
                html5QrcodeScanner.clear();
                isScannerRunning = false;
            }).catch(e => console.log(e));
        }

        if (placeholder) placeholder.classList.remove('hidden');
        if (startBtn) startBtn.classList.remove('hidden');
        if (stopBtn) stopBtn.classList.add('hidden');
    }

    function onScanSuccess(code) {
        // Play subtle beep sound or alert
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
            btnManual.innerText = '🔍 Mencari...';
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
                alert(data.message || 'Pesanan tidak ditemukan.');
            }
        } catch (err) {
            alert('Terjadi kesalahan jaringan saat mencari pesanan.');
        } finally {
            if (btnManual) {
                btnManual.disabled = false;
                btnManual.innerHTML = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg> <span>Cari Pesanan</span>';
            }
        }
    }

    function openVerifyModal(order) {
        document.getElementById('modal-order-code').innerText = '#ORD-' + order.id;
        document.getElementById('modal-customer-name').innerText = order.customer_name;
        document.getElementById('modal-table-number').innerText = order.table_number;
        document.getElementById('modal-pay-choice').innerText = (order.payment_method === 'qris' ? 'QRIS' : 'Tunai');
        document.getElementById('modal-pay-status').innerText = (order.payment_status === 'paid' ? 'LUNAS' : 'BELUM BAYAR');
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

        // Set default method according to order
        switchModalPaymentMethod(order.payment_method === 'qris' ? 'qris' : 'cash');
        setExactModalCash();

        // Reset proof input
        document.getElementById('modal_qris_proof').value = '';
        document.getElementById('proof-filename').innerText = 'Belum ada foto';
        document.getElementById('proof-preview-wrapper').classList.add('hidden');

        document.getElementById('verify-modal').classList.remove('hidden');
    }

    function closeVerifyModal() {
        document.getElementById('verify-modal').classList.add('hidden');
        currentOrder = null;
    }

    function switchModalPaymentMethod(method) {
        selectedModalMethod = method;
        const tabCash = document.getElementById('tab-cash');
        const tabQris = document.getElementById('tab-qris');
        const cashCont = document.getElementById('cash-container');
        const qrisCont = document.getElementById('qris-container');

        if (method === 'cash') {
            tabCash.className = "py-2.5 px-3 rounded-xl border text-xs font-bold text-center transition-all bg-[#BD2000] text-white border-[#BD2000] cursor-pointer flex items-center justify-center gap-1.5 shadow-xs";
            tabQris.className = "py-2.5 px-3 rounded-xl border border-stone-300 text-xs font-bold text-center text-stone-600 hover:text-[#BD2000] transition-all cursor-pointer flex items-center justify-center gap-1.5";
            cashCont.classList.remove('hidden');
            qrisCont.classList.add('hidden');
        } else {
            tabQris.className = "py-2.5 px-3 rounded-xl border text-xs font-bold text-center transition-all bg-[#BD2000] text-white border-[#BD2000] cursor-pointer flex items-center justify-center gap-1.5 shadow-xs";
            tabCash.className = "py-2.5 px-3 rounded-xl border border-stone-300 text-xs font-bold text-center text-stone-600 hover:text-[#BD2000] transition-all cursor-pointer flex items-center justify-center gap-1.5";
            qrisCont.classList.remove('hidden');
            cashCont.classList.add('hidden');
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
            alert('Nominal uang tunai kurang dari total tagihan!');
            return;
        }

        const btn = document.getElementById('btn-submit-cash');
        btn.disabled = true;
        btn.innerText = '⏳ Memproses Pembayaran...';

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
                    title: 'Pembayaran Lunas!',
                    text: data.message,
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
            alert('Terjadi kesalahan jaringan.');
        } finally {
            btn.disabled = false;
            btn.innerText = 'Konfirmasi & Terima Pembayaran Tunai';
        }
    }

    async function submitModalQrisPayment() {
        if (!currentOrder) return;
        const fileInput = document.getElementById('modal_qris_proof');
        if (!fileInput.files || fileInput.files.length === 0) {
            alert('Silakan ambil foto atau upload bukti transfer QRIS terlebih dahulu!');
            return;
        }

        const btn = document.getElementById('btn-submit-qris');
        btn.disabled = true;
        btn.innerText = '⏳ Mengunggah Bukti & Konfirmasi...';

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
                    title: 'Pembayaran QRIS Dikonfirmasi!',
                    text: data.message,
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
            alert('Terjadi kesalahan jaringan saat mengunggah bukti.');
        } finally {
            btn.disabled = false;
            btn.innerText = 'Konfirmasi Pembayaran QRIS Lunas';
        }
    }
</script>
@endpush
