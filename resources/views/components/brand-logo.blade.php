@props([
    'href' => route('landing'),
    'size' => 'md',
    'theme' => 'light',
    'subtitle' => null,
    'tag' => 'a',
])

@php
    $imgSizeClasses = [
        'sm' => 'h-7 w-auto',
        'md' => 'h-8 sm:h-9 w-auto',
        'lg' => 'h-10 sm:h-11 w-auto',
        'xl' => 'h-12 sm:h-14 w-auto',
    ][$size] ?? 'h-8 sm:h-9 w-auto';

    $textSizeClasses = [
        'sm' => 'text-lg',
        'md' => 'text-xl sm:text-2xl',
        'lg' => 'text-2xl sm:text-3xl',
        'xl' => 'text-3xl sm:text-4xl',
    ][$size] ?? 'text-xl sm:text-2xl';

    $sajiColor = $theme === 'dark' ? 'text-white' : 'text-[#8C0000]';
    $hubColor = 'text-[#FFBE0F]';
@endphp

@if($tag === 'a')
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5 flex-shrink-0 group']) }}>
    <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" class="{{ $imgSizeClasses }} flex-shrink-0 object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-200">
    <div class="flex flex-col flex-shrink-0 select-none">
        <span class="font-black {{ $textSizeClasses }} tracking-tight {{ $sajiColor }} leading-none flex items-center">
            <span>Saji</span><span class="{{ $hubColor }}">HUB</span>
        </span>
        @if($subtitle)
            <span class="text-[9px] sm:text-[10px] tracking-widest font-extrabold uppercase text-[#BD2000]/80 mt-0.5">{{ $subtitle }}</span>
        @endif
    </div>
</a>
@else
<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5 flex-shrink-0 group']) }}>
    <img src="{{ asset('images/logo.png') }}" alt="SajiHUB Logo" class="{{ $imgSizeClasses }} flex-shrink-0 object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-200">
    <div class="flex flex-col flex-shrink-0 select-none">
        <span class="font-black {{ $textSizeClasses }} tracking-tight {{ $sajiColor }} leading-none flex items-center">
            <span>Saji</span><span class="{{ $hubColor }}">HUB</span>
        </span>
        @if($subtitle)
            <span class="text-[9px] sm:text-[10px] tracking-widest font-extrabold uppercase text-[#BD2000]/80 mt-0.5">{{ $subtitle }}</span>
        @endif
    </div>
</div>
@endif
