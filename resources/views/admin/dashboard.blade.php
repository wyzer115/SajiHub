@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Cabang')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-white border border-stone-200 rounded-3xl p-6 shadow-sm animate-fade-in-up">
        <div>
            <h2 class="text-xl font-black text-[#8C0000]">{{ auth()->user()->branch->name ?? 'Cabang Utama' }}</h2>
            <p class="text-slate-600 text-sm mt-1 font-medium">{{ auth()->user()->branch->address ?? 'Alamat Cabang' }}</p>
        </div>
        <div class="text-right">
            <span class="text-slate-500 font-extrabold text-xs uppercase">{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-6 animate-fade-in-up">
        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Pendapatan Hari Ini</p>
                <h3 class="text-2xl font-black text-emerald-600">Rp {{ number_format($todayRevenue ?? 0, 0, ',', '.') }}</h3>
            </div>
            <div class="p-3.5 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-200 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Pesanan Hari Ini</p>
                <h3 class="text-2xl font-black text-[#1C1917]">{{ $todayOrders ?? 0 }}</h3>
            </div>
            <div class="p-3.5 bg-blue-50 text-blue-600 rounded-2xl border border-blue-200 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
        </div>

        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Pesanan Aktif</p>
                <h3 class="text-2xl font-black text-[#BD2000]">{{ $activeOrders ?? 0 }}</h3>
            </div>
            <div class="p-3.5 bg-orange-50 text-orange-600 rounded-2xl border border-orange-200 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path></svg>
            </div>
        </div>

        <a href="{{ route('admin.menus.index') }}" class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all flex items-center justify-between group cursor-pointer">
            <div>
                <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Total Menu</p>
                <h3 class="text-2xl font-black text-purple-600 group-hover:text-[#BD2000] transition-colors">{{ $totalMenus ?? 0 }}</h3>
                <p class="text-[11px] text-[#BD2000] font-bold mt-1 flex items-center gap-0.5">Kelola di Halaman Menu →</p>
            </div>
            <div class="p-3.5 bg-purple-50 text-purple-600 group-hover:bg-[#BD2000]/10 group-hover:text-[#BD2000] rounded-2xl border border-purple-200 group-hover:border-[#BD2000]/30 shrink-0 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
        </a>
    </div>

    <!-- Sales Trend Visual Chart Section -->
    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm animate-fade-in-up">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-black text-[#8C0000] flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#BD2000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    <span>Tren Penjualan & Transaksi Harian (7 Hari Terakhir)</span>
                </h3>
                <p class="text-slate-600 text-xs font-medium mt-0.5">Analisis pendapatan dan volume transaksi cabang harian</p>
            </div>
        </div>
        <div class="h-64">
            <canvas id="adminSalesChart"></canvas>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6 animate-fade-in-up">
        <!-- Recent Orders -->
        <div class="lg:col-span-2 bg-white border border-stone-200 rounded-3xl overflow-hidden flex flex-col shadow-sm">
            <div class="p-6 border-b border-stone-200 bg-stone-50 flex justify-between items-center">
                <h3 class="text-lg font-black text-[#8C0000]">Pesanan Terbaru</h3>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left">
                    <thead class="bg-stone-100 text-stone-700 text-xs font-extrabold uppercase tracking-wider border-b border-stone-200">
                        <tr>
                            <th class="px-6 py-4">Pelanggan</th>
                            <th class="px-6 py-4">Meja</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Status Pesanan</th>
                            <th class="px-6 py-4">Status Bayar</th>
                            <th class="px-6 py-4">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200">
                        @forelse($recentOrders ?? [] as $order)
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="px-6 py-4 text-[#1C1917] font-extrabold text-sm">{{ $order->customer_name }}</td>
                            <td class="px-6 py-4 text-slate-700 font-bold">{{ $order->table ? $order->table->table_number : 'Takeaway' }}</td>
                            <td class="px-6 py-4 text-[#BD2000] font-black text-sm">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                @if($order->order_status == 'pending')
                                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800 border border-amber-300">Menunggu</span>
                                @elseif($order->order_status == 'cooking')
                                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-orange-100 text-orange-800 border border-orange-300">Dimasak</span>
                                @elseif($order->order_status == 'served')
                                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-blue-100 text-blue-800 border border-blue-300">Disajikan</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">Selesai</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($order->payment_status == 'paid')
                                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">Lunas</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-red-100 text-red-600 border border-red-200">Belum Bayar</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-xs font-semibold whitespace-nowrap">{{ $order->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500 font-medium">Belum ada pesanan terbaru</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3 Top Selling Menus (3 Menu Terlaris) -->
        <div class="lg:col-span-1 bg-white border border-stone-200 rounded-3xl flex flex-col shadow-sm">
            <div class="p-6 border-b border-stone-200 bg-stone-50 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-black text-[#8C0000] flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#8C0000]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                        <span>3 Menu Terlaris</span>
                    </h3>
                    <p class="text-xs text-slate-500 font-medium">Paling sering dipesan secara otomatis</p>
                </div>
                <a href="{{ route('admin.menus.index') }}" class="text-xs font-extrabold text-[#BD2000] hover:underline shrink-0">
                    Kelola Menu →
                </a>
            </div>
            <div class="p-6 flex-1 space-y-4">
                @forelse($popularMenus ?? [] as $index => $menu)
                <div class="flex items-center space-x-3.5 p-3.5 bg-stone-50 rounded-2xl border border-stone-200 hover:border-[#BD2000]/30 transition-all">
                    <div class="w-12 h-12 rounded-xl overflow-hidden border border-stone-200 shrink-0 relative bg-white flex items-center justify-center">
                        @if($menu->image)
                            <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-stone-100 text-[#BD2000] font-black text-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                        @endif
                        <span class="absolute top-0 left-0 bg-[#BD2000] text-white text-[10px] font-black px-1.5 py-0.5 rounded-br-lg shadow-xs">
                            #{{ $index + 1 }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[#1C1917] font-extrabold truncate text-sm leading-tight">{{ $menu->name }}</p>
                        <p class="text-slate-500 text-xs truncate font-medium mt-0.5">{{ $menu->category->name ?? 'Tanpa Kategori' }} · <span class="text-[#BD2000] font-bold">Rp {{ number_format($menu->price, 0, ',', '.') }}</span></p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-extrabold bg-[#BD2000]/10 text-[#BD2000] border border-[#BD2000]/20">
                            {{ $menu->count ?? 0 }} pesanan
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-slate-500 font-medium text-sm">
                    Belum ada data menu terlaris
                </div>
                @endforelse
            </div>
            <div class="p-4 border-t border-stone-200 text-center bg-stone-50/50 rounded-b-3xl">
                <a href="{{ route('admin.menus.index') }}" class="inline-flex items-center gap-1.5 text-xs font-extrabold text-[#BD2000] hover:text-[#8C0000]">
                    Lihat Seluruh Halaman Menu ({{ $totalMenus ?? 0 }} Menu) →
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctxSales = document.getElementById('adminSalesChart').getContext('2d');
        new Chart(ctxSales, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartDates ?? []) !!},
                datasets: [
                    {
                        type: 'line',
                        label: 'Pendapatan (Rp)',
                        data: {!! json_encode($chartRevenues ?? []) !!},
                        borderColor: '#bd2000',
                        backgroundColor: 'rgba(189, 32, 0, 0.1)',
                        fill: true,
                        tension: 0.35,
                        yAxisID: 'yRev',
                        borderWidth: 2.5,
                    },
                    {
                        type: 'bar',
                        label: 'Jumlah Transaksi',
                        data: {!! json_encode($chartOrders ?? []) !!},
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderRadius: 6,
                        yAxisID: 'yOrd',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#334155', font: { family: 'Outfit', size: 12, weight: 'bold' } }
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { color: '#475569', font: { weight: 'bold' } }
                    },
                    yRev: {
                        type: 'linear',
                        position: 'left',
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: {
                            color: '#475569',
                            font: { weight: 'bold' },
                            callback: function(val) { return 'Rp ' + (val/1000).toLocaleString('id-ID') + 'k'; }
                        }
                    },
                    yOrd: {
                        type: 'linear',
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: { color: '#475569', font: { weight: 'bold' }, precision: 0 }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
