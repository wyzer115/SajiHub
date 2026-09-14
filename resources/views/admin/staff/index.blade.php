@extends('layouts.app')
@section('title', 'Kelola Staff Cabang - SajiHUB')
@section('page-title', 'Kelola Staff Cabang')

@section('content')

<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8 animate-fade-in-up">
    <div>
        <h2 class="text-2xl font-black text-[#8C0000]">Staff Cabang Saya</h2>
        <p class="text-slate-600 text-sm mt-1 font-medium">Kelola akun Owner, Supervisor, Kasir, dan Dapur untuk cabang <span class="text-[#BD2000] font-extrabold">{{ auth()->user()->branch->name }}</span></p>
    </div>
    <a href="{{ route('admin.staff.create') }}"
       class="inline-flex items-center gap-2 bg-[#BD2000] hover:bg-[#8C0000] text-white px-5 py-2.5 rounded-xl font-extrabold text-sm transition-all shadow-md cursor-pointer">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        Tambah Staff Baru
    </a>
</div>

{{-- Staff Grid --}}
@if($staff->isEmpty())
<div class="bg-white border border-stone-200 rounded-3xl p-12 text-center animate-fade-in-up shadow-sm">
    <div class="w-16 h-16 bg-stone-100 rounded-full flex items-center justify-center mx-auto mb-4 border border-stone-200">
        <svg class="w-8 h-8 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
    </div>
    <p class="text-slate-500 text-sm mb-3 font-medium">Belum ada staff terdaftar di cabang ini.</p>
    <a href="{{ route('admin.staff.create') }}" class="inline-flex items-center gap-2 text-[#BD2000] hover:underline text-sm font-extrabold">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Staff Pertama
    </a>
</div>
@else
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 animate-fade-in-up">
    @foreach($staff as $person)
    <div class="bg-white border border-stone-200 rounded-3xl p-5 hover:shadow-md transition-all shadow-sm group">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-[#1C1917] font-black text-lg flex-shrink-0 border border-stone-200
                    {{ $person->role === 'owner' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $person->role === 'supervisor' ? 'bg-amber-100 text-amber-800' : '' }}
                    {{ $person->role === 'kasir' ? 'bg-emerald-100 text-emerald-800' : '' }}
                    {{ in_array($person->role, ['dapur', 'koki']) ? 'bg-purple-100 text-purple-800' : '' }}">
                    {{ substr($person->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-extrabold text-[#1C1917]">{{ $person->name }}</div>
                    <div class="text-xs text-slate-500 font-mono font-bold">{{ '@' . $person->username }}</div>
                </div>
            </div>
            <div class="flex items-center gap-1.5">
                <a href="{{ route('admin.staff.edit', $person->id) }}"
                   class="p-1.5 bg-stone-100 hover:bg-[#BD2000] text-stone-700 hover:text-white rounded-lg transition-colors border border-stone-200"
                   title="Edit">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                <form action="{{ route('admin.staff.destroy', $person->id) }}" method="POST" onsubmit="return showConfirm(event, 'Apakah Anda yakin ingin menghapus akun staff ini?', 'Hapus Staff', 'Ya, Hapus');">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="p-1.5 bg-stone-100 hover:bg-red-600 text-stone-700 hover:text-white rounded-lg transition-colors border border-stone-200 cursor-pointer"
                            title="Hapus">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </div>
        </div>

        <div class="space-y-2">
            <div class="flex items-center gap-2 text-xs text-slate-600 font-semibold">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span class="truncate">{{ $person->email }}</span>
            </div>
            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Bergabung {{ $person->created_at->format('d M Y') }}</span>
            </div>
        </div>

        <div class="mt-4 pt-3 border-t border-stone-200">
            @if($person->role === 'owner')
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-50/80 border border-blue-200/80 text-xs font-bold text-blue-900">
                    <span class="w-2 h-2 rounded-full bg-blue-600 shrink-0"></span>
                    <span>Owner</span>
                    <span class="text-blue-300 font-normal">•</span>
                    <span class="text-blue-600/90 font-medium">Pemilik Cabang</span>
                </div>
            @elseif($person->role === 'supervisor')
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-amber-50/80 border border-amber-200/80 text-xs font-bold text-amber-900">
                    <span class="w-2 h-2 rounded-full bg-amber-600 shrink-0"></span>
                    <span>Supervisor</span>
                    <span class="text-amber-300 font-normal">•</span>
                    <span class="text-amber-600/90 font-medium">Stok & Alat</span>
                </div>
            @elseif($person->role === 'kasir')
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-50/80 border border-emerald-200/80 text-xs font-bold text-emerald-900">
                    <span class="w-2 h-2 rounded-full bg-emerald-600 shrink-0"></span>
                    <span>Kasir</span>
                    <span class="text-emerald-300 font-normal">•</span>
                    <span class="text-emerald-600/90 font-medium">POS Front Office</span>
                </div>
            @else
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-stone-100 border border-stone-200 text-xs font-bold text-stone-800">
                    <span class="w-2 h-2 rounded-full bg-stone-600 shrink-0"></span>
                    <span>Koki Dapur</span>
                    <span class="text-stone-300 font-normal">•</span>
                    <span class="text-stone-600 font-medium">Produksi</span>
                </div>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection
