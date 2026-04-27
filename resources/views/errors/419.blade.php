@extends('errors.layout')

@section('content')
    {{-- 419 SVG Illustration --}}
    <div class="flex justify-center mb-6">
        <svg class="w-48 h-48" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            {{-- Hourglass outer --}}
            <path d="M65 30 L135 30 L135 40 L110 80 L110 95 L135 160 L135 170 L65 170 L65 160 L90 95 L90 80 L65 40 Z"
                class="text-secondary" fill="currentColor" opacity="0.12" stroke="currentColor" stroke-width="3"
                stroke-linejoin="round" />
            {{-- Top sand (full) --}}
            <path d="M72 40 L128 40 L108 78 L92 78 Z" class="text-secondary" fill="currentColor" opacity="0.5" />
            {{-- Bottom sand (partial, time running out) --}}
            <path d="M93 155 L107 155 L115 165 L85 165 Z" class="text-secondary" fill="currentColor" opacity="0.5" />
            {{-- Sand dripping (center) --}}
            <circle cx="100" cy="100" r="3" class="text-secondary" fill="currentColor" opacity="0.7" />
            <circle cx="100" cy="110" r="2" class="text-secondary" fill="currentColor" opacity="0.5" />
            <circle cx="100" cy="118" r="1.5" class="text-secondary" fill="currentColor" opacity="0.3" />
            {{-- Clock ticks around the hourglass --}}
            <circle cx="40" cy="100" r="2.5" class="text-accent" fill="currentColor" opacity="0.5" />
            <circle cx="160" cy="100" r="2.5" class="text-accent" fill="currentColor" opacity="0.5" />
            <circle cx="100" cy="15" r="2.5" class="text-accent" fill="currentColor" opacity="0.4" />
            <circle cx="100" cy="185" r="2.5" class="text-accent" fill="currentColor" opacity="0.4" />
            <circle cx="55" cy="42" r="2" class="text-accent" fill="currentColor" opacity="0.3" />
            <circle cx="145" cy="42" r="2" class="text-accent" fill="currentColor" opacity="0.3" />
            <circle cx="55" cy="158" r="2" class="text-accent" fill="currentColor" opacity="0.3" />
            <circle cx="145" cy="158" r="2" class="text-accent" fill="currentColor" opacity="0.3" />
        </svg>
    </div>

    {{-- Error code --}}
    <p class="text-8xl font-black tracking-tighter text-secondary/40 select-none leading-none mb-2">419</p>

    {{-- Title --}}
    <h1 class="text-2xl font-bold text-base-content mb-3">Sesi Kedaluwarsa</h1>

    {{-- Description --}}
    <p class="text-base-content/60 mb-8 leading-relaxed">
        Halaman ini kedaluwarsa karena tidak ada aktivitas.<br>
        Silakan muat ulang dan coba lagi.
    </p>

    {{-- Action buttons --}}
    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <button onclick="window.history.back()" class="btn btn-outline btn-secondary gap-2">
            <span class="icon-[tabler--arrow-left] size-4"></span>
            Kembali
        </button>
        <button onclick="window.location.reload()" class="btn btn-secondary gap-2">
            <span class="icon-[tabler--refresh] size-4"></span>
            Muat Ulang
        </button>
    </div>
@endsection
