@extends('layouts.app')

@section('title', 'Manajemen Stok & Inventaris - Supervisor')
@section('page-title', 'Stok & Inventaris Cabang')

@section('content')
<div class="space-y-6 animate-fade-in-up">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-[#8C0000]">Stok Barang & Peralatan</h2>
            <p class="text-slate-600 text-sm mt-1 font-medium">Pantau dan kelola ketersediaan bahan makanan, bahan minuman, serta peralatan di cabang <span class="text-[#BD2000] font-extrabold">{{ auth()->user()->branch->name }}</span></p>
        </div>
        <a href="{{ route('supervisor.inventory.create') }}"
           class="inline-flex items-center gap-2 bg-[#BD2000] hover:bg-[#8C0000] text-white px-5 py-2.5 rounded-xl font-extrabold text-sm transition-all shadow-md cursor-pointer">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Tambah Barang Baru
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white border border-stone-200 rounded-3xl p-5 shadow-sm">
        <form method="GET" action="{{ route('supervisor.inventory.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Cari Nama Barang</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Contoh: Beras, Ayam, Wajan..."
                       class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#BD2000]">
            </div>

            <div class="w-full sm:w-56">
                <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Filter Kategori</label>
                <select name="category" class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#BD2000]">
                    <option value="">Semua Kategori</option>
                    <option value="bahan_makanan" {{ request('category') == 'bahan_makanan' ? 'selected' : '' }}>Bahan Makanan</option>
                    <option value="bahan_minuman" {{ request('category') == 'bahan_minuman' ? 'selected' : '' }}>Bahan Minuman</option>
                    <option value="peralatan" {{ request('category') == 'peralatan' ? 'selected' : '' }}>Peralatan / Alat Masak</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2.5 bg-[#BD2000] hover:bg-[#8C0000] text-white rounded-xl text-sm font-extrabold transition-colors shadow-xs cursor-pointer">Filter</button>
                <a href="{{ route('supervisor.inventory.index') }}" class="px-5 py-2.5 bg-stone-100 hover:bg-stone-200 text-stone-700 border border-stone-300 rounded-xl text-sm font-bold transition-colors cursor-pointer">Reset</a>
            </div>
        </form>
    </div>

    <!-- Inventory Table -->
    <div class="bg-white rounded-3xl border border-stone-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-100 border-b border-stone-200 text-stone-700 text-xs font-extrabold uppercase tracking-wider">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Barang</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Jumlah Stok</th>
                        <th class="px-6 py-4">Min. Stok Warning</th>
                        <th class="px-6 py-4">Harga Est. / Unit</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @forelse($inventories as $item)
                    <tr class="hover:bg-stone-50 transition-colors">
                        <td class="px-6 py-4 text-xs font-bold text-slate-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-extrabold text-[#1C1917]">{{ $item->name }}</div>
                            @if($item->notes)
                                <div class="text-xs text-slate-500 font-medium mt-0.5">{{ $item->notes }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($item->category === 'bahan_makanan')
                                <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">Bahan Makanan</span>
                            @elseif($item->category === 'bahan_minuman')
                                <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold bg-blue-50 text-blue-800 border border-blue-200">Bahan Minuman</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">Peralatan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-base font-black text-[#1C1917]">{{ $item->stock }} <span class="text-xs font-semibold text-slate-500">{{ $item->unit }}</span></div>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600 font-semibold">{{ $item->min_stock }} {{ $item->unit }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700 font-bold">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @if($item->isLowStock())
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
                                    Stok Menipis
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                                    Stok Aman
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('supervisor.inventory.edit', $item->id) }}"
                                   class="p-2 bg-stone-100 hover:bg-[#BD2000] text-stone-700 hover:text-white rounded-xl transition-colors border border-stone-200" title="Edit / Restok">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('supervisor.inventory.destroy', $item->id) }}" method="POST" onsubmit="return showConfirm(event, 'Apakah Anda yakin ingin menghapus data inventaris ini?', 'Hapus Inventaris', 'Ya, Hapus');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 bg-stone-100 hover:bg-red-600 text-stone-700 hover:text-white rounded-xl transition-colors border border-stone-200 cursor-pointer" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-500 font-medium">
                            Belum ada data barang inventaris. Silakan klik <strong>+ Tambah Barang Baru</strong> di atas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($inventories->hasPages())
        <div class="px-6 py-4 border-t border-stone-200">
            {{ $inventories->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
