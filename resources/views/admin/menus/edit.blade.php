@extends('layouts.app')
@section('title', 'Edit Menu')
@section('page-title', 'Edit Menu: ' . $menu->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6 animate-fade-in-up">
    <a href="{{ route('admin.menus.index') }}" class="inline-flex items-center text-dark-300 hover:text-white transition-colors mb-4">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Daftar
    </a>

    <div class="bg-dark-800 border border-dark-700 rounded-2xl p-8">
        <form action="{{ route('admin.menus.update', $menu) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-dark-300 text-sm font-medium mb-2">Nama Menu</label>
                <input type="text" name="name" id="name" value="{{ old('name', $menu->name) }}" required 
                    class="w-full bg-dark-900 border border-dark-600 text-white rounded-xl px-4 py-3 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none transition-all"
                    placeholder="Masukkan nama menu">
                @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="category_id" class="block text-dark-300 text-sm font-medium mb-2">Kategori</label>
                    <select name="category_id" id="category_id" required 
                        class="w-full bg-dark-900 border border-dark-600 text-white rounded-xl px-4 py-3 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none transition-all appearance-none">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $menu->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block text-dark-300 text-sm font-medium mb-2">Harga (Rp)</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $menu->price) }}" required min="0" step="100"
                        class="w-full bg-dark-900 border border-dark-600 text-white rounded-xl px-4 py-3 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none transition-all"
                        placeholder="Contoh: 25000">
                    @error('price')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="status" class="block text-dark-300 text-sm font-medium mb-2">Status Ketersediaan</label>
                <select name="status" id="status" required 
                    class="w-full bg-dark-900 border border-dark-600 text-white rounded-xl px-4 py-3 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none transition-all appearance-none">
                    <option value="available" {{ old('status', $menu->status) == 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="sold_out" {{ old('status', $menu->status) == 'sold_out' ? 'selected' : '' }}>Habis</option>
                </select>
                @error('status')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="image" class="block text-dark-300 text-sm font-medium mb-2">Gambar Menu (Opsional - Biarkan kosong jika tidak ingin mengubah)</label>
                
                @if($menu->image)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $menu->image) }}" class="h-20 w-20 object-cover rounded-lg border border-dark-600">
                </div>
                @endif
                
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full bg-dark-900 border border-dark-600 text-dark-300 rounded-xl px-4 py-3 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-dark-700 file:text-white hover:file:bg-dark-600 transition-all focus:outline-none">
                @error('image')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 flex justify-end space-x-4">
                <a href="{{ route('admin.menus.index') }}" class="bg-dark-700 hover:bg-dark-600 text-dark-200 px-6 py-3 rounded-xl transition-all font-medium">Batal</a>
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white font-semibold px-8 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-brand-500/25">
                    Perbarui Menu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
