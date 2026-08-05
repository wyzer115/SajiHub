@extends('layouts.app')
@section('title', 'Daftar Pesanan')
@section('page-title', 'Manajemen Pesanan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between gap-4 items-start sm:items-center animate-fade-in-up">
        <div class="flex space-x-2 w-full sm:w-auto overflow-x-auto pb-2 sm:pb-0">
            <a href="{{ route('kasir.orders.index') }}" class="px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap {{ !request('status') && !request('payment') ? 'bg-brand-500 text-white' : 'bg-dark-800 text-dark-300 hover:bg-dark-700' }}">Semua</a>
            <a href="{{ route('kasir.orders.index', ['status' => 'active']) }}" class="px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap {{ request('status') == 'active' ? 'bg-brand-500 text-white' : 'bg-dark-800 text-dark-300 hover:bg-dark-700' }}">Sedang Aktif</a>
            <a href="{{ route('kasir.orders.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap {{ request('status') == 'pending' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-dark-800 text-dark-300 hover:bg-dark-700' }}">Menunggu</a>
        </div>
        <a href="{{ route('kasir.orders.create') }}" class="bg-brand-500 hover:bg-brand-600 text-white font-semibold px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-brand-500/25 flex items-center space-x-2 whitespace-nowrap">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Buat Pesanan Baru</span>
        </a>
    </div>

    <div class="bg-dark-900 border border-dark-700 rounded-2xl overflow-hidden shadow-sm animate-fade-in-up" style="animation-delay: 100ms;">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-dark-800 text-dark-400 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">ID</th>
                        <th class="px-6 py-4 font-medium">Pelanggan</th>
                        <th class="px-6 py-4 font-medium">Meja</th>
                        <th class="px-6 py-4 font-medium">Item</th>
                        <th class="px-6 py-4 font-medium">Total</th>
                        <th class="px-6 py-4 font-medium">Metode</th>
                        <th class="px-6 py-4 font-medium">Status Pesanan</th>
                        <th class="px-6 py-4 font-medium">Status Bayar</th>
                        <th class="px-6 py-4 font-medium">Waktu</th>
                        <th class="px-6 py-4 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-700/50">
                    @forelse($orders ?? [] as $order)
                    <tr class="hover:bg-dark-800/50 transition-colors">
                        <td class="px-6 py-4 text-white font-medium">#{{ $order->id }}</td>
                        <td class="px-6 py-4 text-dark-200 font-medium">{{ $order->customer_name }}</td>
                        <td class="px-6 py-4 text-dark-300">{{ $order->table->table_number ?? '-' }}</td>
                        <td class="px-6 py-4 text-dark-300">{{ $order->items_count ?? ($order->items ? $order->items->count() : 0) }} item</td>
                        <td class="px-6 py-4 text-brand-400 font-bold whitespace-nowrap">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(($order->payment_method ?? 'cash') == 'cash')
                                <span class="text-xs font-semibold text-dark-300">💵 Tunai</span>
                            @elseif($order->payment_method == 'qris')
                                <span class="text-xs font-semibold text-brand-400">📱 QRIS</span>
                            @else
                                <span class="text-xs font-semibold text-blue-400">🏦 Transfer</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($order->order_status == 'pending')
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-yellow-500/10 text-yellow-400">Menunggu</span>
                            @elseif($order->order_status == 'cooking')
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-orange-500/10 text-orange-400">Dimasak</span>
                            @elseif($order->order_status == 'served')
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-500/10 text-blue-400">Disajikan</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-500/10 text-green-400">Selesai</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($order->payment_status == 'paid')
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-500/10 text-green-400">Lunas</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-500/10 text-red-400">Belum Bayar</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-dark-400 text-xs">{{ $order->created_at->format('H:i') }}<br>{{ $order->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-right flex justify-end space-x-2">
                            <a href="{{ route('kasir.orders.show', $order) }}" class="bg-dark-700 hover:bg-dark-600 text-dark-200 px-3 py-1.5 rounded-lg transition-all text-xs font-medium">Detail</a>
                            
                            @if($order->payment_status == 'unpaid')
                            <form action="{{ route('kasir.orders.pay', $order) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg transition-all text-xs font-bold" onclick="return confirm('Konfirmasi pembayaran lunas tunai?');">
                                    Bayar
                                </button>
                            </form>
                            @endif
                            
                            @if($order->order_status !== 'completed')
                            <form action="{{ route('kasir.orders.update-status', $order) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="order_status" value="completed">
                                <button type="submit" class="bg-brand-500/20 text-brand-400 hover:bg-brand-500/30 px-3 py-1.5 rounded-lg transition-all text-xs font-medium" onclick="return confirm('Selesaikan pesanan ini? Meja akan dikosongkan.');">
                                    Selesaikan
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-dark-400">Belum ada pesanan ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(isset($orders) && $orders->hasPages())
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
