<div x-data="checkoutModalComponent()" 
     x-show="isOpen" 
     x-cloak
     @open-checkout.window="openCheckout($event.detail)"
     class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    
    <div class="bg-white border border-stone-200 w-full max-w-4xl rounded-3xl overflow-hidden shadow-2xl transition-all duration-300 transform scale-100"
         @click.outside="closeModal()">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-stone-200 flex justify-between items-center bg-stone-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#BD2000]/10 border border-[#BD2000]/20 flex items-center justify-center text-[#BD2000] font-black">
                    💳
                </div>
                <div>
                    <h3 class="text-lg font-black text-[#8C0000] leading-tight">Modul Pembayaran SajiHUB</h3>
                    <p class="text-xs text-slate-500 font-medium">Pelanggan: <span class="text-[#BD2000] font-extrabold" x-text="customerName || '-'"></span></p>
                </div>
            </div>
            <button @click="closeModal()" class="p-2 text-stone-400 hover:text-stone-700 hover:bg-stone-100 rounded-xl transition-colors cursor-pointer">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Body Container (Grid 2 Column) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-0 divide-y md:divide-y-0 md:divide-x divide-stone-200">
            
            <!-- SISI KIRI: Rincian Item, Total, & Toggle Method -->
            <div class="p-6 space-y-6 flex flex-col justify-between bg-stone-50">
                <div>
                    <h4 class="text-xs font-bold text-stone-600 uppercase tracking-wider mb-3">Rincian Tagihan</h4>
                    
                    <div class="space-y-2 max-h-48 overflow-y-auto pr-1 scrollbar-thin">
                        <template x-for="(item, idx) in items" :key="idx">
                            <div class="flex justify-between items-center text-sm py-1.5 border-b border-stone-200">
                                <div>
                                    <span class="font-extrabold text-[#1C1917]" x-text="item.name"></span>
                                    <span class="text-xs text-slate-500 ml-1 font-bold" x-text="'x' + item.qty"></span>
                                </div>
                                <span class="font-mono text-stone-700 font-bold" x-text="formatRupiah(item.price * item.qty)"></span>
                            </div>
                        </template>
                    </div>

                    <div class="mt-4 p-4 rounded-2xl bg-white border border-stone-200 space-y-2 shadow-sm">
                        <div class="flex justify-between text-xs text-slate-600 font-medium">
                            <span>Pelanggan</span>
                            <span class="font-extrabold text-[#1C1917]" x-text="customerName"></span>
                        </div>
                        <div class="flex justify-between text-xs text-slate-600 font-medium">
                            <span>Meja</span>
                            <span class="font-extrabold text-[#1C1917]" x-text="tableNumber"></span>
                        </div>
                        <div class="h-px bg-stone-200 my-2"></div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-bold text-[#1C1917]">Total Tagihan</span>
                            <span class="text-2xl font-black text-[#BD2000] font-mono" x-text="formatRupiah(totalAmount)"></span>
                        </div>
                    </div>
                </div>

                <!-- Status Metode Pembayaran (Terdeteksi & Terkunci) -->
                <div>
                    <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-2">Metode Pembayaran (Terdeteksi)</label>
                    <div class="p-3.5 rounded-2xl bg-white border border-stone-200 flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2.5">
                            <template x-if="paymentMethod === 'cash'">
                                <div class="flex items-center gap-2.5 text-stone-800 font-extrabold text-sm">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-base">💵</div>
                                    <div>
                                        <span>Pembayaran Tunai (Cash)</span>
                                        <span class="block text-[10px] text-slate-500 font-bold">Diterima oleh Kasir</span>
                                    </div>
                                </div>
                            </template>
                            <template x-if="paymentMethod === 'qris'">
                                <div class="flex items-center gap-2.5 text-[#BD2000] font-extrabold text-sm">
                                    <div class="w-8 h-8 rounded-xl bg-[#BD2000]/10 text-[#BD2000] flex items-center justify-center font-bold text-base">📲</div>
                                    <div>
                                        <span>Pembayaran QRIS Statis</span>
                                        <span class="block text-[10px] text-slate-500 font-bold">Scan Kode QR Merchant</span>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full bg-stone-100 text-stone-600 border border-stone-200">
                            🔒 Terkunci
                        </span>
                    </div>
                </div>
            </div>

            <!-- SISI KANAN: Form Tunai ATAU Modal Simulasi QRIS -->
            <div class="p-6 relative flex flex-col justify-between bg-white">
                
                <!-- OPSI 1: PEMBAYARAN TUNAI -->
                <div x-show="paymentMethod === 'cash'" class="space-y-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h4 class="text-sm font-extrabold text-[#8C0000] mb-1 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#8C0000]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>Pembayaran Tunai</span>
                        </h4>
                        <p class="text-xs text-slate-500 font-medium mb-4">Masukkan nominal uang tunai yang diterima dari pelanggan.</p>

                        <!-- Input Cash -->
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs text-stone-700 font-bold mb-1 uppercase">Nominal Diterima (Rp)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 font-black">Rp</span>
                                    <input type="number" x-model.number="cashPaid" @input="calculateChange()" min="0" placeholder="0"
                                           class="w-full bg-stone-50 border border-stone-300 text-[#1C1917] rounded-xl pl-12 pr-4 py-3 text-lg font-black font-mono focus:border-[#BD2000] focus:outline-none">
                                </div>
                            </div>

                            <!-- Quick Nominal Buttons (Akumulatif / Nambah) -->
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2">
                                <button type="button" @click="setExactCash()" class="py-2 px-2 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold rounded-xl transition-colors cursor-pointer" title="Set sesuai total tagihan">Uang Pas</button>
                                <button type="button" @click="addCashAmount(50000)" class="py-2 px-2 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold rounded-xl transition-colors cursor-pointer" title="Tambah Rp 50.000">+50.000</button>
                                <button type="button" @click="addCashAmount(100000)" class="py-2 px-2 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold rounded-xl transition-colors cursor-pointer" title="Tambah Rp 100.000">+100.000</button>
                                <button type="button" @click="addCashAmount(200000)" class="py-2 px-2 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold rounded-xl transition-colors cursor-pointer" title="Tambah Rp 200.000">+200.000</button>
                                <button type="button" @click="resetCash()" class="py-2 px-2 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 text-xs font-bold rounded-xl transition-colors cursor-pointer" title="Reset nominal ke Rp 0">Reset</button>
                            </div>

                            <!-- Kembalian Display -->
                            <div class="p-4 rounded-2xl border mt-4 transition-all"
                                 :class="cashPaid >= totalAmount ? 'bg-emerald-50 border-emerald-200' : 'bg-red-50 border-red-200'">
                                <div class="text-xs text-stone-600 font-bold mb-1 uppercase">Uang Kembalian</div>
                                <div class="text-2xl font-black font-mono"
                                     :class="cashPaid >= totalAmount ? 'text-emerald-600' : 'text-red-600'"
                                     x-text="cashPaid >= totalAmount ? formatRupiah(cashChange) : 'Nominal kurang Rp ' + formatRupiah(totalAmount - cashPaid)"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Tunai -->
                    <div class="pt-4 border-t border-stone-200">
                        <button type="button" @click="submitCashPayment()" 
                                :disabled="cashPaid < totalAmount || isProcessing"
                                class="w-full bg-[#BD2000] hover:bg-[#8C0000] disabled:opacity-40 disabled:cursor-not-allowed text-white font-extrabold py-3.5 rounded-xl transition-all shadow-lg flex items-center justify-center gap-2 text-sm cursor-pointer">
                            <span x-show="!isProcessing" class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Konfirmasi Pembayaran Tunai
                            </span>
                            <span x-show="isProcessing" class="animate-spin">⏳ Memproses...</span>
                        </button>
                    </div>
                </div>

                <!-- OPSI 2: PEMBAYARAN QRIS (VERIFIKASI MANUAL KASIR) -->
                <div x-show="paymentMethod === 'qris'" class="space-y-4 flex-1 flex flex-col justify-between">
                    
                    <!-- Merchant Header info -->
                    <div class="bg-stone-50 border border-stone-200 rounded-2xl p-4 text-center space-y-2 relative overflow-hidden">
                        <div class="flex justify-between items-center border-b border-stone-200 pb-2">
                            <div class="flex items-center gap-1.5">
                                <span class="font-black text-[#BD2000] tracking-tighter text-sm">QRIS</span>
                                <span class="text-[10px] text-slate-500 font-medium">Standar Nasional</span>
                            </div>
                            <span class="text-[11px] font-extrabold text-[#BD2000] bg-[#BD2000]/10 px-2 py-0.5 rounded border border-[#BD2000]/20">GPN</span>
                        </div>

                        <!-- Merchant Data Eksplisit -->
                        <div>
                            <h4 class="text-lg font-black text-[#1C1917] tracking-wide">warung akid</h4>
                            <p class="text-xs text-slate-600 font-mono font-bold">NMID : ID1026528881513</p>
                            <p class="text-[11px] text-slate-500 font-mono">Kode Terminal : A01</p>
                        </div>

                        <!-- Display Real Official QR Code Image -->
                        <div class="bg-white p-3 rounded-2xl inline-block shadow-md mx-auto border border-stone-200 max-w-[200px]">
                            <img src="{{ asset('images/qris.jpg') }}" alt="QRIS Resmi Warung Akid" class="w-full h-auto rounded-xl object-contain">
                            <p class="text-[9px] font-black text-stone-800 tracking-tighter mt-1.5 uppercase text-center">SATU QRIS UNTUK SEMUA</p>
                        </div>

                        <!-- Status Informasional -->
                        <div class="flex items-center justify-center gap-2 bg-white px-3 py-2 rounded-xl border border-stone-200 shadow-sm text-xs font-bold text-[#BD2000]">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#BD2000]"></span>
                            <span>Scan Kode QRIS & Verifikasi Pembayaran Pelanggan</span>
                        </div>
                    </div>

                    <!-- Foto / Upload Bukti Transfer Manual -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider">
                            Foto Bukti Transfer Customer <span class="text-red-500">*</span>
                        </label>
                        <input type="file" id="checkout_qris_proof" accept="image/*" capture="environment" @change="handleProofUpload($event)" class="hidden">
                        
                        <div class="flex gap-2 items-center">
                            <button type="button" @click="document.getElementById('checkout_qris_proof').click()"
                                    class="py-2 px-3 bg-stone-100 hover:bg-stone-200 border border-stone-300 text-stone-800 font-bold text-xs rounded-xl transition-all flex items-center gap-1.5 cursor-pointer">
                                <svg class="w-4 h-4 text-stone-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Ambil Foto / Upload Bukti</span>
                            </button>
                            <span class="text-xs text-stone-500 font-medium truncate max-w-[180px]" x-text="proofFileName || 'Belum ada foto'"></span>
                        </div>

                        <!-- Image Preview -->
                        <div x-show="proofPreviewUrl" class="p-2 bg-stone-50 rounded-2xl border border-stone-200 inline-block">
                            <img :src="proofPreviewUrl" alt="Bukti Transfer" class="max-h-36 rounded-xl object-contain border border-stone-200 shadow-xs">
                        </div>
                    </div>

                    <!-- Verifikasi Manual Kasir -->
                    <div class="pt-2">
                        <button type="button" @click="triggerQrisApprove()" 
                                :disabled="qrisApproved || isProcessing"
                                class="w-full bg-[#BD2000] hover:bg-[#8C0000] disabled:opacity-50 text-white font-extrabold py-3.5 rounded-xl transition-all shadow-md text-sm flex items-center justify-center gap-2 cursor-pointer">
                            <span x-show="!isProcessing" class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Konfirmasi Pembayaran QRIS Lunas
                            </span>
                            <span x-show="isProcessing" class="animate-spin">⏳ Memproses...</span>
                        </button>
                    </div>
                </div>

                <!-- OVERLAY SUKSES PEMBAYARAN -->
                <div x-show="cashSuccess || qrisApproved" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-90"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute inset-0 bg-white/95 backdrop-blur-md rounded-2xl flex flex-col items-center justify-center p-6 text-center space-y-4 z-20">
                    <div class="w-20 h-20 bg-emerald-100 border-2 border-emerald-500 text-emerald-600 rounded-full flex items-center justify-center animate-bounce">
                        <svg class="w-10 h-10 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-[#1C1917]" x-text="cashSuccess ? 'Pembayaran Tunai Berhasil!' : 'Pembayaran QRIS Berhasil!'"></h3>
                        <p class="text-xs text-slate-600 mt-1 font-medium">Pelanggan: <span class="font-extrabold text-[#BD2000]" x-text="customerName"></span></p>
                        <p class="text-xs text-emerald-600 font-mono mt-1 font-black uppercase">Status: LUNAS & SELESAI</p>
                    </div>
                    <p class="text-[11px] text-slate-500 animate-pulse font-medium">Menyiapkan Struk Transaksi...</p>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function checkoutModalComponent() {
        return {
            isOpen: false,
            orderId: null,
            customerName: '',
            tableNumber: '',
            totalAmount: 0,
            items: [],
            paymentMethod: 'cash', // 'cash' or 'qris'
            cashPaid: 0,
            cashChange: 0,
            isProcessing: false,
            qrisApproved: false,
            cashSuccess: false,
            timerSeconds: 300,
            timerInterval: null,
            autoApproveTimeout: null,

            proofFile: null,
            proofFileName: '',
            proofPreviewUrl: '',

            get timerDisplay() {
                const mins = Math.floor(this.timerSeconds / 60);
                const secs = this.timerSeconds % 60;
                return String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
            },

            openCheckout(detail) {
                if (!detail) return;
                this.orderId = detail.orderId;
                this.customerName = detail.customerName || 'Pelanggan';
                this.tableNumber = detail.tableNumber || '-';
                this.totalAmount = parseFloat(detail.totalAmount) || 0;
                this.items = detail.items || [];
                this.paymentMethod = (detail.paymentMethod === 'qris') ? 'qris' : 'cash';
                this.cashPaid = detail.cashPaid || this.totalAmount;
                this.calculateChange();
                this.qrisApproved = false;
                this.cashSuccess = false;
                this.isProcessing = false;
                this.proofFile = null;
                this.proofFileName = '';
                this.proofPreviewUrl = '';
                this.isOpen = true;

                if (this.paymentMethod === 'qris') {
                    this.startQrisSimulation();
                }
            },

            setPaymentMethod(method) {
                this.paymentMethod = method;
                if (method === 'qris') {
                    this.startQrisSimulation();
                } else {
                    this.stopQrisSimulation();
                }
            },

            handleProofUpload(e) {
                const file = e.target.files[0];
                if (file) {
                    this.proofFile = file;
                    this.proofFileName = file.name;
                    const reader = new FileReader();
                    reader.onload = (evt) => { this.proofPreviewUrl = evt.target.result; };
                    reader.readAsDataURL(file);
                }
            },

            calculateChange() {
                this.cashChange = Math.max(0, (parseFloat(this.cashPaid) || 0) - this.totalAmount);
            },

            setExactCash() {
                this.cashPaid = this.totalAmount;
                this.calculateChange();
            },

            addCashAmount(amount) {
                this.cashPaid = (parseFloat(this.cashPaid) || 0) + amount;
                this.calculateChange();
            },

            resetCash() {
                this.cashPaid = 0;
                this.calculateChange();
            },

            setCashPreset(amount) {
                this.cashPaid = amount;
                this.calculateChange();
            },

            startQrisSimulation() {
                this.stopQrisSimulation();
                this.qrisApproved = false;
            },

            stopQrisSimulation() {
                if (this.timerInterval) clearInterval(this.timerInterval);
                if (this.autoApproveTimeout) clearTimeout(this.autoApproveTimeout);
            },

            async submitCashPayment() {
                if (this.cashPaid < this.totalAmount) {
                    if (window.showToast) window.showToast('Nominal uang tunai kurang!', 'error');
                    else alert('Nominal uang kurang!');
                    return;
                }

                this.isProcessing = true;

                try {
                    const response = await fetch(`/kasir/orders/${this.orderId}/process-payment`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            payment_method: 'cash',
                            status: 'completed',
                            cash_paid: this.cashPaid,
                        })
                    });

                    const data = await response.json();
                    if (data.success) {
                        this.cashSuccess = true;
                        setTimeout(() => {
                            this.closeModal();
                            if (data.receipt_url) {
                                window.open(data.receipt_url, '_blank');
                            }
                            window.location.reload();
                        }, 1200);
                    } else {
                        if (window.showToast) window.showToast('Gagal memproses pembayaran: ' + (data.message || 'Error'), 'error');
                        else alert('Gagal memproses pembayaran: ' + (data.message || 'Error'));
                    }
                } catch (e) {
                    if (window.showToast) window.showToast('Terjadi kesalahan jaringan.', 'error');
                    else alert('Terjadi kesalahan jaringan.');
                } finally {
                    this.isProcessing = false;
                }
            },

            async triggerQrisApprove() {
                if (!this.proofFile) {
                    if (window.showToast) window.showToast('Silakan ambil foto atau upload bukti transfer QRIS terlebih dahulu!', 'warning');
                    else alert('Silakan ambil foto atau upload bukti transfer QRIS terlebih dahulu!');
                    return;
                }

                this.stopQrisSimulation();
                this.isProcessing = true;

                try {
                    const formData = new FormData();
                    formData.append('payment_method', 'qris');
                    formData.append('status', 'completed');
                    formData.append('merchant_id', 'ID1026528881513');
                    formData.append('payment_proof', this.proofFile);

                    const response = await fetch(`/kasir/orders/${this.orderId}/process-payment`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        this.qrisApproved = true;
                        setTimeout(() => {
                            this.closeModal();
                            if (data.receipt_url) {
                                window.open(data.receipt_url, '_blank');
                            }
                            window.location.reload();
                        }, 1500);
                    } else {
                        alert(data.message || 'Gagal memproses verifikasi QRIS.');
                    }
                } catch (e) {
                    alert('Gagal memproses verifikasi QRIS.');
                } finally {
                    this.isProcessing = false;
                }
            },

            closeModal() {
                this.stopQrisSimulation();
                this.isOpen = false;
            },

            formatRupiah(num) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(num || 0);
            }
        };
    }

    window.openCheckoutModal = function(detail) {
        if (typeof detail === 'string') {
            try { detail = JSON.parse(detail); } catch(e) {}
        }
        window.dispatchEvent(new CustomEvent('open-checkout', { detail: detail }));
    };
</script>
@endpush
