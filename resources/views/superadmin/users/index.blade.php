@extends('layouts.app')
@section('title', 'Kelola Akun Pengguna - SajiHUB')
@section('page-title', 'Manajemen Akun')

@section('content')

<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8 animate-fade-in-up">
    <div>
        <h2 class="text-2xl font-bold text-white">Manajemen Akun</h2>
        <p class="text-dark-400 text-sm mt-1">Kelola akun Admin Cabang, Kasir, dan Koki di seluruh cabang</p>
    </div>
    <a href="{{ route('superadmin.users.create') }}"
       class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-all shadow-lg shadow-brand-500/20 hover:scale-105">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Akun Baru
    </a>
</div>

{{-- Filters --}}
<div class="bg-dark-900 border border-dark-700 rounded-2xl p-5 mb-6 animate-fade-in-up">
    <form method="GET" action="{{ route('superadmin.users.index') }}" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[160px]">
            <label class="block text-xs font-medium text-dark-400 mb-1.5">Filter Cabang</label>
            <select name="branch_id" class="w-full bg-dark-800 border border-dark-600 text-dark-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-brand-500">
                <option value="">Semua Cabang</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-[140px]">
            <label class="block text-xs font-medium text-dark-400 mb-1.5">Filter Role</label>
            <select name="role" class="w-full bg-dark-800 border border-dark-600 text-dark-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-brand-500">
                <option value="">Semua Role</option>
                <option value="admin_cabang" {{ request('role') == 'admin_cabang' ? 'selected' : '' }}>Admin Cabang</option>
                <option value="kasir" {{ request('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                <option value="koki" {{ request('role') == 'koki' ? 'selected' : '' }}>Koki / Dapur</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-brand-500 hover:bg-brand-600 text-white rounded-lg text-sm font-medium transition-colors">Terapkan</button>
            <a href="{{ route('superadmin.users.index') }}" class="px-4 py-2 bg-dark-700 hover:bg-dark-600 text-dark-200 rounded-lg text-sm font-medium transition-colors">Reset</a>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="bg-dark-900 rounded-2xl border border-dark-700 overflow-hidden animate-fade-in-up shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-dark-800 border-b border-dark-700">
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Pengguna</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Username</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Cabang</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Bergabung</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-700/50">
                @forelse($users as $user)
                <tr class="hover:bg-dark-800/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-dark-700 border border-dark-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-white">{{ $user->name }}</div>
                                <div class="text-xs text-dark-400">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-dark-300 font-mono">@{{ $user->username }}</td>
                    <td class="px-6 py-4">
                        @if($user->role === 'admin_cabang')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">Admin Cabang</span>
                        @elseif($user->role === 'kasir')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20">Kasir</span>
                        @elseif($user->role === 'koki')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-500/10 text-purple-400 border border-purple-500/20">Koki / Dapur</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-dark-300">{{ $user->branch->name ?? '<span class="text-dark-500 italic">Tidak ada</span>' }}</div>
                    </td>
                    <td class="px-6 py-4 text-xs text-dark-400">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('superadmin.users.edit', $user->id) }}"
                               class="p-1.5 bg-dark-700 hover:bg-brand-500 text-dark-200 hover:text-white rounded-lg transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST" data-confirm-delete>
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 bg-dark-700 hover:bg-red-500/20 text-dark-200 hover:text-red-400 rounded-lg transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 bg-dark-800 rounded-full flex items-center justify-center">
                                <svg class="w-7 h-7 text-dark-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <p class="text-dark-400 text-sm">Belum ada data pengguna.</p>
                            <a href="{{ route('superadmin.users.create') }}" class="text-brand-500 hover:text-brand-400 text-sm font-medium">+ Tambah sekarang</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-dark-700">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection
