@extends('template.master')
@section('content')
	<div class="pb-4">
		<h1 class="text-2xl font-semibold tracking-tight">Add User</h1>
		<p class="text-base-content/70 mt-1">Create a new user account and assign a role.</p>
	</div>
	<div class="border-base-content/25 w-full rounded-lg border bg-base-100 p-6 shadow-sm max-w-6xl">
		<form action="{{ route('users.store') }}" method="POST">
			@csrf
			<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
				<div>
					<label class="label mb-1 text-sm font-medium" for="name">Full Name</label>
					<input type="text" id="name" name="name" value="{{ old('name') }}"
						class="input input-bordered w-full rounded-sm @error('name') input-error @enderror" placeholder="Full Name..." />
					@error('name')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>
				<div>
					<label class="label mb-1 text-sm font-medium" for="username">Username</label>
					<input type="text" id="username" name="username" value="{{ old('username') }}"
						class="input input-bordered w-full rounded-sm @error('username') input-error @enderror"
						placeholder="Username..." />
					@error('username')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>
				<div>
					<label class="label mb-1 text-sm font-medium" for="email">Email</label>
					<input type="email" id="email" name="email" value="{{ old('email') }}"
						class="input input-bordered w-full rounded-sm @error('email') input-error @enderror" placeholder="Email..." />
					@error('email')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>
				<div>
					<label class="label mb-1 text-sm font-medium" for="password">Password</label>
					<input type="password" id="password" name="password"
						class="input input-bordered w-full rounded-sm @error('password') input-error @enderror" placeholder="********" />
					@error('password')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>
				<div class="md:col-span-2">
					<label class="label mb-1 text-sm font-medium" for="roles">Role</label>
					<select id="roles" name="roles[]" multiple class="@error('roles') is-invalid @enderror hidden"
						data-select='{
							"placeholder": "Select roles...",
							"toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
							"toggleClasses": "advance-select-toggle rounded-sm select-disabled:pointer-events-none select-disabled:opacity-40",
							"toggleSeparators": { "betweenItemsAndCounter": "&" },
							"toggleCountText": "+",
							"toggleCountTextPlacement": "prefix-no-space",
							"toggleCountTextMinItems": 3,
							"toggleCountTextMode": "nItemsAndCount",
							"dropdownClasses": "advance-select-menu max-h-44 overflow-y-auto border border-primary",
							"optionClasses": "advance-select-option selected:select-active",
							"optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"icon-[tabler--check] shrink-0 size-4 text-primary hidden selected:block\"></span></div>",
							"extraMarkup": "<span class=\"icon-[tabler--caret-up-down] shrink-0 size-4 text-base-content absolute top-1/2 end-3 -translate-y-1/2\"></span>"
						}'>
						@foreach ($roles as $role)
							<option value="{{ $role->name }}" {{ in_array($role->name, old('roles', [])) ? 'selected' : '' }}>
								{{ ucfirst($role->name) }}
							</option>
						@endforeach
					</select>
					@error('roles')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>
			</div>
			<div class="flex items-center gap-3 justify-between">
				<a href="{{ route('users.index') }}" class="btn btn-ghost rounded-sm">Cancel</a>
				<button type="submit" class="btn btn-primary rounded-sm">
					<span class="icon-[tabler--device-floppy] size-5"></span>
					Save
				</button>
			</div>
		</form>
	</div>
@endsection
