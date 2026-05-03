@extends('template.auth-master')

@section('content')
	<div class="card bg-base-100 shadow-xl border border-base-300">
		<div class="card-body gap-5">
			{{-- Header --}}
			<div class="text-center space-y-2">
				<h1 class="text-2xl font-bold text-base-content">Forgot Password</h1>
				<p class="text-base-content/60 text-sm">Masukkan email atau username untuk reset password</p>
			</div>

			{{-- Form --}}
			<form method="POST" action="{{ route('password.email') }}" class="space-y-4">
				@csrf

				{{-- Email or Username --}}
				<div class="form-control gap-1.5">
					<label
						class="input input-bordered w-full flex items-center gap-2 @error('login') input-error @enderror focus-within:input-primary">
						<span class="icon-[tabler--user] text-base-content/40"></span>
						<input id="login" type="text" name="login" value="{{ old('login') }}" placeholder="email@example.com or username"
							class="grow bg-transparent outline-none text-base-content placeholder:text-base-content/30" autofocus
							autocomplete="username" />
					</label>
					@error('login')
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
