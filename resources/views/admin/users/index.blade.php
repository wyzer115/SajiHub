@extends('layouts.app')
@section('title', 'Manajemen Karyawan')
@section('page-title', 'Kelola Karyawan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 animate-fade-in-up">
        <div>
            <h2 class="text-xl font-black text-[#8C0000]">Daftar Staf Cabang</h2>
            <p class="text-sm text-slate-600 font-medium mt-1">Kelola akun Kasir, Dapur (Koki), dan Waiter untuk cabang tempat Anda ditugaskan.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 bg-[#BD2000] hover:bg-[#8C0000] text-white px-5 py-2.5 rounded-xl font-extrabold transition-all shadow-md whitespace-nowrap cursor-pointer">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            Tambah Staf Baru
        </a>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-3xl border border-stone-200 overflow-hidden shadow-sm animate-fade-in-up">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-100 border-b border-stone-200 text-stone-700 text-xs font-extrabold uppercase tracking-wider">
                        <th class="px-6 py-4">Nama Karyawan</th>
                        <th class="px-6 py-4">Username</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Peran (Role)</th>
                        <th class="px-6 py-4">Dibuat Pada</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @forelse($users ?? [] as $staf)
                    <tr class="hover:bg-stone-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-2xl bg-[#BD2000]/10 text-[#BD2000] flex items-center justify-center font-black text-sm border border-[#BD2000]/20">
                                    {{ substr($staf->name, 0, 1) }}
                                </div>
                                <div class="text-sm font-extrabold text-[#1C1917]">{{ $staf->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700 font-mono font-bold">{{ $staf->username }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600 font-semibold">{{ $staf->email }}</td>
                        <td class="px-6 py-4">
                            @if($staf->role == 'kasir')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    Kasir (Front Office)
                                </span>
                            @elseif($staf->role == 'koki' || $staf->role == 'dapur')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                    <svg class="w-3.5 h-3.5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    Dapur / Kitchen
                                </span>
                            @elseif($staf->role == 'waiter')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Waiter / Pelayan
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 font-semibold">{{ $staf->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users.edit', $staf->id) }}" class="p-2 bg-stone-100 hover:bg-[#BD2000] text-stone-700 hover:text-white rounded-xl transition-colors border border-stone-200" title="Edit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.users.destroy', $staf->id) }}" method="POST" onsubmit="return showConfirm(event, 'Apakah Anda yakin ingin menghapus akun karyawan ini?', 'Hapus Karyawan', 'Ya, Hapus');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-stone-100 hover:bg-red-600 text-stone-700 hover:text-white rounded-xl transition-colors border border-stone-200 cursor-pointer" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500 font-medium">Belum ada akun karyawan yang terdaftar untuk cabang ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
