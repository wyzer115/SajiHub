@extends('layouts.app')
@section('title', 'QR Code Meja ' . $table->table_number)
@section('page-title', 'QR Code - Meja ' . $table->table_number)

@section('content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in-up">
    <div class="flex justify-between items-center">
        <a href="{{ route('admin.tables.index') }}" class="inline-flex items-center text-dark-300 hover:text-white transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Meja
        </a>
        <button onclick="window.print()" class="bg-brand-500 hover:bg-brand-600 text-white font-semibold px-4 py-2 rounded-xl transition-all shadow-lg shadow-brand-500/20 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak QR Code
        </button>
    </div>

    <!-- Printable Card -->
    <div class="bg-white text-dark-950 p-8 rounded-3xl border border-dark-800 text-center shadow-2xl space-y-6 print:shadow-none print:border-none print:p-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-brand-600 bg-brand-50 px-3 py-1 rounded-full">SajiHUB - {{ auth()->user()->branch->name ?? 'Cabang' }}</span>
            <h2 class="text-3xl font-black mt-3 text-dark-900">MEJA {{ $table->table_number }}</h2>
            <p class="text-sm text-dark-500 mt-1">Pindai kode QR untuk melihat menu & memesan secara langsung</p>
        </div>

        <div class="flex justify-center my-6">
            <div class="p-4 bg-white border-2 border-dark-200 rounded-2xl shadow-inner inline-block">
                <img src="{{ $qrImageUrl }}" alt="QR Code Meja {{ $table->table_number }}" class="w-64 h-64 mx-auto">
            </div>
        </div>

        <div class="bg-dark-50 p-4 rounded-xl text-left border border-dark-200 space-y-2">
            <div class="text-xs font-bold text-dark-500 uppercase">URL Tautan Langsung:</div>
            <div class="text-xs font-mono text-brand-600 break-all select-all font-semibold">{{ $orderUrl }}</div>
        </div>

        <div class="text-xs text-dark-400 font-medium">
            Diproduksi secara otomatis oleh Sistem Manajemen Restoran Multi-Branch SajiHUB
        </div>
    </div>
</div>
@endsection
