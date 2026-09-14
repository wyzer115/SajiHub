@extends('layouts.app')
@section('title', 'Laporan Omzet Global - SajiHUB')
@section('page-title', 'Laporan Omzet Global')

@section('content')

{{-- Summary Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 animate-fade-in-up">
    <div class="bg-white border border-stone-200 rounded-3xl p-5 shadow-sm hover:shadow-md transition-all">
        <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Total Omzet (All-time)</p>
        <h3 class="text-2xl font-black text-[#1C1917]">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        <p class="text-xs text-slate-500 font-semibold mt-1">Dari semua cabang</p>
    </div>
    <div class="bg-white border border-stone-200 rounded-3xl p-5 shadow-sm hover:shadow-md transition-all">
        <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Omzet Bulan Ini</p>
        <h3 class="text-2xl font-black text-emerald-600">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h3>
        <p class="text-xs text-slate-500 font-semibold mt-1">{{ now()->locale('id')->isoFormat('MMMM YYYY') }}</p>
    </div>
    <div class="bg-white border border-stone-200 rounded-3xl p-5 shadow-sm hover:shadow-md transition-all">
        <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Omzet Hari Ini</p>
        <h3 class="text-2xl font-black text-[#BD2000]">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
        <p class="text-xs text-slate-500 font-semibold mt-1">{{ now()->locale('id')->isoFormat('dddd, D MMM YYYY') }}</p>
    </div>
    <div class="bg-white border border-stone-200 rounded-3xl p-5 shadow-sm hover:shadow-md transition-all">
        <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Total Transaksi</p>
        <h3 class="text-2xl font-black text-blue-600">{{ number_format($totalOrders) }}</h3>
        <p class="text-xs text-slate-500 font-semibold mt-1">Pesanan terbayar</p>
    </div>
</div>

{{-- Revenue Trend Chart --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 animate-fade-in-up">
    <div class="lg:col-span-2 bg-white border border-stone-200 rounded-3xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-base font-black text-[#8C0000]">Tren Omzet 6 Bulan Terakhir</h3>
                <p class="text-xs text-slate-600 font-medium">Semua cabang gabungan</p>
            </div>
        </div>
        <canvas id="revenueChart" height="100"></canvas>
    </div>

    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm">
        <h3 class="text-base font-black text-[#8C0000] mb-4">Omzet Bulan Ini per Cabang</h3>
        <div class="space-y-4">
            @php $maxRev = $branchStats->max('monthly_rev') ?: 1; @endphp
            @foreach($branchStats as $stat)
            <div>
                <div class="flex items-center justify-between text-xs mb-1.5">
                    <span class="text-[#1C1917] font-extrabold truncate max-w-[120px]" title="{{ $stat['branch']->name }}">{{ $stat['branch']->name }}</span>
                    <span class="text-[#BD2000] font-black">Rp {{ number_format($stat['monthly_rev'], 0, ',', '.') }}</span>
                </div>
                <div class="h-2 bg-stone-100 rounded-full overflow-hidden border border-stone-200">
                    <div class="h-full bg-[#BD2000] rounded-full transition-all duration-700"
                         style="width: {{ $maxRev > 0 ? round(($stat['monthly_rev'] / $maxRev) * 100) : 0 }}%"></div>
                </div>
                <div class="text-xs text-slate-500 font-semibold mt-1">{{ $stat['monthly_orders'] }} transaksi</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Branch Revenue Breakdown Table --}}
<div class="bg-white border border-stone-200 rounded-3xl overflow-hidden mb-8 shadow-sm animate-fade-in-up">
    <div class="px-6 py-4 border-b border-stone-200 bg-stone-50">
        <h3 class="font-black text-[#8C0000]">Rekap Omzet Per Cabang (Keseluruhan)</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-stone-100 border-b border-stone-200 text-stone-700 text-xs font-extrabold uppercase tracking-wider">
                    <th class="px-6 py-3">Cabang</th>
                    <th class="px-6 py-3">Total Transaksi</th>
                    <th class="px-6 py-3">Total Omzet</th>
                    <th class="px-6 py-3">Rata-rata/Transaksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-200">
                @forelse($revenueByBranch as $branch)
                <tr class="hover:bg-stone-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded-full bg-[#BD2000]"></div>
                            <span class="text-sm font-extrabold text-[#1C1917]">{{ $branch->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-700 font-bold">{{ number_format($branch->paid_orders_count) }} pesanan</td>
                    <td class="px-6 py-4 text-sm font-black text-emerald-700">Rp {{ number_format($branch->total_revenue ?? 0, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-sm text-slate-700 font-bold">
                        Rp {{ $branch->paid_orders_count > 0 ? number_format(($branch->total_revenue / $branch->paid_orders_count), 0, ',', '.') : '0' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-500 font-medium text-sm">Belum ada data transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Recent Transactions --}}
<div class="bg-white border border-stone-200 rounded-3xl overflow-hidden shadow-sm animate-fade-in-up">
    <div class="px-6 py-4 border-b border-stone-200 bg-stone-50">
        <h3 class="font-black text-[#8C0000]">Transaksi Terbaru (15 Terakhir)</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-stone-100 border-b border-stone-200 text-stone-700 text-xs font-extrabold uppercase tracking-wider">
                    <th class="px-6 py-3">#</th>
                    <th class="px-6 py-3">Cabang</th>
                    <th class="px-6 py-3">Pelanggan</th>
                    <th class="px-6 py-3">Meja</th>
                    <th class="px-6 py-3">Total</th>
                    <th class="px-6 py-3 text-right">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-200">
                @forelse($recentOrders as $order)
                <tr class="hover:bg-stone-50 transition-colors">
                    <td class="px-6 py-3 text-sm font-mono text-[#BD2000] font-black">#{{ $order->id }}</td>
                    <td class="px-6 py-3 text-sm text-slate-700 font-bold">{{ $order->branch->name ?? '-' }}</td>
                    <td class="px-6 py-3 text-sm font-extrabold text-[#1C1917]">{{ $order->customer_name ?? 'Umum' }}</td>
                    <td class="px-6 py-3 text-sm text-slate-700 font-bold">{{ $order->table ? $order->table->table_number : 'Takeaway' }}</td>
                    <td class="px-6 py-3 text-sm font-black text-emerald-700">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-3 text-xs text-slate-500 font-semibold text-right">{{ $order->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-slate-500 font-medium text-sm">Belum ada transaksi.</td>
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
    gradient.addColorStop(0, 'rgba(189, 32, 0, 0.2)');
    gradient.addColorStop(1, 'rgba(189, 32, 0, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Omzet (Rp)',
                data: data,
                borderColor: '#bd2000',
                backgroundColor: gradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#bd2000',
                pointBorderColor: '#ffffff',
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
                    backgroundColor: '#1c1917',
                    borderColor: '#e7e5e4',
                    borderWidth: 1,
                    callbacks: {
                        label: ctx => 'Rp ' + ctx.raw.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { color: '#475569', font: { weight: 'bold', size: 11 } }
                },
                y: {
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        color: '#475569',
                        font: { weight: 'bold', size: 11 },
                        callback: v => 'Rp ' + (v >= 1000000 ? (v/1000000).toFixed(1)+'jt' : v.toLocaleString('id-ID'))
                    }
                }
            }
        }
    });
});
</script>
@endpush
