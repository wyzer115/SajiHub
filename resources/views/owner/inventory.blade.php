@extends('layouts.app')
@section('title', 'Pemantauan Aset Stok Gudang')
@section('page-title', 'Pemantauan Aset & Stok Gudang')

@section('content')
<div class="space-y-6 animate-fade-in-up">

    <!-- Header & Branch Filter -->
    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-[#8C0000]">Pemantauan Aset Stok Gudang</h2>
            <p class="text-slate-600 text-xs mt-0.5 font-medium">Monitoring nilai valuasi dan stok kritis: <span class="text-[#BD2000] font-extrabold">{{ $selectedBranch ? $selectedBranch->name : 'Semua Cabang (Gabungan)' }}</span></p>
        </div>
        <form method="GET" action="{{ route('owner.inventory') }}" class="flex items-center gap-2">
            <label for="branch_id" class="text-xs font-bold text-stone-600 whitespace-nowrap">Filter Cabang:</label>
            <select name="branch_id" id="branch_id" onchange="this.form.submit()" class="bg-stone-50 border border-stone-300 text-stone-800 text-xs font-bold rounded-xl px-4 py-2 focus:border-[#BD2000] focus:outline-none shadow-sm cursor-pointer">
                <option value="all" {{ ($selectedBranchId == 'all' || !$selectedBranchId) ? 'selected' : '' }}>🌐 Semua Cabang</option>
                @foreach($branches as $b)
                    <option value="{{ $b->id }}" {{ $selectedBranchId == $b->id ? 'selected' : '' }}>🏢 {{ $b->name }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Valuation Header -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Total Nilai Valuasi Aset Stok</p>
                <h3 class="text-3xl font-black text-[#BD2000]">Rp {{ number_format($totalValuation, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-500 font-semibold mt-1">Estimasi total nilai kapital stok di gudang {{ $selectedBranch ? $selectedBranch->name : 'seluruh cabang' }}</p>
            </div>
            <div class="p-3.5 bg-[#BD2000]/10 text-[#BD2000] rounded-2xl border border-[#BD2000]/20 shrink-0">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
        </div>

        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Status Item Kritis</p>
                @if($lowStockItems->count() > 0)
                    <h3 class="text-3xl font-black text-amber-600">{{ $lowStockItems->count() }} <span class="text-sm font-bold text-slate-500">item menipis</span></h3>
                    <p class="inline-flex items-center gap-1 text-xs text-amber-700 font-semibold mt-1">
                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Perlu tindakan restok dari Supervisor
                    </p>
                @else
                    <h3 class="text-3xl font-black text-emerald-600">Aman</h3>
                    <p class="inline-flex items-center gap-1 text-xs text-emerald-700 font-semibold mt-1">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Seluruh bahan dalam batas aman
                    </p>
                @endif
            </div>
            <div class="p-3.5 bg-amber-100 text-amber-700 rounded-2xl border border-amber-300 shrink-0">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Inventory Asset Table -->
    <div class="bg-white border border-stone-200 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-stone-200 flex justify-between items-center bg-stone-50">
            <h3 class="text-base font-black text-[#8C0000]">Rincian Nilai Aset Barang Gudang</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-stone-100 text-stone-700 text-xs font-extrabold uppercase tracking-wider border-b border-stone-200">
                    <tr>
                        <th class="px-6 py-4">Cabang</th>
                        <th class="px-6 py-4">Nama Item</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Stok Saat Ini</th>
                        <th class="px-6 py-4">Harga Satuan (Rp)</th>
                        <th class="px-6 py-4">Subtotal Valuasi (Rp)</th>
                        <th class="px-6 py-4 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @forelse($inventories as $item)
                    @php $subtotal = $item->stock * $item->unit_price; @endphp
                    <tr class="hover:bg-stone-50 transition-colors">
                        <td class="px-6 py-4 text-xs font-bold text-stone-600"><span class="px-2 py-0.5 rounded-md bg-stone-100 border border-stone-200">{{ $item->branch->name ?? '-' }}</span></td>
                        <td class="px-6 py-4 font-extrabold text-[#1C1917] text-sm">{{ $item->name }}</td>
                        <td class="px-6 py-4 text-xs uppercase text-slate-600 font-bold">{{ str_replace('_', ' ', $item->category) }}</td>
                        <td class="px-6 py-4 text-sm font-black text-[#1C1917]">{{ (float)$item->stock }} <span class="text-xs font-semibold text-slate-500">{{ $item->unit }}</span></td>
                        <td class="px-6 py-4 text-sm text-slate-700 font-bold">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm font-black text-[#BD2000]">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">
                            @if($item->isLowStock())
                                <span class="px-3 py-1 rounded-xl text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">Stok Menipis</span>
                            @else
                                <span class="px-3 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">Stok Aman</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-8 text-center text-slate-500 text-sm font-medium">Belum ada barang inventaris.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($inventories->hasPages())
        <div class="px-6 py-4 border-t border-stone-200 bg-stone-50">
            {{ $inventories->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
