@extends('layouts.app')
@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan #' . $order->id)

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">
    <div class="flex justify-between items-center mb-4">
        <a href="{{ route('kasir.orders.index') }}" class="inline-flex items-center text-dark-300 hover:text-white transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
        
        <div class="flex space-x-2">
            @if($order->payment_status == 'unpaid')
                <form action="{{ route('kasir.orders.pay', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-xl transition-all hover:shadow-lg hover:shadow-green-500/25 flex items-center" onclick="return confirm('Proses pembayaran lunas?');">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Tandai Lunas
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Info Card -->
    <div class="bg-dark-900 border border-dark-700 rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-dark-700/50 bg-dark-800 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold text-white mb-1">Pesanan #{{ $order->id }}</h3>
                <p class="text-dark-400 text-sm">{{ $order->created_at->translatedFormat('d F Y, H:i') }}</p>
            </div>
            <div class="flex flex-col items-end space-y-2">
                @if($order->order_status == 'pending')
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">Menunggu Dimasak</span>
                @elseif($order->order_status == 'cooking')
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-orange-500/10 text-orange-400 border border-orange-500/20">Sedang Dimasak</span>
                @elseif($order->order_status == 'served')
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">Telah Disajikan</span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20">Selesai</span>
                @endif

                @if($order->payment_status == 'paid')
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20">Pembayaran Lunas</span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20">Belum Dibayar</span>
                @endif
            </div>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-5 gap-6 p-6 border-b border-dark-700/50">
            <div>
                <p class="text-dark-400 text-xs uppercase mb-1">Pelanggan</p>
                <p class="text-white font-medium">{{ $order->customer_name }}</p>
            </div>
            <div>
                <p class="text-dark-400 text-xs uppercase mb-1">Meja</p>
                <p class="text-white font-medium">{{ $order->table->table_number ?? '-' }}</p>
            </div>
            <div>
                <p class="text-dark-400 text-xs uppercase mb-1">Kasir</p>
                <p class="text-white font-medium">{{ $order->user->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-dark-400 text-xs uppercase mb-1">Metode</p>
                <p class="text-white font-medium">
                    @if(($order->payment_method ?? 'cash') == 'cash')
                        💵 Tunai
                    @elseif($order->payment_method == 'qris')
                        📱 QRIS
                    @else
                        🏦 Transfer
                    @endif
                </p>
            </div>
            <div>
                <p class="text-dark-400 text-xs uppercase mb-1">Total</p>
                <p class="text-brand-400 font-bold text-lg">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="p-6">
            <h4 class="text-white font-medium mb-4">Item Pesanan</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-dark-800 text-dark-400 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 font-medium rounded-l-lg">Menu</th>
                            <th class="px-4 py-3 font-medium text-center">Jml</th>
                            <th class="px-4 py-3 font-medium text-right">Harga Satuan</th>
                            <th class="px-4 py-3 font-medium text-right rounded-r-lg">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-700/50">
                        @foreach($order->items ?? [] as $item)
                        <tr>
                            <td class="px-4 py-4">
                                <div class="text-white font-medium">{{ $item->menu->name ?? 'Menu Dihapus' }}</div>
                                @if($item->notes)
                                    <div class="text-dark-400 text-xs mt-1 italic flex items-start">
                                        <svg class="w-3 h-3 mr-1 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        {{ $item->notes }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center text-dark-200">{{ $item->quantity }}x</td>
                            <td class="px-4 py-4 text-right text-dark-300">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-4 text-right text-white font-medium">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t-2 border-dark-700">
                        <tr>
                            <td colspan="3" class="px-4 py-4 text-right font-medium text-dark-200">Total Keseluruhan</td>
                            <td class="px-4 py-4 text-right font-bold text-brand-400 text-lg">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
