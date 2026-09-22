@extends('layouts.app')
@section('title', 'Laporan Keuangan & Rekap Kasir')
@section('page-title', 'Laporan Keuangan & Rekap Kasir')

@section('content')
<div class="space-y-6 animate-fade-in-up">

    <!-- Header & Date Filter Banner -->
    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-[#8C0000] flex items-center gap-2 mb-1">
                <svg class="w-6 h-6 text-[#BD2000]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Rekap Keuangan Transaksi Kasir ({{ $branch->name ?? 'Cabang' }})</span>
            </h2>
            <p class="text-stone-600 text-xs font-medium">Rekapitulasi penjualan terbayar, serah terima shift kasir, & rincian pembayaran (Cash vs QRIS).</p>
        </div>

        <!-- Filter Period & Custom Dates -->
        <form method="GET" action="{{ route('kasir.reports') }}" class="flex flex-wrap items-center gap-2 shrink-0">
            <div class="flex items-center bg-stone-100 p-1 rounded-xl">
                <a href="{{ route('kasir.reports', ['preset' => 'today']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $preset === 'today' && !request('start_date') ? 'bg-white text-[#BD2000] shadow-xs' : 'text-stone-600 hover:text-stone-900' }}">
                    Hari Ini (Shift)
                </a>
                <a href="{{ route('kasir.reports', ['preset' => 'weekly']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $preset === 'weekly' && !request('start_date') ? 'bg-white text-[#BD2000] shadow-xs' : 'text-stone-600 hover:text-stone-900' }}">
                    Minggu Ini
                </a>
                <a href="{{ route('kasir.reports', ['preset' => 'monthly']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $preset === 'monthly' && !request('start_date') ? 'bg-white text-[#BD2000] shadow-xs' : 'text-stone-600 hover:text-stone-900' }}">
                    Bulan Ini
                </a>
            </div>

            <input type="date" name="start_date" value="{{ $startDate }}" class="bg-stone-50 border border-stone-300 text-stone-800 text-xs font-semibold rounded-xl px-3 py-1.5 focus:border-[#BD2000] focus:outline-none">
            <span class="text-xs font-bold text-stone-400">-</span>
            <input type="date" name="end_date" value="{{ $endDate }}" class="bg-stone-50 border border-stone-300 text-stone-800 text-xs font-semibold rounded-xl px-3 py-1.5 focus:border-[#BD2000] focus:outline-none">
            <button type="submit" class="bg-[#BD2000] hover:bg-[#8C0000] text-white text-xs font-extrabold px-4 py-2 rounded-xl transition-all shadow-xs cursor-pointer">Filter</button>
        </form>
    </div>

    <!-- Financial KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Omset Lunas -->
        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-stone-500 font-bold text-xs block mb-1">TOTAL OMSET LUNAS</span>
                <h3 class="text-2xl font-black text-[#8C0000]">Rp {{ number_format($totalPaidRevenue, 0, ',', '.') }}</h3>
                <span class="text-[11px] font-semibold text-emerald-600 mt-1 block">Dari {{ $totalPaidOrdersCount }} transaksi terbayar</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-red-50 text-[#BD2000] flex items-center justify-center font-bold text-xl shrink-0">
                💰
            </div>
        </div>

        <!-- Pembayaran Tunai (Cash) -->
        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-stone-500 font-bold text-xs block mb-1">PEMBAYARAN TUNAI (CASH)</span>
                <h3 class="text-2xl font-black text-emerald-700">Rp {{ number_format($cashRevenue, 0, ',', '.') }}</h3>
                <span class="text-[11px] font-semibold text-stone-400 mt-1 block">Fisik kas di laci kasir</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl shrink-0">
                💵
            </div>
        </div>

        <!-- Pembayaran QRIS / Non-Tunai -->
        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-stone-500 font-bold text-xs block mb-1">PEMBAYARAN QRIS / DIGITAL</span>
                <h3 class="text-2xl font-black text-blue-700">Rp {{ number_format($qrisRevenue, 0, ',', '.') }}</h3>
                <span class="text-[11px] font-semibold text-stone-400 mt-1 block">Masuk ke rekening digital</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl shrink-0">
                📱
            </div>
        </div>

        <!-- Rata-rata Keranjang Transaksi (AOV) -->
        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-stone-500 font-bold text-xs block mb-1">RATA-RATA NILAI TRANSAKSI</span>
                <h3 class="text-2xl font-black text-stone-800">Rp {{ number_format($avgTransactionValue, 0, ',', '.') }}</h3>
                <span class="text-[11px] font-semibold text-stone-400 mt-1 block">Per transaksi pelanggan</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl shrink-0">
                📊
            </div>
        </div>
    </div>

    <!-- Category Sales Breakdown & Top Selling Menus Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Rincian Penjualan per Kategori -->
        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-extrabold text-stone-800 uppercase tracking-wider flex items-center gap-2">
                <span>📂 Omset Penjualan per Kategori Menu</span>
            </h3>

            <div class="space-y-3">
                @forelse($categorySales as $cat)
                    @php
                        $percentage = $totalPaidRevenue > 0 ? round(($cat['total'] / $totalPaidRevenue) * 100, 1) : 0;
                    @endphp
                    <div class="p-3.5 bg-stone-50 border border-stone-200 rounded-2xl space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-extrabold text-stone-900">{{ $cat['name'] }}</span>
                            <span class="font-black text-[#BD2000]">Rp {{ number_format($cat['total'], 0, ',', '.') }} ({{ $cat['qty'] }} porsi)</span>
                        </div>
                        <div class="w-full bg-stone-200 h-2 rounded-full overflow-hidden">
                            <div class="bg-[#BD2000] h-full rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <div class="text-[10px] text-stone-500 font-medium text-right">{{ $percentage }}% dari total omset terbayar</div>
                    </div>
                @empty
                    <div class="p-6 text-center text-xs font-semibold text-stone-400">Belum ada transaksi terbayar pada periode ini.</div>
                @endforelse
            </div>
        </div>

        <!-- Top 5 Menu Terlaris -->
        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-extrabold text-stone-800 uppercase tracking-wider flex items-center gap-2">
                <span>🔥 Top 5 Menu Terlaris (Best Seller Kasir)</span>
            </h3>

            <div class="divide-y divide-stone-100">
                @forelse($topMenus as $index => $menu)
                    <div class="py-3 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-xl bg-amber-100 text-amber-800 font-extrabold flex items-center justify-center shrink-0">
                                #{{ $index + 1 }}
                            </div>
                            <div>
                                <span class="font-extrabold text-stone-900 block text-sm">{{ $menu['name'] }}</span>
                                <span class="text-stone-500 font-medium text-[11px]">{{ $menu['qty'] }} porsi terjual</span>
                            </div>
                        </div>
                        <span class="font-black text-stone-900">Rp {{ number_format($menu['total'], 0, ',', '.') }}</span>
                    </div>
                @empty
                    <div class="p-6 text-center text-xs font-semibold text-stone-400">Belum ada data menu terlaris.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Settlement Log: Paid Transactions Table -->
    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-extrabold text-stone-800 uppercase tracking-wider">Daftar Transaksi Lunas Kasir</h3>
            <button onclick="window.print()" class="bg-stone-100 hover:bg-stone-200 text-stone-700 border border-stone-300 px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak Rekap Shift Kasir</span>
            </button>
        </div>

        <div class="overflow-x-auto border border-stone-200 rounded-2xl">
            <table class="w-full text-left text-xs text-stone-700">
                <thead class="bg-stone-100 text-stone-700 uppercase font-black tracking-wider text-[11px] border-b border-stone-200">
                    <tr>
                        <th class="px-4 py-3">Tanggal & Jam</th>
                        <th class="px-4 py-3">Kode Order</th>
                        <th class="px-4 py-3">Pelanggan</th>
                        <th class="px-4 py-3">Meja</th>
                        <th class="px-4 py-3">Metode Bayar</th>
                        <th class="px-4 py-3">Kasir</th>
                        <th class="px-4 py-3 text-right">Total Transaksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($paginatedOrders as $order)
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="px-4 py-3 font-semibold whitespace-nowrap">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 font-extrabold text-stone-900">#{{ $order->id }}</td>
                            <td class="px-4 py-3 font-semibold text-stone-800">{{ $order->customer_name }}</td>
                            <td class="px-4 py-3 font-medium">{{ $order->table ? 'Meja ' . $order->table->table_number : 'Takeaway' }}</td>
                            <td class="px-4 py-3 font-bold">
                                @if($order->payment_method === 'cash')
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-800 border border-emerald-200 px-2.5 py-0.5 rounded-lg text-[11px]">💵 Cash</span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-800 border border-blue-200 px-2.5 py-0.5 rounded-lg text-[11px]">📱 QRIS</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-stone-600 font-medium">{{ $order->user->name ?? 'Kasir' }}</td>
                            <td class="px-4 py-3 text-right font-black text-stone-900 text-sm">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-stone-400 font-semibold">
                                Belum ada transaksi lunas pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-2">
            {{ $paginatedOrders->links() }}
        </div>
    </div>
</div>
@endsection
