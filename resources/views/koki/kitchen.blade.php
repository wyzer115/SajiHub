@extends('layouts.app')
@section('title', 'Layar Monitor Dapur Real-Time')
@section('page-title', 'Layar Monitor Dapur')

@push('scripts')
<script>
    // Auto refresh every 15 seconds to fetch incoming orders real-time
    setTimeout(() => {
        window.location.reload();
    }, 15000);
</script>
@endpush

@section('content')
@php
    $pendingOrders = $orders->where('order_status', 'pending');
    $cookingOrders = $orders->where('order_status', 'cooking');
    $totalOrdersCount = $orders->count();
@endphp

<div class="space-y-8 animate-fade-in-up">

    <!-- Header status banner -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white border border-stone-200 rounded-2xl p-5 shadow-sm">
        <div>
            <h2 class="text-xl font-black text-[#8C0000] flex items-center gap-2">
                <svg class="w-6 h-6 text-[#8C0000]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span>Monitor Layar Dapur</span>
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
            </h2>
            <p class="text-slate-600 text-xs mt-0.5 font-medium">Memantau pesanan masuk secara real-time di cabang <span class="text-[#BD2000] font-extrabold">{{ auth()->user()->branch->name }}</span> (Auto Refresh 15 detik)</p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                <svg class="w-3.5 h-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Menunggu: {{ $pendingOrders->count() }}
            </span>
            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold bg-orange-50 text-orange-700 border border-orange-200">
                <svg class="w-3.5 h-3.5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                Dimasak: {{ $cookingOrders->count() }}
            </span>
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center bg-white border border-stone-200 rounded-3xl shadow-sm">
            <svg class="w-20 h-20 text-stone-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <h3 class="text-xl font-black text-[#1C1917] mb-2">Tidak ada antrean pesanan saat ini</h3>
            <p class="text-slate-500 text-sm font-medium">Dapur sedang sepi. Pesanan baru akan langsung otomatis muncul di layar ini.</p>
        </div>
    @else
        <!-- MENUNGGU DIMASAK -->
        @if($pendingOrders->count() > 0)
        <div>
            <div class="flex items-center space-x-3 mb-4">
                <h2 class="text-lg font-black text-[#8C0000] flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#BD2000]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Menunggu Dimasak</span>
                </h2>
                <span class="px-3 py-0.5 bg-amber-100 text-amber-800 rounded-full text-xs font-extrabold border border-amber-200">{{ $pendingOrders->count() }} Antrean</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($pendingOrders as $order)
                <div class="bg-white border-2 border-amber-300 rounded-3xl overflow-hidden flex flex-col h-full shadow-md">
                    <div class="p-4 bg-amber-50 border-b border-amber-200 flex justify-between items-start">
                        <div>
                            <span class="text-[11px] font-extrabold text-amber-800 uppercase tracking-wider block">Pesanan {{ $loop->iteration }}</span>
                            <p class="text-amber-900 text-xs font-bold mt-0.5">{{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-right flex flex-col items-end gap-1.5">
                            <div class="bg-white border border-amber-300 px-3 py-1 rounded-xl shadow-xs">
                                <span class="text-[10px] text-stone-500 uppercase font-bold block leading-none mb-1">{{ $order->table ? 'Meja' : 'Tipe' }}</span>
                                <span class="text-base font-black text-[#1C1917] leading-none">{{ $order->table ? $order->table->table_number : 'Bawa Pulang' }}</span>
                            </div>
                            <span class="order-timer-badge text-xs px-2.5 py-1 rounded-lg font-extrabold font-mono inline-flex items-center gap-1 bg-amber-100 text-amber-800 border border-amber-300" data-created-at="{{ $order->created_at->toIso8601String() }}">
                                <svg class="w-3.5 h-3.5 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>00:00</span>
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-5 flex-1 bg-white">
                        <p class="text-slate-600 text-xs font-medium mb-4">Pelanggan: <span class="text-[#1C1917] font-extrabold">{{ $order->customer_name }}</span></p>
                        
                        <div class="space-y-3">
                            @foreach($order->items as $item)
                            <div class="flex items-start">
                                <div class="bg-stone-100 text-[#1C1917] font-black w-8 h-8 rounded-lg flex items-center justify-center shrink-0 border border-stone-300 mr-3 text-sm">
                                    {{ $item->quantity }}x
                                </div>
                                <div>
                                    <p class="text-[#1C1917] font-black text-base leading-tight">{{ $item->menu->name ?? 'Menu Dihapus' }}</p>
                                    @if($item->notes)
                                        <p class="text-amber-800 text-xs font-bold mt-1.5 flex items-start bg-amber-50 py-1.5 px-2.5 rounded-lg border border-amber-200">
                                            <svg class="w-3.5 h-3.5 mr-1 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                            "{{ $item->notes }}"
                                        </p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="p-4 border-t border-amber-200 bg-amber-50/50">
                        <form action="{{ route('koki.orders.update-status', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="order_status" value="cooking">
                            <button type="submit" class="w-full bg-[#BD2000] hover:bg-[#8C0000] text-white font-extrabold py-3 rounded-xl transition-all shadow-md flex justify-center items-center gap-2 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path></svg>
                                Mulai Masak
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- SEDANG DIMASAK -->
        @if($cookingOrders->count() > 0)
        <div>
            <div class="flex items-center space-x-3 mb-4">
                <h2 class="text-lg font-black text-[#8C0000] flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#BD2000]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                    <span>Sedang Dimasak</span>
                </h2>
                <span class="px-3 py-0.5 bg-orange-100 text-orange-800 rounded-full text-xs font-extrabold border border-orange-200">{{ $cookingOrders->count() }} Dimasak</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($cookingOrders as $order)
                <div class="bg-white border-2 border-orange-300 rounded-3xl overflow-hidden flex flex-col h-full shadow-md">
                    <div class="p-4 bg-orange-50 border-b border-orange-200 flex justify-between items-start">
                        <div>
                            <span class="text-[11px] font-extrabold text-orange-800 uppercase tracking-wider block">Pesanan {{ $loop->iteration }}</span>
                            <p class="text-orange-900 text-xs font-bold mt-0.5">{{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-right flex flex-col items-end gap-1.5">
                            <div class="bg-white border border-orange-300 px-3 py-1 rounded-xl shadow-xs">
                                <span class="text-[10px] text-stone-500 uppercase font-bold block leading-none mb-1">{{ $order->table ? 'Meja' : 'Tipe' }}</span>
                                <span class="text-base font-black text-[#1C1917] leading-none">{{ $order->table ? $order->table->table_number : 'Bawa Pulang' }}</span>
                            </div>
                            <span class="order-timer-badge text-xs px-2.5 py-1 rounded-lg font-extrabold font-mono inline-flex items-center gap-1 bg-orange-100 text-orange-800 border border-orange-300" data-created-at="{{ $order->created_at->toIso8601String() }}">
                                <svg class="w-3.5 h-3.5 text-orange-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>00:00</span>
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-5 flex-1 bg-white">
                        <p class="text-slate-600 text-xs font-medium mb-4">Pelanggan: <span class="text-[#1C1917] font-extrabold">{{ $order->customer_name }}</span></p>
                        
                        <div class="space-y-3">
                            @foreach($order->items as $item)
                            <div class="flex items-start">
                                <div class="bg-stone-100 text-[#1C1917] font-black w-8 h-8 rounded-lg flex items-center justify-center shrink-0 border border-stone-300 mr-3 text-sm">
                                    {{ $item->quantity }}x
                                </div>
                                <div>
                                    <p class="text-[#1C1917] font-black text-base leading-tight">{{ $item->menu->name ?? 'Menu Dihapus' }}</p>
                                    @if($item->notes)
                                        <p class="text-orange-800 text-xs font-bold mt-1.5 flex items-start bg-orange-50 py-1.5 px-2.5 rounded-lg border border-orange-200">
                                            <svg class="w-3.5 h-3.5 mr-1 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                            "{{ $item->notes }}"
                                        </p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="p-4 border-t border-orange-200 bg-orange-50/50">
                        <form action="{{ route('koki.orders.update-status', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="order_status" value="served">
                            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3 rounded-xl transition-all shadow-md flex justify-center items-center gap-2 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Tandai Siap Disajikan
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @endif
</div>

<script>
    let isSoundEnabled = localStorage.getItem('kitchen_sound_enabled') !== 'false';

    function updateSoundBtnUI() {
        const btn = document.getElementById('btn-toggle-sound');
        if (btn) {
            if (isSoundEnabled) {
                btn.className = 'px-3 py-1.5 rounded-xl text-xs font-bold bg-stone-100 text-emerald-700 border border-emerald-300 transition-all flex items-center gap-1.5 cursor-pointer shadow-sm';
                btn.innerHTML = `<svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg> Suara: AKTIF`;
            } else {
                btn.className = 'px-3 py-1.5 rounded-xl text-xs font-bold bg-stone-100 text-red-700 border border-red-300 transition-all flex items-center gap-1.5 cursor-pointer shadow-sm';
                btn.innerHTML = `<svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/></svg> Suara: NONAKTIF`;
            }
        }
    }

    function toggleAudioAlert() {
        isSoundEnabled = !isSoundEnabled;
        localStorage.setItem('kitchen_sound_enabled', isSoundEnabled);
        updateSoundBtnUI();
        if (isSoundEnabled) {
            playChimeSound();
        }
    }

    function playChimeSound() {
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            const ctx = new AudioContext();
            
            const now = ctx.currentTime;
            const osc1 = ctx.createOscillator();
            const osc2 = ctx.createOscillator();
            const gain = ctx.createGain();

            osc1.type = 'sine';
            osc2.type = 'sine';

            osc1.frequency.setValueAtTime(880, now); // A5 note
            osc2.frequency.setValueAtTime(1046.5, now + 0.15); // C6 note

            gain.gain.setValueAtTime(0, now);
            gain.gain.linearRampToValueAtTime(0.3, now + 0.05);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.45);

            osc1.connect(gain);
            osc2.connect(gain);
            gain.connect(ctx.destination);

            osc1.start(now);
            osc1.stop(now + 0.2);
            osc2.start(now + 0.15);
            osc2.stop(now + 0.45);
        } catch(e) {
            console.log('Audio Context Error:', e);
        }
    }

    // Check if new orders arrived after auto-refresh
    document.addEventListener('DOMContentLoaded', function() {
        updateSoundBtnUI();

        const currentCount = {{ $totalOrdersCount }};
        const prevCount = parseInt(localStorage.getItem('kitchen_last_order_count') || '0');

        if (currentCount > prevCount && prevCount > 0 && isSoundEnabled) {
            playChimeSound();
        }
        localStorage.setItem('kitchen_last_order_count', currentCount);

        // Update live MM:SS ticking timer
        function updateTimers() {
            const now = new Date();
            document.querySelectorAll('.order-timer-badge').forEach(badge => {
                const createdAt = new Date(badge.dataset.createdAt);
                const diffMs = Math.max(0, now - createdAt);
                const totalSeconds = Math.floor(diffMs / 1000);
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;

                const formattedMin = String(minutes).padStart(2, '0');
                const formattedSec = String(seconds).padStart(2, '0');

                if (minutes >= 15) {
                    badge.className = 'order-timer-badge text-xs px-2.5 py-1 rounded-md font-bold font-mono inline-flex items-center gap-1 bg-red-500/20 text-red-400 border border-red-500/40 animate-pulse';
                    badge.innerHTML = `TERLAMBAT ${formattedMin}:${formattedSec}`;
                } else if (minutes >= 10) {
                    badge.className = 'order-timer-badge text-xs px-2.5 py-1 rounded-md font-bold font-mono inline-flex items-center gap-1 bg-orange-500/20 text-orange-300 border border-orange-500/40';
                    badge.innerHTML = `${formattedMin}:${formattedSec}`;
                } else {
                    badge.className = 'order-timer-badge text-xs px-2.5 py-1 rounded-md font-bold font-mono inline-flex items-center gap-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/40';
                    badge.innerHTML = `${formattedMin}:${formattedSec}`;
                }
            });
        }

        updateTimers();
        setInterval(updateTimers, 1000);
    });
</script>
@endsection
