@auth
@if(auth()->user()->isKasir() || auth()->user()->isSuperAdmin() || auth()->user()->isAdminCabang())
<!-- Kasir QR Ticket Scanner Modal -->
<div id="kasirQrModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6 hidden transition-all duration-200 opacity-0 pointer-events-none">
    <!-- Backdrop Blur Overlay -->
    <div id="kasirQrModalBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity duration-200"></div>

    <!-- Modal Container -->
    <div id="kasirQrModalCard" class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl border border-slate-200/80 overflow-hidden transform scale-95 transition-all duration-200 z-10">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200/80 bg-slate-50/60 flex justify-between items-center">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-red-50 text-red-600 border border-red-200/60 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 leading-tight">Scan QR Tiket Pesanan</h3>
                    <p class="text-xs font-semibold text-slate-500">Pindai QR HP Customer atau ketik No. Order</p>
                </div>
            </div>
            <button type="button" onclick="closeKasirQrScanner()" class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-all cursor-pointer">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-6 space-y-5">
            <!-- Camera Container -->
            <div class="relative bg-slate-950 rounded-xl overflow-hidden border border-slate-800 min-h-[220px] flex items-center justify-center">
                <div id="kasir-qr-reader" class="w-full h-full min-h-[220px]"></div>
                <div id="kasir-qr-placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 p-4 text-center">
                    <svg class="w-12 h-12 text-red-600 animate-pulse mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 13a3 3 0 11-6 0 3 3 0 116 0z"/>
                    </svg>
                    <p class="text-xs font-bold text-slate-300">Menyiapkan Kamera Scanner...</p>
                </div>
            </div>

            <!-- Manual Input Form -->
            <div class="pt-2 border-t border-slate-200/80">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Atau Masukkan Kode / ID Pesanan Manual</label>
                <form id="formManualOrder" onsubmit="handleManualOrderSubmit(event)" class="flex gap-2">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-bold text-sm">#</span>
                        <input type="text" id="manualOrderId" placeholder="Contoh: 12 atau ORD-12" class="w-full pl-8 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:ring-red-500/20 focus:border-red-500 outline-none">
                    </div>
                    <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all cursor-pointer active:scale-95 whitespace-nowrap">
                        Buka Pesanan &rarr;
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    #kasir-qr-reader video {
        transform: scaleX(1) !important;
        -webkit-transform: scaleX(1) !important;
        object-fit: cover !important;
    }
</style>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
let kasirQrScannerInstance = null;

function openKasirQrScanner() {
    const modal = document.getElementById('kasirQrModal');
    const modalCard = document.getElementById('kasirQrModalCard');
    if (!modal) return;

    modal.classList.remove('hidden');
    requestAnimationFrame(() => {
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modalCard.classList.remove('scale-95');
        modalCard.classList.add('scale-100');
    });

    startKasirQrScanner();
}

function closeKasirQrScanner() {
    const modal = document.getElementById('kasirQrModal');
    const modalCard = document.getElementById('kasirQrModalCard');
    if (!modal) return;

    modalCard.classList.remove('scale-100');
    modalCard.classList.add('scale-95');
    modal.classList.add('opacity-0');

    if (kasirQrScannerInstance) {
        kasirQrScannerInstance.stop().then(() => {
            kasirQrScannerInstance = null;
        }).catch(() => {
            kasirQrScannerInstance = null;
        });
    }

    setTimeout(() => {
        modal.classList.add('hidden', 'pointer-events-none');
    }, 150);
}

function startKasirQrScanner() {
    const placeholder = document.getElementById('kasir-qr-placeholder');
    if (placeholder) placeholder.style.display = 'flex';

    if (kasirQrScannerInstance) {
        return;
    }

    const scanner = new Html5Qrcode("kasir-qr-reader");
    kasirQrScannerInstance = scanner;

    scanner.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 200, height: 200 } },
        (decodedText) => {
            if (placeholder) placeholder.style.display = 'none';
            processScannedQrData(decodedText);
        },
        (errorMessage) => {
            if (placeholder) placeholder.style.display = 'none';
        }
    ).then(() => {
        const videoEl = document.querySelector('#kasir-qr-reader video');
        if (videoEl) {
            videoEl.style.transform = 'scaleX(1)';
            videoEl.style.webkitTransform = 'scaleX(1)';
        }
    }).catch(err => {
        if (placeholder) {
            placeholder.innerHTML = `<p class="text-xs font-bold text-red-400 p-2">Kamera tidak aktif atau izin ditolak. Silakan gunakan input manual ID pesanan di bawah.</p>`;
        }
    });
}

function processScannedQrData(scannedData) {
    closeKasirQrScanner();
    
    // Check if URL or raw order number
    let orderId = scannedData;
    const match = scannedData.match(/\/kasir\/orders\/(\d+)/);
    if (match && match[1]) {
        orderId = match[1];
    } else {
        orderId = scannedData.replace(/[^0-9]/g, '');
    }

    if (orderId) {
        if (typeof window.showToast === 'function') {
            window.showToast('Kode QR Tiket Terdeteksi! Membuka pesanan #' + orderId + '...', 'success');
        }
        window.location.href = "{{ url('/kasir/orders') }}/" + orderId + "?auto_pay=1";
    } else {
        alert('Format QR Code Tiket tidak valid!');
    }
}

function handleManualOrderSubmit(e) {
    e.preventDefault();
    const input = document.getElementById('manualOrderId');
    if (!input || !input.value.trim()) return;

    const rawVal = input.value.trim();
    const orderId = rawVal.replace(/[^0-9]/g, '');

    if (orderId) {
        closeKasirQrScanner();
        window.location.href = "{{ url('/kasir/orders') }}/" + orderId + "?auto_pay=1";
    } else {
        alert('Silakan masukkan nomor angka ID pesanan yang valid.');
    }
}
</script>
@endif
@endauth
