<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Struk Digital #ORD-{{ $order->id }} — SajiHUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
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
        <a href="{{ route('pesan', ['branch_id' => $order->branch_id, 'table_id' => $order->table_id]) }}" class="inline-flex items-center gap-2 text-xs font-bold text-stone-600 hover:text-[#BD2000] transition-colors bg-white px-4 py-2 rounded-xl border border-stone-200 shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Halaman Pesan</span>
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

                {{-- QR Code for Kasir Scanner --}}
                <div class="inline-block p-3.5 bg-white rounded-2xl border border-stone-200 shadow-md">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=SAJI-ORD-{{ $order->id }}" 
                         alt="QR Konfirmasi Order #{{ $order->id }}" 
                         class="w-48 h-48 mx-auto object-contain">
                </div>

                <div class="flex items-center justify-center gap-2">
                    <span class="text-[11px] text-stone-500 font-bold uppercase tracking-wider">Kode Tiket:</span>
                    <span class="text-xs font-mono font-black text-[#BD2000] bg-white py-1 px-3 rounded-xl border border-stone-200 shadow-xs">
                        SAJI-ORD-{{ $order->id }}
                    </span>
                </div>

                <div class="pt-2 text-[11px] text-amber-800 font-medium">
                    <span class="inline-block animate-pulse">⏳ Halaman ini akan otomatis ter-update saat kasir mengonfirmasi pesanan Anda.</span>
                </div>
            </div>
        @else
            <div class="bg-emerald-50 rounded-2xl p-4 border border-emerald-200 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-emerald-800 font-bold uppercase tracking-wider">Status Pembayaran</span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-black uppercase tracking-wider">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        <span>LUNAS ({{ strtoupper($order->payment_method) }})</span>
                    </span>
                </div>
                <p class="text-[11px] text-emerald-700 bg-white/70 border border-emerald-200 rounded-xl p-2.5 font-medium leading-relaxed">
                    ✨ Pembayaran berhasil dikonfirmasi! Pesanan Anda sedang disiapkan oleh koki di Dapur.
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
                <span class="font-bold text-[#BD2000]">{{ $order->table ? $order->table->table_number : 'Takeaway / Bebas' }}</span>
            </div>
            <div class="flex justify-between text-stone-600">
                <span>Waktu Pesan:</span>
                <span class="font-bold text-stone-900">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
            </div>
            <div class="flex justify-between text-stone-600">
                <span>Metode Bayar:</span>
                <span class="font-bold uppercase text-stone-900">{{ strtoupper($order->payment_method) }}</span>
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

    @if ($order->payment_status !== 'paid')
    <script>
        setInterval(() => {
            fetch("{{ route('pesan.status', $order->id) }}")
                .then(res => res.json())
                .then(data => {
                    if (data && data.payment_status === 'paid') {
                        window.location.reload();
                    }
                })
                .catch(err => console.log('Checking order status...'));
        }, 3500);
    </script>
    @endif

</body>
</html>
