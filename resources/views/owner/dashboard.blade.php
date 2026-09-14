@extends('layouts.app')

@section('title', 'Dasbor Owner - SajiHUB')
@section('page-title', 'Dasbor Pemilik Cabang')

@section('content')
<div class="space-y-8 animate-fade-in-up">

    <!-- Header info -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-[#8C0000]">Ringkasan Eksekutif Cabang</h2>
            <p class="text-slate-600 text-sm mt-1 font-medium">Laporan arus kas (pemasukan & pengeluaran) serta pengawasan stok barang <span class="text-[#BD2000] font-extrabold">{{ $branch->name }}</span></p>
        </div>
    </div>

    <!-- Metric Cards (Pemasukan, Pengeluaran, Laba Bersih) - Balanced & Harmonious Card Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1.5">Total Pemasukan (Omzet)</p>
                <h3 class="text-3xl font-black text-emerald-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-500 font-semibold mt-2">Bulan Ini: <span class="text-[#1C1917] font-black">Rp {{ number_format($monthRevenue, 0, ',', '.') }}</span></p>
            </div>
            <div class="p-3.5 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-200 shrink-0">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1.5">Total Pengeluaran</p>
                <h3 class="text-3xl font-black text-red-600">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-500 font-semibold mt-2">Bulan Ini: <span class="text-[#1C1917] font-black">Rp {{ number_format($monthExpenses, 0, ',', '.') }}</span></p>
            </div>
            <div class="p-3.5 bg-red-50 text-red-600 rounded-2xl border border-red-200 shrink-0">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
            </div>
        </div>

        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1.5">Laba Bersih Estimasi</p>
                <h3 class="text-3xl font-black {{ $netProfit >= 0 ? 'text-[#BD2000]' : 'text-red-600' }}">Rp {{ number_format($netProfit, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-500 font-semibold mt-2">Pemasukan - Pengeluaran</p>
            </div>
            <div class="p-3.5 bg-[#BD2000]/10 text-[#BD2000] rounded-2xl border border-[#BD2000]/20 shrink-0">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Visual Sales & Expenses Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white border border-stone-200 rounded-3xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-black text-[#8C0000] flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#8C0000]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                        <span>Grafik Pemasukan vs Pengeluaran (7 Hari Terakhir)</span>
                    </h3>
                    <p class="text-slate-600 text-xs font-medium mt-0.5">Tren performa arus kas bulanan cabang {{ $branch->name }}</p>
                </div>
            </div>
            <div class="h-64">
                <canvas id="ownerFinancialChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-1 bg-white border border-stone-200 rounded-3xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-black text-[#8C0000] flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#8C0000]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span>Distribusi Inventaris</span>
                    </h3>
                    <p class="text-slate-600 text-xs font-medium mt-0.5">Kategori bahan & peralatan</p>
                </div>
            </div>
            <div class="h-64 flex items-center justify-center">
                <canvas id="inventoryDoughnutChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Inventory Overview Section -->
    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="text-lg font-black text-[#8C0000]">Status Stok & Inventaris Cabang</h3>
                <p class="text-xs text-slate-600 font-medium">Pemantauan ketersediaan bahan makanan, bahan minuman, dan peralatan</p>
            </div>
            @if($lowStockCount > 0)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    {{ $lowStockCount }} Item Stok Menipis!
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Stok Aman
                </span>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="p-4 bg-stone-50 rounded-2xl border border-stone-200">
                <span class="text-xs text-stone-500 font-bold uppercase">Bahan Makanan</span>
                <div class="text-xl font-black text-[#1C1917] mt-1">{{ $bahanMakananCount }} <span class="text-xs font-semibold text-slate-500">jenis</span></div>
            </div>
            <div class="p-4 bg-stone-50 rounded-2xl border border-stone-200">
                <span class="text-xs text-stone-500 font-bold uppercase">Bahan Minuman</span>
                <div class="text-xl font-black text-[#1C1917] mt-1">{{ $bahanMinumanCount }} <span class="text-xs font-semibold text-slate-500">jenis</span></div>
            </div>
            <div class="p-4 bg-stone-50 rounded-2xl border border-stone-200">
                <span class="text-xs text-stone-500 font-bold uppercase">Peralatan / Alat Masak</span>
                <div class="text-xl font-black text-[#1C1917] mt-1">{{ $peralatanCount }} <span class="text-xs font-semibold text-slate-500">unit</span></div>
            </div>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-stone-200">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-100 text-stone-700 text-xs font-extrabold uppercase tracking-wider border-b border-stone-200">
                        <th class="px-5 py-3.5">No</th>
                        <th class="px-5 py-3.5">Nama Item</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5">Stok Saat Ini</th>
                        <th class="px-5 py-3.5">Minimal Stok</th>
                        <th class="px-5 py-3.5 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @forelse($inventories as $item)
                    <tr class="hover:bg-stone-50 transition-colors">
                        <td class="px-5 py-3.5 text-xs text-slate-500 font-bold">{{ $loop->iteration }}</td>
                        <td class="px-5 py-3.5 text-sm font-extrabold text-[#1C1917]">{{ $item->name }}</td>
                        <td class="px-5 py-3.5 text-xs uppercase text-slate-600 font-bold">
                            {{ str_replace('_', ' ', $item->category) }}
                        </td>
                        <td class="px-5 py-3.5 text-sm font-black text-[#1C1917]">
                            {{ $item->stock }} <span class="text-xs font-semibold text-slate-500">{{ $item->unit }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-600 font-semibold">{{ $item->min_stock }} {{ $item->unit }}</td>
                        <td class="px-5 py-3.5 text-right">
                            @if($item->isLowStock())
                                <span class="px-3 py-1 rounded-xl text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">Stok Menipis</span>
                            @else
                                <span class="px-3 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">Stok Aman</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-6 text-center text-slate-500 text-sm font-medium">Belum ada data barang inventaris.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Tables: Sales Orders vs Expenses -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Paid Orders -->
        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm">
            <h3 class="text-lg font-black text-[#8C0000] mb-4">Pemasukan Terakhir (Transaksi Lunas)</h3>
            <div class="overflow-x-auto rounded-2xl border border-stone-200">
                <table class="w-full text-left text-sm text-[#1C1917]">
                    <thead class="bg-stone-100 text-stone-700 text-xs font-extrabold uppercase border-b border-stone-200">
                        <tr>
                            <th class="p-3">No</th>
                            <th class="p-3">Pelanggan</th>
                            <th class="p-3">Waktu</th>
                            <th class="p-3 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200">
                        @forelse($recentOrders as $order)
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="p-3 text-slate-500 text-xs font-bold">{{ $loop->iteration }}</td>
                            <td class="p-3 font-extrabold text-[#1C1917]">{{ $order->customer_name }}</td>
                            <td class="p-3 text-xs text-slate-500 font-semibold">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-3 text-right font-black text-emerald-700">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="p-4 text-center text-slate-500 text-xs font-medium">Belum ada transaksi lunas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Expenses -->
        <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm">
            <h3 class="text-lg font-black text-[#8C0000] mb-4">Pengeluaran Terakhir</h3>
            <div class="overflow-x-auto rounded-2xl border border-stone-200">
                <table class="w-full text-left text-sm text-[#1C1917]">
                    <thead class="bg-stone-100 text-stone-700 text-xs font-extrabold uppercase border-b border-stone-200">
                        <tr>
                            <th class="p-3">No</th>
                            <th class="p-3">Keterangan</th>
                            <th class="p-3">Tanggal</th>
                            <th class="p-3 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200">
                        @forelse($recentExpenses as $exp)
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="p-3 text-slate-500 text-xs font-bold">{{ $loop->iteration }}</td>
                            <td class="p-3 font-extrabold text-[#1C1917]">{{ $exp->title }}</td>
                            <td class="p-3 text-xs text-slate-500 font-semibold">{{ $exp->date->format('d/m/Y') }}</td>
                            <td class="p-3 text-right font-black text-red-600">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="p-4 text-center text-slate-500 text-xs font-medium">Belum ada catatan pengeluaran.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Line Chart: Financial Trend
        const ctxFin = document.getElementById('ownerFinancialChart').getContext('2d');
        new Chart(ctxFin, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartDates) !!},
                datasets: [
                    {
                        label: 'Pemasukan (Rp)',
                        data: {!! json_encode($chartRevenues) !!},
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5, 150, 105, 0.1)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2.5,
                        pointRadius: 4,
                        pointBackgroundColor: '#059669',
                    },
                    {
                        label: 'Pengeluaran (Rp)',
                        data: {!! json_encode($chartExpenses) !!},
                        borderColor: '#dc2626',
                        backgroundColor: 'rgba(220, 38, 38, 0.1)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2.5,
                        pointRadius: 4,
                        pointBackgroundColor: '#dc2626',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#334155', font: { family: 'Outfit', size: 12, weight: 'bold' } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { color: '#475569', font: { weight: 'bold' } }
                    },
                    y: {
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: {
                            color: '#475569',
                            font: { weight: 'bold' },
                            callback: function(val) { return 'Rp ' + (val/1000).toLocaleString('id-ID') + 'k'; }
                        }
                    }
                }
            }
        });

        // Doughnut Chart: Inventory Categories
        const ctxInv = document.getElementById('inventoryDoughnutChart').getContext('2d');
        new Chart(ctxInv, {
            type: 'doughnut',
            data: {
                labels: ['Bahan Makanan', 'Bahan Minuman', 'Peralatan'],
                datasets: [{
                    data: [{{ $bahanMakananCount }}, {{ $bahanMinumanCount }}, {{ $peralatanCount }}],
                    backgroundColor: ['#d97706', '#2563eb', '#7c3aed'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#334155', padding: 15, font: { weight: 'bold' } }
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endpush
@endsection
