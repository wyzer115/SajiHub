@extends('layouts.app')
@section('title', 'Laporan Keuangan')
@section('page-title', 'Laporan Keuangan Cabang')

@section('content')
<div class="space-y-6">
    
    <!-- Filter Date Range -->
    <div class="bg-dark-900 border border-dark-700 rounded-2xl p-6 shadow-sm animate-fade-in-up">
        <form action="{{ route('admin.reports') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="flex-grow w-full max-w-xs">
                <label for="preset" class="block text-dark-300 text-xs font-semibold uppercase tracking-wider mb-2">Rentang Waktu Laporan</label>
                <select name="preset" id="preset" class="w-full bg-dark-800 border border-dark-600 text-white rounded-xl px-4 py-3 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 focus:outline-none transition-all text-sm cursor-pointer" onchange="this.form.submit()">
                    <option value="today" {{ $preset == 'today' ? 'selected' : '' }}>Harian (Hari Ini)</option>
                    <option value="weekly" {{ $preset == 'weekly' ? 'selected' : '' }}>Mingguan (7 Hari Terakhir)</option>
                    <option value="monthly" {{ $preset == 'monthly' ? 'selected' : '' }}>Bulanan (30 Hari Terakhir)</option>
                    <option value="yearly" {{ $preset == 'yearly' ? 'selected' : '' }}>Tahunan (1 Tahun Terakhir)</option>
                    <option value="all" {{ $preset == 'all' ? 'selected' : '' }}>Semua Transaksi (Seluruh Waktu)</option>
                </select>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto shrink-0">
                <a href="{{ route('admin.reports.export', ['preset' => $preset]) }}" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-emerald-600/25 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Ekspor Excel
                </a>
            </div>
        </form>
    </div>

    <!-- Cards Summary -->
    <div class="grid lg:grid-cols-3 md:grid-cols-1 gap-6 animate-fade-in-up" style="animation-delay: 100ms;">
        <div class="bg-dark-900 border border-dark-700 rounded-2xl p-6 relative overflow-hidden group shadow-sm">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-xl group-hover:bg-emerald-500/20 transition-all"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-dark-400 text-xs font-semibold uppercase tracking-wider mb-1.5">Total Pendapatan</p>
                    <h3 class="text-3xl font-bold text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    <p class="text-xs text-dark-500 mt-2">Dari transaksi yang sudah lunas</p>
                </div>
                <div class="p-3 bg-emerald-500/10 text-emerald-400 rounded-xl">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-dark-900 border border-dark-700 rounded-2xl p-6 relative overflow-hidden group shadow-sm">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-blue-500/10 rounded-full blur-xl group-hover:bg-blue-500/20 transition-all"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-dark-400 text-xs font-semibold uppercase tracking-wider mb-1.5">Jumlah Pesanan</p>
                    <h3 class="text-3xl font-bold text-white">{{ $totalOrders }} <span class="text-base font-normal text-dark-500">transaksi</span></h3>
                    <p class="text-xs text-dark-500 mt-2">Selama periode penyaringan</p>
                </div>
                <div class="p-3 bg-blue-500/10 text-blue-400 rounded-xl">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-dark-900 border border-dark-700 rounded-2xl p-6 relative overflow-hidden group shadow-sm">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-purple-500/10 rounded-full blur-xl group-hover:bg-purple-500/20 transition-all"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-dark-400 text-xs font-semibold uppercase tracking-wider mb-1.5">Rata-Rata Transaksi</p>
                    <h3 class="text-3xl font-bold text-white">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</h3>
                    <p class="text-xs text-dark-500 mt-2">Nilai belanja rata-rata per meja</p>
                </div>
                <div class="p-3 bg-purple-500/10 text-purple-400 rounded-xl">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart & Payment Methods -->
    <div class="grid lg:grid-cols-3 gap-6 animate-fade-in-up" style="animation-delay: 200ms;">
        <!-- Daily Revenue Trend Chart -->
        <div class="lg:col-span-2 bg-dark-900 border border-dark-700 rounded-2xl p-6 shadow-sm flex flex-col">
            <h3 class="text-lg font-bold text-white mb-4">Tren Omzet Harian</h3>
            <div class="relative w-full flex-grow min-h-[300px]">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Payment Breakdown -->
        <div class="lg:col-span-1 bg-dark-900 border border-dark-700 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-bold text-white mb-4">Metode Pembayaran</h3>
                <div class="relative w-full min-h-[220px] flex items-center justify-center">
                    <canvas id="paymentChart"></canvas>
                </div>
            </div>
            
            <div class="space-y-3 mt-4 border-t border-dark-800 pt-4">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-dark-400 flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-amber-500"></span> Tunai
                    </span>
                    <span class="font-bold text-white">Rp {{ number_format($paymentMethods->get('cash')['total'] ?? 0, 0, ',', '.') }} ({{ $paymentMethods->get('cash')['count'] ?? 0 }})</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-dark-400 flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-brand-500"></span> QRIS
                    </span>
                    <span class="font-bold text-white">Rp {{ number_format($paymentMethods->get('qris')['total'] ?? 0, 0, ',', '.') }} ({{ $paymentMethods->get('qris')['count'] ?? 0 }})</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-dark-400 flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-blue-500"></span> Transfer
                    </span>
                    <span class="font-bold text-white">Rp {{ number_format($paymentMethods->get('transfer')['total'] ?? 0, 0, ',', '.') }} ({{ $paymentMethods->get('transfer')['count'] ?? 0 }})</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Detail Table -->
    <div class="bg-dark-900 border border-dark-700 rounded-2xl overflow-hidden shadow-sm animate-fade-in-up" style="animation-delay: 300ms;">
        <div class="p-6 border-b border-dark-700/50">
            <h3 class="text-lg font-bold text-white">Detail Transaksi Selesai</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-dark-800 border-b border-dark-700 text-xs font-semibold text-dark-400 uppercase tracking-wider">
                        <th class="px-6 py-4">ID Pesanan</th>
                        <th class="px-6 py-4">Tanggal / Jam</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Meja</th>
                        <th class="px-6 py-4">Metode Bayar</th>
                        <th class="px-6 py-4">Kasir PJ</th>
                        <th class="px-6 py-4 text-right">Total Belanja</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-700/50 text-sm">
                    @forelse($ordersList ?? [] as $order)
                    <tr class="hover:bg-dark-800/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-white">#{{ $order->id }}</td>
                        <td class="px-6 py-4 text-dark-300">{{ $order->created_at->format('d M Y, H:i') }} WIB</td>
                        <td class="px-6 py-4 text-white font-medium">{{ $order->customer_name }}</td>
                        <td class="px-6 py-4 text-dark-300">Meja {{ $order->table->table_number ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($order->payment_method == 'cash')
                                <span class="text-xs font-semibold text-dark-300">💵 Tunai</span>
                            @elseif($order->payment_method == 'qris')
                                <span class="text-xs font-semibold text-brand-400">📱 QRIS</span>
                            @else
                                <span class="text-xs font-semibold text-blue-400">🏦 Transfer</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-dark-300">{{ $order->user->name ?? 'Scan QR / Sistem' }}</td>
                        <td class="px-6 py-4 text-right font-bold text-brand-400">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-dark-400">Tidak ada transaksi ditemukan pada rentang tanggal terpilih.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Revenue trend line chart
    const revCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($chartData) !!},
                borderColor: '#e85824',
                backgroundColor: 'rgba(232, 88, 36, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    grid: {
                        color: '#282b30'
                    },
                    ticks: {
                        color: '#72767d',
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#72767d'
                    }
                }
            }
        }
    });

    // 2. Payment breakdown donut chart
    const payCtx = document.getElementById('paymentChart').getContext('2d');
    new Chart(payCtx, {
        type: 'doughnut',
        data: {
            labels: ['Tunai', 'QRIS', 'Transfer'],
            datasets: [{
                data: [
                    {{ $paymentMethods->get('cash')['total'] ?? 0 }},
                    {{ $paymentMethods->get('qris')['total'] ?? 0 }},
                    {{ $paymentMethods->get('transfer')['total'] ?? 0 }}
                ],
                backgroundColor: ['#f59e0b', '#e85824', '#3b82f6'],
                borderColor: '#1e2124',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '75%'
        }
    });

</script>
@endpush
