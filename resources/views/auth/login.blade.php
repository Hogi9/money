@extends('template.auth-master')

@section('content')
	<div class="card bg-base-100 shadow-xl border border-base-300">
		<div class="card-body gap-5">

			{{-- Header --}}
			<div class="text-center space-y-2">
				<div class="flex justify-center">
					<div class="w-16 h-16 rounded-full bg-primary/15 flex items-center justify-center">
						<span class="icon-[tabler--user] text-primary text-4xl"></span>
					</div>
				</div>
				<h1 class="text-2xl font-bold text-base-content">Welcome Back</h1>
				<p class="text-base-content/60 text-sm">Sign in to your Money account</p>
			</div>

			{{-- Form --}}
			<form method="POST" action="{{ route('login') }}" class="space-y-4">
				@csrf

				{{-- Email or Username --}}
				<div class="form-control gap-1.5">
					<label class="label pb-0" for="username">
						<span class="label-text font-medium text-base-content">Email or Username</span>
					</label>
					<label
						class="input input-bordered w-full flex items-center gap-2 @error('username') input-error @enderror focus-within:input-primary">
						<span class="icon-[tabler--user] text-base-content/40"></span>
						<input id="username" type="text" name="username" value="{{ old('username') }}"
							placeholder="email@example.com or username"
							class="grow bg-transparent outline-none text-base-content placeholder:text-base-content/30" autofocus
							autocomplete="username" />
					</label>
					@error('username')
						<span class="text-error text-xs">{{ $message }}</span>
					@enderror
				</div>

				{{-- Password --}}
				<div class="form-control gap-1.5">
					<label class="label pb-0" for="password">
						<span class="label-text font-medium text-base-content">Password</span>
					</label>
					<label
						class="input input-bordered w-full flex items-center gap-2 @error('password') input-error @enderror focus-within:input-primary">
						<span class="icon-[tabler--lock] text-base-content/40"></span>
						<input id="password" type="password" name="password" placeholder="••••••••"
							class="grow bg-transparent outline-none text-base-content placeholder:text-base-content/30"
							autocomplete="current-password" />
					</label>
					@error('password')
						<span class="text-error text-xs">{{ $message }}</span>
					@enderror
				</div>

				{{-- Remember Me & Forgot Password --}}
				<div class="flex items-center justify-between">
					<label class="flex items-center gap-2 cursor-pointer">
						<input type="checkbox" name="remember" class="checkbox checkbox-primary checkbox-sm" />
						<span class="text-sm text-base-content/70">Remember me</span>
					</label>
					<a href="{{ route('password.request') }}" class="text-sm text-primary hover:underline">
						Forget password?
					</a>
				</div>

				{{-- Submit --}}
				<button type="submit" class="btn btn-primary w-full mt-2">
					Sign In
				</button>
			</form>

			{{-- Divider --}}
			<div class="divider text-base-content/30 text-xs my-1">or</div>

			{{-- Register Link --}}
			<p class="text-center text-sm text-base-content/60">
				Don't have an account?
				@if (Route::has('register'))
					<a href="{{ route('register') }}" class="text-primary font-semibold hover:underline">Register now</a>
				@endif
			</p>

		</div>
	</div>
@endsection
