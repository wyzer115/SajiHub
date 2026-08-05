@extends('layouts.app')
@section('title', 'Manajemen Meja')
@section('page-title', 'Meja & QR Code')

@section('content')
<div class="space-y-6">
    <!-- Form Tambah Meja -->
    <div class="bg-dark-800 border border-dark-700 rounded-2xl p-6 animate-fade-in-up">
        <form action="{{ route('admin.tables.store') }}" method="POST" class="flex flex-col sm:flex-row gap-4 items-end max-w-2xl">
            @csrf
            <div class="flex-1 w-full">
                <label for="table_number" class="block text-dark-300 text-sm font-medium mb-2">Tambah Meja Baru</label>
                <input type="text" name="table_number" id="table_number" required placeholder="Nomor meja baru (contoh: 01, VIP-1)..."
                    class="w-full bg-dark-900 border border-dark-600 text-white rounded-xl px-4 py-3 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none transition-all">
            </div>
            <button type="submit" class="w-full sm:w-auto bg-brand-500 hover:bg-brand-600 text-white font-semibold px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-brand-500/25 whitespace-nowrap">
                + Tambah Meja
            </button>
        </form>
        @error('table_number')
            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
        @enderror
    </div>

    <!-- Grid Meja -->
    <div class="grid lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 gap-4 animate-fade-in-up" style="animation-delay: 100ms;">
        @forelse($tables ?? [] as $table)
        <div class="bg-dark-900 border {{ $table->status == 'empty' ? 'border-green-500/30' : 'border-red-500/30' }} rounded-2xl p-5 flex flex-col relative overflow-hidden group">
            
            <div class="absolute top-0 right-0 p-3">
                @if($table->status == 'empty')
                    <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider bg-green-500/10 text-green-400">Kosong</span>
                @else
                    <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider bg-red-500/10 text-red-400">Terisi</span>
                @endif
            </div>

            <div class="mb-4">
                <p class="text-dark-400 text-xs font-medium uppercase tracking-wider mb-1">Meja</p>
                <h3 class="text-3xl font-bold text-white">{{ $table->table_number }}</h3>
            </div>

            <div class="bg-dark-800 rounded-xl p-3 mb-4 border border-dark-700/50 flex-1">
                <p class="text-dark-400 text-[10px] uppercase mb-1">QR Token</p>
                <div class="flex items-center justify-between">
                    <code class="text-dark-200 text-xs font-mono truncate mr-2">{{ Str::limit($table->qr_code_token, 15) }}</code>
                    <form action="{{ route('admin.tables.regenerate-qr', $table) }}" method="POST">
                        @csrf
                        <button type="submit" title="Regenerate QR" class="text-brand-400 hover:text-brand-300 p-1 bg-brand-500/10 rounded transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </button>
                    </form>
                </div>
            </div>

            <div class="flex space-x-2 mt-auto">
                <form action="{{ route('admin.tables.update', $table) }}" method="POST" class="flex-1">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ $table->status == 'empty' ? 'occupied' : 'empty' }}">
                    <button type="submit" class="w-full bg-dark-700 hover:bg-dark-600 text-dark-200 px-3 py-2 rounded-xl transition-all text-xs font-medium">
                        Ubah Status
                    </button>
                </form>

                <form action="{{ route('admin.tables.destroy', $table) }}" method="POST" onsubmit="return confirm('Hapus meja ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500/10 text-red-400 hover:bg-red-500/20 px-3 py-2 rounded-xl transition-all flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center bg-dark-900 border border-dark-700 rounded-2xl">
            <svg class="w-16 h-16 text-dark-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            <p class="text-dark-300 mb-2">Belum ada meja yang terdaftar</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
