@extends('layouts.app')
@section('title', 'Ketersediaan Stok Menu Dapur')
@section('page-title', 'Kontrol Ketersediaan Menu (Sold Out Toggle)')

@section('content')
<div class="space-y-6 animate-fade-in-up">

    <!-- Header info banner -->
    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-black text-[#8C0000] flex items-center gap-2">
                <svg class="w-6 h-6 text-[#8C0000]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>Kontrol Stok Sold-Out Dapur</span>
            </h2>
            <p class="text-slate-600 text-xs font-medium mt-0.5">Ubah status ketersediaan menu secara instant dari dapur jika stok bahan habis. Kasir & QR pelanggan akan otomatis mengikuti status ini.</p>
        </div>
        <a href="{{ route('koki.kitchen') }}" class="bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold px-4 py-2 rounded-xl border border-stone-300 transition-all flex items-center gap-1.5">
            <svg class="w-4 h-4 text-stone-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            Kembali ke Layar Dapur
        </a>
    </div>

    <!-- Menu Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($menus as $menu)
        <div class="bg-white border {{ $menu->status === 'available' ? 'border-stone-200' : 'border-red-300 bg-red-50/50' }} rounded-3xl p-5 shadow-sm flex flex-col justify-between transition-all">
            <div class="space-y-3">
                <div class="flex justify-between items-start">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-[#BD2000] bg-[#BD2000]/10 px-2.5 py-0.5 rounded-full border border-[#BD2000]/20">
                        {{ $menu->category->name ?? 'Menu' }}
                    </span>
                    @if($menu->status === 'available')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Tersedia
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                            <svg class="w-3 h-3 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Habis (Sold Out)
                        </span>
                    @endif
                </div>

                <div>
                    <h3 class="text-lg font-black text-[#1C1917] leading-snug">{{ $menu->name }}</h3>
                    <p class="text-xs font-extrabold text-[#BD2000] mt-1">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="pt-5 border-t border-stone-200 mt-4">
                <form action="{{ route('koki.menu-status.toggle', $menu) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    @if($menu->status === 'available')
                        <button type="submit" class="w-full bg-red-100 hover:bg-red-600 text-red-700 hover:text-white font-extrabold py-2.5 px-4 rounded-xl border border-red-200 transition-all text-xs flex justify-center items-center gap-2 cursor-pointer">
                            <span>Tandai Sebagai HABIS (Sold Out)</span>
                        </button>
                    @else
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-2.5 px-4 rounded-xl shadow-md transition-all text-xs flex justify-center items-center gap-2 cursor-pointer">
                            <span>Tandai TERSEDIA Kembali</span>
                        </button>
                    @endif
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
