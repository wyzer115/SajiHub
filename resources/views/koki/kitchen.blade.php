@extends('layouts.app')
@section('title', 'Tampilan Dapur')
@section('page-title', 'Dapur - Pesanan Masuk')

@push('scripts')
<script>
    // Auto refresh every 30 seconds
    setTimeout(() => {
        window.location.reload();
    }, 30000);
</script>
@endpush

@section('content')
@php
    $pendingOrders = $orders->where('order_status', 'pending');
    $cookingOrders = $orders->where('order_status', 'cooking');
@endphp

<div class="space-y-8 animate-fade-in-up">
    @if($orders->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center bg-dark-900 border border-dark-700 rounded-3xl">
            <svg class="w-24 h-24 text-dark-500 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <h3 class="text-2xl font-bold text-white mb-2">Tidak ada pesanan saat ini</h3>
            <p class="text-dark-400">Dapur sedang sepi. Sistem akan memuat ulang otomatis setiap 30 detik.</p>
        </div>
    @else
        <!-- MENUNGGU -->
        @if($pendingOrders->count() > 0)
        <div>
            <div class="flex items-center space-x-3 mb-6">
                <h2 class="text-xl font-bold text-white">Menunggu Dimasak</h2>
                <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-sm font-bold">{{ $pendingOrders->count() }}</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($pendingOrders as $order)
                <div class="bg-yellow-500/5 border border-yellow-500/20 rounded-2xl overflow-hidden flex flex-col h-full shadow-lg shadow-yellow-500/5">
                    <div class="p-4 bg-yellow-500/10 border-b border-yellow-500/20 flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-bold text-white mb-1">#{{ $order->id }}</h3>
                            <p class="text-yellow-400/80 text-xs font-medium">{{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-right">
                            <div class="bg-dark-900 border border-yellow-500/30 px-3 py-1 rounded-lg">
                                <span class="text-[10px] text-dark-300 uppercase block leading-none mb-1">Meja</span>
                                <span class="text-lg font-bold text-white leading-none">{{ $order->table->table_number ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-5 flex-1 bg-dark-900/50">
                        <p class="text-dark-300 text-sm mb-4">Pelanggan: <span class="text-white font-medium">{{ $order->customer_name }}</span></p>
                        
                        <div class="space-y-3">
                            @foreach($order->items as $item)
                            <div class="flex items-start">
                                <div class="bg-dark-800 text-white font-bold w-8 h-8 rounded flex items-center justify-center shrink-0 border border-dark-600 mr-3">
                                    {{ $item->quantity }}
                                </div>
                                <div>
                                    <p class="text-white font-medium text-base leading-tight">{{ $item->menu->name ?? 'Menu Dihapus' }}</p>
                                    @if($item->notes)
                                        <p class="text-yellow-400 text-sm font-semibold mt-1.5 flex items-start bg-yellow-500/5 py-2 px-3 rounded-lg border border-yellow-500/20">
                                            <svg class="w-4 h-4 mr-1.5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                            {{ $item->notes }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="p-4 border-t border-yellow-500/20 bg-dark-900/80">
                        <form action="{{ route('koki.orders.update-status', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="order_status" value="cooking">
                            <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-dark-950 font-bold py-3 rounded-xl transition-all shadow-lg shadow-yellow-500/20 flex justify-center items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path></svg>
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
        <div class="mt-12">
            <div class="flex items-center space-x-3 mb-6">
                <h2 class="text-xl font-bold text-white">Sedang Dimasak</h2>
                <span class="px-3 py-1 bg-orange-500/20 text-orange-400 rounded-full text-sm font-bold">{{ $cookingOrders->count() }}</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($cookingOrders as $order)
                <div class="bg-orange-500/5 border border-orange-500/30 rounded-2xl overflow-hidden flex flex-col h-full shadow-lg shadow-orange-500/10 relative group">
                    <div class="absolute inset-0 border-2 border-orange-500/20 rounded-2xl animate-pulse pointer-events-none"></div>
                    
                    <div class="p-4 bg-orange-500/10 border-b border-orange-500/30 flex justify-between items-start relative z-10">
                        <div>
                            <h3 class="text-lg font-bold text-white mb-1">#{{ $order->id }}</h3>
                            <p class="text-orange-400/80 text-xs font-medium">{{ $order->updated_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-right">
                            <div class="bg-dark-900 border border-orange-500/50 px-3 py-1 rounded-lg">
                                <span class="text-[10px] text-dark-300 uppercase block leading-none mb-1">Meja</span>
                                <span class="text-lg font-bold text-white leading-none">{{ $order->table->table_number ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-5 flex-1 bg-dark-900/50 relative z-10">
                        <p class="text-dark-300 text-sm mb-4">Pelanggan: <span class="text-white font-medium">{{ $order->customer_name }}</span></p>
                        
                        <div class="space-y-3">
                            @foreach($order->items as $item)
                            <div class="flex items-start">
                                <div class="bg-dark-800 text-white font-bold w-8 h-8 rounded flex items-center justify-center shrink-0 border border-dark-600 mr-3">
                                    {{ $item->quantity }}
                                </div>
                                <div>
                                    <p class="text-white font-medium text-base leading-tight">{{ $item->menu->name ?? 'Menu Dihapus' }}</p>
                                    @if($item->notes)
                                        <p class="text-orange-400 text-sm font-semibold mt-1.5 flex items-start bg-orange-500/5 py-2 px-3 rounded-lg border border-orange-500/30">
                                            <svg class="w-4 h-4 mr-1.5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                            {{ $item->notes }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="p-4 border-t border-orange-500/30 bg-dark-900/80 relative z-10">
                        <form action="{{ route('koki.orders.update-status', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="order_status" value="served">
                            <button type="submit" class="w-full bg-brand-500 hover:bg-brand-600 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-brand-500/20 flex justify-center items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Siap Disajikan
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
@endsection
