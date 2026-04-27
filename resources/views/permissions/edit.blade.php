@extends('template.master')
@section('content')
	<div class="pb-4">
		<h1 class="text-2xl font-semibold tracking-tight">Edit Permission</h1>
		<p class="text-base-content/70 mt-1">Edit the details of the permission <strong>{{ $permission->name }}</strong>.</p>
	</div>
	<div class="border-base-content/25 w-full rounded-lg border bg-base-100 p-6 shadow-sm">
		<form action="{{ route('permissions.update', $permission->id) }}" method="POST">
			@csrf
			@method('PUT')
			<div class="mb-6">
				<label class="label mb-1 text-sm font-medium" for="name">Permission Name</label>
				<input type="text" id="name" name="name" value="{{ old('name', $permission->name) }}"
					class="input input-bordered w-full rounded-sm @error('name') input-error @enderror" placeholder="Permission name" />
				@error('name')
					<p class="text-error text-xs mt-1">{{ $message }}</p>
				@enderror
			</div>
			<div class="flex items-center gap-3">
				<button type="submit" class="btn btn-primary rounded-sm">
					<span class="icon-[tabler--device-floppy] size-5"></span>
					Update Permission
				</button>
				<a href="{{ route('permissions.index') }}" class="btn btn-ghost rounded-sm">Cancel</a>
			</div>
		</form>
	</div>
@endsection
