@extends('template.auth-master')

@section('content')
	<div class="card bg-base-100 shadow-xl border border-base-300">
		<div class="card-body gap-5">
			{{-- Header --}}
			<div class="text-center space-y-2">
				<h1 class="text-2xl font-bold text-base-content">Forgot Password</h1>
				<p class="text-base-content/60 text-sm">Enter your email to reset your password</p>
			</div>

			{{-- Form --}}
			<form method="POST" action="{{ route('password.email') }}" class="space-y-4">
				@csrf

				{{-- Email --}}
				<div class="form-control gap-1.5">
					<label
						class="input input-bordered w-full flex items-center gap-2 @error('email') input-error @enderror focus-within:input-primary">
						<span class="icon-[tabler--user] text-base-content/40"></span>
						<input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com"
							class="grow bg-transparent outline-none text-base-content placeholder:text-base-content/30" autofocus
							autocomplete="email" />
					</label>
					@error('email')
						<span class="text-error text-xs">{{ $message }}</span>
					@enderror
				</div>

				{{-- Submit --}}
				<button type="submit" class="btn btn-primary w-full mt-2">
					Send Password Reset Link
					<span class="icon-[tabler--arrow-right] ml-2"></span>
				</button>
			</form>
		</div>
	</div>
@endsection
