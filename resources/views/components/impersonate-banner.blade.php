@if(session()->has('impersonator_id'))
    <div class="fixed top-0 left-0 right-0 z-[9999] bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 text-dark-950 px-4 py-2 text-center text-sm font-bold shadow-lg flex items-center justify-center gap-4">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 animate-pulse text-dark-950" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            <span>Anda sedang mengintip Dasbor Cabang: <strong class="underline">{{ auth()->user()->branch->name ?? 'Cabang' }}</strong></span>
        </div>
        <form action="{{ route('superadmin.impersonate.leave') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="bg-dark-950 hover:bg-dark-900 text-amber-500 hover:text-amber-400 px-3 py-1 rounded-lg text-xs font-black transition-all uppercase tracking-wider flex items-center gap-1 shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Kembali ke Super Admin
            </button>
        </form>
    </div>
    <style>
        /* Push the page content down when the banner is visible */
        body {
            padding-top: 2.75rem !important;
        }
        aside#sidebar, main, header, .sticky {
            top: 2.75rem !important;
        }
    </style>
@endif
