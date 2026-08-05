@extends('layouts.app')
@section('title', 'Dashboard Super Admin')
@section('page-title', 'Ringkasan Global')

@section('content')

<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card 1 -->
    <div class="bg-dark-800 border border-dark-700 rounded-2xl p-6 hover:bg-dark-800/80 hover:border-dark-600 transition-all duration-300 animate-fade-in-up delay-100 shadow-sm">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm font-medium text-dark-400 mb-1">Total Cabang Aktif</p>
                <h3 class="text-3xl font-bold text-white">{{ $totalBranches ?? 0 }} <span class="text-base font-normal text-dark-500">cabang</span></h3>
            </div>
            <div class="p-3 bg-blue-500/10 text-blue-500 rounded-xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-dark-800 border border-dark-700 rounded-2xl p-6 hover:bg-dark-800/80 hover:border-dark-600 transition-all duration-300 animate-fade-in-up delay-200 shadow-sm">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm font-medium text-dark-400 mb-1">Total Pendapatan</p>
                <h3 class="text-3xl font-bold text-white">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h3>
                @if(isset($revenueGrowth))
                <div class="mt-2 flex items-center text-sm {{ $revenueGrowth >= 0 ? 'text-emerald-500' : 'text-red-500' }}">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $revenueGrowth >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6' }}"></path>
                    </svg>
                    <span>{{ abs($revenueGrowth) }}% dari bulan lalu</span>
                </div>
                @endif
            </div>
            <div class="p-3 bg-emerald-500/10 text-emerald-500 rounded-xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-dark-800 border border-dark-700 rounded-2xl p-6 hover:bg-dark-800/80 hover:border-dark-600 transition-all duration-300 animate-fade-in-up delay-300 shadow-sm">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm font-medium text-dark-400 mb-1">Total Karyawan</p>
                <h3 class="text-3xl font-bold text-white">{{ $totalEmployees ?? 0 }} <span class="text-base font-normal text-dark-500">orang</span></h3>
            </div>
            <div class="p-3 bg-purple-500/10 text-purple-500 rounded-xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="bg-dark-800 border border-dark-700 rounded-2xl p-6 hover:bg-dark-800/80 hover:border-dark-600 transition-all duration-300 animate-fade-in-up delay-400 shadow-sm">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm font-medium text-dark-400 mb-1">Total Pesanan</p>
                <h3 class="text-3xl font-bold text-white">{{ $totalOrders ?? 0 }} <span class="text-base font-normal text-dark-500">pesanan</span></h3>
            </div>
            <div class="p-3 bg-amber-500/10 text-amber-500 rounded-xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
        </div>
    </div>
</div>

<!-- Header Action -->
<div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6 animate-fade-in-up delay-400">
    <h2 class="text-lg font-semibold text-white">Daftar Cabang Aktif</h2>
    <a href="{{ route('superadmin.branches.create') }}" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-xl font-medium transition-colors shadow-lg shadow-brand-500/20">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Cabang Baru
    </a>
</div>

<!-- Branches Table -->
<div class="bg-dark-900 rounded-2xl border border-dark-700 overflow-hidden mb-8 animate-fade-in-up delay-400 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-dark-800 border-b border-dark-700">
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">ID Cabang</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Nama Cabang</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Lokasi</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Admin PJ</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Pesanan</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-700/50">
                @forelse($branches ?? [] as $branch)
                <tr class="hover:bg-dark-800/50 transition-colors">
                    <td class="px-6 py-4 text-sm font-medium text-dark-300">BR-{{ str_pad($branch->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-white">{{ $branch->name }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-dark-300 truncate max-w-[200px]">{{ $branch->address }}</td>
                    <td class="px-6 py-4">
                        @if($branch->status == 'Buka' || true)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                                Buka
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/10 text-blue-500 border border-blue-500/20">
                                Stabil
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-dark-300">
                        {{ $branch->admin->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-dark-300">{{ $branch->orders_count ?? 0 }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button class="p-1.5 bg-dark-700 hover:bg-dark-600 text-dark-200 hover:text-white rounded-lg transition-colors" title="Lihat Detail">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                            <a href="{{ route('superadmin.branches.edit', $branch->id) }}" class="p-1.5 bg-dark-700 hover:bg-brand-500 text-dark-200 hover:text-white rounded-lg transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-dark-400">Belum ada data cabang.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Orders -->
<h2 class="text-lg font-semibold text-white mb-4 animate-fade-in-up delay-400">Pesanan Terakhir</h2>
<div class="bg-dark-900 rounded-2xl border border-dark-700 overflow-hidden animate-fade-in-up delay-400 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-dark-800 border-b border-dark-700">
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">ID Pesanan</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Cabang</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Status Dapur</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Pembayaran</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-700/50">
                @forelse($recentOrders ?? [] as $order)
                <tr class="hover:bg-dark-800/50 transition-colors">
                    <td class="px-6 py-4 text-sm font-medium text-white">#{{ $order->id }}</td>
                    <td class="px-6 py-4 text-sm text-dark-300">{{ $order->branch->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-dark-300">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-white">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @if($order->order_status == 'pending')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-yellow-500/10 text-yellow-500 border border-yellow-500/20">Menunggu</span>
                        @elseif($order->order_status == 'cooking')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-orange-500/10 text-orange-500 border border-orange-500/20">Dimasak</span>
                        @elseif($order->order_status == 'served')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-500/10 text-blue-500 border border-blue-500/20">Disajikan</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">Selesai</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($order->payment_status == 'paid')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">Lunas</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-red-500/10 text-red-500 border border-red-500/20">Belum Bayar</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-xs text-dark-400">{{ $order->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-dark-400">Belum ada pesanan terbaru.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
