@extends('layouts.app')
@section('title', 'Manajemen Kategori')
@section('page-title', 'Daftar Kategori')

@section('content')
<div class="max-w-4xl space-y-6 animate-fade-in-up">
    <!-- Form Tambah -->
    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="flex flex-col sm:flex-row gap-4 items-end">
            @csrf
            <div class="flex-1 w-full">
                <label for="name" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Tambah Kategori Baru</label>
                <input type="text" name="name" id="name" required placeholder="Nama kategori baru..."
                    class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all">
            </div>
            <button type="submit" class="w-full sm:w-auto bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold px-6 py-3 rounded-xl transition-all shadow-md cursor-pointer whitespace-nowrap">
                + Tambah
            </button>
        </form>
        @error('name')
            <p class="text-red-600 text-xs mt-2 font-bold">{{ $message }}</p>
        @enderror
    </div>

    <!-- Daftar Kategori -->
    <div class="bg-white border border-stone-200 rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-stone-100 text-stone-700 text-xs font-extrabold uppercase tracking-wider border-b border-stone-200">
                    <tr>
                        <th class="px-6 py-4 w-16">No</th>
                        <th class="px-6 py-4">Nama Kategori</th>
                        <th class="px-6 py-4">Jumlah Menu</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @forelse($categories ?? [] as $category)
                    <tr class="hover:bg-stone-50 transition-colors group">
                        <td class="px-6 py-4 text-slate-500 font-bold text-xs">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="flex items-center space-x-2">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $category->name }}" required
                                    class="bg-stone-50 border border-stone-300 focus:border-[#BD2000] text-[#1C1917] font-extrabold rounded-xl px-3 py-1.5 focus:outline-none transition-all w-full sm:w-auto">
                                <button type="submit" class="opacity-0 group-hover:opacity-100 bg-stone-100 hover:bg-[#BD2000] text-stone-700 hover:text-white px-3 py-1.5 rounded-xl transition-all text-xs font-bold border border-stone-300 cursor-pointer">
                                    Simpan
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-[#BD2000]/10 text-[#BD2000] border border-[#BD2000]/20">
                                {{ $category->menus_count ?? 0 }} Menu
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return showConfirm(event, 'Hapus kategori ini? Semua menu di dalamnya mungkin akan terdampak.', 'Hapus Kategori', 'Ya, Hapus');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-600 hover:text-white rounded-xl transition-all border border-red-200 cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-500 font-medium text-sm">Belum ada kategori. Silakan buat di atas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
