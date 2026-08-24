@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Cabang')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-dark-900 border border-dark-700 rounded-2xl p-6 shadow-sm animate-fade-in-up">
        <div>
            <h2 class="text-xl font-bold text-white">{{ auth()->user()->branch->name ?? 'Cabang Utama' }}</h2>
            <p class="text-dark-300 text-sm mt-1">{{ auth()->user()->branch->address ?? 'Alamat Cabang' }}</p>
        </div>
        <div class="text-right">
            <span class="text-dark-400 text-sm">{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-6 animate-fade-in-up" style="animation-delay: 100ms;">
        <div class="bg-dark-900 border border-dark-700 rounded-2xl p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-green-500/10 rounded-full blur-xl group-hover:bg-green-500/20 transition-all"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-dark-300 text-sm font-medium mb-1">Pendapatan Hari Ini</p>
                    <h3 class="text-2xl font-bold text-white">Rp {{ number_format($todayRevenue ?? 0, 0, ',', '.') }}</h3>
                </div>
                <div class="p-3 bg-green-500/10 text-green-400 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-dark-900 border border-dark-700 rounded-2xl p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-blue-500/10 rounded-full blur-xl group-hover:bg-blue-500/20 transition-all"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-dark-300 text-sm font-medium mb-1">Pesanan Hari Ini</p>
                    <h3 class="text-2xl font-bold text-white">{{ $todayOrders ?? 0 }}</h3>
                </div>
                <div class="p-3 bg-blue-500/10 text-blue-400 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-dark-900 border border-dark-700 rounded-2xl p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-orange-500/10 rounded-full blur-xl group-hover:bg-orange-500/20 transition-all animate-pulse"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-dark-300 text-sm font-medium mb-1">Pesanan Aktif</p>
                    <h3 class="text-2xl font-bold text-white">{{ $activeOrders ?? 0 }}</h3>
                </div>
                <div class="p-3 bg-orange-500/10 text-orange-400 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-dark-900 border border-dark-700 rounded-2xl p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-purple-500/10 rounded-full blur-xl group-hover:bg-purple-500/20 transition-all"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-dark-300 text-sm font-medium mb-1">Total Menu</p>
                    <h3 class="text-2xl font-bold text-white">{{ $totalMenus ?? 0 }}</h3>
                </div>
                <div class="p-3 bg-purple-500/10 text-purple-400 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6 animate-fade-in-up" style="animation-delay: 200ms;">
        <!-- Recent Orders -->
        <div class="lg:col-span-2 bg-dark-900 border border-dark-700 rounded-2xl overflow-hidden flex flex-col">
            <div class="p-6 border-b border-dark-700/50 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-white">Pesanan Terbaru</h3>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left">
                    <thead class="bg-dark-800 text-dark-400 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4 font-medium">Pelanggan</th>
                            <th class="px-6 py-4 font-medium">Meja</th>
                            <th class="px-6 py-4 font-medium">Total</th>
                            <th class="px-6 py-4 font-medium">Status Pesanan</th>
                            <th class="px-6 py-4 font-medium">Status Bayar</th>
                            <th class="px-6 py-4 font-medium">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-700/50">
                        @forelse($recentOrders ?? [] as $order)
                        <tr class="hover:bg-dark-800/50 transition-colors">
                            <td class="px-6 py-4 text-dark-300">{{ $order->customer_name }}</td>
                            <td class="px-6 py-4 text-dark-300">{{ $order->table->table_number ?? '-' }}</td>
                            <td class="px-6 py-4 text-white font-medium">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                @if($order->order_status == 'pending')
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-500/10 text-yellow-400">Menunggu</span>
                                @elseif($order->order_status == 'cooking')
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-orange-500/10 text-orange-400">Dimasak</span>
                                @elseif($order->order_status == 'served')
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400">Disajikan</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-500/10 text-green-400">Selesai</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($order->payment_status == 'paid')
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-500/10 text-green-400">Lunas</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-500/10 text-red-400">Belum Bayar</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-dark-400 text-sm">{{ $order->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-dark-400">Belum ada pesanan terbaru</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Popular Menus -->
        <div class="lg:col-span-1 bg-dark-900 border border-dark-700 rounded-2xl flex flex-col">
            <div class="p-6 border-b border-dark-700/50">
                <h3 class="text-lg font-semibold text-white">Menu Terpopuler</h3>
            </div>
            <div class="p-6 flex-1 space-y-4">
                @forelse($popularMenus ?? [] as $index => $menu)
                <div class="flex items-center space-x-4 p-3 bg-dark-800 rounded-xl border border-dark-700/50">
                    <div class="w-12 h-12 bg-dark-700 rounded-lg flex items-center justify-center text-brand-500 font-bold text-lg">
                        #{{ $index + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white font-medium truncate">{{ $menu->name }}</p>
                        <p class="text-dark-400 text-xs truncate">{{ $menu->category->name ?? 'Tanpa Kategori' }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-brand-500/10 text-brand-400">
                            {{ $menu->count ?? 0 }} pesanan
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-dark-400">
                    Belum ada data menu populer
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
