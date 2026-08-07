@extends('layouts.app')
@section('title', 'Laporan Omzet Global - SajiHUB')
@section('page-title', 'Laporan Omzet Global')

@section('content')

{{-- Summary Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 animate-fade-in-up">
    <div class="bg-dark-800 border border-dark-700 rounded-2xl p-5 hover:border-dark-500 transition-all">
        <p class="text-xs font-medium text-dark-400 mb-1">Total Omzet (All-time)</p>
        <h3 class="text-2xl font-bold text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        <p class="text-xs text-dark-500 mt-1">Dari semua cabang</p>
    </div>
    <div class="bg-dark-800 border border-dark-700 rounded-2xl p-5 hover:border-dark-500 transition-all">
        <p class="text-xs font-medium text-dark-400 mb-1">Omzet Bulan Ini</p>
        <h3 class="text-2xl font-bold text-emerald-400">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h3>
        <p class="text-xs text-dark-500 mt-1">{{ now()->locale('id')->isoFormat('MMMM YYYY') }}</p>
    </div>
    <div class="bg-dark-800 border border-dark-700 rounded-2xl p-5 hover:border-dark-500 transition-all">
        <p class="text-xs font-medium text-dark-400 mb-1">Omzet Hari Ini</p>
        <h3 class="text-2xl font-bold text-brand-400">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
        <p class="text-xs text-dark-500 mt-1">{{ now()->locale('id')->isoFormat('dddd, D MMM YYYY') }}</p>
    </div>
    <div class="bg-dark-800 border border-dark-700 rounded-2xl p-5 hover:border-dark-500 transition-all">
        <p class="text-xs font-medium text-dark-400 mb-1">Total Transaksi</p>
        <h3 class="text-2xl font-bold text-blue-400">{{ number_format($totalOrders) }}</h3>
        <p class="text-xs text-dark-500 mt-1">Pesanan terbayar</p>
    </div>
</div>

{{-- Revenue Trend Chart --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 animate-fade-in-up">
    <div class="lg:col-span-2 bg-dark-900 border border-dark-700 rounded-2xl p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-base font-semibold text-white">Tren Omzet 6 Bulan Terakhir</h3>
                <p class="text-xs text-dark-400">Semua cabang gabungan</p>
            </div>
        </div>
        <canvas id="revenueChart" height="100"></canvas>
    </div>

    <div class="bg-dark-900 border border-dark-700 rounded-2xl p-6">
        <h3 class="text-base font-semibold text-white mb-4">Omzet Bulan Ini per Cabang</h3>
        <div class="space-y-3">
            @php $maxRev = $branchStats->max('monthly_rev') ?: 1; @endphp
            @foreach($branchStats as $stat)
            <div>
                <div class="flex items-center justify-between text-xs mb-1.5">
                    <span class="text-dark-300 truncate max-w-[120px]" title="{{ $stat['branch']->name }}">{{ $stat['branch']->name }}</span>
                    <span class="text-white font-semibold">Rp {{ number_format($stat['monthly_rev'], 0, ',', '.') }}</span>
                </div>
                <div class="h-1.5 bg-dark-700 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-brand-500 to-brand-400 rounded-full transition-all duration-700"
                         style="width: {{ $maxRev > 0 ? round(($stat['monthly_rev'] / $maxRev) * 100) : 0 }}%"></div>
                </div>
                <div class="text-xs text-dark-500 mt-1">{{ $stat['monthly_orders'] }} transaksi</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Branch Revenue Breakdown Table --}}
<div class="bg-dark-900 border border-dark-700 rounded-2xl overflow-hidden mb-8 animate-fade-in-up">
    <div class="px-6 py-4 border-b border-dark-700">
        <h3 class="font-semibold text-white">Rekap Omzet Per Cabang (Keseluruhan)</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-dark-800 border-b border-dark-700">
                    <th class="px-6 py-3 text-xs font-semibold text-dark-400 uppercase tracking-wider">Cabang</th>
                    <th class="px-6 py-3 text-xs font-semibold text-dark-400 uppercase tracking-wider">Total Transaksi</th>
                    <th class="px-6 py-3 text-xs font-semibold text-dark-400 uppercase tracking-wider">Total Omzet</th>
                    <th class="px-6 py-3 text-xs font-semibold text-dark-400 uppercase tracking-wider">Rata-rata/Transaksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-700/50">
                @forelse($revenueByBranch as $branch)
                <tr class="hover:bg-dark-800/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-brand-500"></div>
                            <span class="text-sm font-medium text-white">{{ $branch->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-dark-300">{{ number_format($branch->paid_orders_count) }} pesanan</td>
                    <td class="px-6 py-4 text-sm font-semibold text-white">Rp {{ number_format($branch->total_revenue ?? 0, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-sm text-dark-300">
                        Rp {{ $branch->paid_orders_count > 0 ? number_format(($branch->total_revenue / $branch->paid_orders_count), 0, ',', '.') : '0' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-dark-400 text-sm">Belum ada data transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Recent Transactions --}}
<div class="bg-dark-900 border border-dark-700 rounded-2xl overflow-hidden animate-fade-in-up">
    <div class="px-6 py-4 border-b border-dark-700">
        <h3 class="font-semibold text-white">Transaksi Terbaru (15 Terakhir)</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-dark-800 border-b border-dark-700">
                    <th class="px-6 py-3 text-xs font-semibold text-dark-400 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-xs font-semibold text-dark-400 uppercase tracking-wider">Cabang</th>
                    <th class="px-6 py-3 text-xs font-semibold text-dark-400 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-3 text-xs font-semibold text-dark-400 uppercase tracking-wider">Meja</th>
                    <th class="px-6 py-3 text-xs font-semibold text-dark-400 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-xs font-semibold text-dark-400 uppercase tracking-wider">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-700/50">
                @forelse($recentOrders as $order)
                <tr class="hover:bg-dark-800/50 transition-colors">
                    <td class="px-6 py-3 text-sm text-dark-400">#{{ $order->id }}</td>
                    <td class="px-6 py-3 text-sm text-dark-300">{{ $order->branch->name ?? '-' }}</td>
                    <td class="px-6 py-3 text-sm text-white">{{ $order->customer_name ?? 'Umum' }}</td>
                    <td class="px-6 py-3 text-sm text-dark-400">{{ $order->table->table_number ?? '-' }}</td>
                    <td class="px-6 py-3 text-sm font-semibold text-emerald-400">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-3 text-xs text-dark-400">{{ $order->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-dark-400 text-sm">Belum ada transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const labels = @json($chartLabels);
    const data   = @json($chartData);

    const ctx = document.getElementById('revenueChart').getContext('2d');

    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(249, 115, 22, 0.3)');
    gradient.addColorStop(1, 'rgba(249, 115, 22, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Omzet (Rp)',
                data: data,
                borderColor: '#f97316',
                backgroundColor: gradient,
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#f97316',
                pointBorderColor: '#1c1917',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#292524',
                    borderColor: '#44403c',
                    borderWidth: 1,
                    callbacks: {
                        label: ctx => 'Rp ' + ctx.raw.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(68,64,60,0.4)' },
                    ticks: { color: '#78716c', font: { size: 11 } }
                },
                y: {
                    grid: { color: 'rgba(68,64,60,0.4)' },
                    ticks: {
                        color: '#78716c',
                        font: { size: 11 },
                        callback: v => 'Rp ' + (v >= 1000000 ? (v/1000000).toFixed(1)+'jt' : v.toLocaleString('id-ID'))
                    }
                }
            }
        }
    });
});
</script>
@endpush
