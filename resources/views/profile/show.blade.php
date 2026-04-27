@extends('template.master')
@section('content')
	<div class="pb-4">
		<h1 class="text-2xl font-semibold tracking-tight">My Profile</h1>
		<p class="text-base-content/70 mt-1">View and update your personal account information.</p>
	</div>

	<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
		{{-- Profile Summary Card --}}
		<div
			class="border-base-content/25 rounded-lg border bg-base-100 p-6 shadow-sm flex flex-col items-center gap-3 text-center">
			<div class="avatar avatar-placeholder">
				<div class="bg-primary/15 text-primary rounded-full w-20 h-20 flex items-center justify-center">
					<span class="text-3xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
				</div>
			</div>
			<div>
				<p class="text-lg font-semibold">{{ $user->name }}</p>
				<p class="text-sm text-base-content/60">&#64;{{ $user->username }}</p>
				<p class="text-sm text-base-content/60">{{ $user->email }}</p>
			</div>
			@if ($user->roles->isNotEmpty())
				<div class="flex flex-wrap gap-1 justify-center">
					@foreach ($user->roles as $role)
						<span class="badge badge-primary badge-soft rounded-sm text-xs">{{ ucfirst($role->name) }}</span>
					@endforeach
				</div>
			@endif
			@if ($user->teams->isNotEmpty())
				<div class="w-full mt-2 text-left">
					<p class="text-xs font-semibold text-base-content/50 uppercase tracking-wider mb-2">Teams</p>
					<ul class="space-y-1">
						@foreach ($user->teams as $team)
							<li class="flex items-center gap-2 text-sm">
								<span class="icon-[tabler--users-group] size-4 text-base-content/50"></span>
								{{ $team->name }}
								@if ($team->pivot->role)
									<span class="badge badge-soft badge-neutral rounded-sm text-xs ms-auto">{{ $team->pivot->role }}</span>
								@endif
							</li>
						@endforeach
					</ul>
				</div>
			@endif
		</div>

		{{-- Edit Form --}}
		<div class="lg:col-span-2 border-base-content/25 rounded-lg border bg-base-100 p-6 shadow-sm">
			<h2 class="text-base font-semibold mb-4">Edit Information</h2>
			<form action="{{ route('profile.update') }}" method="POST">
				@csrf
				@method('PUT')
				<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
					<div>
						<label class="label mb-1 text-sm font-medium" for="name">Full Name</label>
						<input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
							class="input input-bordered w-full rounded-sm @error('name') input-error @enderror" placeholder="Full Name..." />
						@error('name')
							<p class="text-error text-xs mt-1">{{ $message }}</p>
						@enderror
					</div>
					<div>
						<label class="label mb-1 text-sm font-medium" for="username">Username</label>
						<input type="text" id="username" name="username" value="{{ old('username', $user->username) }}"
							class="input input-bordered w-full rounded-sm @error('username') input-error @enderror"
							placeholder="Username..." />
						@error('username')
							<p class="text-error text-xs mt-1">{{ $message }}</p>
						@enderror
					</div>
					<div class="md:col-span-2">
						<label class="label mb-1 text-sm font-medium" for="email">Email</label>
						<input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
							class="input input-bordered w-full rounded-sm @error('email') input-error @enderror" placeholder="Email..." />
						@error('email')
							<p class="text-error text-xs mt-1">{{ $message }}</p>
						@enderror
					</div>
				</div>

				<div class="divider text-xs text-base-content/40">Change Password</div>

				<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
					<div>
						<label class="label mb-1 text-sm font-medium" for="password">New Password</label>
						<input type="password" id="password" name="password"
							class="input input-bordered w-full rounded-sm @error('password') input-error @enderror"
							placeholder="Leave blank to keep current" />
						@error('password')
							<p class="text-error text-xs mt-1">{{ $message }}</p>
						@enderror
					</div>
					<div>
						<label class="label mb-1 text-sm font-medium" for="password_confirmation">Confirm Password</label>
						<input type="password" id="password_confirmation" name="password_confirmation"
							class="input input-bordered w-full rounded-sm" placeholder="Confirm new password" />
					</div>
				</div>

				<div class="flex justify-end">
					<button type="submit" class="btn btn-primary rounded-sm">
						<span class="icon-[tabler--device-floppy] size-5"></span>
						Save
					</button>
				</div>
			</form>
		</div>
	</div>
@endsection
