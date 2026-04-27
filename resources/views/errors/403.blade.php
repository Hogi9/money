@extends('errors.layout')

@section('content')
    {{-- 403 SVG Illustration --}}
    <div class="flex justify-center mb-6">
        <svg class="w-48 h-48 text-warning" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            {{-- Shield body --}}
            <path d="M100 20 L160 45 L160 100 C160 140 130 165 100 180 C70 165 40 140 40 100 L40 45 Z"
                fill="currentColor" opacity="0.15" stroke="currentColor" stroke-width="4" stroke-linejoin="round" />
            {{-- Lock shackle --}}
            <rect x="82" y="95" width="36" height="30" rx="4"
                fill="currentColor" opacity="0.8" />
            <path d="M88 95 L88 82 C88 71 112 71 112 82 L112 95"
                stroke="currentColor" stroke-width="5" stroke-linecap="round" fill="none" opacity="0.8" />
            {{-- Lock keyhole --}}
            <circle cx="100" cy="108" r="5" fill="white" opacity="0.9" />
            <rect x="97" y="110" width="6" height="7" rx="1" fill="white" opacity="0.9" />
            {{-- Stars / sparkles around shield --}}
            <circle cx="35" cy="60" r="3" fill="currentColor" opacity="0.4" />
            <circle cx="165" cy="75" r="2" fill="currentColor" opacity="0.3" />
            <circle cx="50" cy="150" r="2.5" fill="currentColor" opacity="0.3" />
            <circle cx="155" cy="155" r="3" fill="currentColor" opacity="0.4" />
        </svg>
    </div>

    {{-- Error code --}}
    <p class="text-8xl font-black tracking-tighter text-warning/60 select-none leading-none mb-2">403</p>

    {{-- Title --}}
    <h1 class="text-2xl font-bold text-base-content mb-3">Akses Ditolak</h1>

    {{-- Description --}}
    <p class="text-base-content/60 mb-8 leading-relaxed">
        Kamu tidak memiliki izin untuk mengakses halaman ini.<br>
        Hubungi administrator jika kamu rasa ini adalah kesalahan.
    </p>

    {{-- Action buttons --}}
    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/' }}"
            class="btn btn-outline btn-warning gap-2">
            <span class="icon-[tabler--arrow-left] size-4"></span>
            Kembali
        </a>
        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-warning gap-2">
                <span class="icon-[tabler--home] size-4"></span>
                Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="btn btn-warning gap-2">
                <span class="icon-[tabler--login] size-4"></span>
                Login
            </a>
        @endauth
    </div>
@endsection
