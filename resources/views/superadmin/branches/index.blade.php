@extends('layouts.app')
@section('title', 'Manajemen Cabang')
@section('page-title', 'Manajemen Cabang')

@section('content')
<!-- Header Action -->
<div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6 animate-fade-in-up delay-100">
    <h2 class="text-lg font-semibold text-white">Daftar Semua Cabang</h2>
    <a href="{{ route('superadmin.branches.create') }}" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-xl font-medium transition-colors shadow-lg shadow-brand-500/20">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Cabang Baru
    </a>
</div>

<!-- Branches Table -->
<div class="bg-dark-900 rounded-2xl border border-dark-700 overflow-hidden mb-8 animate-fade-in-up delay-200 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-dark-800 border-b border-dark-700">
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Alamat</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Telepon</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Jumlah Karyawan</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Jumlah Pesanan</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-700/50">
                @forelse($branches ?? [] as $branch)
                <tr class="hover:bg-dark-800/50 transition-colors">
                    <td class="px-6 py-4 text-sm font-medium text-dark-300">BR-{{ str_pad($branch->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-white">{{ $branch->name }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-dark-300 truncate max-w-[200px]">{{ $branch->address }}</td>
                    <td class="px-6 py-4 text-sm text-dark-300">{{ $branch->phone ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-dark-300">{{ $branch->users_count ?? 0 }}</td>
                    <td class="px-6 py-4 text-sm text-dark-300">{{ $branch->orders_count ?? 0 }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('superadmin.branches.edit', $branch->id) }}" class="p-1.5 bg-dark-700 hover:bg-brand-500 text-dark-200 hover:text-white rounded-lg transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('superadmin.branches.destroy', $branch->id) }}" method="POST" class="inline" data-confirm-delete>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 bg-dark-700 hover:bg-red-500 text-dark-200 hover:text-white rounded-lg transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-dark-400">Belum ada data cabang.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
