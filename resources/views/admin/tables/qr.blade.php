@extends('layouts.app')
@section('title', 'QR Code Meja ' . $table->table_number)
@section('page-title', 'QR Code - Meja ' . $table->table_number)

@section('content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in-up">
    <div class="flex justify-between items-center">
        <a href="{{ route('admin.tables.index') }}" class="inline-flex items-center text-slate-600 hover:text-[#BD2000] font-bold text-sm transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Meja
        </a>
        <button onclick="window.print()" class="bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold px-4 py-2 rounded-xl transition-all shadow-md flex items-center cursor-pointer">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak QR Code
        </button>
    </div>

    <!-- Printable Card -->
    <div class="bg-white text-[#1C1917] p-8 rounded-3xl border border-stone-200 text-center shadow-xl space-y-6 print:shadow-none print:border-none print:p-4">
        <div>
            <span class="text-xs font-black uppercase tracking-widest text-[#BD2000] bg-[#BD2000]/10 px-3 py-1 rounded-full border border-[#BD2000]/20">SajiHUB - {{ auth()->user()->branch->name ?? 'Cabang' }}</span>
            <h2 class="text-3xl font-black mt-3 text-[#8C0000]">MEJA {{ $table->table_number }}</h2>
            <p class="text-sm text-slate-600 font-medium mt-1">Pindai kode QR untuk melihat menu & memesan secara langsung</p>
        </div>

        <div class="flex justify-center my-6">
            <div class="p-4 bg-stone-50 border-2 border-stone-300 rounded-3xl shadow-xs inline-block">
                <img src="{{ $qrImageUrl }}" alt="QR Code Meja {{ $table->table_number }}" class="w-64 h-64 mx-auto rounded-xl">
            </div>
        </div>

        <div class="bg-stone-50 p-4 rounded-2xl text-left border border-stone-200 space-y-2">
            <div class="text-xs font-bold text-stone-500 uppercase">URL Tautan Langsung:</div>
            <div class="text-xs font-mono text-[#BD2000] break-all select-all font-bold">{{ $orderUrl }}</div>
        </div>

        <div class="text-xs text-slate-500 font-medium">
            Diproduksi secara otomatis oleh Sistem Manajemen Restoran Multi-Branch SajiHUB
        </div>
    </div>
</div>
@endsection
