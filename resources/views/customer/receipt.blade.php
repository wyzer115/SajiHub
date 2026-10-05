<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Struk Digital #ORD-{{ $order->id }} — SajiHUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('partials.head-assets')
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #F4F1EA;
            color: #1C1917;
        }
        .mono {
            font-family: 'Space Mono', monospace;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                padding: 0 !important;
            }
            .receipt-card {
                box-shadow: none !important;
                border: none !important;
                max-width: 100% !important;
                width: 100% !important;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4 sm:p-6">

    <div class="no-print mb-6 text-center">
        <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 text-xs font-bold text-stone-600 hover:text-[#BD2000] transition-colors bg-white px-4 py-2 rounded-xl border border-stone-200 shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Beranda</span>
        </a>
    </div>

    {{-- Struk Digital Card Container --}}
    <div class="receipt-card w-full max-w-md bg-white border border-stone-200 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden space-y-6">
        
        {{-- Top Brand Banner --}}
        <div class="text-center border-b border-dashed border-stone-300 pb-5 space-y-2">
            <div class="flex justify-center mb-2">
                <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" class="h-12 w-auto object-contain">
            </div>
            <h1 class="text-lg font-black text-[#8C0000] tracking-wide uppercase">
                {{ $order->branch->name ?? 'SajiHUB Restaurant' }}
            </h1>
            <p class="text-xs text-stone-500 font-medium">
                {{ $order->branch->address ?? 'Jl. Kuliner Otentik No. 1' }}
            </p>
            <p class="text-[11px] text-stone-400 font-mono">
                Telp: {{ $order->branch->phone ?? '0812-3456-7890' }}
            </p>
        </div>

        {{-- Status Badge Header & Confirmation Ticket --}}
        @if ($order->payment_status !== 'paid')
            <div class="bg-amber-50/90 rounded-3xl p-5 border-2 border-dashed border-amber-300 text-center space-y-3.5 shadow-sm animate-fade-in">
                <div class="inline-flex items-center gap-1.5 px-3.5 py-1 bg-amber-200/70 text-amber-900 rounded-full text-xs font-black uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                    <span>Menunggu Konfirmasi Kasir</span>
                </div>
                
                <h2 class="text-base font-black text-stone-900">
                    Tiket Konfirmasi Pesanan
                </h2>
                <p class="text-xs text-stone-600 font-medium max-w-xs mx-auto leading-relaxed">
                    Tunjukkan QR Code ini ke Kasir untuk verifikasi pesanan dan menyelesaikan pembayaran 
                    <strong class="text-[#BD2000] uppercase font-black">({{ $order->payment_method === 'qris' ? 'QRIS' : 'Tunai' }})</strong>.
                </p>

                {{-- QR Code for Kasir Scanner (Crisp HD with High Error Correction & Large Modules) --}}
                <div class="inline-block p-4 bg-white rounded-3xl border-2 border-stone-200 shadow-lg cursor-pointer hover:scale-102 transition-transform" onclick="toggleQrZoom(true)" title="Klik untuk memperbesar QR">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=450x450&ecc=M&margin=4&data=SAJI-ORD-{{ $order->id }}" 
                         alt="QR Konfirmasi Order #{{ $order->id }}" 
                         class="w-64 h-64 mx-auto object-contain select-none"
                         style="image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;">
                </div>

                <div class="flex items-center justify-center gap-2">
                    <button type="button" onclick="toggleQrZoom(true)" class="text-[11px] text-[#BD2000] hover:text-[#8C0000] font-black uppercase tracking-wider flex items-center gap-1 cursor-pointer bg-white px-3 py-1.5 rounded-xl border border-stone-200 shadow-xs hover:bg-stone-50 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                        <span>Perbesar QR</span>
                    </button>
                    <span class="text-xs font-mono font-black text-[#BD2000] bg-white py-1.5 px-3 rounded-xl border border-stone-200 shadow-xs">
                        SAJI-ORD-{{ $order->id }}
                    </span>
                </div>

                <div class="flex items-center justify-center gap-1.5 text-[11px] text-amber-800 font-bold bg-amber-100/60 py-1.5 px-3 rounded-xl border border-amber-200/80">
                    <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Terangkan layar HP Anda agar mudah dibaca scanner kasir</span>
                </div>

                <div class="pt-1 text-[11px] text-amber-800 font-medium">
                    <span class="inline-block animate-pulse">Halaman ini akan otomatis kembali ke dashboard awal saat kasir mengonfirmasi.</span>
                </div>
            </div>
        @else
            <div class="bg-emerald-50 rounded-2xl p-4 border border-emerald-200 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-emerald-800 font-bold uppercase tracking-wider">Status Pembayaran</span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-black uppercase tracking-wider">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        <span>Lunas</span>
                    </span>
                </div>
                <p class="text-[11px] text-emerald-700 bg-white/70 border border-emerald-200 rounded-xl p-2.5 font-medium leading-relaxed">
                    Pembayaran berhasil dikonfirmasi. Pesanan Anda sedang disiapkan oleh staf di Dapur.
                </p>
            </div>
        @endif

        {{-- Order Metadata --}}
        <div class="text-xs space-y-2 border-b border-stone-200 pb-4 font-medium">
            <div class="flex justify-between text-stone-600">
                <span>No. Pesanan:</span>
                <span class="font-bold text-stone-900 mono">#ORD-{{ $order->id }}</span>
            </div>
            <div class="flex justify-between text-stone-600">
                <span>Nama Pemesan:</span>
                <span class="font-bold text-stone-900">{{ $order->customer_name }}</span>
            </div>
            <div class="flex justify-between text-stone-600">
                <span>Nomor Meja:</span>
                <span class="font-bold text-[#BD2000]">{{ $order->table ? $order->table->table_number : 'Bawa Pulang' }}</span>
            </div>
            <div class="flex justify-between text-stone-600">
                <span>Waktu Pesan:</span>
                <span class="font-bold text-stone-900">{{ $order->created_at->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i') }} WIB</span>
            </div>
            <div class="flex justify-between text-stone-600">
                <span>Metode Bayar:</span>
                <span class="font-bold text-stone-900">{{ $order->payment_method === 'cash' ? 'Tunai' : ($order->payment_method === 'qris' ? 'QRIS' : 'Transfer') }}</span>
            </div>
        </div>

        {{-- Item List Table --}}
        <div class="space-y-3">
            <h3 class="text-xs font-black text-stone-400 uppercase tracking-wider">Rincian Menu Pesanan</h3>
            <div class="divide-y divide-stone-100">
                @foreach ($order->items as $item)
                    <div class="py-2.5 flex items-start justify-between gap-3 text-xs">
                        <div class="space-y-0.5">
                            <span class="font-bold text-stone-800">{{ $item->menu->name ?? 'Menu' }}</span>
                            <div class="text-[11px] text-stone-500 font-mono">
                                {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                            </div>
                            @if (!empty($item->notes))
                                <div class="text-[10px] text-amber-700 italic bg-amber-50/60 px-1.5 py-0.5 rounded border border-amber-200/50 inline-block">
                                    Catatan: {{ $item->notes }}
                                </div>
                            @endif
                        </div>
                        <div class="font-bold text-stone-900 mono text-right">
                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Total Calculation --}}
        <div class="border-t-2 border-dashed border-stone-300 pt-4 space-y-2">
            <div class="flex justify-between items-center text-sm font-black">
                <span class="text-stone-700 uppercase tracking-wider">TOTAL BAYAR</span>
                <span class="text-lg text-[#BD2000] mono">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Footer QR/Barcode Visual & Thank You --}}
        <div class="text-center pt-4 border-t border-stone-100 space-y-3">
            <div class="flex justify-center">
                {{-- Stylized Barcode SVG --}}
                <svg class="h-10 w-48 text-stone-700" fill="currentColor" viewBox="0 0 200 40">
                    <rect x="0" y="0" width="4" height="40"/>
                    <rect x="6" y="0" width="2" height="40"/>
                    <rect x="12" y="0" width="6" height="40"/>
                    <rect x="22" y="0" width="2" height="40"/>
                    <rect x="28" y="0" width="4" height="40"/>
                    <rect x="36" y="0" width="8" height="40"/>
                    <rect x="48" y="0" width="2" height="40"/>
                    <rect x="54" y="0" width="4" height="40"/>
                    <rect x="62" y="0" width="6" height="40"/>
                    <rect x="72" y="0" width="2" height="40"/>
                    <rect x="78" y="0" width="8" height="40"/>
                    <rect x="90" y="0" width="4" height="40"/>
                    <rect x="98" y="0" width="2" height="40"/>
                    <rect x="104" y="0" width="6" height="40"/>
                    <rect x="114" y="0" width="4" height="40"/>
                    <rect x="122" y="0" width="8" height="40"/>
                    <rect x="134" y="0" width="2" height="40"/>
                    <rect x="140" y="0" width="4" height="40"/>
                    <rect x="148" y="0" width="6" height="40"/>
                    <rect x="158" y="0" width="2" height="40"/>
                    <rect x="164" y="0" width="8" height="40"/>
                    <rect x="176" y="0" width="4" height="40"/>
                    <rect x="184" y="0" width="2" height="40"/>
                    <rect x="190" y="0" width="6" height="40"/>
                </svg>
            </div>
            <p class="text-[11px] font-bold text-stone-500 uppercase tracking-widest">
                Terima Kasih Atas Kunjungan Anda!
            </p>
            <p class="text-[10px] text-stone-400">
                SajiHUB — Restoran & Kuliner Otentik Indonesia
            </p>
        </div>

        {{-- Action Buttons (No Print) --}}
        <div class="no-print pt-2 flex gap-3">
            <button type="button" onclick="window.print()" class="flex-1 py-3 bg-stone-900 hover:bg-black text-white font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak / Simpan Struk</span>
            </button>
        </div>

    </div>

    {{-- Fullscreen QR Zoom Lightbox Modal for Effortless Cashier Scanning --}}
    <div id="qr-zoom-modal" class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md hidden items-center justify-center p-4" onclick="toggleQrZoom(false)">
        <div class="bg-white p-6 rounded-3xl max-w-sm w-full text-center space-y-4 shadow-2xl relative" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center border-b border-stone-100 pb-2">
                <span class="text-xs font-black text-[#BD2000] uppercase tracking-wider">Tiket Konfirmasi #ORD-{{ $order->id }}</span>
                <button type="button" onclick="toggleQrZoom(false)" class="text-stone-400 hover:text-stone-800 text-lg font-bold p-1 cursor-pointer">✕</button>
            </div>
            <div class="p-3 bg-white border border-stone-200 rounded-2xl shadow-inner">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=600x600&ecc=M&margin=4&data=SAJI-ORD-{{ $order->id }}" 
                     alt="QR Konfirmasi Order #{{ $order->id }}" 
                     class="w-72 h-72 mx-auto object-contain select-none"
                     style="image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;">
            </div>
            <p class="text-xs font-bold text-stone-700">Tunjukkan QR ini ke kamera kasir</p>
            <button type="button" onclick="toggleQrZoom(false)" class="w-full py-3 bg-[#BD2000] hover:bg-[#8C0000] text-white text-xs font-bold uppercase rounded-xl transition-all shadow-md cursor-pointer">Tutup</button>
        </div>
    </div>

    <script>
        function toggleQrZoom(show) {
            const m = document.getElementById('qr-zoom-modal');
            if (m) {
                if (show) {
                    m.classList.remove('hidden');
                    m.classList.add('flex');
                } else {
                    m.classList.add('hidden');
                    m.classList.remove('flex');
                }
            }
        }
    </script>

    @if ($order->payment_status !== 'paid')
    <script>
        let isRedirecting = false;
        const dashboardUrl = "{{ route('landing') }}";

        const checkInterval = setInterval(() => {
            if (isRedirecting) return;
            fetch("{{ route('pesan.status', $order->id) }}")
                .then(res => res.json())
                .then(data => {
                    if (data && (data.payment_status === 'paid' || data.is_confirmed)) {
                        isRedirecting = true;
                        clearInterval(checkInterval);

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Pesanan Berhasil Dikonfirmasi!',
                                html: '<p class="text-xs font-bold text-stone-700">Pembayaran tiket <strong>#ORD-{{ $order->id }}</strong> telah diterima oleh kasir.</p><p class="text-[11px] text-stone-500 mt-2 font-medium">Mengarahkan kembali ke dashboard awal...</p>',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                                allowOutsideClick: false,
                                customClass: { popup: 'rounded-3xl shadow-2xl font-sans' }
                            }).then(() => {
                                window.location.href = dashboardUrl;
                            });
                        } else {
                            window.location.href = dashboardUrl;
                        }
                    }
                })
                .catch(err => console.log('Checking order status...'));
        }, 2500);
    </script>
    @endif

</body>
</html>
