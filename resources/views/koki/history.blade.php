@extends('layouts.app')
@section('title', 'Riwayat Masak Hari Ini')
@section('page-title', 'Riwayat Masak Hari Ini')

@section('content')
<div class="space-y-6 animate-fade-in-up">

    <!-- Header info -->
    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex justify-between items-center">
        <div>
            <h2 class="text-xl font-black text-[#8C0000] flex items-center gap-2">
                <svg class="w-6 h-6 text-[#8C0000]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Riwayat Hidangan Selesai Disajikan</span>
            </h2>
            <p class="text-slate-600 text-xs font-medium mt-0.5">Daftar pesanan yang telah selesai dimasak dan disajikan oleh koki hari ini</p>
        </div>
        <a href="{{ route('koki.kitchen') }}" class="inline-flex items-center gap-1.5 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold px-4 py-2 rounded-xl border border-stone-300 transition-all">
            <svg class="w-4 h-4 text-stone-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            Ke Layar Monitor Dapur
        </a>
    </div>

    <!-- History Table -->
    <div class="bg-white border border-stone-200 rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-stone-100 text-stone-700 text-xs font-extrabold uppercase tracking-wider border-b border-stone-200">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Meja</th>
                        <th class="px-6 py-4">Detail Menu Masakan</th>
                        <th class="px-6 py-4">Status Dapur</th>
                        <th class="px-6 py-4 text-right">Waktu Selesai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @forelse($completedOrders as $order)
                    <tr class="hover:bg-stone-50 transition-colors">
                        <td class="px-6 py-4 text-xs font-bold text-slate-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-extrabold text-[#1C1917] text-sm">{{ $order->customer_name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700 font-bold">{{ $order->table ? $order->table->table_number : 'Takeaway' }}</td>
                        <td class="px-6 py-4 text-xs text-slate-700">
                            <ul class="space-y-1">
                                @foreach($order->items as $item)
                                    <li><span class="font-black text-[#1C1917]">{{ $item->quantity }}x</span> {{ $item->menu->name ?? 'Menu' }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="px-6 py-4">
                            @if($order->order_status === 'served')
                                <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-blue-100 text-blue-800 border border-blue-300">
                                    Disajikan
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                    Selesai
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-xs text-slate-500 font-semibold">{{ $order->updated_at->format('H:i') }} WIB ({{ $order->updated_at->diffForHumans() }})</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500 text-sm font-medium">Belum ada hidangan yang diselesaikan hari ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($completedOrders->hasPages())
        <div class="p-4 border-t border-stone-200">
            {{ $completedOrders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
