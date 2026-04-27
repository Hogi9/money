@extends('errors.layout')

@section('content')
    {{-- 500 SVG Illustration --}}
    <div class="flex justify-center mb-6">
        <svg class="w-48 h-48" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            {{-- Gear large --}}
            <path d="M100 55 L108 40 L120 48 L118 62 C124 65 129 70 133 76 L147 74 L155 86 L143 95
                     C144 101 144 107 143 113 L155 122 L147 134 L133 132 C129 138 124 143 118 146
                     L120 160 L108 168 L100 153 L92 168 L80 160 L82 146 C76 143 71 138 67 132
                     L53 134 L45 122 L57 113 C56 107 56 101 57 95 L45 86 L53 74 L67 76
                     C71 70 76 65 82 62 L80 48 L92 40 Z"
                class="text-error" fill="currentColor" opacity="0.15" stroke="currentColor" stroke-width="2.5"
                stroke-linejoin="round" />
            {{-- Gear small (overlapping) --}}
            <path d="M148 85 L153 76 L161 80 L160 89 C163 91 165 94 166 97 L175 97 L178 106 L170 110
                     C170 113 169 116 168 119 L175 124 L172 133 L163 131 C161 134 158 136 155 138
                     L156 147 L147 150 L143 142 C140 142 137 142 134 141 L130 149 L121 146 L122 137
                     C119 135 117 132 115 129 L106 130 L103 121 L111 117 C111 114 112 111 113 108
                     L106 103 L109 94 L118 96 C120 93 122 91 125 89 L124 80 L133 77 L137 85
                     C140 85 143 85 146 86 Z"
                class="text-warning" fill="currentColor" opacity="0.15" stroke="currentColor" stroke-width="2"
                stroke-linejoin="round" />
            {{-- Inner circles --}}
            <circle cx="100" cy="100" r="18" class="text-base-100" fill="currentColor" />
            <circle cx="100" cy="100" r="12" class="text-error" fill="currentColor" opacity="0.7" />
            <circle cx="140" cy="113" r="11" class="text-base-100" fill="currentColor" />
            <circle cx="140" cy="113" r="7" class="text-warning" fill="currentColor" opacity="0.7" />
            {{-- Exclamation in large gear --}}
            <rect x="98" y="91" width="4" height="10" rx="2" fill="white" />
            <circle cx="100" cy="106" r="2" fill="white" />
        </svg>
    </div>

    {{-- Error code --}}
    <p class="text-8xl font-black tracking-tighter text-error/40 select-none leading-none mb-2">500</p>

    {{-- Title --}}
    <h1 class="text-2xl font-bold text-base-content mb-3">Terjadi Kesalahan Server</h1>

    {{-- Description --}}
    <p class="text-base-content/60 mb-8 leading-relaxed">
        Ada sesuatu yang tidak beres di sisi server kami.<br>
        Tim kami sedang bekerja keras untuk memperbaikinya.
    </p>

    {{-- Action buttons --}}
    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <button onclick="window.history.back()" class="btn btn-outline btn-error gap-2">
            <span class="icon-[tabler--arrow-left] size-4"></span>
            Kembali
        </button>
        <button onclick="window.location.reload()" class="btn btn-error gap-2">
            <span class="icon-[tabler--refresh] size-4"></span>
            Coba Lagi
        </button>
        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-ghost gap-2">
                <span class="icon-[tabler--home] size-4"></span>
                Dashboard
            </a>
        @endauth
    </div>
@endsection
