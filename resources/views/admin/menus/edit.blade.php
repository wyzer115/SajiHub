@extends('layouts.app')
@section('title', 'Edit Menu')
@section('page-title', 'Edit Menu: ' . $menu->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6 animate-fade-in-up">
    <a href="{{ route('admin.menus.index') }}" class="inline-flex items-center text-slate-600 hover:text-[#BD2000] font-bold text-sm transition-colors mb-4">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Daftar Menu
    </a>

    <div class="bg-white border border-stone-200 rounded-3xl p-8 shadow-sm">
        <form action="{{ route('admin.menus.update', $menu) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Nama Menu *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $menu->name) }}" required 
                    class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                    placeholder="Masukkan nama menu">
                @error('name')
                    <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="category_id" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Kategori *</label>
                    <select name="category_id" id="category_id" required 
                        class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $menu->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Harga (Rp) *</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $menu->price) }}" required min="0" step="100"
                        class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                        placeholder="Contoh: 25000">
                    @error('price')
                        <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="status" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Status Ketersediaan</label>
                <select name="status" id="status" required 
                    class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all">
                    <option value="available" {{ old('status', $menu->status) == 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="sold_out" {{ old('status', $menu->status) == 'sold_out' ? 'selected' : '' }}>Habis</option>
                </select>
                @error('status')
                    <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Manual Input Gambar (Pilihan URL atau File Upload) -->
            <div class="space-y-3 p-4 bg-stone-50 rounded-2xl border border-stone-200">
                <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider">Input Gambar Menu (Manual Link / Upload)</label>
                
                @if($menu->image)
                <div class="flex items-center gap-3 p-2 bg-white rounded-xl border border-stone-200">
                    <img src="{{ $menu->image_url }}" class="h-16 w-16 object-cover rounded-lg border border-stone-300 shrink-0" alt="{{ $menu->name }}">
                    <span class="text-xs text-slate-500 font-medium">Gambar saat ini: <code class="text-[#BD2000] font-mono text-[11px]">{{ Str::limit($menu->image, 30) }}</code></span>
                </div>
                @endif
                
                <div>
                    <label for="image_url" class="block text-xs text-slate-600 font-semibold mb-1">🔗 Ketik / Paste Manual Link URL Gambar (Opsional)</label>
                    <input type="url" name="image_url" id="image_url" value="{{ old('image_url', str_starts_with($menu->image ?? '', 'http') ? $menu->image : '') }}"
                        class="w-full bg-white border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-2.5 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                        placeholder="https://images.unsplash.com/... atau tautan gambar langsung">
                </div>

                <div class="text-xs text-slate-400 font-bold text-center">--- ATAU UPLOAD FILE ---</div>

                <div>
                    <label for="image" class="block text-xs text-slate-600 font-semibold mb-1">📁 Upload File Gambar dari Perangkat</label>
                    <input type="file" name="image" id="image" accept="image/*"
                        class="w-full bg-white border border-stone-300 text-slate-700 rounded-xl px-4 py-2.5 text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-stone-100 file:text-stone-700 hover:file:bg-stone-200 transition-all focus:outline-none">
                </div>
                @error('image')
                    <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p>
                @enderror
                @error('image_url')
                    <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Resep Bahan (BOM) Section -->
            <div class="border-t border-stone-200 pt-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h4 class="text-base font-black text-[#8C0000] flex items-center gap-2">
                            <span>🥩 Resep Bahan Baku (BOM)</span>
                            <span class="text-xs font-bold text-[#BD2000] bg-[#BD2000]/10 px-2.5 py-0.5 rounded-full border border-[#BD2000]/20">Auto Stok Deduct</span>
                        </h4>
                        <p class="text-slate-600 text-xs font-medium mt-1">Pilih bahan inventaris dan takaran per 1 porsi menu ini. Stok bahan akan otomatis berkurang setiap transaksi.</p>
                    </div>
                    <button type="button" id="add-ingredient-btn" class="px-3.5 py-2 bg-[#BD2000]/10 hover:bg-[#BD2000]/20 text-[#BD2000] rounded-xl text-xs font-extrabold border border-[#BD2000]/20 transition-all flex items-center gap-1 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah Bahan
                    </button>
                </div>

                <div id="ingredients-container" class="space-y-3">
                    @forelse($menu->ingredients as $idx => $ing)
                    <div class="ingredient-row grid grid-cols-12 gap-3 items-center bg-stone-50 p-3.5 rounded-2xl border border-stone-200">
                        <div class="col-span-6">
                            <label class="block text-slate-600 text-xs font-bold mb-1">Pilih Bahan Inventaris</label>
                            <select name="ingredients[{{ $idx }}][inventory_id]" class="w-full bg-white border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-3 py-2 text-sm focus:border-[#BD2000] focus:outline-none">
                                <option value="">-- Pilih Bahan Baku --</option>
                                @foreach($inventories ?? [] as $inv)
                                    <option value="{{ $inv->id }}" {{ $ing->inventory_id == $inv->id ? 'selected' : '' }}>
                                        {{ $inv->name }} (Satuan: {{ $inv->unit }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-4">
                            <label class="block text-slate-600 text-xs font-bold mb-1">Takaran / Porsi</label>
                            <input type="number" step="0.001" name="ingredients[{{ $idx }}][quantity]" value="{{ floatval($ing->quantity) }}" placeholder="Contoh: 0.2" class="w-full bg-white border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-3 py-2 text-sm focus:border-[#BD2000] focus:outline-none">
                        </div>
                        <div class="col-span-2 text-right pt-4">
                            <button type="button" class="remove-ingredient-btn text-red-600 hover:bg-red-600 hover:text-white text-xs font-bold px-3 py-1.5 bg-red-50 rounded-xl border border-red-200 transition-all cursor-pointer">
                                Hapus
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="ingredient-row grid grid-cols-12 gap-3 items-center bg-stone-50 p-3.5 rounded-2xl border border-stone-200">
                        <div class="col-span-6">
                            <label class="block text-slate-600 text-xs font-bold mb-1">Pilih Bahan Inventaris</label>
                            <select name="ingredients[0][inventory_id]" class="w-full bg-white border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-3 py-2 text-sm focus:border-[#BD2000] focus:outline-none">
                                <option value="">-- Pilih Bahan Baku --</option>
                                @foreach($inventories ?? [] as $inv)
                                    <option value="{{ $inv->id }}">{{ $inv->name }} (Satuan: {{ $inv->unit }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-4">
                            <label class="block text-slate-600 text-xs font-bold mb-1">Takaran / Porsi</label>
                            <input type="number" step="0.001" name="ingredients[0][quantity]" placeholder="Contoh: 0.2" class="w-full bg-white border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-3 py-2 text-sm focus:border-[#BD2000] focus:outline-none">
                        </div>
                        <div class="col-span-2 text-right pt-4">
                            <button type="button" class="remove-ingredient-btn text-red-600 hover:bg-red-600 hover:text-white text-xs font-bold px-3 py-1.5 bg-red-50 rounded-xl border border-red-200 transition-all cursor-pointer">
                                Hapus
                            </button>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let ingredientIndex = {{ count($menu->ingredients) > 0 ? count($menu->ingredients) : 1 }};
                    const container = document.getElementById('ingredients-container');
                    const addBtn = document.getElementById('add-ingredient-btn');

                    const inventoryOptions = `@foreach($inventories ?? [] as $inv)<option value="{{ $inv->id }}">{{ addslashes($inv->name) }} (Satuan: {{ $inv->unit }})</option>@endforeach`;

                    addBtn.addEventListener('click', function() {
                        const row = document.createElement('div');
                        row.className = 'ingredient-row grid grid-cols-12 gap-3 items-center bg-stone-50 p-3.5 rounded-2xl border border-stone-200';
                        row.innerHTML = `
                            <div class="col-span-6">
                                <label class="block text-slate-600 text-xs font-bold mb-1">Pilih Bahan Inventaris</label>
                                <select name="ingredients[${ingredientIndex}][inventory_id]" class="w-full bg-white border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-3 py-2 text-sm focus:border-[#BD2000] focus:outline-none">
                                    <option value="">-- Pilih Bahan Baku --</option>
                                    ${inventoryOptions}
                                </select>
                            </div>
                            <div class="col-span-4">
                                <label class="block text-slate-600 text-xs font-bold mb-1">Takaran / Porsi</label>
                                <input type="number" step="0.001" name="ingredients[${ingredientIndex}][quantity]" placeholder="Contoh: 0.2" class="w-full bg-white border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-3 py-2 text-sm focus:border-[#BD2000] focus:outline-none">
                            </div>
                            <div class="col-span-2 text-right pt-4">
                                <button type="button" class="remove-ingredient-btn text-red-600 hover:bg-red-600 hover:text-white text-xs font-bold px-3 py-1.5 bg-red-50 rounded-xl border border-red-200 transition-all cursor-pointer">
                                    Hapus
                                </button>
                            </div>
                        `;
                        container.appendChild(row);
                        ingredientIndex++;
                    });

                    container.addEventListener('click', function(e) {
                        if (e.target.classList.contains('remove-ingredient-btn')) {
                            const rows = container.querySelectorAll('.ingredient-row');
                            if (rows.length > 1) {
                                e.target.closest('.ingredient-row').remove();
                            } else {
                                e.target.closest('.ingredient-row').querySelectorAll('input, select').forEach(el => el.value = '');
                            }
                        }
                    });
                });
            </script>

            <div class="pt-4 flex justify-end space-x-4">
                <a href="{{ route('admin.menus.index') }}" class="bg-stone-100 hover:bg-stone-200 text-stone-700 border border-stone-300 px-6 py-3 rounded-xl transition-all font-bold">Batal</a>
                <button type="submit" class="bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold px-8 py-3 rounded-xl transition-all shadow-md cursor-pointer">
                    Perbarui Menu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
