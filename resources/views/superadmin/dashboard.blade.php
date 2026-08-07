@extends('layouts.app')
@section('title', 'Dashboard Super Admin')
@section('page-title', 'Ringkasan Global')

@section('content')

<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card 1 -->
    <div class="bg-dark-800 border border-dark-700 rounded-2xl p-6 hover:bg-dark-800/80 hover:border-dark-600 transition-all duration-300 animate-fade-in-up delay-100 shadow-sm">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm font-medium text-dark-400 mb-1">Total Cabang Aktif</p>
                <h3 class="text-3xl font-bold text-white">{{ $totalBranches ?? 0 }} <span class="text-base font-normal text-dark-500">cabang</span></h3>
            </div>
            <div class="p-3 bg-blue-500/10 text-blue-500 rounded-xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-dark-800 border border-dark-700 rounded-2xl p-6 hover:bg-dark-800/80 hover:border-dark-600 transition-all duration-300 animate-fade-in-up delay-200 shadow-sm">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm font-medium text-dark-400 mb-1">Total Pendapatan</p>
                <h3 class="text-3xl font-bold text-white">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h3>
                @if(isset($revenueGrowth))
                <div class="mt-2 flex items-center text-sm {{ $revenueGrowth >= 0 ? 'text-emerald-500' : 'text-red-500' }}">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $revenueGrowth >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6' }}"></path>
                    </svg>
                    <span>{{ abs($revenueGrowth) }}% dari bulan lalu</span>
                </div>
                @endif
            </div>
            <div class="p-3 bg-emerald-500/10 text-emerald-500 rounded-xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-dark-800 border border-dark-700 rounded-2xl p-6 hover:bg-dark-800/80 hover:border-dark-600 transition-all duration-300 animate-fade-in-up delay-300 shadow-sm">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm font-medium text-dark-400 mb-1">Total Karyawan</p>
                <h3 class="text-3xl font-bold text-white">{{ $totalEmployees ?? 0 }} <span class="text-base font-normal text-dark-500">orang</span></h3>
            </div>
            <div class="p-3 bg-purple-500/10 text-purple-500 rounded-xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="bg-dark-800 border border-dark-700 rounded-2xl p-6 hover:bg-dark-800/80 hover:border-dark-600 transition-all duration-300 animate-fade-in-up delay-400 shadow-sm">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm font-medium text-dark-400 mb-1">Total Pesanan</p>
                <h3 class="text-3xl font-bold text-white">{{ $totalOrders ?? 0 }} <span class="text-base font-normal text-dark-500">pesanan</span></h3>
            </div>
            <div class="p-3 bg-amber-500/10 text-amber-500 rounded-xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 animate-fade-in-up delay-300">
    <!-- Monthly Global Revenue Trend -->
    <div class="bg-dark-800 border border-dark-700 rounded-2xl p-6 shadow-sm flex flex-col">
        <h3 class="text-base font-bold text-white mb-4">Tren Pendapatan Bulanan Global</h3>
        <div class="relative w-full h-[260px]">
            <canvas id="globalRevenueChart"></canvas>
        </div>
    </div>

    <!-- Revenue Comparison per Branch -->
    <div class="bg-dark-800 border border-dark-700 rounded-2xl p-6 shadow-sm flex flex-col">
        <h3 class="text-base font-bold text-white mb-4">Perbandingan Pendapatan Antar Cabang</h3>
        <div class="relative w-full h-[260px]">
            <canvas id="branchRevenueChart"></canvas>
        </div>
    </div>
</div>

<!-- Header Action -->
<div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6 animate-fade-in-up delay-400">
    <h2 class="text-lg font-semibold text-white">Daftar Cabang Aktif</h2>
    <a href="{{ route('superadmin.branches.create') }}" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-xl font-medium transition-colors shadow-lg shadow-brand-500/20">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Cabang Baru
    </a>
</div>

