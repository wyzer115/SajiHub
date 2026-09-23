@extends('layouts.app')

@section('title', 'Laporan Keuangan')
@section('page-title', 'Laporan Keuangan Cabang')

@section('content')
<div class="space-y-6">
    
    <!-- Filter Date Range (Preset Buttons) -->
    <div class="bg-white border border-stone-200 rounded-3xl p-5 shadow-sm animate-fade-in-up flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full lg:w-auto">
            <span class="text-xs font-black text-stone-500 uppercase tracking-wider shrink-0">Rentang Waktu:</span>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.reports', ['preset' => 'today']) }}"
                   class="px-4 py-2.5 rounded-xl text-xs font-extrabold transition-all border flex items-center gap-1.5 {{ $preset == 'today' ? 'bg-[#BD2000] text-white border-[#BD2000] shadow-sm' : 'bg-stone-50 hover:bg-stone-100 text-stone-700 border-stone-300' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Hari Ini
                </a>
                <a href="{{ route('admin.reports', ['preset' => 'weekly']) }}"
                   class="px-4 py-2.5 rounded-xl text-xs font-extrabold transition-all border flex items-center gap-1.5 {{ $preset == 'weekly' ? 'bg-[#BD2000] text-white border-[#BD2000] shadow-sm' : 'bg-stone-50 hover:bg-stone-100 text-stone-700 border-stone-300' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Minggu Ini
                </a>
                <a href="{{ route('admin.reports', ['preset' => 'monthly']) }}"
                   class="px-4 py-2.5 rounded-xl text-xs font-extrabold transition-all border flex items-center gap-1.5 {{ $preset == 'monthly' ? 'bg-[#BD2000] text-white border-[#BD2000] shadow-sm' : 'bg-stone-50 hover:bg-stone-100 text-stone-700 border-stone-300' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Bulan Ini
                </a>
                <a href="{{ route('admin.reports', ['preset' => 'yearly']) }}"
                   class="px-4 py-2.5 rounded-xl text-xs font-extrabold transition-all border flex items-center gap-1.5 {{ $preset == 'yearly' ? 'bg-[#BD2000] text-white border-[#BD2000] shadow-sm' : 'bg-stone-50 hover:bg-stone-100 text-stone-700 border-stone-300' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Tahun Ini
                </a>
                <a href="{{ route('admin.reports', ['preset' => 'all']) }}"
                   class="px-4 py-2.5 rounded-xl text-xs font-extrabold transition-all border flex items-center gap-1.5 {{ $preset == 'all' ? 'bg-[#BD2000] text-white border-[#BD2000] shadow-sm' : 'bg-stone-50 hover:bg-stone-100 text-stone-700 border-stone-300' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    Semua Waktu
                </a>
            </div>
        </div>

        <a href="{{ route('admin.reports.export', ['preset' => $preset]) }}" class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold px-5 py-2.5 rounded-xl transition-all shadow-md text-xs shrink-0 cursor-pointer whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Ekspor Excel
        </a>
    </div>

    <!-- Cards Summary -->
    <div class="grid lg:grid-cols-3 md:grid-cols-1 gap-6 animate-fade-in-up">
        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1.5">Total Pendapatan</p>
                <h3 class="text-3xl font-black text-emerald-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-500 font-semibold mt-2">Dari transaksi yang sudah lunas</p>
            </div>
            <div class="p-3.5 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-200 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1.5">Jumlah Pesanan</p>
                <h3 class="text-3xl font-black text-[#1C1917]">{{ $totalOrders }} <span class="text-base font-bold text-slate-500">transaksi</span></h3>
                <p class="text-xs text-slate-500 font-semibold mt-2">Pesanan telah diselesaikan</p>
            </div>
            <div class="p-3.5 bg-blue-50 text-blue-600 rounded-2xl border border-blue-200 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
        </div>

        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1.5">Rata-Rata Nilai Transaksi</p>
                <h3 class="text-3xl font-black text-[#BD2000]">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-500 font-semibold mt-2">Per pesanan selesai</p>
            </div>
            <div class="p-3.5 bg-[#BD2000]/10 text-[#BD2000] rounded-2xl border border-[#BD2000]/20 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
        </div>
    </div>

    <!-- Chart & Payment Methods -->
    <div class="grid lg:grid-cols-3 gap-6 animate-fade-in-up">
        <!-- Daily Revenue Trend Chart -->
        <div class="lg:col-span-2 bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex flex-col">
            <h3 class="text-lg font-black text-[#8C0000] mb-4">Tren Omzet Harian</h3>
            <div class="relative w-full flex-grow min-h-[300px]">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Payment Methods Breakdown -->
        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex flex-col">
            <h3 class="text-lg font-black text-[#8C0000] mb-4">Metode Pembayaran</h3>
            <div class="relative w-full flex-grow min-h-[200px] flex items-center justify-center">
                <canvas id="paymentChart"></canvas>
            </div>
            <div class="mt-4 space-y-2 pt-4 border-t border-stone-200">
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                        <span class="text-stone-700 font-bold">Tunai</span>
                    </div>
                    <span class="font-black text-[#1C1917]">Rp {{ number_format($paymentMethods->get('cash')['total'] ?? 0, 0, ',', '.') }} ({{ $paymentMethods->get('cash')['count'] ?? 0 }})</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-[#BD2000]"></span>
                        <span class="text-stone-700 font-bold">QRIS</span>
                    </div>
                    <span class="font-black text-[#1C1917]">Rp {{ number_format($paymentMethods->get('qris')['total'] ?? 0, 0, ',', '.') }} ({{ $paymentMethods->get('qris')['count'] ?? 0 }})</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white border border-stone-200 rounded-3xl overflow-hidden shadow-sm animate-fade-in-up">
        <div class="p-6 border-b border-stone-200 bg-stone-50 flex justify-between items-center">
            <h3 class="text-lg font-black text-[#8C0000]">Rincian Transaksi Selesai</h3>
            <span class="text-xs text-slate-600 font-semibold">Total {{ $paginatedOrders->total() }} transaksi lunas</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-stone-100 text-stone-700 text-xs font-extrabold uppercase tracking-wider border-b border-stone-200">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Tanggal & Waktu</th>
                        <th class="px-6 py-4">Pelanggan / Meja</th>
                        <th class="px-6 py-4">Metode</th>
                        <th class="px-6 py-4 text-right">Total Transaksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @forelse($paginatedOrders as $order)
                    <tr class="hover:bg-stone-50 transition-colors">
                        <td class="px-6 py-4 text-xs font-bold text-slate-500">{{ ($paginatedOrders->currentPage() - 1) * $paginatedOrders->perPage() + $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600 font-semibold">{{ $order->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-extrabold text-[#1C1917]">{{ $order->customer_name }}</div>
                            <div class="text-xs text-slate-500 font-medium">{{ $order->table ? 'Meja ' . $order->table->table_number : 'Takeaway / Non-meja' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-extrabold uppercase
                                {{ $order->payment_method === 'cash' ? 'bg-amber-100 text-amber-800 border border-amber-300' : '' }}
                                {{ $order->payment_method === 'qris' ? 'bg-[#BD2000]/10 text-[#BD2000] border border-[#BD2000]/20' : '' }}
                                {{ $order->payment_method === 'transfer' ? 'bg-blue-100 text-blue-800 border border-blue-300' : '' }}">
                                {{ $order->payment_method }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-black text-emerald-700 text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500 font-medium">Tidak ada transaksi lunas pada rentang waktu ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($paginatedOrders->hasPages())
        <div class="px-6 py-4 border-t border-stone-200 bg-stone-50">
            {{ $paginatedOrders->links() }}
        </div>
        @endif
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
                borderColor: '#bd2000',
                backgroundColor: 'rgba(189, 32, 0, 0.1)',
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
                        color: 'rgba(0,0,0,0.05)'
                    },
                    ticks: {
                        color: '#475569',
                        font: { weight: 'bold' },
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
                        color: '#475569',
                        font: { weight: 'bold' }
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
            labels: ['Tunai', 'QRIS'],
            datasets: [{
                data: [
                    {{ $paymentMethods->get('cash')['total'] ?? 0 }},
                    {{ $paymentMethods->get('qris')['total'] ?? 0 }}
                ],
                backgroundColor: ['#f59e0b', '#bd2000'],
                borderWidth: 0
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
