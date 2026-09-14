@extends('layouts.app')
@section('title', 'Laporan Laba Rugi Eksekutif')
@section('page-title', 'Laporan Laba Rugi (P&L)')

@section('content')
<div class="space-y-6 animate-fade-in-up">

    <!-- Header & Date Filter -->
    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-[#8C0000]">Laporan Keuangan Cabang {{ $branch->name }}</h2>
            <p class="text-slate-600 text-xs mt-0.5 font-medium">Periode: <span class="text-[#BD2000] font-extrabold">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</span></p>
        </div>
        <form method="GET" action="{{ route('owner.reports') }}" class="flex items-center gap-3 flex-wrap">
            <input type="date" name="start_date" value="{{ $startDate }}" class="bg-stone-50 border border-stone-300 text-[#1C1917] font-bold rounded-xl px-3 py-2 text-xs focus:border-[#BD2000] focus:outline-none">
            <span class="text-slate-500 text-xs font-bold">s/d</span>
            <input type="date" name="end_date" value="{{ $endDate }}" class="bg-stone-50 border border-stone-300 text-[#1C1917] font-bold rounded-xl px-3 py-2 text-xs focus:border-[#BD2000] focus:outline-none">
            <button type="submit" class="bg-[#BD2000] hover:bg-[#8C0000] text-white px-4 py-2 rounded-xl text-xs font-extrabold transition-all shadow-sm cursor-pointer">Filter</button>
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-1.5 bg-stone-100 hover:bg-stone-200 text-stone-700 border border-stone-300 px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer">
                <svg class="w-4 h-4 text-stone-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak
            </button>
        </form>
    </div>

    <!-- P&L Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider block mb-1">Total Pendapatan Kotor</span>
                <h3 class="text-3xl font-black text-emerald-600">Rp {{ number_format($revenue, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-500 font-semibold mt-2">Dari {{ $totalOrders }} transaksi lunas</p>
            </div>
            <div class="p-3.5 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-200 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider block mb-1">Total Beban & Pengeluaran</span>
                <h3 class="text-3xl font-black text-red-600">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-500 font-semibold mt-2">{{ count($expenses) }} catatan biaya operasional</p>
            </div>
            <div class="p-3.5 bg-red-50 text-red-600 rounded-2xl border border-red-200 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
            </div>
        </div>

        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider block mb-1">Laba Bersih Operasional</span>
                <h3 class="text-3xl font-black {{ $netProfit >= 0 ? 'text-[#BD2000]' : 'text-red-600' }}">Rp {{ number_format($netProfit, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-500 font-semibold mt-2">Pendapatan Kotor - Beban Pengeluaran</p>
            </div>
            <div class="p-3.5 bg-[#BD2000]/10 text-[#BD2000] rounded-2xl border border-[#BD2000]/20 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
        </div>
    </div>

    <!-- Expense Breakdown Table -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-white border border-stone-200 rounded-3xl p-6 shadow-sm">
            <h3 class="text-base font-black text-[#8C0000] mb-4">Rincian Biaya per Kategori</h3>
            <div class="space-y-3">
                @forelse($expensesByCategory as $cat => $sum)
                <div class="flex justify-between items-center p-3 bg-stone-50 rounded-2xl border border-stone-200">
                    <span class="text-xs uppercase text-slate-700 font-extrabold">{{ str_replace('_', ' ', $cat) }}</span>
                    <span class="text-sm font-black text-red-600">Rp {{ number_format($sum, 0, ',', '.') }}</span>
                </div>
                @empty
                <p class="text-slate-500 text-xs py-4 text-center font-medium">Belum ada rincian beban biaya.</p>
                @endforelse
            </div>
        </div>

        <div class="lg:col-span-2 bg-white border border-stone-200 rounded-3xl p-6 shadow-sm">
            <h3 class="text-base font-black text-[#8C0000] mb-4">Daftar Transaksi Biaya Operasional</h3>
            <div class="overflow-x-auto rounded-2xl border border-stone-200">
                <table class="w-full text-left text-xs text-[#1C1917] border-collapse">
                    <thead class="bg-stone-100 text-stone-700 font-extrabold uppercase border-b border-stone-200">
                        <tr>
                            <th class="p-3.5">Keterangan</th>
                            <th class="p-3.5">Kategori</th>
                            <th class="p-3.5">Tanggal</th>
                            <th class="p-3.5 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200">
                        @forelse($expenses as $exp)
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="p-3.5 font-extrabold text-[#1C1917] text-sm">{{ $exp->title }}</td>
                            <td class="p-3.5 uppercase text-slate-600 font-bold">{{ str_replace('_', ' ', $exp->category) }}</td>
                            <td class="p-3.5 text-slate-600 font-semibold">{{ $exp->date->format('d/m/Y') }}</td>
                            <td class="p-3.5 text-right font-black text-red-600 text-sm">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="p-4 text-center text-slate-500 font-medium">Tidak ada pengeluaran pada periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
