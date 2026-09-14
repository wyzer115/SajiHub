@extends('layouts.app')
@section('title', 'Riwayat Semua Transaksi')
@section('page-title', 'Riwayat Semua Transaksi')

@section('content')
<div class="space-y-6 animate-fade-in-up">

    <!-- Header Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Total Pemasukan Lunas</p>
                <h3 class="text-3xl font-black text-emerald-600">Rp {{ number_format($totalPaidRevenue, 0, ',', '.') }}</h3>
            </div>
            <div class="p-3.5 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-200 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Total Keseluruhan Transaksi</p>
                <h3 class="text-3xl font-black text-[#1C1917]">{{ $orders->total() }} <span class="text-sm font-bold text-slate-500">transaksi</span></h3>
            </div>
            <div class="p-3.5 bg-[#BD2000]/10 text-[#BD2000] rounded-2xl border border-[#BD2000]/20 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Filter Form & Quick Preset Buttons -->
    <div class="bg-white border border-stone-200 rounded-3xl p-5 shadow-sm space-y-4">
        <!-- Quick Preset Filter Buttons -->
        <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-stone-200/60">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-[#BD2000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="text-xs font-black text-stone-600 uppercase tracking-wider">Filter Cepat:</span>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('kasir.transactions', ['preset' => 'today']) }}"
                   class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all border shadow-xs flex items-center gap-1.5 {{ $preset == 'today' ? 'bg-[#BD2000] text-white border-[#BD2000] shadow-sm' : 'bg-stone-50 hover:bg-stone-100 text-stone-700 border-stone-300' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Hari Ini
                </a>
                <a href="{{ route('kasir.transactions', ['preset' => 'weekly']) }}"
                   class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all border shadow-xs flex items-center gap-1.5 {{ $preset == 'weekly' ? 'bg-[#BD2000] text-white border-[#BD2000] shadow-sm' : 'bg-stone-50 hover:bg-stone-100 text-stone-700 border-stone-300' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Minggu Ini
                </a>
                <a href="{{ route('kasir.transactions', ['preset' => 'monthly']) }}"
                   class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all border shadow-xs flex items-center gap-1.5 {{ $preset == 'monthly' ? 'bg-[#BD2000] text-white border-[#BD2000] shadow-sm' : 'bg-stone-50 hover:bg-stone-100 text-stone-700 border-stone-300' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Bulan Ini
                </a>
                <a href="{{ route('kasir.transactions', ['preset' => 'yearly']) }}"
                   class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all border shadow-xs flex items-center gap-1.5 {{ $preset == 'yearly' ? 'bg-[#BD2000] text-white border-[#BD2000] shadow-sm' : 'bg-stone-50 hover:bg-stone-100 text-stone-700 border-stone-300' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Tahun Ini
                </a>
            </div>
        </div>

        <!-- Custom Date Range Form -->
        <form method="GET" action="{{ route('kasir.transactions') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-stone-700 mb-1.5 uppercase tracking-wider">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate ?? request('start_date') }}" class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-2.5 text-sm focus:border-[#BD2000] focus:outline-none transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-stone-700 mb-1.5 uppercase tracking-wider">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate ?? request('end_date') }}" class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-2.5 text-sm focus:border-[#BD2000] focus:outline-none transition-all">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="w-full bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold py-2.5 rounded-xl text-sm transition-all shadow-md cursor-pointer">
                    Filter Tanggal
                </button>
                <a href="{{ route('kasir.transactions') }}" class="bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold px-4 py-2.5 rounded-xl text-sm border border-stone-300 transition-all text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Transactions -->
    <div class="bg-white border border-stone-200 rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-stone-100 text-stone-700 text-xs font-extrabold uppercase tracking-wider border-b border-stone-200">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Meja</th>
                        <th class="px-6 py-4">Status Bayar</th>
                        <th class="px-6 py-4">Metode</th>
                        <th class="px-6 py-4">Total Tagihan</th>
                        <th class="px-6 py-4">Waktu Transaksi</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-stone-50 transition-colors">
                        <td class="px-6 py-4 text-xs font-bold text-slate-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-extrabold text-[#1C1917]">{{ $order->customer_name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700 font-bold">{{ $order->table ? $order->table->table_number : 'Bawa Pulang' }}</td>
                        <td class="px-6 py-4">
                            @if($order->payment_status == 'paid')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold uppercase bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                                    LUNAS
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold uppercase bg-amber-50 text-amber-800 border border-amber-200">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
                                    BELUM BAYAR
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs font-extrabold uppercase text-stone-700">
                            {{ $order->payment_method ?? 'cash' }}
                        </td>
                        <td class="px-6 py-4 text-sm font-black text-emerald-700">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-xs text-slate-600 font-semibold">{{ $order->created_at->format('d/m/Y H:i') }} WIB</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            @if($order->payment_status == 'paid')
                                <a href="{{ route('kasir.orders.receipt', $order) }}" target="_blank" class="inline-flex items-center gap-1.5 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold px-3 py-1.5 rounded-xl border border-stone-300 transition-all">
                                    <svg class="w-3.5 h-3.5 text-stone-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    Cetak Struk
                                </a>
                            @else
                                <a href="{{ route('kasir.orders.show', $order) }}" class="inline-flex items-center gap-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold px-3 py-1.5 rounded-xl border border-amber-200 transition-all">
                                    <svg class="w-3.5 h-3.5 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    Proses Bayar
                                </a>
                            @endif
                            <a href="{{ route('kasir.orders.show', $order) }}" class="inline-flex items-center gap-1 bg-[#BD2000]/10 hover:bg-[#BD2000]/20 text-[#BD2000] text-xs font-extrabold px-3 py-1.5 rounded-xl border border-[#BD2000]/20 transition-all">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-500 text-sm font-medium">Tidak ada riwayat transaksi ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="p-4 border-t border-stone-200">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
