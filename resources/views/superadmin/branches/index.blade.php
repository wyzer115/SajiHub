@extends('layouts.app')
@section('title', 'Manajemen Cabang')
@section('page-title', 'Manajemen Cabang')

@section('content')
<div x-data="{ 
    showEditModal: false, 
    editBranchId: null, 
    editBranchName: '', 
    editBranchAddress: '', 
    editBranchPhone: '', 
    editBranchStatus: 'buka',
    editUrl: '',
    openEditModal(branch) {
        this.editBranchId = branch.id;
        this.editBranchName = branch.name;
        this.editBranchAddress = branch.address;
        this.editBranchPhone = branch.phone || '';
        this.editBranchStatus = branch.status;
        this.editUrl = `/superadmin/branches/${branch.id}`;
        this.showEditModal = true;
    },
    showDeleteModal: false,
    deleteUrl: '',
    deleteBranchName: '',
    openDeleteModal(branch) {
        this.deleteBranchName = branch.name;
        this.deleteUrl = `/superadmin/branches/${branch.id}`;
        this.showDeleteModal = true;
    },
    showStatusModal: false,
    statusBranchId: null,
    statusBranchName: '',
    newStatus: 'buka',
    statusNote: '',
    statusUrl: '',
    openStatusModal(branch) {
        this.statusBranchId = branch.id;
        this.statusBranchName = branch.name;
        this.newStatus = branch.status;
        this.statusNote = branch.status_note || '';
        this.statusUrl = `/superadmin/branches/${branch.id}/status`;
        this.showStatusModal = true;
    }
}" class="space-y-6">

    <!-- Header Action -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 animate-fade-in-up delay-100">
        <h2 class="text-lg font-semibold text-white">Daftar Semua Cabang</h2>
        <a href="{{ route('superadmin.branches.create') }}" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-xl font-medium transition-colors shadow-lg shadow-brand-500/20">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Cabang Baru
        </a>
    </div>

    <!-- Branches Table -->
    <div class="bg-dark-900 rounded-2xl border border-dark-700 overflow-hidden animate-fade-in-up delay-200 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-dark-800 border-b border-dark-700">
                        <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Alamat</th>
                        <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Telepon</th>
                        <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Jumlah Karyawan</th>
                        <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider">Jumlah Pesanan</th>
                        <th class="px-6 py-4 text-xs font-semibold text-dark-400 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-700/50">
                    @forelse($branches ?? [] as $branch)
                    <tr class="hover:bg-dark-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-white">{{ $branch->name }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-dark-300 truncate max-w-[200px]">{{ $branch->address }}</td>
                        <td class="px-6 py-4 text-sm text-dark-300">{{ $branch->phone ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <button @click="openStatusModal({ id: {{ $branch->id }}, name: '{{ addslashes($branch->name) }}', status: '{{ $branch->status }}', status_note: '{{ addslashes($branch->status_note) }}' })"
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border transition-all cursor-pointer
                                {{ $branch->status === 'buka' ? 'bg-green-500/10 text-green-400 border-green-500/20 hover:bg-green-500/20' : '' }}
                                {{ $branch->status === 'tutup' ? 'bg-red-500/10 text-red-400 border-red-500/20 hover:bg-red-500/20' : '' }}
                                {{ $branch->status === 'maintenance' ? 'bg-amber-500/10 text-amber-400 border-amber-500/20 hover:bg-amber-500/20' : '' }}">
                                <span class="w-1.5 h-1.5 rounded-full animate-pulse
                                {{ $branch->status === 'buka' ? 'bg-green-400' : '' }}
                                {{ $branch->status === 'tutup' ? 'bg-red-400' : '' }}
                                {{ $branch->status === 'maintenance' ? 'bg-amber-400' : '' }}"></span>
                                <span class="capitalize">{{ $branch->status }}</span>
                            </button>
                        </td>
                        <td class="px-6 py-4 text-sm text-dark-300">{{ $branch->users_count ?? 0 }}</td>
                        <td class="px-6 py-4 text-sm text-dark-300">{{ $branch->orders_count ?? 0 }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <!-- Impersonate Button -->
                                @if($branch->admin)
                                <form action="{{ route('superadmin.branches.impersonate', $branch->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1.5 bg-dark-700 hover:bg-amber-500 text-dark-200 hover:text-white rounded-lg transition-colors" title="Intip Cabang">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </form>
                                @else
                                <button disabled class="p-1.5 bg-dark-800 text-dark-600 rounded-lg cursor-not-allowed" title="Belum ada Admin Cabang">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                                    </svg>
                                </button>
                                @endif

                                <!-- Edit Button -->
                                <button type="button" @click="openEditModal({ id: {{ $branch->id }}, name: '{{ addslashes($branch->name) }}', address: '{{ addslashes($branch->address) }}', phone: '{{ addslashes($branch->phone) }}', status: '{{ $branch->status }}' })"
                                    class="p-1.5 bg-dark-700 hover:bg-brand-500 text-dark-200 hover:text-white rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>

                                <!-- Delete Button -->
                                <button type="button" @click="openDeleteModal({ id: {{ $branch->id }}, name: '{{ addslashes($branch->name) }}' })"
                                    class="p-1.5 bg-dark-700 hover:bg-red-500 text-dark-200 hover:text-white rounded-lg transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
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

    <!-- Modal Edit Cabang -->
    <div x-show="showEditModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade-in">
        <div class="bg-dark-900 border border-dark-700 rounded-3xl w-full max-w-lg p-6 shadow-2xl" @click.away="showEditModal = false">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-white">Edit Data Cabang</h3>
                <button @click="showEditModal = false" class="text-dark-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form :action="editUrl" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_name" class="block text-dark-300 text-xs font-semibold uppercase tracking-wider mb-2">Nama Cabang</label>
                    <input type="text" name="name" id="edit_name" required x-model="editBranchName"
                        class="w-full bg-dark-800 border border-dark-600 text-white rounded-xl px-4 py-2.5 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 focus:outline-none transition-all text-sm">
                </div>
                <div>
                    <label for="edit_address" class="block text-dark-300 text-xs font-semibold uppercase tracking-wider mb-2">Alamat Cabang</label>
                    <textarea name="address" id="edit_address" required rows="3" x-model="editBranchAddress"
                        class="w-full bg-dark-800 border border-dark-600 text-white rounded-xl px-4 py-2.5 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 focus:outline-none transition-all text-sm"></textarea>
                </div>
                <div>
                    <label for="edit_phone" class="block text-dark-300 text-xs font-semibold uppercase tracking-wider mb-2">Nomor Telepon</label>
                    <input type="text" name="phone" id="edit_phone" x-model="editBranchPhone"
                        class="w-full bg-dark-800 border border-dark-600 text-white rounded-xl px-4 py-2.5 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 focus:outline-none transition-all text-sm">
                </div>
                <div>
                    <label for="edit_status" class="block text-dark-300 text-xs font-semibold uppercase tracking-wider mb-2">Status Operasional</label>
                    <select name="status" id="edit_status" required x-model="editBranchStatus"
                        class="w-full bg-dark-800 border border-dark-600 text-white rounded-xl px-4 py-2.5 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 focus:outline-none transition-all text-sm cursor-pointer">
                        <option value="buka">Buka</option>
                        <option value="tutup">Tutup</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" @click="showEditModal = false"
                        class="px-5 py-2.5 bg-dark-800 hover:bg-dark-700 text-white font-medium rounded-xl transition-colors text-sm">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white font-semibold rounded-xl transition-colors text-sm shadow-lg shadow-brand-500/20">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Hapus Cabang -->
    <div x-show="showDeleteModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade-in">
        <div class="bg-dark-900 border border-dark-700 rounded-3xl w-full max-w-md p-6 shadow-2xl" @click.away="showDeleteModal = false">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-white">Hapus Cabang</h3>
                <button @click="showDeleteModal = false" class="text-dark-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="space-y-4">
                <p class="text-sm text-dark-300">
                    Apakah Anda yakin ingin menghapus cabang <strong class="text-white" x-text="deleteBranchName"></strong>? Tindakan ini akan menghapus semua data terkait dan tidak dapat dibatalkan.
                </p>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showDeleteModal = false"
                        class="px-5 py-2.5 bg-dark-800 hover:bg-dark-700 text-white font-medium rounded-xl transition-colors text-sm">
                        Batal
                    </button>
                    <form :action="deleteUrl" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-colors text-sm shadow-lg shadow-red-600/20">
                            Ya, Hapus Cabang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ubah Status Cabang -->
    <div x-show="showStatusModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade-in">
        <div class="bg-dark-900 border border-dark-700 rounded-3xl w-full max-w-lg p-6 shadow-2xl" @click.away="showStatusModal = false">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-xl font-bold text-white">Ubah Status Operasional</h3>
                    <p class="text-xs text-dark-400 mt-1" x-text="statusBranchName"></p>
                </div>
                <button @click="showStatusModal = false" class="text-dark-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form :action="statusUrl" method="POST" class="space-y-5">
                @csrf
                @method('PATCH')
                
                <!-- Radio Tiles Pilihan Status -->
                <div class="grid grid-cols-3 gap-3">
                    <!-- Buka -->
                    <label class="relative flex flex-col items-center justify-center p-4 rounded-2xl border cursor-pointer transition-all hover:bg-dark-800"
                        :class="newStatus === 'buka' ? 'border-green-500 bg-green-500/5 text-green-400 ring-1 ring-green-500/20' : 'border-dark-700 bg-dark-800/40 text-dark-300'">
                        <input type="radio" name="status" value="buka" x-model="newStatus" class="sr-only">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-400 mb-2"></span>
                        <span class="text-xs font-bold uppercase tracking-wider">Buka</span>
                    </label>
                    
                    <!-- Tutup -->
                    <label class="relative flex flex-col items-center justify-center p-4 rounded-2xl border cursor-pointer transition-all hover:bg-dark-800"
                        :class="newStatus === 'tutup' ? 'border-red-500 bg-red-500/5 text-red-400 ring-1 ring-red-500/20' : 'border-dark-700 bg-dark-800/40 text-dark-300'">
                        <input type="radio" name="status" value="tutup" x-model="newStatus" class="sr-only">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-400 mb-2"></span>
                        <span class="text-xs font-bold uppercase tracking-wider">Tutup</span>
                    </label>
                    
                    <!-- Maintenance -->
                    <label class="relative flex flex-col items-center justify-center p-4 rounded-2xl border cursor-pointer transition-all hover:bg-dark-800"
                        :class="newStatus === 'maintenance' ? 'border-amber-500 bg-amber-500/5 text-amber-400 ring-1 ring-amber-500/20' : 'border-dark-700 bg-dark-800/40 text-dark-300'">
                        <input type="radio" name="status" value="maintenance" x-model="newStatus" class="sr-only">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400 mb-2"></span>
                        <span class="text-xs font-bold uppercase tracking-wider">Maintenance</span>
                    </label>
                </div>

                <!-- Input Textarea Alasan/Catatan -->
                <div x-show="newStatus === 'tutup' || newStatus === 'maintenance'" x-cloak class="animate-fade-in">
                    <label for="status_note" class="block text-dark-300 text-xs font-semibold uppercase tracking-wider mb-2">Alasan / Catatan Penutupan</label>
                    <textarea name="status_note" id="status_note" rows="3" x-model="statusNote" :required="newStatus === 'tutup' || newStatus === 'maintenance'"
                        class="w-full bg-dark-800 border border-dark-600 text-white rounded-xl px-4 py-2.5 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 focus:outline-none transition-all text-sm"
                        placeholder="Contoh: Renovasi gedung, Libur lebaran, atau Pemeliharaan server basis data..."></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showStatusModal = false"
                        class="px-5 py-2.5 bg-dark-800 hover:bg-dark-700 text-white font-medium rounded-xl transition-colors text-sm">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white font-semibold rounded-xl transition-colors text-sm shadow-lg shadow-brand-500/20">
                        Simpan Status
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
