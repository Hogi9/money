@extends('template.auth-master')

@section('content')
    <div class="card bg-base-100 shadow-xl border border-base-300">
        <div class="card-body gap-5">

            {{-- Header --}}
            <div class="text-center space-y-2">
                <div class="flex justify-center">
                    <div class="w-16 h-16 rounded-full bg-error/15 flex items-center justify-center">
                        <span class="icon-[tabler--lock-open] text-error text-4xl"></span>
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-base-content">Buat Password Baru</h1>
                <p class="text-base-content/60 text-sm">Masukkan password baru untuk akun kamu</p>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Email --}}
                <div class="form-control gap-1.5">
                    <label class="label pb-0" for="email">
                        <span class="label-text font-medium text-base-content">Email</span>
                    </label>
                    <label class="input input-bordered w-full flex items-center gap-2 @error('email') input-error @enderror focus-within:input-primary">
                        <span class="icon-[tabler--mail] text-base-content/40"></span>
                        <input id="email" type="email" name="email" value="{{ old('email', $email) }}"
                            class="grow bg-transparent outline-none text-base-content placeholder:text-base-content/30"
                            autocomplete="email" required />
                    </label>
                    @error('email')
                        <span class="text-error text-xs">{{ $message }}</span>
                    @enderror
                </div>

                {{-- New Password --}}
                <div class="form-control gap-1.5">
                    <label class="label pb-0" for="password">
                        <span class="label-text font-medium text-base-content">Password Baru</span>
                    </label>
                    <label class="input input-bordered w-full flex items-center gap-2 @error('password') input-error @enderror focus-within:input-primary">
                        <span class="icon-[tabler--lock] text-base-content/40"></span>
                        <input id="password" type="password" name="password" placeholder="••••••••"
                            class="grow bg-transparent outline-none text-base-content placeholder:text-base-content/30"
                            autocomplete="new-password" required />
                    </label>
                    @error('password')
                        <span class="text-error text-xs">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="form-control gap-1.5">
                    <label class="label pb-0" for="password_confirmation">
                        <span class="label-text font-medium text-base-content">Konfirmasi Password</span>
                    </label>
                    <label class="input input-bordered w-full flex items-center gap-2 focus-within:input-primary">
                        <span class="icon-[tabler--lock-check] text-base-content/40"></span>
                        <input id="password_confirmation" type="password" name="password_confirmation" placeholder="••••••••"
                            class="grow bg-transparent outline-none text-base-content placeholder:text-base-content/30"
                            autocomplete="new-password" required />
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-primary w-full mt-2">
                    Reset Password
                    <span class="icon-[tabler--arrow-right] ml-2"></span>
                </button>
            </form>

        </div>
    </div>
@endsection
