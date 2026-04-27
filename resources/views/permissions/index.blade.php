@extends('template.master')
@section('content')
	<div class="pb-4">
		<h1 class="text-2xl font-semibold tracking-tight">Permission Management</h1>
		<p class="text-base-content/70 mt-1">Manage the list of permissions available in the application.</p>
	</div>
	<div class="border-base-content/25 w-full rounded-lg border bg-base-100 p-4 shadow-sm">
		<div class="mb-4 flex items-center gap-3 justify-between">
			<div class="flex items-center gap-4">
				<form method="GET" action="{{ route('permissions.index') }}" class="flex items-center gap-2">
					<div class="input input-sm flex max-w-[250px] items-center space-x-4 rounded-sm">
						<span class="icon-[tabler--search] text-base-content/80 size-5 shrink-0"></span>
						<input type="search" name="search" class="grow bg-transparent outline-none" placeholder="Search permissions..."
							value="{{ request('search') }}" />
					</div>
					<button type="submit" class="btn btn-sm btn-outline btn-primary rounded-sm">Search</button>
				</form>
				@if (request('search'))
					<a href="{{ route('permissions.index') }}"
						class="btn btn-sm btn-text flex items-center gap-1 p-0 h-auto min-h-0 font-normal normal-case text-base-content/60 hover:text-primary hover:bg-transparent shadow-none border-none">
						<span class="icon-[tabler--x] size-4"></span>
						Clear Filter
					</a>
				@endif
			</div>
			@can('create-permissions')
				<div class="flex">
					<a href="{{ route('permissions.create') }}" class="btn btn-sm btn-primary rounded-sm">
						<span class="icon-[tabler--plus] size-5"></span>
						Add Permission
					</a>
				</div>
			@endcan
		</div>
		<div class="overflow-x-auto">
			<table class="table" id="main-table">
				<thead>
					<tr>
						<th>#</th>
						<th>Actions</th>
						<th>Permission Name</th>
						<th>Guard</th>
						<th>Created</th>
					</tr>
				</thead>
				<tbody>
					@forelse($permissions as $permission)
						<tr class="row-hover">
							<td>{{ $permissions->firstItem() + $loop->index }}</td>
							<td>
								@can('edit-permissions')
									<a href="{{ route('permissions.edit', $permission->id) }}"
										class="btn btn-circle btn-text btn-sm hover:text-warning" aria-label="Edit">
										<span class="icon-[tabler--pencil] size-5"></span>
									</a>
								@endcan
								@can('delete-permissions')
									<button type="button" class="delete-button btn btn-circle btn-text btn-sm hover:text-error"
										aria-haspopup="dialog" aria-expanded="false" aria-controls="modal-delete" data-overlay="#modal-delete"
										data-action="{{ route('permissions.destroy', $permission->id) }}">
										<span class="icon-[tabler--trash] size-5"></span>
									</button>
								@endcan
							</td>
							<td class="font-medium">{{ $permission->name }}</td>
							<td><span class="badge badge-soft badge-info text-xs">{{ $permission->guard_name }}</span></td>
							<td>{{ $permission->created_at->format('d M Y') }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="5" class="text-center text-base-content/50 py-8">No permissions found.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
	<div id="modal-delete" class="overlay modal overlay-open:opacity-100 overlay-open:duration-300 modal-middle hidden"
		role="dialog" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title">Confirm Deletion</h3>
					<button type="button" class="btn btn-text btn-circle btn-sm absolute end-3 top-3" aria-label="Close"
						data-overlay="#modal-delete">
						<span class="icon-[tabler--x] size-4"></span>
					</button>
				</div>
				<div class="modal-body">
					Are you sure you want to delete this data?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-soft btn-secondary" data-overlay="#modal-delete">Close</button>
					<form id="delete-form" method="POST" class="inline">
						@csrf
						@method('DELETE')
						<button type="submit" class="btn btn-error">Delete</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="mt-4">
		{{ $permissions->links('template.pagination') }}
	</div>
@endsection
@push('scripts')
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const deleteButtons = document.querySelectorAll('.delete-button');
			const deleteForm = document.getElementById('delete-form');

			deleteButtons.forEach(button => {
				button.addEventListener('click', function() {
					const action = this.getAttribute('data-action');
					if (deleteForm) {
						deleteForm.setAttribute('action', action);
					}
				});
			});
		});
	</script>
@endpush
