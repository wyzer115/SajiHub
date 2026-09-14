@extends('layouts.app')
@section('title', 'Pengeluaran Operasional')
@section('page-title', 'Pengeluaran Operasional Cabang')

@section('content')
<div class="space-y-6 animate-fade-in-up">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-[#8C0000]">Catatan Biaya & Pengeluaran</h2>
            <p class="text-slate-600 text-xs mt-0.5 font-medium">Kelola pengeluaran operasional cabang (utility, perbaikan, pembelian darurat)</p>
        </div>
        <a href="{{ route('supervisor.expenses.create') }}" class="bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold px-5 py-2.5 rounded-xl transition-all shadow-md flex items-center gap-2 cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            Tambah Pengeluaran Baru
        </a>
    </div>

    <!-- Month Summary Card -->
    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Total Pengeluaran Bulan Ini</p>
            <h3 class="text-3xl font-black text-red-600">Rp {{ number_format($totalMonthExpenses, 0, ',', '.') }}</h3>
        </div>
        <div class="p-3.5 bg-red-50 text-red-600 rounded-2xl border border-red-200 shrink-0">
            <svg class="w-7 h-7 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="bg-white border border-stone-200 rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-stone-100 text-stone-700 text-xs font-extrabold uppercase tracking-wider border-b border-stone-200">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Keterangan Biaya</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Nominal (Rp)</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @forelse($expenses as $exp)
                    <tr class="hover:bg-stone-50 transition-colors">
                        <td class="px-6 py-4 text-xs font-bold text-slate-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <p class="font-extrabold text-[#1C1917] text-sm">{{ $exp->title }}</p>
                            @if($exp->notes)
                                <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $exp->notes }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-extrabold uppercase bg-stone-100 text-stone-700 border border-stone-300">
                                {{ str_replace('_', ' ', $exp->category) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600 font-semibold">{{ $exp->date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm font-black text-red-600">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('supervisor.expenses.destroy', $exp) }}" method="POST" onsubmit="return showConfirm(event, 'Apakah Anda yakin ingin menghapus catatan pengeluaran ini?', 'Hapus Pengeluaran', 'Ya, Hapus')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-white text-xs font-bold px-3 py-1 bg-red-50 hover:bg-red-600 rounded-xl border border-red-200 transition-colors cursor-pointer">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500 text-sm font-medium">Belum ada data pengeluaran operasional.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($expenses->hasPages())
        <div class="p-4 border-t border-stone-200">
            {{ $expenses->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
