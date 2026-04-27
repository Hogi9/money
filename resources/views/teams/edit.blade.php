@extends('template.master')
@section('content')
	<div class="pb-4">
		<h1 class="text-2xl font-semibold tracking-tight">Edit Group</h1>
		<p class="text-base-content/70 mt-1">Edit information for <strong>{{ $team->name }}</strong>.</p>
	</div>
	<div class="border-base-content/25 w-full rounded-lg border bg-base-100 p-6 shadow-sm w-full">
		<form action="{{ route('teams.update', $team->id) }}" method="POST">
			@csrf
			@method('PUT')
			<div class="mb-4">
				<label class="label mb-1 text-sm font-medium" for="name">Group Name</label>
				<input type="text" id="name" name="name" value="{{ old('name', $team->name) }}"
					class="input input-bordered w-full rounded-sm @error('name') input-error @enderror"
					placeholder="Enter group name..." />
				@error('name')
					<p class="text-error text-xs mt-1">{{ $message }}</p>
				@enderror
			</div>
			<div class="mb-6">
				<label class="label mb-1 text-sm font-medium" for="description">Description</label>
				<textarea id="description" name="description" rows="3"
				 class="textarea textarea-bordered w-full rounded-sm @error('description') textarea-error @enderror"
				 placeholder="Brief description...">{{ old('description', $team->description) }}</textarea>
				@error('description')
					<p class="text-error text-xs mt-1">{{ $message }}</p>
				@enderror
			</div>
			<div class="flex items-center gap-3 justify-between">
				<a href="{{ route('teams.show', $team->id) }}" class="btn btn-ghost rounded-sm">Cancel</a>
				<button type="submit" class="btn btn-primary rounded-sm">
					<span class="icon-[tabler--device-floppy] size-5"></span>
					Save
				</button>
			</div>
		</form>
	</div>
@endsection
