@auth
@php
    $user = auth()->user();
    $roleName = match($user->role ?? '') {
        'superadmin'   => 'Super Admin',
        'admin_cabang' => 'Admin Cabang',
        'owner'        => 'Owner Cabang',
        'supervisor'   => 'Supervisor',
        'kasir'        => 'Kasir',
        'dapur', 'koki'=> 'Staf Dapur',
        default        => ucfirst($user->role ?? 'Pengguna')
    };
@endphp

<!-- Logout Confirmation Modal Card -->
<div id="logoutModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6 hidden transition-all duration-200 opacity-0 pointer-events-none">
    <!-- Dark Blur Overlay -->
    <div id="logoutModalBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity duration-200"></div>

    <!-- Modal Card -->
    <div id="logoutModalCard" class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-200/80 overflow-hidden transform scale-95 transition-all duration-200 z-10">
        
        <!-- Top Accent Bar -->
        <div class="h-1.5 bg-red-600"></div>

        <div class="p-6 sm:p-7 text-center">
            <!-- Icon Badge -->
            <div class="mx-auto w-12 h-12 rounded-xl bg-red-50 border border-red-200/60 flex items-center justify-center mb-4 text-red-600 shadow-xs">
                <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </div>

            <!-- Title -->
            <h3 class="text-lg font-bold text-slate-900 tracking-tight mb-1">
                Konfirmasi Keluar Akun
            </h3>

            <!-- Subtitle Question -->
            <p class="text-xs font-medium text-slate-500 leading-relaxed max-w-xs mx-auto mb-4">
                Apakah Anda yakin ingin logout dari role <span class="font-bold text-red-600">{{ $roleName }}</span>?
            </p>

            <!-- User Info Profile Card -->
            <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3 mb-6 text-left flex items-center gap-3 shadow-xs">
                <div class="w-9 h-9 rounded-lg bg-red-600 text-white flex items-center justify-center font-bold text-xs shadow-xs shrink-0 font-mono">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="text-xs font-bold text-slate-900 truncate">{{ $user->name }}</div>
                    <div class="text-[11px] font-medium text-red-600 flex items-center gap-1 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                        <span>Role: {{ $roleName }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-2 gap-3">
                <button type="button" id="btnCancelLogout" class="w-full py-2.5 px-4 rounded-xl font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200/80 active:scale-95 transition-all text-xs cursor-pointer">
                    Batal
                </button>
                <button type="button" id="btnConfirmLogout" class="w-full py-2.5 px-4 rounded-xl font-bold text-white bg-red-600 hover:bg-red-700 border border-red-600 shadow-xs active:scale-95 transition-all text-xs flex items-center justify-center gap-2 cursor-pointer uppercase tracking-wider">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" />
                    </svg>
                    <span>Ya, Keluar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('logoutModal');
    const modalBackdrop = document.getElementById('logoutModalBackdrop');
    const modalCard = document.getElementById('logoutModalCard');
    const btnCancel = document.getElementById('btnCancelLogout');
    const btnConfirm = document.getElementById('btnConfirmLogout');

    let currentTargetForm = null;

    function openLogoutModal(form) {
        currentTargetForm = form;
        if (!modal) return;
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modalCard.classList.remove('scale-95');
            modalCard.classList.add('scale-100');
        });
    }

    function closeLogoutModal() {
        if (!modal) return;
        modalCard.classList.remove('scale-100');
        modalCard.classList.add('scale-95');
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden', 'pointer-events-none');
            currentTargetForm = null;
        }, 150);
    }

    // Intercept all logout forms in the app
    document.querySelectorAll('form[action*="logout"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            openLogoutModal(form);
        });
    });

    if (btnCancel) btnCancel.addEventListener('click', closeLogoutModal);
    if (modalBackdrop) modalBackdrop.addEventListener('click', closeLogoutModal);

    if (btnConfirm) {
        btnConfirm.addEventListener('click', function() {
            if (currentTargetForm) {
                btnConfirm.disabled = true;
                btnConfirm.innerHTML = `
                    <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Mengeluarkan...</span>
                `;
                currentTargetForm.submit();
            }
        });
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            closeLogoutModal();
        }
    });
});
</script>
@endauth

