@extends('layouts.app')
@section('title', 'Laporan Keuangan Cabang')
@section('page-title', 'Laporan Keuangan - ' . ($branch->name ?? 'Cabang'))

@section('content')
<div class="space-y-8 animate-fade-in-up">
    <!-- Header summary cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-dark-900 border border-dark-700 rounded-2xl p-6 relative overflow-hidden">
            <div class="text-xs font-semibold text-dark-400 uppercase tracking-wider mb-2">Omzet Hari Ini</div>
            <div class="text-2xl font-bold text-emerald-400">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</div>
            <div class="text-xs text-dark-400 mt-2">{{ $todayOrders }} pesanan hari ini</div>
        </div>

        <div class="bg-dark-900 border border-dark-700 rounded-2xl p-6 relative overflow-hidden">
            <div class="text-xs font-semibold text-dark-400 uppercase tracking-wider mb-2">Omzet Minggu Ini</div>
            <div class="text-2xl font-bold text-brand-400">Rp {{ number_format($weekRevenue, 0, ',', '.') }}</div>
            <div class="text-xs text-dark-400 mt-2">Senin s/d Minggu</div>
        </div>

        <div class="bg-dark-900 border border-dark-700 rounded-2xl p-6 relative overflow-hidden">
            <div class="text-xs font-semibold text-dark-400 uppercase tracking-wider mb-2">Omzet Bulan Ini</div>
            <div class="text-2xl font-bold text-blue-400">Rp {{ number_format($monthRevenue, 0, ',', '.') }}</div>
            <div class="text-xs text-dark-400 mt-2">{{ $monthOrders }} transaksi berhasil</div>
        </div>

        <div class="bg-dark-900 border border-dark-700 rounded-2xl p-6 relative overflow-hidden">
            <div class="text-xs font-semibold text-dark-400 uppercase tracking-wider mb-2">Total Akumulasi Omzet</div>
            <div class="text-2xl font-bold text-purple-400">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="text-xs text-dark-400 mt-2">Semua transaksi lunas</div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Daily Trend (30 Days) -->
        <div class="bg-dark-900 border border-dark-700 rounded-2xl p-6">
            <h3 class="text-lg font-bold text-white mb-4">Tren Omzet Harian (30 Hari Terakhir)</h3>
            <div class="h-64">
                <canvas id="dailyTrendChart"></canvas>
            </div>
        </div>

        <!-- Monthly Trend (6 Months) -->
        <div class="bg-dark-900 border border-dark-700 rounded-2xl p-6">
            <h3 class="text-lg font-bold text-white mb-4">Tren Omzet Bulanan (6 Bulan Terakhir)</h3>
            <div class="h-64">
                <canvas id="monthlyTrendChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Selling Menus & Recent Transactions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Top Menus -->
        <div class="bg-dark-900 border border-dark-700 rounded-2xl p-6">
            <h3 class="text-lg font-bold text-white mb-4">Menu Terlaris</h3>
            <div class="space-y-4">
                @forelse($topMenus as $index => $item)
                    <div class="flex items-center justify-between p-3 bg-dark-800/50 rounded-xl border border-dark-700/50">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 rounded-lg bg-brand-500/10 text-brand-400 flex items-center justify-center font-bold text-xs">#{{ $index + 1 }}</span>
                            <div>
                                <div class="text-sm font-semibold text-white">{{ $item->name }}</div>
                                <div class="text-xs text-dark-400">{{ $item->total_qty }} porsi terjual</div>
                            </div>
                        </div>
                        <div class="text-sm font-bold text-emerald-400">Rp {{ number_format($item->total_rev, 0, ',', '.') }}</div>
                    </div>
                @empty
                    <p class="text-dark-500 text-sm">Belum ada data penjualan menu.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="lg:col-span-2 bg-dark-900 border border-dark-700 rounded-2xl p-6">
            <h3 class="text-lg font-bold text-white mb-4">Transaksi Terakhir</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-dark-300">
                    <thead class="bg-dark-800 text-dark-400 uppercase text-xs">
                        <tr>
                            <th class="p-3">ID Pesanan</th>
                            <th class="p-3">Pelanggan</th>
                            <th class="p-3">Meja</th>
                            <th class="p-3">Total</th>
                            <th class="p-3">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-800">
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-dark-800/30 transition-colors">
                                <td class="p-3 font-bold text-white">#{{ $order->id }}</td>
                                <td class="p-3">{{ $order->customer_name }}</td>
                                <td class="p-3">Meja {{ $order->table->table_number ?? '-' }}</td>
                                <td class="p-3 font-bold text-emerald-400">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="p-3 text-xs text-dark-400">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-4 text-center text-dark-500">Belum ada transaksi lunas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Daily Chart
    const dailyCtx = document.getElementById('dailyTrendChart').getContext('2d');
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: @json($chartDailyLabels),
            datasets: [{
                label: 'Omzet Harian (Rp)',
                data: @json($chartDailyData),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#9ca3af' } },
                y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#9ca3af' } }
            }
        }
    });

    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyTrendChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: @json($chartMonthLabels),
            datasets: [{
                label: 'Omzet Bulanan (Rp)',
                data: @json($chartMonthData),
                backgroundColor: '#f59e0b',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#9ca3af' } },
                y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#9ca3af' } }
            }
        }
    });
</script>
@endpush
