@extends('layouts.app')
@section('title', 'Manajemen Kategori')
@section('page-title', 'Daftar Kategori')

@section('content')
<div class="max-w-4xl space-y-6 animate-fade-in-up">
    <!-- Form Tambah -->
    <div class="bg-dark-800 border border-dark-700 rounded-2xl p-6">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="flex flex-col sm:flex-row gap-4 items-end">
            @csrf
            <div class="flex-1 w-full">
                <label for="name" class="block text-dark-300 text-sm font-medium mb-2">Tambah Kategori Baru</label>
                <input type="text" name="name" id="name" required placeholder="Nama kategori baru..."
                    class="w-full bg-dark-900 border border-dark-600 text-white rounded-xl px-4 py-3 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none transition-all">
            </div>
            <button type="submit" class="w-full sm:w-auto bg-brand-500 hover:bg-brand-600 text-white font-semibold px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-brand-500/25 whitespace-nowrap">
                + Tambah
            </button>
        </form>
        @error('name')
            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
        @enderror
    </div>

    <!-- Daftar Kategori -->
    <div class="bg-dark-900 border border-dark-700 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-dark-800 text-dark-400 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium w-16">ID</th>
                        <th class="px-6 py-4 font-medium">Nama Kategori</th>
                        <th class="px-6 py-4 font-medium">Jumlah Menu</th>
                        <th class="px-6 py-4 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-700/50">
                    @forelse($categories ?? [] as $category)
                    <tr class="hover:bg-dark-800/50 transition-colors group">
                        <td class="px-6 py-4 text-dark-400">#{{ $category->id }}</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="flex items-center space-x-2">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $category->name }}" required
                                    class="bg-transparent border border-transparent hover:border-dark-600 focus:border-brand-500 focus:bg-dark-900 text-white rounded-lg px-3 py-1.5 focus:outline-none transition-all w-full sm:w-auto">
                                <button type="submit" class="opacity-0 group-hover:opacity-100 bg-dark-700 hover:bg-dark-600 text-dark-200 px-3 py-1.5 rounded-lg transition-all text-xs font-medium">
                                    Simpan
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-dark-700 text-dark-300">
                                {{ $category->menus_count ?? 0 }} Menu
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini? Semua menu di dalamnya mungkin akan terdampak.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500/10 text-red-400 hover:bg-red-500/20 px-3 py-1.5 rounded-lg transition-all text-sm font-medium">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-dark-400">Belum ada kategori</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
