@extends('template.master')
@section('content')
	<div class="pb-4">
		<h1 class="text-2xl font-semibold tracking-tight">User Management</h1>
		<p class="text-base-content/70 mt-1">Manage all user accounts in the application.</p>
	</div>
	<div class="border-base-content/25 w-full rounded-lg border bg-base-100 p-4 shadow-sm">
		<div class="mb-4 grid grid-cols-1 gap-3 lg:flex lg:items-center lg:justify-between">
			<form method="GET" action="{{ route('users.index') }}"
				class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:flex lg:items-center lg:flex-1">

				<select name="role" class="select select-sm rounded-sm w-full lg:w-36">
					<option value="">All Roles</option>
					@foreach ($roles as $role)
						<option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
							{{ ucfirst($role->name) }}
						</option>
					@endforeach
				</select>

				<div class="input input-sm flex items-center space-x-2 rounded-sm w-full lg:max-w-[220px]">
					<span class="icon-[tabler--search] text-base-content/80 size-5 shrink-0"></span>
					<input type="search" name="search" class="grow bg-transparent outline-none" placeholder="Search..."
						value="{{ request('search') }}" />
				</div>

				<div class="flex items-center gap-2">
					<button type="submit"
						class="btn btn-sm btn-outline btn-primary rounded-sm w-[80vw] sm:w-full lg:w-auto">Filter</button>

					@if (request()->hasAny(['search', 'role']))
						<a href="{{ route('users.index') }}"
							class="btn btn-sm btn-text flex items-center gap-1 p-0 h-auto min-h-0 font-normal normal-case text-base-content/60 hover:text-primary hover:bg-transparent shadow-none border-none">
							<span class="icon-[tabler--x] size-4"></span>Clear
						</a>
					@endif
				</div>
			</form>

			@can('create-users')
				<div class="w-full lg:w-auto">
					<a href="{{ route('users.create') }}" class="btn btn-sm btn-primary rounded-sm w-full lg:w-auto justify-center">
						<span class="icon-[tabler--plus] size-5"></span>
						Add User
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
						<th>Name</th>
						<th>Username</th>
						<th>Email</th>
						<th>Role</th>
						<th>Joined</th>
					</tr>
				</thead>
				<tbody>
					@forelse($users as $user)
						<tr class="row-hover">
							<td>{{ $users->firstItem() + $loop->index }}</td>
							<td>
								@can('edit-users')
									<a href="{{ route('users.edit', $user->id) }}" class="btn btn-circle btn-text btn-sm hover:text-warning"
										aria-label="Edit">
										<span class="icon-[tabler--pencil] size-5"></span>
									</a>
								@endcan
								@can('delete-users')
									@if ($user->id !== auth()->id())
										<button type="button" class="delete-btn btn btn-circle btn-text btn-sm hover:text-error" aria-haspopup="dialog"
											aria-expanded="false" aria-controls="modal-delete" data-overlay="#modal-delete"
											data-action="{{ route('users.destroy', $user->id) }}">
											<span class="icon-[tabler--trash] size-5"></span>
										</button>
									@endif
								@endcan
							</td>
							<td>
								<div class="flex items-center gap-2">
									<div
										class="bg-primary text-primary-content rounded-full w-8 h-8 flex items-center justify-center text-sm font-semibold shrink-0">
										{{ strtoupper(substr($user->name, 0, 1)) }}
									</div>
									<span class="font-medium">{{ $user->name }}</span>
									@if ($user->id === auth()->id())
										<span class="badge badge-soft badge-success text-xs">You</span>
									@endif
								</div>
							</td>
							<td class="text-base-content/70">{{ $user->username }}</td>
							<td>{{ $user->email }}</td>
							<td>
								@php $userRoles = $user->roles; @endphp
								@forelse($userRoles->take(2) as $role)
									<span class="badge badge-soft badge-primary text-xs">{{ $role->name }}</span>
								@empty
									<span class="text-base-content/40 text-xs">No roles</span>
								@endforelse
								@if ($userRoles->count() > 2)
									<span class="badge badge-soft badge-neutral text-xs">+{{ $userRoles->count() - 2 }}</span>
								@endif
							</td>
							<td>{{ $user->created_at->format('d M Y') }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="7" class="text-center text-base-content/50 py-8">No users found.</td>
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
		{{ $users->links('template.pagination') }}
	</div>
@endsection
@push('scripts')
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const deleteButtons = document.querySelectorAll('.delete-btn');
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