<!-- Branches Table -->
<div class="bg-dark-900 rounded-2xl border border-dark-700 overflow-hidden mb-8 animate-fade-in-up delay-400 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-dark-800 border-b border-dark-700">
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">ID Cabang</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Nama Cabang</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Lokasi</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Admin PJ</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Pesanan</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-700/50">
                @forelse($branches ?? [] as $branch)
                <tr class="hover:bg-dark-800/50 transition-colors">
                    <td class="px-6 py-4 text-sm font-medium text-dark-300">BR-{{ str_pad($branch->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-white">{{ $branch->name }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-dark-300 truncate max-w-[200px]">{{ $branch->address }}</td>
                    <td class="px-6 py-4">
                        @if($branch->status == 'Buka' || true)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                                Buka
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/10 text-blue-500 border border-blue-500/20">
                                Stabil
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-dark-300">
                        {{ $branch->admin->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-dark-300">{{ $branch->orders_count ?? 0 }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button class="show-branch-btn p-1.5 bg-dark-700 hover:bg-dark-600 text-dark-200 hover:text-white rounded-lg transition-colors cursor-pointer" 
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
                            <a href="{{ route('superadmin.branches.edit', $branch->id) }}" class="p-1.5 bg-dark-700 hover:bg-brand-500 text-dark-200 hover:text-white rounded-lg transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-dark-400">Belum ada data cabang.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Orders -->
<h2 class="text-lg font-semibold text-white mb-4 animate-fade-in-up delay-400">Pesanan Terakhir</h2>
<div class="bg-dark-900 rounded-2xl border border-dark-700 overflow-hidden animate-fade-in-up delay-400 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-dark-800 border-b border-dark-700">
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">ID Pesanan</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Cabang</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Status Dapur</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Pembayaran</th>
                    <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-700/50">
                @forelse($recentOrders ?? [] as $order)
                <tr class="hover:bg-dark-800/50 transition-colors">
                    <td class="px-6 py-4 text-sm font-medium text-white">#{{ $order->id }}</td>
                    <td class="px-6 py-4 text-sm text-dark-300">{{ $order->branch->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-dark-300">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-white">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @if($order->order_status == 'pending')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-yellow-500/10 text-yellow-500 border border-yellow-500/20">Menunggu</span>
                        @elseif($order->order_status == 'cooking')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-orange-500/10 text-orange-500 border border-orange-500/20">Dimasak</span>
                        @elseif($order->order_status == 'served')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-500/10 text-blue-500 border border-blue-500/20">Disajikan</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">Selesai</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($order->payment_status == 'paid')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">Lunas</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-red-500/10 text-red-500 border border-red-500/20">Belum Bayar</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-xs text-dark-400">{{ $order->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-dark-400">Belum ada pesanan terbaru.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detail Cabang -->
<div id="branch-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm hidden animate-fade-in">
    <div class="bg-dark-900 border border-dark-700 rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl relative animate-scale-up">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-dark-800 flex justify-between items-center bg-dark-950/50">
            <h3 class="text-xl font-bold text-white uppercase tracking-tight flex items-center gap-2">
                🏢 Detail Cabang
            </h3>
            <button id="close-modal-btn" class="text-dark-400 hover:text-white hover:bg-dark-800 p-1.5 rounded-lg transition-colors cursor-pointer">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <!-- Content -->
        <div class="p-6 space-y-6">
            <div>
                <div class="text-[10px] uppercase font-bold tracking-widest text-brand-500 mb-1">Nama Cabang</div>
                <h4 id="modal-branch-name" class="text-2xl font-black text-white">Nama Cabang</h4>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="text-[10px] uppercase font-bold tracking-widest text-dark-500 mb-1">ID Cabang</div>
                    <div id="modal-branch-id" class="text-sm font-semibold text-white">BR-001</div>
                </div>
                <div>
                    <div class="text-[10px] uppercase font-bold tracking-widest text-dark-500 mb-1">Telepon</div>
                    <div id="modal-branch-phone" class="text-sm font-semibold text-white">-</div>
                </div>
            </div>

            <div>
                <div class="text-[10px] uppercase font-bold tracking-widest text-dark-500 mb-1">Alamat Lengkap</div>
                <p id="modal-branch-address" class="text-sm text-dark-300 leading-relaxed font-medium">Alamat</p>
            </div>

            <!-- PJ Admin Info -->
            <div class="bg-dark-950/40 border border-dark-800 rounded-2xl p-4">
                <div class="text-[10px] uppercase font-bold tracking-widest text-brand-500 mb-2">Penanggung Jawab (PJ)</div>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-dark-400">Nama Admin:</span>
                        <span id="modal-branch-admin-name" class="text-white font-bold">-</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-dark-400">Username:</span>
                        <span id="modal-branch-admin-username" class="text-dark-300 font-mono">-</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-dark-400">Email:</span>
                        <span id="modal-branch-admin-email" class="text-dark-300 font-mono">-</span>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-dark-950/40 border border-dark-800 rounded-xl p-3 text-center">
                    <div class="text-xs text-dark-500 font-semibold mb-1">Karyawan</div>
                    <div id="modal-branch-users-count" class="text-lg font-black text-white">0</div>
                </div>
                <div class="bg-dark-950/40 border border-dark-800 rounded-xl p-3 text-center">
                    <div class="text-xs text-dark-500 font-semibold mb-1">Pesanan</div>
                    <div id="modal-branch-orders-count" class="text-lg font-black text-white">0</div>
                </div>
                <div class="bg-dark-950/40 border border-dark-800 rounded-xl p-3 text-center">
                    <div class="text-xs text-dark-500 font-semibold mb-1">Total Omset</div>
                    <div id="modal-branch-revenue" class="text-lg font-black text-brand-400">Rp 0</div>
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
                backgroundColor: '#3b82f6',
                borderRadius: 8,
                maxBarThickness: 40
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
