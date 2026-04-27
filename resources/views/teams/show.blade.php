@extends('template.master')
@section('content')
	<div class="pb-4 flex items-start justify-between">
		<div>
			<h1 class="text-2xl font-semibold tracking-tight">{{ $team->name }}</h1>
			<p class="text-base-content/70 mt-1">{{ $team->description ?? 'No description available.' }}</p>
		</div>
		<div class="flex gap-2">
			@can('update', $team)
				<a href="{{ route('teams.edit', $team->id) }}" class="btn btn-sm btn-outline btn-primary rounded-sm">
					<span class="icon-[tabler--pencil] size-4"></span>
					Edit Group
				</a>
			@endcan
			<button type="button" class="delete-button btn btn-sm btn-outline btn-error rounded-sm" aria-haspopup="dialog"
				aria-expanded="false" aria-controls="modal-delete" data-overlay="#modal-delete"
				data-action="{{ route('teams.destroy', $team->id) }}">
				<span class="icon-[tabler--trash] size-4"></span>
				Delete Group
			</button>
		</div>
	</div>

	@include('teams.delete.modal')

	<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
		{{-- Daftar Anggota --}}
		<div class="lg:col-span-2 border-base-content/25 rounded-lg border bg-base-100 p-4 shadow-sm">
			<h2 class="font-semibold text-base mb-4 flex items-center gap-2">
				<span class="icon-[tabler--users] size-5"></span>
				Group Members
				<span class="badge badge-soft badge-neutral text-xs">{{ $team->members->count() }}</span>
			</h2>
			<div class="overflow-x-auto">
				<table class="table">
					<thead>
						<tr>
							<th>Name</th>
							<th>Email</th>
							<th>Role</th>
							<th>Joined</th>
							@can('removeMember', $team)
								<th>Actions</th>
							@endcan
						</tr>
					</thead>
					<tbody>
						@foreach ($team->members as $member)
							<tr class="row-hover">
								<td>
									<div class="flex items-center gap-2">
										<div
											class="bg-primary text-primary-content rounded-full w-8 h-8 flex items-center justify-center text-sm font-semibold shrink-0">
											{{ strtoupper(substr($member->name, 0, 1)) }}
										</div>
										<div>
											<div class="font-medium text-sm">{{ $member->name }}</div>
											<div class="text-xs text-base-content/50">{{ $member->username }}</div>
										</div>
									</div>
								</td>
								<td class="text-sm">{{ $member->email }}</td>
								<td>
									@if ($member->pivot->role === 'owner')
										<span class="badge badge-soft badge-warning text-xs">
											<span class="icon-[tabler--crown] size-3 me-1"></span>Owner
										</span>
									@else
										<span class="badge badge-soft badge-info text-xs">Member</span>
									@endif
								</td>
								<td class="text-xs text-base-content/60">
									{{ \Carbon\Carbon::parse($member->pivot->joined_at)->format('d M Y') }}
								</td>
								@can('removeMember', $team)
									<td>
										@if ($member->pivot->role !== 'owner')
											<form action="{{ route('teams.members.remove', [$team, $member]) }}" method="POST" class="inline"
												onsubmit="return confirm('Remove {{ $member->name }} from the group?')">
												@csrf
												@method('DELETE')
												<button type="submit" class="btn btn-xs btn-outline btn-error rounded-sm">
													<span class="icon-[tabler--user-minus] size-4"></span>
													Remove
												</button>
											</form>
										@else
											<span class="text-base-content/30 text-xs">—</span>
										@endif
									</td>
								@endcan
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>

		{{-- Tambah Anggota --}}
		@can('addMember', $team)
			<div class="border-base-content/25 rounded-lg border bg-base-100 p-4 shadow-sm h-fit">
				<h2 class="font-semibold text-base mb-4 flex items-center gap-2">
					<span class="icon-[tabler--user-plus] size-5"></span>
					Add Member
				</h2>
				@if ($availableUsers->isEmpty())
					<p class="text-base-content/50 text-sm">All users are already members.</p>
				@else
					<form action="{{ route('teams.members.add', $team) }}" method="POST">
						@csrf
						<div class="mb-3">
							<label class="label mb-1 text-sm font-medium" for="user_id">Choose User</label>
							<select id="user_id" name="user_id" class="select select-bordered w-full rounded-sm select-sm">
								<option value="">-- Choose User --</option>
								@foreach ($availableUsers as $user)
									<option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
								@endforeach
							</select>
						</div>
						<button type="submit" class="btn btn-primary btn-sm w-full rounded-sm">
							<span class="icon-[tabler--user-plus] size-4"></span>
							Add Member
						</button>
					</form>
				@endif

				<div class="divider my-4 text-xs text-base-content/40">Info</div>
				<div class="text-xs text-base-content/60 space-y-1">
					<p class="flex items-center gap-1">
						<span class="icon-[tabler--shield-check] size-4 text-warning"></span>
						Owner can manage members
					</p>
					<p class="flex items-center gap-1">
						<span class="icon-[tabler--lock] size-4 text-error"></span>
						Owner cannot be removed
					</p>
				</div>
			</div>
		@endcan
	</div>

	<div class="pt-4">
		<a href="{{ route('teams.index') }}" class="btn btn-ghost btn-sm rounded-sm">
			<span class="icon-[tabler--arrow-left] size-4"></span>
			Back to Group List
		</a>
	</div>
@endsection
@include('teams.delete.script')
