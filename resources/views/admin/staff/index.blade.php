@extends('layouts.app')
@section('title', 'Kelola Staff Cabang - SajiHUB')
@section('page-title', 'Kelola Staff Cabang')

@section('content')

<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8 animate-fade-in-up">
    <div>
        <h2 class="text-2xl font-bold text-white">Staff Cabang Saya</h2>
        <p class="text-dark-400 text-sm mt-1">Kelola akun Kasir dan Koki untuk cabang <span class="text-brand-400 font-semibold">{{ auth()->user()->branch->name }}</span></p>
    </div>
    <a href="{{ route('admin.staff.create') }}"
       class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-all shadow-lg shadow-brand-500/20 hover:scale-105">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Staff Baru
    </a>
</div>

{{-- Staff Grid --}}
@if($staff->isEmpty())
<div class="bg-dark-900 border border-dark-700 rounded-2xl p-12 text-center animate-fade-in-up">
    <div class="w-16 h-16 bg-dark-800 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-dark-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
    </div>
    <p class="text-dark-400 text-sm mb-3">Belum ada staff terdaftar di cabang ini.</p>
    <a href="{{ route('admin.staff.create') }}" class="inline-flex items-center gap-2 text-brand-500 hover:text-brand-400 text-sm font-medium">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Staff Pertama
    </a>
</div>
@else
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 animate-fade-in-up">
    @foreach($staff as $member)
    <div class="bg-dark-900 border border-dark-700 rounded-2xl p-5 hover:border-dark-500 transition-all hover:shadow-lg group">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0
                    {{ $member->role === 'kasir' ? 'bg-green-500/20 border-2 border-green-500/30' : 'bg-purple-500/20 border-2 border-purple-500/30' }}">
                    {{ substr($member->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-semibold text-white">{{ $member->name }}</div>
                    <div class="text-xs text-dark-400 font-mono">@{{ $member->username }}</div>
                </div>
            </div>
            <div class="flex items-center gap-1.5">
                <a href="{{ route('admin.staff.edit', $member->id) }}"
                   class="p-1.5 bg-dark-700 hover:bg-brand-500 text-dark-300 hover:text-white rounded-lg transition-colors opacity-0 group-hover:opacity-100"
                   title="Edit">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                <form action="{{ route('admin.staff.destroy', $member->id) }}" method="POST" data-confirm-delete>
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="p-1.5 bg-dark-700 hover:bg-red-500/20 text-dark-300 hover:text-red-400 rounded-lg transition-colors opacity-0 group-hover:opacity-100"
                            title="Hapus">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </div>
        </div>

        <div class="space-y-2">
            <div class="flex items-center gap-2 text-xs text-dark-400">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span class="truncate">{{ $member->email }}</span>
            </div>
            <div class="flex items-center gap-2 text-xs text-dark-400">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Bergabung {{ $member->created_at->format('d M Y') }}</span>
            </div>
        </div>

        <div class="mt-4 pt-3 border-t border-dark-700">
            @if($member->role === 'kasir')
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-500/10 text-green-400 border border-green-500/20">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                    Kasir — Front Office
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-500/10 text-purple-400 border border-purple-500/20">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 1.586l-4 4v12.828l4-4V1.586zM3.707 3.293A1 1 0 002 4v10a1 1 0 00.293.707L6 18.414V5.586L3.707 3.293zm10.586 13.414L18 14.414V4a1 1 0 00-1.707-.707L14 5.586v12.121l.293.293z" clip-rule="evenodd"/></svg>
                    Koki — Dapur
                </span>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection
