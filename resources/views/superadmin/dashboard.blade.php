@extends('layouts.app')
@section('title', 'Dashboard Super Admin')
@section('page-title', 'Ringkasan Global')

@section('content')

<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card 1 -->
    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Total Cabang Aktif</p>
            <h3 class="text-3xl font-black text-blue-600">{{ $totalBranches ?? 0 }} <span class="text-sm font-bold text-slate-500">cabang</span></h3>
        </div>
        <div class="p-3.5 bg-blue-50 text-blue-600 rounded-2xl border border-blue-200 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Total Pendapatan</p>
            <h3 class="text-3xl font-black text-emerald-600">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h3>
            @if(isset($revenueGrowth))
            <div class="mt-2 flex items-center text-xs font-extrabold {{ $revenueGrowth >= 0 ? 'text-emerald-700' : 'text-red-600' }}">
                <span>{{ $revenueGrowth >= 0 ? '▲ +' : '▼ -' }}{{ abs($revenueGrowth) }}% dari bulan lalu</span>
            </div>
            @endif
        </div>
        <div class="p-3.5 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-200 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Total Karyawan</p>
            <h3 class="text-3xl font-black text-purple-600">{{ $totalEmployees ?? 0 }} <span class="text-sm font-bold text-slate-500">orang</span></h3>
        </div>
        <div class="p-3.5 bg-purple-50 text-purple-600 rounded-2xl border border-purple-200 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Total Pesanan</p>
            <h3 class="text-3xl font-black text-[#BD2000]">{{ $totalOrders ?? 0 }} <span class="text-sm font-bold text-slate-500">Pesanan</span></h3>
        </div>
        <div class="p-3.5 bg-[#BD2000]/10 text-[#BD2000] rounded-2xl border border-[#BD2000]/20 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 animate-fade-in-up">
    <!-- Monthly Global Revenue Trend -->
    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex flex-col">
        <h3 class="text-base font-black text-[#8C0000] mb-4">Tren Pendapatan Bulanan Global</h3>
        <div class="relative w-full h-[260px]">
            <canvas id="globalRevenueChart"></canvas>
        </div>
    </div>

    <!-- Revenue Comparison per Branch -->
    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex flex-col">
        <h3 class="text-base font-black text-[#8C0000] mb-4">Perbandingan Pendapatan Antar Cabang</h3>
        <div class="relative w-full h-[260px]">
            <canvas id="branchRevenueChart"></canvas>
        </div>
    </div>
</div>

<!-- Header Action -->
<div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6 animate-fade-in-up">
    <h2 class="text-lg font-black text-[#8C0000]">Daftar Cabang Aktif</h2>
    <a href="{{ route('superadmin.branches.create') }}" class="inline-flex items-center gap-2 bg-[#BD2000] hover:bg-[#8C0000] text-white px-5 py-2.5 rounded-xl font-extrabold text-sm transition-all shadow-md cursor-pointer">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
        Tambah Cabang Baru
    </a>
</div>

<!-- Branches Table -->
<div class="bg-white border border-stone-200 rounded-3xl overflow-hidden mb-8 shadow-sm animate-fade-in-up">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-stone-100 border-b border-stone-200 text-stone-700 text-xs font-extrabold uppercase tracking-wider">
                    <th class="px-6 py-4">Nama Cabang</th>
                    <th class="px-6 py-4">Lokasi</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Admin PJ</th>
                    <th class="px-6 py-4">Pesanan</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-200">
                @forelse($branches ?? [] as $branch)
                <tr class="hover:bg-stone-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="text-sm font-extrabold text-[#1C1917]">{{ $branch->name }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 font-semibold truncate max-w-[200px]">{{ $branch->address }}</td>
                    <td class="px-6 py-4">
                        @if($branch->status == 'buka')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                                Buka
                            </span>
                        @elseif($branch->status == 'tutup')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                <span class="w-2 h-2 rounded-full bg-red-500 shrink-0"></span>
                                Tutup
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                <span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
                                Maintenance
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-700 font-bold">
                        {{ $branch->admin->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-700 font-bold">{{ $branch->orders_count ?? 0 }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button class="show-branch-btn p-2 bg-stone-100 hover:bg-stone-200 text-stone-700 border border-stone-300 rounded-xl transition-colors cursor-pointer" 
                                    title="Lihat Detail"
                                    data-id="BR-{{ str_pad($branch->id, 3, '0', STR_PAD_LEFT) }}"
                                    data-name="{{ $branch->name }}"
                                    data-address="{{ $branch->address }}"
                                    data-phone="{{ $branch->phone ?? '-' }}"
                                    data-admin-name="{{ $branch->admin->name ?? '-' }}"
                                    data-admin-username="{{ $branch->admin->username ?? '-' }}"
                                    data-admin-email="{{ $branch->admin->email ?? '-' }}"
                                    data-users-count="{{ $branch->users_count ?? 0 }}"
                                    data-orders-count="{{ $branch->orders_count ?? 0 }}"
                                    data-revenue="Rp {{ number_format($branchRevenues->firstWhere('id', $branch->id)->orders_sum_total_price ?? 0, 0, ',', '.') }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                            <a href="{{ route('superadmin.branches.edit', $branch->id) }}" class="p-2 bg-stone-100 hover:bg-[#BD2000] text-stone-700 hover:text-white rounded-xl transition-colors border border-stone-300" title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-slate-500 font-medium">Belum ada data cabang.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Orders -->
<h2 class="text-lg font-black text-[#8C0000] mb-4 animate-fade-in-up">Pesanan Terakhir</h2>
<div class="bg-white border border-stone-200 rounded-3xl overflow-hidden mb-8 shadow-sm animate-fade-in-up">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-stone-100 border-b border-stone-200 text-stone-700 text-xs font-extrabold uppercase tracking-wider">
                    <th class="px-6 py-4">Cabang</th>
                    <th class="px-6 py-4">Pelanggan</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Status Dapur</th>
                    <th class="px-6 py-4">Pembayaran</th>
                    <th class="px-6 py-4 text-right">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-200">
                @forelse($recentOrders ?? [] as $order)
                <tr class="hover:bg-stone-50 transition-colors">
                    <td class="px-6 py-4 text-sm font-extrabold text-[#1C1917]">{{ $order->branch->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-700 font-semibold">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4 text-sm font-black text-[#BD2000]">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @if($order->order_status == 'pending')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-amber-100 text-amber-800 border border-amber-300">Menunggu</span>
                        @elseif($order->order_status == 'cooking')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-orange-100 text-orange-800 border border-orange-300">Dimasak</span>
                        @elseif($order->order_status == 'served')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-blue-100 text-blue-800 border border-blue-300">Disajikan</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-800 border border-emerald-300">Selesai</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($order->payment_status == 'paid')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-800 border border-emerald-300">Lunas</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-red-100 text-red-600 border border-red-200">Belum Bayar</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-xs text-slate-500 font-semibold text-right">{{ $order->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-slate-500 font-medium">Belum ada pesanan terbaru.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detail Cabang -->
<div id="branch-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm hidden animate-fade-in">
    <div class="bg-white border border-stone-200 rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl relative">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-stone-200 flex justify-between items-center bg-stone-50">
            <h3 class="text-xl font-black text-[#8C0000] uppercase tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-[#8C0000]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0V11m0 0h5M12 11H7m5 0v5m0 0h5m-5 0H7"/></svg>
                <span>Detail Cabang</span>
            </h3>
            <button id="close-modal-btn" class="text-stone-400 hover:text-stone-700 hover:bg-stone-100 p-1.5 rounded-xl transition-colors cursor-pointer">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <!-- Content -->
        <div class="p-6 space-y-6">
            <div>
                <div class="text-[10px] uppercase font-bold tracking-widest text-[#BD2000] mb-1">Nama Cabang</div>
                <h4 id="modal-branch-name" class="text-2xl font-black text-[#1C1917]">Nama Cabang</h4>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="text-[10px] uppercase font-bold tracking-widest text-stone-500 mb-1">ID Cabang</div>
                    <div id="modal-branch-id" class="text-sm font-black text-[#1C1917]">BR-001</div>
                </div>
                <div>
                    <div class="text-[10px] uppercase font-bold tracking-widest text-stone-500 mb-1">Telepon</div>
                    <div id="modal-branch-phone" class="text-sm font-black text-[#1C1917]">-</div>
                </div>
            </div>

            <div>
                <div class="text-[10px] uppercase font-bold tracking-widest text-stone-500 mb-1">Alamat Lengkap</div>
                <p id="modal-branch-address" class="text-sm text-slate-700 leading-relaxed font-semibold">Alamat</p>
            </div>

            <!-- PJ Admin Info -->
            <div class="bg-stone-50 border border-stone-200 rounded-2xl p-4">
                <div class="text-[10px] uppercase font-bold tracking-widest text-[#BD2000] mb-2">Penanggung Jawab (PJ)</div>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-600 font-medium">Nama Admin:</span>
                        <span id="modal-branch-admin-name" class="text-[#1C1917] font-black">-</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-600 font-medium">Username:</span>
                        <span id="modal-branch-admin-username" class="text-slate-700 font-mono font-bold">-</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-600 font-medium">Email:</span>
                        <span id="modal-branch-admin-email" class="text-slate-700 font-mono font-bold">-</span>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-stone-50 border border-stone-200 rounded-2xl p-3 text-center">
                    <div class="text-xs text-stone-500 font-bold mb-1">Karyawan</div>
                    <div id="modal-branch-users-count" class="text-lg font-black text-[#1C1917]">0</div>
                </div>
                <div class="bg-stone-50 border border-stone-200 rounded-2xl p-3 text-center">
                    <div class="text-xs text-stone-500 font-bold mb-1">Pesanan</div>
                    <div id="modal-branch-orders-count" class="text-lg font-black text-[#1C1917]">0</div>
                </div>
                <div class="bg-stone-50 border border-stone-200 rounded-2xl p-3 text-center">
                    <div class="text-xs text-stone-500 font-bold mb-1">Total Omset</div>
                    <div id="modal-branch-revenue" class="text-lg font-black text-[#BD2000]">Rp 0</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Monthly global trend line chart
    const globalCtx = document.getElementById('globalRevenueChart').getContext('2d');
    new Chart(globalCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyLabels ?? []) !!},
            datasets: [{
                label: 'Omzet Bulanan (Rp)',
                data: {!! json_encode($monthlyValues ?? []) !!},
                borderColor: '#059669',
                backgroundColor: 'rgba(5, 150, 105, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#059669',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
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

    // 2. Bar chart comparing branches
    const branchCtx = document.getElementById('branchRevenueChart').getContext('2d');
    const branchNames = {!! json_encode($branchRevenues ? $branchRevenues->pluck('name') : []) !!};
    const branchTotals = {!! json_encode($branchRevenues ? $branchRevenues->map(fn($b) => (float)($b->orders_sum_total_price ?? 0)) : []) !!};

    new Chart(branchCtx, {
        type: 'bar',
        data: {
            labels: branchNames,
            datasets: [{
                label: 'Total Pendapatan (Rp)',
                data: branchTotals,
                backgroundColor: '#bd2000',
                borderRadius: 12,
                borderSkipped: false,
                maxBarThickness: 32
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

    // Modal Detail Cabang Controller
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('branch-modal');
        const closeModalBtn = document.getElementById('close-modal-btn');
        
        const mName = document.getElementById('modal-branch-name');
        const mId = document.getElementById('modal-branch-id');
        const mPhone = document.getElementById('modal-branch-phone');
        const mAddress = document.getElementById('modal-branch-address');
        const mAdminName = document.getElementById('modal-branch-admin-name');
        const mAdminUsername = document.getElementById('modal-branch-admin-username');
        const mAdminEmail = document.getElementById('modal-branch-admin-email');
        const mUsersCount = document.getElementById('modal-branch-users-count');
        const mOrdersCount = document.getElementById('modal-branch-orders-count');
        const mRevenue = document.getElementById('modal-branch-revenue');

        document.querySelectorAll('.show-branch-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                mName.textContent = btn.dataset.name;
                mId.textContent = btn.dataset.id;
                mPhone.textContent = btn.dataset.phone;
                mAddress.textContent = btn.dataset.address;
                mAdminName.textContent = btn.dataset.adminName;
                mAdminUsername.textContent = btn.dataset.adminUsername;
                mAdminEmail.textContent = btn.dataset.adminEmail;
                mUsersCount.textContent = btn.dataset.usersCount;
                mOrdersCount.textContent = btn.dataset.ordersCount;
                mRevenue.textContent = btn.dataset.revenue;

                modal.classList.remove('hidden');
            });
        });

        const closeModal = () => {
            modal.classList.add('hidden');
        };

        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', closeModal);
        }
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });
        }
    });
</script>
@endpush
