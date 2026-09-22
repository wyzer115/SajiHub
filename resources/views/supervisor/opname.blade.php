@extends('layouts.app')
@section('title', 'Audit & Rekonsiliasi Stok Harian')
@section('page-title', 'Lembar Audit & Rekonsiliasi Stok Harian (SPV)')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 animate-fade-in-up">

    <!-- Header info banner & Date Filter -->
    <div class="bg-white border border-stone-200 rounded-3xl p-6 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-[#8C0000] flex items-center gap-2 mb-1">
                <svg class="w-6 h-6 text-[#BD2000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <span>Lembar Rekonsiliasi Stok Harian (Opening - In - Out - Closing)</span>
            </h2>
            <p class="text-stone-600 text-xs font-medium">Bandingkan pemakaian fisik riil di dapur dengan data penjualan kasir (PLU) untuk mendeteksi kerugian & menjaga SOP resep.</p>
        </div>
        <form method="GET" action="{{ route('supervisor.opname.index') }}" class="flex items-center gap-2 shrink-0">
            <label for="date" class="text-xs font-bold text-stone-600">Tanggal Audit:</label>
            <input type="date" name="date" id="date" value="{{ $date }}" onchange="this.form.submit()"
                class="bg-stone-50 border border-stone-300 text-stone-800 text-xs font-bold rounded-xl px-3 py-2 focus:border-[#BD2000] focus:outline-none">
        </form>
    </div>

    <!-- Main Card -->
    <div class="bg-white border border-stone-200 rounded-3xl p-8 shadow-sm space-y-6">

        <!-- Mode Tab Selector: Form Audit vs Riwayat Log -->
        <div class="flex items-center gap-3 p-1.5 bg-stone-100 rounded-2xl w-fit">
            <button type="button" id="tab-audit" onclick="switchMainSection('audit')"
                class="px-5 py-2.5 rounded-xl text-xs font-extrabold transition-all shadow-sm bg-white text-[#BD2000]">
                📋 Form Audit Lembar Stok
            </button>
            <button type="button" id="tab-history" onclick="switchMainSection('history')"
                class="px-5 py-2.5 rounded-xl text-xs font-bold text-stone-600 hover:text-stone-900 transition-all">
                📊 Riwayat Log Audit Harian
            </button>
        </div>

        <!-- Section 1: Form Audit -->
        <div id="section-audit-form" class="space-y-6">

            <!-- Sub Tab: Existing vs New Item -->
            <div class="flex items-center gap-2 border-b border-stone-100 pb-3">
                <button type="button" id="subtab-existing" onclick="setMode('existing')"
                    class="px-4 py-2 rounded-lg text-xs font-bold bg-stone-100 text-[#BD2000]">
                    📦 Pilih Barang dari Inventaris
                </button>
                <button type="button" id="subtab-new" onclick="setMode('new')"
                    class="px-4 py-2 rounded-lg text-xs font-bold text-stone-500 hover:text-stone-900">
                    ➕ Tambah Barang Baru
                </button>
            </div>

            <form action="{{ route('supervisor.opname.store') }}" method="POST" class="space-y-6" id="opname-form">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">
                <input type="hidden" name="mode" id="form-mode" value="existing">

                <!-- Mode 1: Select Existing Item -->
                <div id="section-existing" class="space-y-4">
                    <div>
                        <label for="inventory_id" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Pilih Bahan Baku Inventaris *</label>
                        <select name="inventory_id" id="inventory_id" onchange="onInventorySelect()" required
                            class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3.5 text-sm focus:border-[#BD2000] focus:outline-none transition-all">
                            <option value="">-- Pilih Bahan Baku dari Inventaris Cabang --</option>
                            @foreach($inventories as $inv)
                                <option value="{{ $inv->id }}"
                                    data-opening="{{ $inv->opening_stock }}"
                                    data-plu="{{ $inv->plu_sales }}"
                                    data-unit="{{ $inv->unit }}"
                                    data-price="{{ $inv->unit_price }}"
                                    data-name="{{ $inv->name }}"
                                    {{ old('inventory_id') == $inv->id ? 'selected' : '' }}>
                                    {{ $inv->name }} &nbsp;—&nbsp; Opening: {{ (float)$inv->opening_stock }} {{ $inv->unit }} | PLU Kasir: {{ (float)$inv->plu_sales }} {{ $inv->unit }} (Rp {{ number_format($inv->unit_price, 0, ',', '.') }}/{{ $inv->unit }})
                                </option>
                            @endforeach
                        </select>
                        @error('inventory_id')
                            <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Mode 2: Form Tambah Barang Baru Saja -->
                <div id="section-new" class="hidden space-y-5 bg-stone-50 border border-stone-200 p-6 rounded-2xl">
                    <h3 class="text-xs font-extrabold text-[#8C0000] uppercase tracking-wider flex items-center gap-2">
                        <span>✨ Tambah Barang / Bahan Baku Baru ke Inventaris</span>
                    </h3>

                    <div>
                        <label for="item_name" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Nama Bahan Baku Baru *</label>
                        <input type="text" name="item_name" id="item_name" value="{{ old('item_name') }}"
                            class="w-full bg-white border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                            placeholder="Contoh: Es Batu, Sirup Hazelnut, Susu Oat...">
                        @error('item_name')
                            <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="category" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Kategori Barang *</label>
                            <select name="category" id="category"
                                class="w-full bg-white border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all">
                                <option value="bahan_makanan">Bahan Makanan</option>
                                <option value="bahan_minuman">Bahan Minuman</option>
                                <option value="peralatan">Peralatan / Alat Masak</option>
                            </select>
                        </div>

                        <div>
                            <label for="unit" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Satuan (UOM) *</label>
                            <input type="text" name="unit" id="unit" list="unit-suggestions" value="{{ old('unit') }}"
                                class="w-full bg-white border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                                placeholder="Contoh: pack, kg, liter, ml, pcs, botol...">
                            <datalist id="unit-suggestions">
                                <option value="kg"></option>
                                <option value="gram"></option>
                                <option value="liter"></option>
                                <option value="ml"></option>
                                <option value="pcs"></option>
                                <option value="pack"></option>
                                <option value="botol"></option>
                            </datalist>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="stock" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Jumlah Stok Awal *</label>
                            <input type="number" step="0.001" name="stock" id="stock" value="{{ old('stock', 0) }}" min="0"
                                class="w-full bg-white border border-stone-300 text-[#1C1917] font-black rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                                placeholder="Contoh: 10">
                        </div>

                        <div>
                            <label for="unit_price" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Harga Satuan HPP (Rp) *</label>
                            <input type="number" name="unit_price" id="unit_price" value="{{ old('unit_price', 0) }}" min="0" step="100"
                                class="w-full bg-white border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                                placeholder="Contoh: 20000">
                        </div>

                        <div>
                            <label for="min_stock" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Min. Stok Warning</label>
                            <input type="number" step="0.001" name="min_stock" id="min_stock" value="{{ old('min_stock', 5) }}" min="0"
                                class="w-full bg-white border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                                placeholder="Default: 5">
                        </div>
                    </div>
                </div>

                <!-- Audit Fields Section (HANYA MUNCUL PADA MODE EXISTING) -->
                <div id="section-audit-fields" class="space-y-6">
                    <!-- Input Elements Grid: Opening, In, Out, Closing, PLU -->
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 pt-2">
                        <div>
                            <label for="opening_stock" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Opening (Stok Pagi)</label>
                            <input type="number" step="0.001" name="opening_stock" id="opening_stock" value="{{ old('opening_stock', 0) }}" oninput="calculateReconciliation()"
                                class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-bold rounded-xl px-3.5 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all">
                        </div>

                        <div>
                            <label for="stock_in" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">In (Belanja Masuk)</label>
                            <input type="number" step="0.001" name="stock_in" id="stock_in" value="{{ old('stock_in', 0) }}" oninput="calculateReconciliation()"
                                class="w-full bg-emerald-50/50 border border-emerald-300 text-emerald-900 font-bold rounded-xl px-3.5 py-3 text-sm focus:border-emerald-600 focus:outline-none transition-all">
                        </div>

                        <div>
                            <label for="stock_out_waste" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Out (Waste/Basi)</label>
                            <input type="number" step="0.001" name="stock_out_waste" id="stock_out_waste" value="{{ old('stock_out_waste', 0) }}" oninput="calculateReconciliation()"
                                class="w-full bg-red-50/50 border border-red-300 text-red-900 font-bold rounded-xl px-3.5 py-3 text-sm focus:border-red-600 focus:outline-none transition-all">
                        </div>

                        <div>
                            <label for="closing_stock" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Closing (Hitung Malam) *</label>
                            <input type="number" step="0.001" name="closing_stock" id="closing_stock" value="{{ old('closing_stock') }}" required min="0" oninput="calculateReconciliation()"
                                class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-black text-base rounded-xl px-3.5 py-3 focus:border-[#BD2000] focus:outline-none transition-all"
                                placeholder="Stok fisik malam...">
                            @error('closing_stock')
                                <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">PLU (Penjualan Kasir)</label>
                            <div class="bg-blue-50 border border-blue-200 text-blue-900 font-black rounded-xl px-3.5 py-3 text-base flex items-center justify-between">
                                <span id="display-plu">0</span>
                                <span id="display-plu-unit" class="text-xs text-blue-600 font-semibold"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Live Calculation & Summary Banner -->
                    <div id="live-recon-card" class="hidden p-5 rounded-2xl border transition-all space-y-3">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b border-stone-200 pb-3 text-xs">
                            <div>
                                <span class="text-stone-500 font-medium">Pemakaian Fisik (Use = Opening + In - Out - Closing):</span>
                                <div class="text-base font-extrabold text-stone-900" id="calc-use">-</div>
                            </div>
                            <div>
                                <span class="text-stone-500 font-medium">Selisih (Diff = Use - PLU Kasir):</span>
                                <div class="text-base font-extrabold" id="calc-diff">-</div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div id="diff-icon" class="mt-0.5"></div>
                            <div>
                                <h4 id="diff-title" class="text-xs font-black uppercase tracking-wider"></h4>
                                <p id="diff-desc" class="text-xs font-medium mt-0.5"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Alasan & Catatan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="reason" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Alasan Penyesuaian / Selisih *</label>
                            <select name="reason" id="reason" required 
                                class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3.5 text-sm focus:border-[#BD2000] focus:outline-none transition-all">
                                <option value="selisih_hitung">Selisih Hitung Rutin</option>
                                <option value="rusak_basi">Barang Basi / Rusak (Waste)</option>
                                <option value="lost_hilang">Lost / Hilang</option>
                                <option value="koreksi_stok">Koreksi Data Input Manual</option>
                            </select>
                        </div>

                        <div>
                            <label for="notes" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Catatan Audit Tambahan (Opsional)</label>
                            <textarea name="notes" id="notes" rows="2" 
                                class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] font-semibold rounded-xl px-4 py-3 text-sm focus:border-[#BD2000] focus:outline-none transition-all"
                                placeholder="Catatan penyebab selisih atau keterangan tambahan..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Action Buttons -->
                <div class="pt-4 flex justify-end space-x-4 border-t border-stone-100">
                    <a href="{{ route('supervisor.inventory.index') }}" class="bg-stone-100 hover:bg-stone-200 text-stone-700 border border-stone-300 px-6 py-3 rounded-xl transition-all font-bold text-xs">Batal</a>
                    <button type="submit" id="submit-btn" class="bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold px-8 py-3 rounded-xl transition-all shadow-md cursor-pointer text-xs">
                        Simpan & Auditing Stok Harian
                    </button>
                </div>
            </form>
        </div>

        <!-- Section 2: Riwayat Audit Table -->
        <div id="section-audit-history" class="hidden space-y-4">
            <h3 class="text-sm font-extrabold text-stone-800">Riwayat Audit Lembar Stok Cabang</h3>

            <div class="overflow-x-auto border border-stone-200 rounded-2xl">
                <table class="w-full text-left text-xs text-stone-700">
                    <thead class="bg-stone-100 text-stone-700 uppercase font-black tracking-wider text-[11px] border-b border-stone-200">
                        <tr>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Bahan Baku</th>
                            <th class="px-4 py-3">Opening</th>
                            <th class="px-4 py-3">In</th>
                            <th class="px-4 py-3">Out</th>
                            <th class="px-4 py-3">Closing</th>
                            <th class="px-4 py-3">Use (Fisik)</th>
                            <th class="px-4 py-3">PLU (Kasir)</th>
                            <th class="px-4 py-3">Diff (Selisih)</th>
                            <th class="px-4 py-3">Kerugian (Rp)</th>
                            <th class="px-4 py-3">Auditor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse($recentReconciliations as $recon)
                            <tr class="hover:bg-stone-50 transition-colors">
                                <td class="px-4 py-3 font-semibold whitespace-nowrap">{{ $recon->date->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 font-extrabold text-stone-900">{{ $recon->inventory->name ?? '-' }}</td>
                                <td class="px-4 py-3 font-medium">{{ (float)$recon->opening_stock }} {{ $recon->inventory->unit ?? '' }}</td>
                                <td class="px-4 py-3 font-semibold text-emerald-700">+{{ (float)$recon->stock_in }}</td>
                                <td class="px-4 py-3 font-semibold text-red-600">-{{ (float)$recon->stock_out_waste }}</td>
                                <td class="px-4 py-3 font-extrabold text-stone-900">{{ (float)$recon->closing_stock }}</td>
                                <td class="px-4 py-3 font-black text-stone-800">{{ (float)$recon->use_physical }}</td>
                                <td class="px-4 py-3 font-black text-blue-700">{{ (float)$recon->plu_sales }}</td>
                                <td class="px-4 py-3 font-black">
                                    @if($recon->diff > 0)
                                        <span class="text-red-600">+{{ (float)$recon->diff }} (Minus)</span>
                                    @elseif($recon->diff < 0)
                                        <span class="text-amber-600">{{ (float)$recon->diff }} (Plus)</span>
                                    @else
                                        <span class="text-emerald-600">0 (Pas)</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-bold text-stone-800">
                                    @if($recon->loss_cost > 0)
                                        <span class="text-red-600 font-extrabold">Rp {{ number_format($recon->loss_cost, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-stone-400">Rp 0</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-stone-600 font-medium">{{ $recon->user->name ?? 'System' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-4 py-8 text-center text-stone-400 font-semibold">
                                    Belum ada log rekonsiliasi stok harian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-2">
                {{ $recentReconciliations->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    let currentMode = 'existing';
    let selectedPlu = 0;
    let selectedUnit = '';
    let selectedUnitPrice = 0;

    function switchMainSection(section) {
        const tabAudit = document.getElementById('tab-audit');
        const tabHistory = document.getElementById('tab-history');
        const secForm = document.getElementById('section-audit-form');
        const secHistory = document.getElementById('section-audit-history');

        if (section === 'audit') {
            tabAudit.className = "px-5 py-2.5 rounded-xl text-xs font-extrabold transition-all shadow-sm bg-white text-[#BD2000]";
            tabHistory.className = "px-5 py-2.5 rounded-xl text-xs font-bold text-stone-600 hover:text-stone-900 transition-all";
            secForm.classList.remove('hidden');
            secHistory.classList.add('hidden');
        } else {
            tabHistory.className = "px-5 py-2.5 rounded-xl text-xs font-extrabold transition-all shadow-sm bg-white text-[#BD2000]";
            tabAudit.className = "px-5 py-2.5 rounded-xl text-xs font-bold text-stone-600 hover:text-stone-900 transition-all";
            secHistory.classList.remove('hidden');
            secForm.classList.add('hidden');
        }
    }

    function setMode(mode) {
        currentMode = mode;
        const subtabExisting = document.getElementById('subtab-existing');
        const subtabNew = document.getElementById('subtab-new');
        const secExisting = document.getElementById('section-existing');
        const secNew = document.getElementById('section-new');
        const auditFields = document.getElementById('section-audit-fields');
        const submitBtn = document.getElementById('submit-btn');
        const modeInput = document.getElementById('form-mode');

        modeInput.value = mode;

        if (mode === 'existing') {
            subtabExisting.className = "px-4 py-2 rounded-lg text-xs font-bold bg-stone-100 text-[#BD2000]";
            subtabNew.className = "px-4 py-2 rounded-lg text-xs font-bold text-stone-500 hover:text-stone-900";
            secExisting.classList.remove('hidden');
            secNew.classList.add('hidden');
            auditFields.classList.remove('hidden');
            submitBtn.innerHTML = 'Simpan & Auditing Stok Harian';
            
            document.getElementById('inventory_id').required = true;
            document.getElementById('item_name').required = false;
            document.getElementById('unit').required = false;
            document.getElementById('unit_price').required = false;
            document.getElementById('stock').required = false;
            document.getElementById('closing_stock').required = true;
            document.getElementById('reason').required = true;
            onInventorySelect();
        } else {
            subtabNew.className = "px-4 py-2 rounded-lg text-xs font-bold bg-stone-100 text-[#BD2000]";
            subtabExisting.className = "px-4 py-2 rounded-lg text-xs font-bold text-stone-500 hover:text-stone-900";
            secNew.classList.remove('hidden');
            secExisting.classList.add('hidden');
            auditFields.classList.add('hidden');
            submitBtn.innerHTML = '✨ Simpan Barang Baru';

            document.getElementById('inventory_id').required = false;
            document.getElementById('item_name').required = true;
            document.getElementById('unit').required = true;
            document.getElementById('unit_price').required = true;
            document.getElementById('stock').required = true;
            document.getElementById('closing_stock').required = false;
            document.getElementById('reason').required = false;
            document.getElementById('live-recon-card').classList.add('hidden');
        }
    }

    function onInventorySelect() {
        const select = document.getElementById('inventory_id');
        const selectedOption = select.options[select.selectedIndex];

        if (select.value && selectedOption) {
            const opening = parseFloat(selectedOption.getAttribute('data-opening')) || 0;
            selectedPlu = parseFloat(selectedOption.getAttribute('data-plu')) || 0;
            selectedUnit = selectedOption.getAttribute('data-unit') || '';
            selectedUnitPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;

            document.getElementById('opening_stock').value = opening;
            document.getElementById('display-plu').innerText = selectedPlu;
            document.getElementById('display-plu-unit').innerText = selectedUnit;
        } else {
            selectedPlu = 0;
            selectedUnit = '';
            selectedUnitPrice = 0;
            document.getElementById('opening_stock').value = 0;
            document.getElementById('display-plu').innerText = 0;
            document.getElementById('display-plu-unit').innerText = '';
        }
        calculateReconciliation();
    }

    function calculateReconciliation() {
        const opening = parseFloat(document.getElementById('opening_stock').value) || 0;
        const stockIn = parseFloat(document.getElementById('stock_in').value) || 0;
        const stockOut = parseFloat(document.getElementById('stock_out_waste').value) || 0;
        const closingInput = document.getElementById('closing_stock');
        const liveCard = document.getElementById('live-recon-card');
        const titleEl = document.getElementById('diff-title');
        const descEl = document.getElementById('diff-desc');
        const iconEl = document.getElementById('diff-icon');

        if (closingInput.value === '' || (currentMode === 'existing' && !document.getElementById('inventory_id').value)) {
            liveCard.classList.add('hidden');
            return;
        }

        const closing = parseFloat(closingInput.value) || 0;

        // Formula: Use = Opening + In - Out - Closing
        const usePhysical = opening + stockIn - stockOut - closing;

        // Formula: Diff = Use - PLU
        const diff = usePhysical - selectedPlu;

        document.getElementById('calc-use').innerText = `${usePhysical.toFixed(2)} ${selectedUnit}`;
        
        const diffEl = document.getElementById('calc-diff');
        liveCard.classList.remove('hidden');

        if (diff > 0) {
            const wasteCost = Math.abs(diff) * selectedUnitPrice;
            diffEl.innerText = `+${diff.toFixed(2)} ${selectedUnit} (Minus / Loss)`;
            diffEl.className = "text-base font-extrabold text-red-600";
            
            liveCard.className = "p-5 rounded-2xl border transition-all bg-red-50 border-red-200 text-red-800 space-y-3";
            iconEl.innerHTML = `<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`;
            titleEl.innerText = `🔴 Varian Minus (Pemakaian Fisik > Penjualan Kasir)`;
            descEl.innerText = `Pemakaian fisik lebih besar ${diff.toFixed(2)} ${selectedUnit} dari kasir. Potensi kerugian Rp ${Number(wasteCost).toLocaleString('id-ID')} akan otomatis dicatat ke Pengeluaran.`;
        } else if (diff > 0) {
            diffEl.innerText = `${diff.toFixed(2)} ${selectedUnit} (Plus / Kelebihan)`;
            diffEl.className = "text-base font-extrabold text-amber-600";

            liveCard.className = "p-5 rounded-2xl border transition-all bg-amber-50 border-amber-200 text-amber-800 space-y-3";
            iconEl.innerHTML = `<svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
            titleEl.innerText = `🟡 Varian Plus (Penjualan Kasir > Pemakaian Fisik)`;
            descEl.innerText = `Penjualan kasir tercatat lebih banyak dari fisik terpakai. Evaluasi porsi/takaran resep koki/barista agar sesuai SOP.`;
        } else {
            diffEl.innerText = `0 ${selectedUnit} (Pas / Match)`;
            diffEl.className = "text-base font-extrabold text-emerald-600";

            liveCard.className = "p-5 rounded-2xl border transition-all bg-emerald-50 border-emerald-200 text-emerald-800 space-y-3";
            iconEl.innerHTML = `<svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`;
            titleEl.innerText = `🟢 Audit Sempurna (Presisi 100%)`;
            descEl.innerText = `Pemakaian fisik di lapangan persis cocok dengan catatan penjualan kasir.`;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        setMode('existing');
    });
</script>
@endsection
