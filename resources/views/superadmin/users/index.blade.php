@extends('layouts.app')
@section('title', 'Manajemen Admin Cabang')
@section('page-title', 'Kelola Admin Cabang')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 animate-fade-in-up">
        <div>
            <h2 class="text-lg font-semibold text-white">Daftar Penanggung Jawab Cabang</h2>
            <p class="text-sm text-dark-400 mt-1">Superadmin dapat membuat dan mengelola akun Admin Cabang untuk masing-masing cabang restoran.</p>
        </div>
        <a href="{{ route('superadmin.users.create') }}" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white px-4 py-2.5 rounded-xl font-medium transition-colors shadow-lg shadow-brand-500/20 whitespace-nowrap">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            Tambah Admin Baru
        </a>
    </div>

    <!-- Users Table -->
    <div class="bg-dark-900 rounded-2xl border border-dark-700 overflow-hidden shadow-sm animate-fade-in-up" style="animation-delay: 100ms;">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-dark-800 border-b border-dark-700">
                        <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Nama Admin</th>
                        <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Username</th>
                        <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Penempatan Cabang</th>
                        <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Dibuat Pada</th>
                        <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-700/50">
                    @forelse($users ?? [] as $admin)
                    <tr class="hover:bg-dark-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-brand-500/10 text-brand-400 flex items-center justify-center font-bold text-sm">
                                    {{ substr($admin->name, 0, 1) }}
                                </div>
                                <div class="text-sm font-bold text-white">{{ $admin->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-dark-300 font-mono">{{ $admin->username }}</td>
                        <td class="px-6 py-4 text-sm text-dark-300">{{ $admin->email }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                {{ $admin->branch->name ?? 'Belum Ditugaskan' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-dark-400">{{ $admin->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('superadmin.users.edit', $admin->id) }}" class="p-1.5 bg-dark-700 hover:bg-brand-500 text-dark-200 hover:text-white rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('superadmin.users.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun admin ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 bg-dark-700 hover:bg-red-500/20 text-dark-200 hover:text-red-400 rounded-lg transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-dark-400">Belum ada akun Admin Cabang yang terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
