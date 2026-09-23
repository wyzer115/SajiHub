@extends('layouts.app')
@section('title', 'Laporan Laba Rugi Eksekutif')
@section('page-title', 'Laporan Laba Rugi (P&L)')

@section('content')
<style>
    @media print {
        body {
            background-color: #ffffff !important;
            color: #111827 !important;
        }
        #sidebar, #sidebar-overlay, header, nav, .no-print {
            display: none !important;
        }
        main {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        .flex.h-screen {
            height: auto !important;
            overflow: visible !important;
            display: block !important;
        }
        .print-only {
            display: block !important;
        }
        .shadow-sm, .shadow-md, .shadow-xl {
            box-shadow: none !important;
            border: 1px solid #d1d5db !important;
        }
        .page-break-inside-avoid {
            break-inside: avoid;
            page-break-inside: avoid;
        }
    }
    .print-only {
        display: none;
    }
</style>

<div class="space-y-6 animate-fade-in-up">

    <!-- Print Header Only (Tampil saat di-print) -->
    <div class="print-only mb-6 border-b-2 border-stone-800 pb-4">
        <h1 class="text-2xl font-black text-[#8C0000] uppercase tracking-wide">LAPORAN LABA RUGI EKSEKUTIF (PROFIT & LOSS)</h1>
        <p class="text-sm font-bold text-stone-800">SajiHub Multi-Branch Culinary Enterprise</p>
        <div class="mt-2 text-xs text-stone-600 flex justify-between">
            <span>Cabang: <strong>{{ $selectedBranch ? $selectedBranch->name : 'Semua Cabang (Laporan Gabungan)' }}</strong></span>
            <span>Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</strong></span>
            <span>Dicetak: {{ now()->format('d/m/Y H:i') }} WIB</span>
        </div>
    </div>

    <!-- Header & Interactive Filter Bar (No Print) -->
    <div class="no-print bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex flex-col xl:flex-row items-start xl:items-center justify-between gap-5">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase bg-red-100 text-[#8C0000] border border-red-200">Executive Report</span>
                <span class="text-xs font-bold text-stone-500">• {{ $periodLabel }}</span>
            </div>
            <h2 class="text-2xl font-black text-[#8C0000] mt-1">Laporan Keuangan: {{ $selectedBranch ? $selectedBranch->name : 'Semua Cabang (Gabungan)' }}</h2>
            <p class="text-slate-600 text-xs mt-0.5 font-medium">Periode terpilih: <span class="text-[#BD2000] font-extrabold">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</span></p>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full xl:w-auto">
            <!-- Preset Buttons -->
            <div class="flex items-center gap-1.5 bg-stone-100 p-1.5 rounded-2xl border border-stone-200">
                <a href="{{ route('owner.reports', ['preset' => 'today', 'branch_id' => $selectedBranchId]) }}"
                   class="px-3 py-1.5 rounded-xl text-xs font-black transition-all {{ $preset === 'today' ? 'bg-[#BD2000] text-white shadow-sm' : 'text-stone-700 hover:bg-stone-200' }}">
                    Hari Ini
                </a>
                <a href="{{ route('owner.reports', ['preset' => 'week', 'branch_id' => $selectedBranchId]) }}"
                   class="px-3 py-1.5 rounded-xl text-xs font-black transition-all {{ $preset === 'week' ? 'bg-[#BD2000] text-white shadow-sm' : 'text-stone-700 hover:bg-stone-200' }}">
                    Minggu Ini
                </a>
                <a href="{{ route('owner.reports', ['preset' => 'month', 'branch_id' => $selectedBranchId]) }}"
                   class="px-3 py-1.5 rounded-xl text-xs font-black transition-all {{ ($preset === 'month' || !$preset) ? 'bg-[#BD2000] text-white shadow-sm' : 'text-stone-700 hover:bg-stone-200' }}">
                    Bulan Ini
                </a>
            </div>

            <!-- Form Filter Cabang & Tanggal -->
            <form method="GET" action="{{ route('owner.reports') }}" class="flex items-center gap-2 flex-wrap">
                <input type="hidden" name="preset" value="custom">
                <select name="branch_id" onchange="this.form.submit()" class="bg-stone-50 border border-stone-300 text-stone-800 text-xs font-bold rounded-xl px-3 py-2 focus:border-[#BD2000] focus:outline-none shadow-sm cursor-pointer">
                    <option value="all" {{ ($selectedBranchId == 'all' || !$selectedBranchId) ? 'selected' : '' }}>🌐 Semua Cabang</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ $selectedBranchId == $b->id ? 'selected' : '' }}>🏢 {{ $b->name }}</option>
                    @endforeach
                </select>
                <input type="date" name="start_date" value="{{ $startDate }}" class="bg-stone-50 border border-stone-300 text-[#1C1917] font-bold rounded-xl px-2.5 py-2 text-xs focus:border-[#BD2000] focus:outline-none">
                <span class="text-slate-400 text-xs font-bold">-</span>
                <input type="date" name="end_date" value="{{ $endDate }}" class="bg-stone-50 border border-stone-300 text-[#1C1917] font-bold rounded-xl px-2.5 py-2 text-xs focus:border-[#BD2000] focus:outline-none">
                <button type="submit" class="bg-[#BD2000] hover:bg-[#8C0000] text-white px-3.5 py-2 rounded-xl text-xs font-extrabold transition-all shadow-sm cursor-pointer">Filter</button>
            </form>

            <!-- Export & Print Actions -->
            <div class="flex items-center gap-2">
                <a href="{{ route('owner.reports.export', request()->all()) }}" class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white px-3.5 py-2 rounded-xl text-xs font-extrabold transition-all shadow-sm cursor-pointer whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Excel
                </a>
                <button type="button" onclick="window.print()" class="inline-flex items-center gap-1.5 bg-stone-100 hover:bg-stone-200 text-stone-700 border border-stone-300 px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer whitespace-nowrap">
                    <svg class="w-4 h-4 text-stone-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak
                </button>
            </div>
        </div>
    </div>

    <!-- P&L Executive Summary Cards (4 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Pendapatan Kotor -->
        <div class="bg-white border border-stone-200 rounded-3xl p-5 shadow-sm flex flex-col justify-between page-break-inside-avoid">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider">Pendapatan Kotor</span>
                <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-black text-emerald-600">Rp {{ number_format($revenue, 0, ',', '.') }}</h3>
                <div class="flex items-center justify-between text-[11px] text-stone-500 font-semibold mt-2 pt-2 border-t border-stone-100">
                    <span>{{ $totalOrders }} pesanan lunas</span>
                    <span>AOV: Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Beban Operasional -->
        <div class="bg-white border border-stone-200 rounded-3xl p-5 shadow-sm flex flex-col justify-between page-break-inside-avoid">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider">Total Beban Biaya</span>
                <div class="p-2.5 bg-red-50 text-red-600 rounded-2xl border border-red-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-black text-red-600">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h3>
                <div class="flex items-center justify-between text-[11px] text-stone-500 font-semibold mt-2 pt-2 border-t border-stone-100">
                    <span>{{ count($expenses) }} transaksi beban</span>
                    <span class="font-bold text-amber-600">Rasio Beban: {{ $expenseRatio }}%</span>
                </div>
            </div>
        </div>

        <!-- Laba Bersih Operasional -->
        <div class="bg-white border border-stone-200 rounded-3xl p-5 shadow-sm flex flex-col justify-between page-break-inside-avoid">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider">Laba Bersih (Net Profit)</span>
                <div class="p-2.5 {{ $netProfit >= 0 ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-red-50 text-red-600 border border-red-200' }} rounded-2xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-black {{ $netProfit >= 0 ? 'text-[#BD2000]' : 'text-red-600' }}">Rp {{ number_format($netProfit, 0, ',', '.') }}</h3>
                <div class="flex items-center justify-between text-[11px] text-stone-500 font-semibold mt-2 pt-2 border-t border-stone-100">
                    <span>Pendapatan - Beban</span>
                    <span class="font-extrabold {{ $netProfit >= 0 ? 'text-emerald-600' : 'text-red-600' }}">{{ $netProfit >= 0 ? 'Surplus Untung' : 'Defisit Rugi' }}</span>
                </div>
            </div>
        </div>

        <!-- Net Profit Margin -->
        <div class="bg-white border border-stone-200 rounded-3xl p-5 shadow-sm flex flex-col justify-between page-break-inside-avoid">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider">Net Profit Margin</span>
                <div class="p-2.5 bg-amber-50 text-amber-600 rounded-2xl border border-amber-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl font-black {{ $profitMargin >= 15 ? 'text-emerald-600' : ($profitMargin >= 0 ? 'text-amber-600' : 'text-red-600') }}">{{ $profitMargin }}%</h3>
                <div class="flex items-center justify-between text-[11px] text-stone-500 font-semibold mt-2 pt-2 border-t border-stone-100">
                    <span>Target Ideal: >15%</span>
                    <span class="font-extrabold">{{ $profitMargin >= 20 ? 'Sangat Sehat' : ($profitMargin >= 10 ? 'Normal' : 'Evaluasi') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Performa Antar Cabang (Hanya Muncul Jika Filter "Semua Cabang") -->
    @if(!$selectedBranch && $branchPerformances->isNotEmpty())
    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm space-y-4 page-break-inside-avoid">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-black text-[#8C0000] flex items-center gap-2">
                    <span>🏢 Perbandingan Kinerja Seluruh Cabang</span>
                </h3>
                <p class="text-xs text-stone-500 font-medium">Analisis kontribusi pendapatan, biaya, dan margin keuntungan antar outlet.</p>
            </div>
            <span class="text-xs font-extrabold text-stone-500 bg-stone-100 px-3 py-1 rounded-xl border border-stone-200">{{ $branches->count() }} Cabang Terdaftar</span>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-stone-200">
            <table class="w-full text-left text-xs text-[#1C1917]">
                <thead class="bg-stone-100 text-stone-700 font-extrabold uppercase border-b border-stone-200">
                    <tr>
                        <th class="p-3.5">Nama Cabang</th>
                        <th class="p-3.5 text-center">Pesanan Selesai</th>
                        <th class="p-3.5 text-right">Pendapatan Kotor</th>
                        <th class="p-3.5 text-right">Beban Operasional</th>
                        <th class="p-3.5 text-right">Laba Bersih</th>
                        <th class="p-3.5 text-center">Profit Margin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @foreach($branchPerformances as $bp)
                    <tr class="hover:bg-stone-50 transition-colors">
                        <td class="p-3.5 font-black text-stone-900 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#BD2000]"></span>
                            {{ $bp['branch']->name }}
                        </td>
                        <td class="p-3.5 text-center font-bold text-stone-600">{{ $bp['orders'] }} order</td>
                        <td class="p-3.5 text-right font-black text-emerald-600 text-sm">Rp {{ number_format($bp['revenue'], 0, ',', '.') }}</td>
                        <td class="p-3.5 text-right font-bold text-red-600 text-sm">Rp {{ number_format($bp['expense'], 0, ',', '.') }}</td>
                        <td class="p-3.5 text-right font-black {{ $bp['profit'] >= 0 ? 'text-[#BD2000]' : 'text-red-600' }} text-sm">
                            Rp {{ number_format($bp['profit'], 0, ',', '.') }}
                        </td>
                        <td class="p-3.5 text-center font-black">
                            <span class="px-2.5 py-1 rounded-xl text-[11px] {{ $bp['margin'] >= 15 ? 'bg-emerald-100 text-emerald-800' : ($bp['margin'] >= 0 ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                {{ $bp['margin'] }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Breakdown Pendapatan: Metode Pembayaran & Top 5 Menu Terlaris -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Metode Pembayaran -->
        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm space-y-4 page-break-inside-avoid">
            <h3 class="text-base font-black text-[#8C0000] flex items-center gap-2">
                <span>💳 Komposisi Metode Pembayaran</span>
            </h3>
            <div class="space-y-3">
                @forelse($paymentMethods as $pmKey => $pm)
                <div class="p-3.5 bg-stone-50 border border-stone-200 rounded-2xl space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-extrabold uppercase text-stone-800">
                            {{ $pmKey === 'cash' ? '💵 Tunai (Cash Kasir)' : ($pmKey === 'qris' ? '📱 QRIS Digital' : '🏦 ' . strtoupper($pmKey)) }}
                        </span>
                        <span class="font-black text-stone-900">
                            Rp {{ number_format($pm['total'], 0, ',', '.') }} <span class="text-stone-500 font-semibold text-[11px]">({{ $pm['count'] }} trx)</span>
                        </span>
                    </div>
                    <div class="w-full bg-stone-200 h-2 rounded-full overflow-hidden">
                        <div class="bg-[#BD2000] h-full rounded-full transition-all duration-500" style="width: {{ $pm['percentage'] }}%"></div>
                    </div>
                    <div class="text-[10px] text-stone-500 font-bold text-right">{{ $pm['percentage'] }}% dari total pendapatan</div>
                </div>
                @empty
                <p class="text-slate-400 text-xs py-4 text-center font-medium">Belum ada transaksi pendapatan terbayar pada periode ini.</p>
                @endforelse
            </div>
        </div>

        <!-- Top 5 Menu Penyumbang Omset -->
        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm space-y-4 page-break-inside-avoid">
            <h3 class="text-base font-black text-[#8C0000] flex items-center gap-2">
                <span>🔥 Top 5 Menu Penyumbang Omset</span>
            </h3>
            <div class="divide-y divide-stone-100">
                @forelse($topMenus as $idx => $item)
                <div class="py-3 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-xl bg-amber-100 text-amber-800 font-black flex items-center justify-center shrink-0">
                            #{{ $idx + 1 }}
                        </div>
                        <div>
                            <span class="font-black text-stone-900 block text-sm">{{ $item->menu->name ?? 'Menu Dihapus' }}</span>
                            <span class="text-stone-500 font-medium text-[11px]">{{ $item->total_qty }} porsi terjual</span>
                        </div>
                    </div>
                    <span class="font-black text-emerald-600 text-sm">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</span>
                </div>
                @empty
                <p class="text-slate-400 text-xs py-4 text-center font-medium">Belum ada data penjualan menu pada periode ini.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Rincian Beban Biaya: Kategori & Transaksi Biaya -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Rincian Kategori Biaya -->
        <div class="lg:col-span-1 bg-white border border-stone-200 rounded-3xl p-6 shadow-sm page-break-inside-avoid">
            <h3 class="text-base font-black text-[#8C0000] mb-4">Rincian Beban per Kategori</h3>
            <div class="space-y-3">
                @forelse($expensesByCategory as $cat => $sum)
                @php
                    $catPercentage = $totalExpenses > 0 ? round(($sum / $totalExpenses) * 100, 1) : 0;
                @endphp
                <div class="p-3 bg-stone-50 rounded-2xl border border-stone-200 space-y-1">
                    <div class="flex justify-between items-center">
                        <span class="text-xs uppercase text-slate-700 font-extrabold">{{ str_replace('_', ' ', $cat) }}</span>
                        <span class="text-sm font-black text-red-600">Rp {{ number_format($sum, 0, ',', '.') }}</span>
                    </div>
                    <div class="text-[10px] text-stone-500 font-bold text-right">{{ $catPercentage }}% dari total beban</div>
                </div>
                @empty
                <p class="text-slate-500 text-xs py-4 text-center font-medium">Belum ada rincian beban biaya.</p>
                @endforelse
            </div>
        </div>

        <!-- Daftar Transaksi Biaya Operasional -->
        <div class="lg:col-span-2 bg-white border border-stone-200 rounded-3xl p-6 shadow-sm page-break-inside-avoid">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-black text-[#8C0000]">Daftar Transaksi Biaya Operasional</h3>
                <span class="text-xs font-bold text-stone-500">{{ $paginatedExpenses->total() }} transaksi tercatat</span>
            </div>

            <div class="overflow-x-auto rounded-2xl border border-stone-200">
                <table class="w-full text-left text-xs text-[#1C1917] border-collapse">
                    <thead class="bg-stone-100 text-stone-700 font-extrabold uppercase border-b border-stone-200">
                        <tr>
                            <th class="p-3.5">Keterangan</th>
                            @if(!$selectedBranch)
                            <th class="p-3.5">Cabang</th>
                            @endif
                            <th class="p-3.5">Kategori</th>
                            <th class="p-3.5">Tanggal</th>
                            <th class="p-3.5 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200">
                        @forelse($paginatedExpenses as $exp)
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="p-3.5">
                                <span class="font-extrabold text-[#1C1917] text-sm block">{{ $exp->title }}</span>
                                @if($exp->notes)
                                    <span class="text-[11px] text-stone-500">{{ $exp->notes }}</span>
                                @endif
                            </td>
                            @if(!$selectedBranch)
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-stone-100 text-stone-700 border border-stone-200">
                                    {{ $exp->branch->name ?? '-' }}
                                </span>
                            </td>
                            @endif
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-extrabold uppercase bg-red-50 text-red-700 border border-red-200">
                                    {{ str_replace('_', ' ', $exp->category) }}
                                </span>
                            </td>
                            <td class="p-3.5 text-slate-600 font-semibold">{{ $exp->date ? $exp->date->format('d/m/Y') : '-' }}</td>
                            <td class="p-3.5 text-right font-black text-red-600 text-sm">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="{{ !$selectedBranch ? 5 : 4 }}" class="p-4 text-center text-slate-500 font-medium">Tidak ada pengeluaran pada periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($paginatedExpenses->hasPages())
            <div class="mt-4 p-3 border-t border-stone-200 bg-stone-50 rounded-2xl">
                {{ $paginatedExpenses->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
