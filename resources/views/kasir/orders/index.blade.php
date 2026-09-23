@extends('layouts.app')
@section('title', 'Daftar Pesanan')
@section('page-title', 'Manajemen Pesanan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between gap-4 items-start sm:items-center animate-fade-in-up">
        <div class="flex space-x-2 w-full sm:w-auto overflow-x-auto pb-2 sm:pb-0">
            <a href="{{ route('kasir.orders.index') }}" class="px-4 py-2 rounded-xl text-xs font-extrabold whitespace-nowrap {{ !request('status') && !request('payment') ? 'bg-[#BD2000] text-white shadow-sm' : 'bg-white border border-stone-200 text-stone-700 hover:bg-stone-100' }}">Semua</a>
            <a href="{{ route('kasir.orders.index', ['status' => 'active']) }}" class="px-4 py-2 rounded-xl text-xs font-extrabold whitespace-nowrap {{ request('status') == 'active' ? 'bg-[#BD2000] text-white shadow-sm' : 'bg-white border border-stone-200 text-stone-700 hover:bg-stone-100' }}">Sedang Aktif</a>
            <a href="{{ route('kasir.orders.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-xs font-extrabold whitespace-nowrap {{ request('status') == 'pending' ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-white border border-stone-200 text-stone-700 hover:bg-stone-100' }}">Menunggu</a>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('kasir.orders.scan') }}" class="group bg-white hover:bg-[#BD2000] text-[#BD2000] hover:text-white border-2 border-[#BD2000] font-extrabold px-5 py-3 rounded-xl transition-all shadow-xs flex items-center space-x-2 whitespace-nowrap cursor-pointer text-xs">
                <svg class="w-4 h-4 text-[#BD2000] group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                <span>Scan QR Konfirmasi</span>
            </a>
            <a href="{{ route('kasir.orders.create') }}" class="bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold px-5 py-3 rounded-xl transition-all shadow-md flex items-center space-x-2 whitespace-nowrap cursor-pointer text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                <span>Buat Pesanan Baru</span>
            </a>
        </div>
    </div>

    <div class="bg-white border border-stone-200 rounded-3xl overflow-hidden shadow-sm animate-fade-in-up">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-stone-100 text-stone-700 text-xs font-extrabold uppercase tracking-wider border-b border-stone-200">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Meja</th>
                        <th class="px-6 py-4">Item</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Metode</th>
                        <th class="px-6 py-4">Status Pesanan</th>
                        <th class="px-6 py-4">Status Bayar</th>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @forelse($orders ?? [] as $order)
                    <tr class="hover:bg-stone-50 transition-colors">
                        <td class="px-6 py-4 text-xs font-bold text-slate-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-[#1C1917] font-extrabold text-sm">{{ $order->customer_name }}</td>
                        <td class="px-6 py-4 text-slate-700 font-bold">{{ $order->table ? $order->table->table_number : 'Bawa Pulang' }}</td>
                        <td class="px-6 py-4 text-slate-600 font-semibold">{{ $order->items_count ?? ($order->items ? $order->items->count() : 0) }} item</td>
                        <td class="px-6 py-4 text-[#BD2000] font-black text-sm whitespace-nowrap">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(($order->payment_method ?? 'cash') == 'cash')
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-stone-700">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    Tunai
                                </span>
                            @elseif($order->payment_method == 'qris')
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-[#BD2000]">
                                    <svg class="w-3.5 h-3.5 text-[#BD2000]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    QRIS
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-blue-600">
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Transfer
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($order->order_status == 'pending')
                                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-300">Menunggu</span>
                            @elseif($order->order_status == 'cooking')
                                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-orange-100 text-orange-800 border border-orange-300">Dimasak</span>
                            @elseif($order->order_status == 'served')
                                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-blue-100 text-blue-800 border border-blue-300">Disajikan</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-300">Selesai</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($order->payment_status == 'paid')
                                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-300">Lunas</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-red-100 text-red-600 border border-red-200">Belum Bayar</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 font-semibold whitespace-nowrap">{{ $order->created_at->diffForHumans() }}</td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                @if($order->payment_status == 'unpaid')
                                    @if(($order->payment_method ?? 'cash') === 'qris')
                                        <form action="{{ route('kasir.orders.pay', $order) }}" method="POST" class="inline" onsubmit="return showConfirm(event, 'Konfirmasi pembayaran QRIS pesanan #{{ $order->id }} sebagai LUNAS?', 'Konfirmasi QRIS', 'Ya, Tandai Lunas')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-extrabold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition-all shadow-xs cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                <span>Tandai Lunas</span>
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
                                                class="inline-flex items-center gap-1 text-xs font-extrabold text-white bg-[#BD2000] hover:bg-[#8C0000] px-3.5 py-1.5 rounded-xl transition-all shadow-xs cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            <span>Bayar</span>
                                        </button>
                                    @endif
                                @endif
                                <a href="{{ route('kasir.orders.show', $order) }}" class="inline-flex items-center space-x-1 text-xs font-extrabold text-stone-700 bg-stone-100 hover:bg-stone-200 border border-stone-300 px-3 py-1.5 rounded-xl transition-all">
                                    <span>Detail</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center text-slate-500 font-medium">
                            Belum ada data pesanan saat ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
