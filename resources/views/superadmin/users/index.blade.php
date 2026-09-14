@extends('layouts.app')

@section('title', 'Kelola Admin Cabang - SajiHUB')
@section('page-title', 'Kelola Admin Cabang')

@section('content')

<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8 animate-fade-in-up">
    <div>
        <h2 class="text-2xl font-black text-[#8C0000]">Kelola Admin Cabang</h2>
        <p class="text-slate-600 text-sm mt-1 font-medium">Daftar penanggung jawab / manager cabang restoran SajiHUB</p>
    </div>
    <a href="{{ route('superadmin.users.create') }}"
       class="inline-flex items-center gap-2 bg-[#BD2000] hover:bg-[#8C0000] text-white px-5 py-2.5 rounded-xl font-extrabold text-sm transition-all shadow-md cursor-pointer">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        Tambah Admin Cabang Baru
    </a>
</div>

{{-- Filters --}}
<div class="bg-white border border-stone-200 rounded-3xl p-5 mb-6 animate-fade-in-up shadow-sm">
    <form method="GET" action="{{ route('superadmin.users.index') }}" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[200px] max-w-xs">
            <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Filter Cabang</label>
            <select name="branch_id" class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#BD2000]">
                <option value="">Semua Cabang</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-[#BD2000] hover:bg-[#8C0000] text-white rounded-xl text-sm font-extrabold transition-colors shadow-md cursor-pointer">Terapkan</button>
            <a href="{{ route('superadmin.users.index') }}" class="px-4 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 rounded-xl text-sm font-bold border border-stone-300 transition-colors">Reset</a>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-3xl border border-stone-200 overflow-hidden animate-fade-in-up shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-stone-100 border-b border-stone-200 text-stone-700 text-xs font-extrabold uppercase tracking-wider">
                    <th class="px-6 py-4">Admin Cabang</th>
                    <th class="px-6 py-4">Username</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Penempatan Cabang</th>
                    <th class="px-6 py-4">Bergabung</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-200">
                @forelse($users as $user)
                <tr class="hover:bg-stone-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-2xl bg-[#BD2000]/10 border border-[#BD2000]/20 flex items-center justify-center text-[#BD2000] font-black text-sm flex-shrink-0">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="text-sm font-extrabold text-[#1C1917]">{{ $user->name }}</div>
                                <div class="text-xs text-slate-500 font-semibold">Admin Cabang / Manager</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-700 font-mono font-bold">@ {{ $user->username }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600 font-semibold">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold bg-stone-100 text-stone-800 border border-stone-200">
                            {{ $user->branch->name ?? 'Belum Ditugaskan' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-xs text-slate-500 font-semibold">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('superadmin.users.edit', $user->id) }}"
                               class="p-2 bg-stone-100 hover:bg-[#BD2000] text-stone-700 hover:text-white rounded-xl transition-colors border border-stone-200" title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST" onsubmit="return showConfirm(event, 'Apakah Anda yakin ingin menghapus akun admin cabang ini?', 'Hapus Admin Cabang', 'Ya, Hapus');">
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
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 bg-stone-100 rounded-full flex items-center justify-center border border-stone-200">
                                <svg class="w-7 h-7 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <p class="text-slate-500 font-medium text-sm">Belum ada akun Admin Cabang yang terdaftar.</p>
                            <a href="{{ route('superadmin.users.create') }}" class="text-[#BD2000] hover:underline text-sm font-extrabold">+ Tambah sekarang</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-stone-200">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection
