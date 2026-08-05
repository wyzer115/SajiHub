@extends('layouts.app')
@section('title', 'Manajemen Menu')
@section('page-title', 'Daftar Menu')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between gap-4 items-start sm:items-center animate-fade-in-up">
        <div class="flex space-x-4 w-full sm:w-auto">
            <input type="text" placeholder="Cari menu..." class="bg-dark-800 border border-dark-600 text-white rounded-xl px-4 py-3 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none transition-all flex-1 sm:w-64">
        </div>
        <a href="{{ route('admin.menus.create') }}" class="bg-brand-500 hover:bg-brand-600 text-white font-semibold px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-brand-500/25 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Menu</span>
        </a>
    </div>

    <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-6 animate-fade-in-up" style="animation-delay: 100ms;">
        @forelse($menus ?? [] as $menu)
        <div class="bg-dark-900 border border-dark-700 rounded-2xl overflow-hidden flex flex-col group">
            <div class="h-48 bg-dark-700 relative flex items-center justify-center">
                @if($menu->image)
                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                @else
                    <svg class="w-16 h-16 text-dark-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path></svg>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-dark-900 via-transparent to-transparent opacity-80"></div>
                <div class="absolute bottom-4 left-4">
                    <span class="text-xs bg-dark-800 text-dark-300 px-2 py-1 rounded-lg border border-dark-600/50">{{ $menu->category->name ?? 'Tanpa Kategori' }}</span>
                </div>
            </div>
            
            <div class="p-5 flex-1 flex flex-col">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-lg font-semibold text-white truncate pr-2">{{ $menu->name }}</h3>
                    @if($menu->status == 'available')
                        <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-500/10 text-green-400 border border-green-500/20 shrink-0">Tersedia</span>
                    @else
                        <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-500/10 text-red-400 border border-red-500/20 shrink-0">Habis</span>
                    @endif
                </div>
                
                <p class="text-brand-400 font-bold text-xl mb-4 mt-auto">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                
                <div class="flex space-x-2 pt-4 border-t border-dark-700/50">
                    <a href="{{ route('admin.menus.edit', $menu) }}" class="flex-1 bg-dark-700 hover:bg-dark-600 text-dark-200 text-center px-4 py-2 rounded-xl transition-all text-sm font-medium">Edit</a>
                    
                    <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus menu ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500/10 text-red-400 hover:bg-red-500/20 px-4 py-2 rounded-xl transition-all flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center bg-dark-900 border border-dark-700 rounded-2xl">
            <svg class="w-16 h-16 text-dark-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            <p class="text-dark-300 mb-4">Belum ada menu yang ditambahkan</p>
            <a href="{{ route('admin.menus.create') }}" class="text-brand-400 hover:text-brand-300 font-medium">Tambah Menu Pertama</a>
        </div>
        @endforelse
    </div>

    @if(isset($menus) && $menus->hasPages())
    <div class="mt-6">
        {{ $menus->links() }}
    </div>
    @endif
</div>
@endsection
