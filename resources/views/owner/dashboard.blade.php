@extends('layouts.app')

@section('title', 'Dasbor Owner - SajiHUB')
@section('page-title', 'Dasbor Utama Owner')

@section('content')
<div class="space-y-8 animate-fade-in-up">

    <!-- Header info -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white border border-stone-200 rounded-3xl p-6 shadow-sm">
        <div>
            <h2 class="text-2xl font-black text-[#8C0000]">Ringkasan Eksekutif</h2>
            <p class="text-slate-600 text-sm mt-1 font-medium">Laporan arus kas (pemasukan & pengeluaran) serta pengawasan stok barang: <span class="text-[#BD2000] font-extrabold">{{ $selectedBranch ? $selectedBranch->name : 'Semua Cabang (Gabungan)' }}</span></p>
        </div>
        <form method="GET" action="{{ route('owner.dashboard') }}" class="flex items-center gap-2">
            <label for="branch_id" class="text-xs font-bold text-stone-600 whitespace-nowrap">Filter Cabang:</label>
            <select name="branch_id" id="branch_id" onchange="this.form.submit()" class="bg-stone-50 border border-stone-300 text-stone-800 text-xs font-bold rounded-xl px-4 py-2.5 focus:border-[#BD2000] focus:outline-none shadow-sm cursor-pointer">
                <option value="all" {{ ($selectedBranchId == 'all' || !$selectedBranchId) ? 'selected' : '' }}>🌐 Semua Cabang (Gabungan)</option>
                @foreach($branches as $b)
                    <option value="{{ $b->id }}" {{ $selectedBranchId == $b->id ? 'selected' : '' }}>🏢 {{ $b->name }}</option>
                @endforeach
            </select>
            @if(request('period'))
                <input type="hidden" name="period" value="{{ request('period') }}">
            @endif
            @if(request('start_date'))
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            @endif
            @if(request('end_date'))
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            @endif
        </form>
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
            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 mb-4">
                <div>
                    <h3 class="text-lg font-black text-[#8C0000] flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#8C0000]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                        <span>Grafik Pemasukan vs Pengeluaran ({{ $periodLabel }})</span>
                    </h3>
                    <p class="text-slate-500 text-xs font-semibold mt-0.5">Tren arus kas berdasarkan periode waktu yang dipilih</p>
                </div>

                <!-- Tombol Periode: Hari Ini, Minggu Ini, Bulan Ini + Pilihan Tanggal Kustom -->
                <div class="flex flex-wrap items-center gap-2">
                    <div class="flex items-center gap-1 bg-stone-100 p-1 rounded-2xl">
                        <a href="{{ route('owner.dashboard', array_filter(['branch_id' => $selectedBranchId, 'period' => 'today'])) }}"
                           class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold transition-all {{ $period === 'today' ? 'bg-[#BD2000] text-white shadow-xs' : 'text-stone-600 hover:text-stone-900' }}">
                            Hari Ini
                        </a>
                        <a href="{{ route('owner.dashboard', array_filter(['branch_id' => $selectedBranchId, 'period' => 'week'])) }}"
                           class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold transition-all {{ $period === 'week' ? 'bg-[#BD2000] text-white shadow-xs' : 'text-stone-600 hover:text-stone-900' }}">
                            Minggu Ini
                        </a>
                        <a href="{{ route('owner.dashboard', array_filter(['branch_id' => $selectedBranchId, 'period' => 'month'])) }}"
                           class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold transition-all {{ $period === 'month' ? 'bg-[#BD2000] text-white shadow-xs' : 'text-stone-600 hover:text-stone-900' }}">
                            Bulan Ini
                        </a>
                    </div>

                    <!-- Form Tanggal Kustom (Tetap Mempertahankan Pilihan Tanggal) -->
                    <form method="GET" action="{{ route('owner.dashboard') }}" class="flex items-center gap-1.5 bg-stone-50 border border-stone-200 rounded-2xl p-1">
                        @if($selectedBranchId && $selectedBranchId !== 'all')
                            <input type="hidden" name="branch_id" value="{{ $selectedBranchId }}">
                        @endif
                        <input type="hidden" name="period" value="custom">
                        <input type="date" name="start_date" value="{{ $startDate }}" class="text-[11px] bg-white border border-stone-300 rounded-xl px-2.5 py-1 text-stone-700 font-semibold focus:outline-none focus:border-[#BD2000]">
                        <span class="text-xs text-stone-400 font-bold">-</span>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="text-[11px] bg-white border border-stone-300 rounded-xl px-2.5 py-1 text-stone-700 font-semibold focus:outline-none focus:border-[#BD2000]">
                        <button type="submit" class="px-3 py-1 bg-stone-800 hover:bg-[#8C0000] text-white rounded-xl text-xs font-extrabold transition-all shadow-xs cursor-pointer" title="Terapkan Rentang Tanggal">
                            Filter
                        </button>
                    </form>
                </div>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="ownerFinancialChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-1 bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-black text-[#8C0000] mb-1">Pengawasan Stok Gudang</h3>
                <p class="text-slate-500 text-xs font-semibold mb-4">Ringkasan ketersediaan bahan & alat</p>
                
                <div class="space-y-3">
                    <div class="p-3.5 bg-stone-50 rounded-2xl border border-stone-200 flex items-center justify-between">
                        <span class="text-xs font-bold text-stone-600">Bahan Makanan</span>
                        <span class="text-sm font-black text-stone-900">{{ $bahanMakananCount }} item</span>
                    </div>
                    <div class="p-3.5 bg-stone-50 rounded-2xl border border-stone-200 flex items-center justify-between">
                        <span class="text-xs font-bold text-stone-600">Bahan Minuman</span>
                        <span class="text-sm font-black text-stone-900">{{ $bahanMinumanCount }} item</span>
                    </div>
                    <div class="p-3.5 bg-stone-50 rounded-2xl border border-stone-200 flex items-center justify-between">
                        <span class="text-xs font-bold text-stone-600">Peralatan</span>
                        <span class="text-sm font-black text-stone-900">{{ $peralatanCount }} item</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-stone-200 flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-stone-500 uppercase block">Stok Menipis</span>
                    <span class="text-xl font-black {{ $lowStockCount > 0 ? 'text-amber-600' : 'text-emerald-600' }}">{{ $lowStockCount }} Item</span>
                </div>
                <a href="{{ route('owner.inventory') }}" class="px-4 py-2 bg-[#BD2000] hover:bg-[#8C0000] text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                    Lihat Aset Gudang &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Inventory Preview Table -->
    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-black text-[#8C0000]">Daftar Stok Barang</h3>
                <p class="text-slate-500 text-xs font-semibold mt-0.5">Pemantauan stok dan batas minimal</p>
            </div>
            <a href="{{ route('owner.inventory') }}" class="text-xs font-bold text-[#BD2000] hover:underline">Semua Stok &rarr;</a>
        </div>
        <div class="overflow-x-auto rounded-2xl border border-stone-200">
            <table class="w-full text-left text-sm text-[#1C1917]">
                <thead class="bg-stone-100 text-stone-700 text-xs font-extrabold uppercase border-b border-stone-200">
                    <tr>
                        <th class="p-3">Item</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Stok Saat Ini</th>
                        <th class="p-3">Batas Min.</th>
                        <th class="p-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @forelse($inventories->take(5) as $inv)
                    <tr class="hover:bg-stone-50 transition-colors">
                        <td class="p-3 font-bold text-[#1C1917]">{{ $inv->name }}</td>
                        <td class="p-3 text-xs text-slate-500 font-semibold">{{ ucfirst(str_replace('_', ' ', $inv->category)) }}</td>
                        <td class="p-3 font-black text-[#1C1917]">{{ (float)$inv->stock }} {{ $inv->unit }}</td>
                        <td class="p-3 text-xs text-slate-500 font-semibold">{{ (float)$inv->min_stock }} {{ $inv->unit }}</td>
                        <td class="p-3">
                            @if($inv->isLowStock())
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">Menipis</span>
                            @else
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">Aman</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-slate-500 text-xs font-medium">Belum ada data barang inventaris.</td>
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
                            <th class="p-3">Cabang</th>
                            <th class="p-3">Pelanggan</th>
                            <th class="p-3">Waktu</th>
                            <th class="p-3 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200">
                        @forelse($recentOrders as $order)
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="p-3 text-slate-500 text-xs font-bold">{{ $loop->iteration }}</td>
                            <td class="p-3 text-xs font-bold text-stone-600"><span class="px-2 py-0.5 rounded-md bg-stone-100 border border-stone-200">{{ $order->branch->name ?? '-' }}</span></td>
                            <td class="p-3 font-extrabold text-[#1C1917]">{{ $order->customer_name }}</td>
                            <td class="p-3 text-xs text-slate-500 font-semibold">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-3 text-right font-black text-emerald-700">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="p-4 text-center text-slate-500 text-xs font-medium">Belum ada transaksi lunas.</td></tr>
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
                            <th class="p-3">Cabang</th>
                            <th class="p-3">Keterangan</th>
                            <th class="p-3">Tanggal</th>
                            <th class="p-3 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200">
                        @forelse($recentExpenses as $exp)
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="p-3 text-slate-500 text-xs font-bold">{{ $loop->iteration }}</td>
                            <td class="p-3 text-xs font-bold text-stone-600"><span class="px-2 py-0.5 rounded-md bg-stone-100 border border-stone-200">{{ $exp->branch->name ?? '-' }}</span></td>
                            <td class="p-3 font-extrabold text-[#1C1917]">{{ $exp->title }}</td>
                            <td class="p-3 text-xs text-slate-500 font-semibold">{{ $exp->date->format('d/m/Y') }}</td>
                            <td class="p-3 text-right font-black text-red-600">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="p-4 text-center text-slate-500 text-xs font-medium">Belum ada catatan pengeluaran.</td></tr>
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
