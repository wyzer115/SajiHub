@php
    $user = auth()->user();
@endphp

@if($user && !$user->isSuperAdmin() && $user->branch && $user->branch->status !== 'buka')
    @php
        $branch = $user->branch;
        $isTutup = $branch->status === 'tutup';
        $hasImpersonate = session()->has('impersonator_id');
    @endphp
    <div class="fixed left-0 right-0 z-[9998] px-4 py-2 text-center text-sm font-bold shadow-lg flex items-center justify-center gap-2
        {{ $hasImpersonate ? 'top-9' : 'top-0' }}
        {{ $isTutup ? 'bg-red-600 text-white' : 'bg-amber-500 text-dark-950' }}">
        
        @if($isTutup)
            <svg class="w-5 h-5 animate-pulse text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <span>🔴 Cabang Ini Sedang Ditutup oleh Pusat.
                @if($branch->status_note)
                    Catatan: <strong class="underline">{{ $branch->status_note }}</strong>
                @endif
            </span>
        @else
            <svg class="w-5 h-5 animate-pulse text-dark-950" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <span>⚠️ Cabang Ini Sedang Maintenance.
                @if($branch->status_note)
                    Catatan: <strong class="underline">{{ $branch->status_note }}</strong>
                @endif
            </span>
        @endif
    </div>
    <style>
        /* Push content down when the banners are active */
        body {
            padding-top: {{ $hasImpersonate ? '5.25rem' : '2.75rem' }} !important;
        }
        aside#sidebar, main, header, .sticky {
            top: {{ $hasImpersonate ? '5.25rem' : '2.75rem' }} !important;
        }
    </style>
@endif
