@extends('layouts.app')
@section('title', 'Manajemen Meja')
@section('page-title', 'Meja & QR Code')

@php
    $routePrefix = auth()->user()->isKasir() ? 'kasir' : 'admin';
@endphp

@section('content')
<div class="space-y-6">
    <!-- Form Tambah Meja Baru -->
    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm animate-fade-in-up">
        <h3 class="text-base font-black text-[#8C0000] uppercase tracking-wider mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#BD2000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Meja Baru</span>
        </h3>
        <form action="{{ route($routePrefix . '.tables.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end max-w-4xl">
            @csrf
            <div>
                <label for="table_number" class="block text-stone-700 text-xs font-bold uppercase tracking-wider mb-2">Nomor / Nama Meja *</label>
                <input type="text" name="table_number" id="table_number" required placeholder="Contoh: Meja 9, VIP-1..."
                    class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all">
            </div>
            <div>
                <label for="capacity" class="block text-stone-700 text-xs font-bold uppercase tracking-wider mb-2">Kapasitas Kursi (Orang) *</label>
                <input type="number" name="capacity" id="capacity" required min="1" max="100" value="4" placeholder="Jumlah orang (misal: 4)..."
                    class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all">
            </div>
            <div>
                <button type="submit" class="w-full bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold py-3 px-6 rounded-xl transition-all shadow-md cursor-pointer">
                    + Tambah Meja
                </button>
            </div>
        </form>
        @error('table_number')
            <p class="text-red-600 text-xs mt-2 font-bold">{{ $message }}</p>
        @enderror
        @error('capacity')
            <p class="text-red-600 text-xs mt-2 font-bold">{{ $message }}</p>
        @enderror
    </div>

    <!-- Grid Meja -->
    <div class="grid lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 gap-4 animate-fade-in-up">
        @forelse($tables ?? [] as $table)
        <div class="bg-white border {{ $table->status == 'empty' ? 'border-emerald-300 bg-emerald-50/20' : 'border-red-200 bg-red-50/20' }} rounded-3xl p-5 flex flex-col relative overflow-hidden group shadow-sm">
            
            <!-- Top Status Badges -->
            <div class="flex items-center justify-between mb-3">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-stone-100 text-stone-700 border border-stone-300">
                    <svg class="w-3.5 h-3.5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span>{{ $table->capacity ?? 4 }} Kursi</span>
                </span>
                @if($table->status == 'empty')
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-300">Kosong</span>
                @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-red-100 text-red-600 border border-red-200">Terisi</span>
                @endif
            </div>

            <!-- Table Info -->
            <div class="mb-3">
                <p class="text-stone-500 text-[10px] font-bold uppercase tracking-wider mb-0.5">Nama Meja</p>
                <h3 class="text-2xl font-black text-[#1C1917] truncate">{{ $table->table_number }}</h3>
            </div>

            <!-- QR Code Visual -->
            <div class="mb-3 flex flex-col items-center justify-center p-3 bg-stone-50 rounded-2xl border border-stone-200">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('order.qr', ['branch_id' => $table->branch_id, 'table' => $table->table_number])) }}" class="w-24 h-24 bg-white p-1 rounded-xl shadow-xs border border-stone-200 mb-2 animate-fade-in" alt="QR {{ $table->table_number }}">
                <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data={{ urlencode(route('order.qr', ['branch_id' => $table->branch_id, 'table' => $table->table_number])) }}" target="_blank" class="text-[10px] text-[#BD2000] hover:underline font-extrabold uppercase tracking-wider flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span>Cetak / Buka QR</span>
                </a>
            </div>

            <div class="bg-stone-50 rounded-2xl p-2.5 mb-3 border border-stone-200">
                <p class="text-stone-500 text-[10px] font-bold uppercase mb-1">QR Token</p>
                <div class="flex items-center justify-between">
                    <code class="text-[#1C1917] text-xs font-mono font-bold truncate mr-2">{{ Str::limit($table->qr_code_token, 14) }}</code>
                    <form action="{{ route($routePrefix . '.tables.regenerate-qr', $table) }}" method="POST">
                        @csrf
                        <button type="submit" title="Regenerate QR Token" class="text-[#BD2000] hover:text-[#8C0000] p-1 bg-[#BD2000]/10 rounded-lg transition-colors cursor-pointer border border-[#BD2000]/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Card Action Controls (Kosongkan, Edit & Delete) -->
            <div class="space-y-2 mt-auto">
                @if($table->status == 'occupied')
                    <form action="{{ route($routePrefix . '.tables.update', $table) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="empty">
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-2 px-3 rounded-xl transition-all text-xs uppercase tracking-wider flex items-center justify-center gap-1.5 cursor-pointer shadow-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>Kosongkan Meja Ini</span>
                        </button>
                    </form>
                @endif
                <div class="flex items-center gap-2">
                    <button type="button" onclick="openEditModal({{ $table->id }}, '{{ addslashes($table->table_number) }}', {{ $table->capacity ?? 4 }}, '{{ $table->status }}')"
                        class="flex-1 bg-stone-100 hover:bg-stone-200 text-stone-700 border border-stone-300 px-3 py-2 rounded-xl transition-all text-xs font-bold uppercase tracking-wider flex items-center justify-center gap-1.5 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <span>Edit Meja</span>
                    </button>

                    <form action="{{ route($routePrefix . '.tables.destroy', $table) }}" method="POST" onsubmit="return showConfirm(event, 'Apakah Anda yakin ingin menghapus meja ini?', 'Hapus Meja', 'Ya, Hapus');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Hapus Meja" class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-3 py-2 rounded-xl transition-all flex items-center justify-center cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center bg-white border border-stone-200 rounded-3xl">
            <svg class="w-16 h-16 text-stone-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            <p class="text-slate-500 font-medium mb-2">Belum ada meja yang terdaftar</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Edit Meja -->
<div id="edit-table-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-200">
    <div class="w-full max-w-md bg-white border border-stone-200 rounded-3xl p-6 shadow-2xl space-y-6">
        <div class="flex justify-between items-center border-b border-stone-200 pb-4">
            <h3 class="text-lg font-black text-[#8C0000] uppercase tracking-wider flex items-center gap-2">
                <svg class="w-5 h-5 text-[#BD2000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <span>Edit Data Meja</span>
            </h3>
            <button type="button" onclick="closeEditModal()" class="text-stone-400 hover:text-stone-700 bg-stone-100 p-2 rounded-full border border-stone-200 transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="edit-table-form" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label for="edit_table_number" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Nomor / Nama Meja</label>
                <input type="text" name="table_number" id="edit_table_number" required class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none">
            </div>

            <div>
                <label for="edit_capacity" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Kapasitas Kursi (Orang)</label>
                <input type="number" name="capacity" id="edit_capacity" required min="1" max="100" class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none">
            </div>

            <div>
                <label for="edit_status" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Status Meja</label>
                <select name="status" id="edit_status" required class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none">
                    <option value="empty">Kosong</option>
                    <option value="occupied">Terisi</option>
                </select>
            </div>

            <div class="pt-4 border-t border-stone-200 flex items-center justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-xl bg-stone-100 text-stone-700 border border-stone-300 font-bold text-xs uppercase hover:bg-stone-200 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold text-xs uppercase shadow-md transition-all cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const currentRoutePrefix = '{{ $routePrefix }}';

    function openEditModal(tableId, tableNumber, capacity, status) {
        const modal = document.getElementById('edit-table-modal');
        const form = document.getElementById('edit-table-form');
        const inputNumber = document.getElementById('edit_table_number');
        const inputCapacity = document.getElementById('edit_capacity');
        const inputStatus = document.getElementById('edit_status');

        form.action = `/${currentRoutePrefix}/tables/${tableId}`;
        inputNumber.value = tableNumber;
        inputCapacity.value = capacity;
        inputStatus.value = status;

        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.classList.add('opacity-100');
    }

    function closeEditModal() {
        const modal = document.getElementById('edit-table-modal');
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0', 'pointer-events-none');
    }
</script>
@endsection
