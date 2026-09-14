@extends('layouts.app')
@section('title', 'Detail Pesanan ' . $order->customer_name)
@section('page-title', 'Detail Pesanan ' . $order->customer_name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
        <a href="{{ route('kasir.orders.index') }}" class="inline-flex items-center text-slate-600 hover:text-[#BD2000] font-bold transition-colors text-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Pesanan
        </a>
        
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('kasir.orders.receipt', $order) }}" target="_blank" class="bg-stone-100 border border-stone-300 hover:bg-stone-200 text-stone-700 font-bold px-4 py-2 rounded-xl transition-all flex items-center text-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Struk
            </a>

            @if($order->payment_status == 'unpaid')
                @if(($order->payment_method ?? 'cash') === 'qris')
                    <form action="{{ route('kasir.orders.pay', $order) }}" method="POST" class="inline" onsubmit="return showConfirm(event, 'Konfirmasi pembayaran QRIS pesanan {{ $order->customer_name }} sebagai LUNAS?', 'Konfirmasi QRIS', 'Ya, Tandai Lunas')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold px-5 py-2 rounded-xl transition-all shadow-md flex items-center gap-2 cursor-pointer text-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Tandai Lunas (QRIS)</span>
                        </button>
                    </form>
                @else
                    <button type="button" 
                            onclick='openCheckoutModal({!! json_encode([
                                "orderId" => $order->id,
                                "customerName" => $order->customer_name,
                                "tableNumber" => $order->table ? $order->table->table_number : "Takeaway",
                                "totalAmount" => (float)$order->total_price,
                                "paymentMethod" => "cash",
                                "items" => $order->items->map(function($item) {
                                    return [
                                        "name" => $item->menu->name ?? "Menu",
                                        "qty" => (int)$item->quantity,
                                        "price" => (float)$item->price,
                                    ];
                                })->values()->all()
                            ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!})'
                            class="bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold px-5 py-2 rounded-xl transition-all shadow-md flex items-center gap-2 cursor-pointer text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>Bayar (Tunai)</span>
                    </button>
                @endif
            @endif
        </div>
    </div>

    <!-- Main Detail Card -->
    <div class="bg-white border border-stone-200 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-6 border-b border-stone-200 bg-stone-50 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-black text-[#8C0000] mb-1">Pesanan {{ $order->customer_name }}</h3>
                <p class="text-slate-600 text-sm font-medium">{{ $order->created_at->format('d F Y, H:i') }} WIB</p>
            </div>
            <div class="flex flex-col items-end space-y-2">
                @if($order->order_status == 'pending')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Menunggu Dimasak
                    </span>
                @elseif($order->order_status == 'cooking')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-700 border border-orange-200">
                        <svg class="w-3.5 h-3.5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                        Sedang Dimasak
                    </span>
                @elseif($order->order_status == 'served')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Telah Disajikan
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Selesai
                    </span>
                @endif

                @if($order->payment_status == 'paid')
                    <span class="px-3 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-300">LUNAS</span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-black bg-red-100 text-red-600 border border-red-200">BELUM DIBAYAR</span>
                @endif
            </div>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-5 gap-6 p-6 border-b border-stone-200">
            <div>
                <p class="text-stone-500 text-xs font-bold uppercase mb-1">Pelanggan</p>
                <p class="text-[#1C1917] font-extrabold">{{ $order->customer_name ?? 'Pelanggan Resto' }}</p>
            </div>
            <div>
                <p class="text-stone-500 text-xs font-bold uppercase mb-1">Meja</p>
                <p class="text-[#1C1917] font-extrabold">{{ $order->table ? $order->table->table_number : 'Bawa Pulang' }}</p>
            </div>
            <div>
                <p class="text-stone-500 text-xs font-bold uppercase mb-1">Kasir</p>
                <p class="text-[#1C1917] font-extrabold">{{ $order->user->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-stone-500 text-xs font-bold uppercase mb-1">Metode</p>
                <p class="text-[#1C1917] font-extrabold uppercase">
                    @if(($order->payment_method ?? 'cash') == 'cash')
                        Tunai
                    @elseif($order->payment_method == 'qris')
                        QRIS
                    @else
                        Transfer
                    @endif
                </p>
            </div>
            <div>
                <p class="text-stone-500 text-xs font-bold uppercase mb-1">Total</p>
                <p class="text-[#BD2000] font-black text-lg">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            </div>
        </div>

        @if($order->transaction)
        <div class="p-6 bg-stone-50 border-b border-stone-200">
            <h4 class="text-xs font-bold text-stone-700 uppercase tracking-wider mb-3">Detail Transaksi Terdaftar</h4>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-slate-500 font-bold">Metode</span>
                    <p class="text-[#1C1917] font-black uppercase mt-0.5">{{ $order->transaction->payment_method }}</p>
                </div>
                @if($order->transaction->payment_method === 'qris')
                <div>
                    <span class="text-slate-500 font-bold">NMID Merchant</span>
                    <p class="text-[#BD2000] font-mono font-black mt-0.5">{{ $order->transaction->merchant_id ?? 'ID1026528881513' }}</p>
                </div>
                @endif
                @if($order->transaction->cash_paid > 0)
                <div>
                    <span class="text-slate-500 font-bold">Uang Diterima</span>
                    <p class="text-[#1C1917] font-mono font-black mt-0.5">Rp {{ number_format($order->transaction->cash_paid, 0, ',', '.') }}</p>
                </div>
                <div>
                    <span class="text-slate-500 font-bold">Kembalian</span>
                    <p class="text-emerald-700 font-mono font-black mt-0.5">Rp {{ number_format($order->transaction->cash_change, 0, ',', '.') }}</p>
                </div>
                @endif
                <div>
                    <span class="text-slate-500 font-bold">Waktu Bayar</span>
                    <p class="text-slate-700 font-mono font-bold mt-0.5">{{ $order->transaction->paid_at ? $order->transaction->paid_at->format('d/m/Y H:i:s') : '-' }}</p>
                </div>
            </div>
        </div>
        @endif

        <div class="p-6">
            <h4 class="text-[#8C0000] font-black text-sm uppercase tracking-wider mb-4">Daftar Item Pesanan</h4>
            <div class="overflow-x-auto rounded-2xl border border-stone-200">
                <table class="w-full text-left">
                    <thead class="bg-stone-100 text-stone-700 text-xs font-extrabold uppercase tracking-wider border-b border-stone-200">
                        <tr>
                            <th class="px-4 py-3.5">Menu</th>
                            <th class="px-4 py-3.5 text-center">Jml</th>
                            <th class="px-4 py-3.5 text-right">Harga Satuan</th>
                            <th class="px-4 py-3.5 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200">
                        @foreach($order->items as $item)
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="px-4 py-4">
                                <div class="font-extrabold text-[#1C1917]">{{ $item->menu->name ?? 'Menu Dihapus' }}</div>
                                @if($item->notes)
                                    <div class="text-xs text-[#BD2000] font-bold mt-1">Catatan: "{{ $item->notes }}"</div>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center font-black text-[#1C1917]">{{ $item->quantity }}</td>
                            <td class="px-4 py-4 text-right text-slate-700 font-bold">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-4 text-right font-black text-[#1C1917]">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
