@extends('errors.layout')

@section('content')
    {{-- 404 SVG Illustration --}}
    <div class="flex justify-center mb-6">
        <svg class="w-48 h-48" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            {{-- Compass / map base --}}
            <circle cx="100" cy="100" r="70" class="text-primary" fill="currentColor" opacity="0.1"
                stroke="currentColor" stroke-width="3" />
            <circle cx="100" cy="100" r="55" class="text-primary" stroke="currentColor" stroke-width="1.5"
                stroke-dasharray="6 4" opacity="0.4" />
            {{-- Compass cross lines --}}
            <line x1="100" y1="38" x2="100" y2="62" class="text-primary" stroke="currentColor" stroke-width="2.5"
                stroke-linecap="round" opacity="0.5" />
            <line x1="100" y1="138" x2="100" y2="162" class="text-primary" stroke="currentColor" stroke-width="2.5"
                stroke-linecap="round" opacity="0.5" />
            <line x1="38" y1="100" x2="62" y2="100" class="text-primary" stroke="currentColor" stroke-width="2.5"
                stroke-linecap="round" opacity="0.5" />
            <line x1="138" y1="100" x2="162" y2="100" class="text-primary" stroke="currentColor" stroke-width="2.5"
                stroke-linecap="round" opacity="0.5" />
            {{-- Compass needle (north = primary, south = base-content) --}}
            <polygon points="100,60 107,100 100,108 93,100" class="text-error" fill="currentColor" />
            <polygon points="100,140 107,100 100,108 93,100" class="text-base-content" fill="currentColor"
                opacity="0.4" />
            {{-- Center circle --}}
            <circle cx="100" cy="100" r="7" class="text-base-100" fill="currentColor" />
            <circle cx="100" cy="100" r="4" class="text-primary" fill="currentColor" />
            {{-- Sparkle stars --}}
            <circle cx="148" cy="52" r="3" class="text-secondary" fill="currentColor" opacity="0.6" />
            <circle cx="52" cy="148" r="2" class="text-secondary" fill="currentColor" opacity="0.5" />
            <circle cx="155" cy="140" r="2.5" class="text-accent" fill="currentColor" opacity="0.5" />
        </svg>
    </div>

    {{-- Error code --}}
    <p class="text-8xl font-black tracking-tighter text-primary/40 select-none leading-none mb-2">404</p>

    {{-- Title --}}
    <h1 class="text-2xl font-bold text-base-content mb-3">Halaman Tidak Ditemukan</h1>

    {{-- Description --}}
    <p class="text-base-content/60 mb-8 leading-relaxed">
        Halaman yang kamu cari tidak ada atau telah dipindahkan.<br>
        Mungkin kamu tersesat di antara awan-awan Ghibli?
    </p>

    {{-- Action buttons --}}
    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/' }}"
            class="btn btn-outline btn-primary gap-2">
            <span class="icon-[tabler--arrow-left] size-4"></span>
            Kembali
        </a>
        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-primary gap-2">
                <span class="icon-[tabler--home] size-4"></span>
                Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary gap-2">
                <span class="icon-[tabler--login] size-4"></span>
                Login
            </a>
        @endauth
    </div>
@endsection
