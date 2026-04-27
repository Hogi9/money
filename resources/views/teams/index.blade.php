@extends('template.master')
@section('content')
	<div class="pb-4">
		<h1 class="text-2xl font-semibold tracking-tight">Groups / Teams</h1>
		<p class="text-base-content/70 mt-1">Manage your group and team memberships</p>
	</div>
	<div class="border-base-content/25 w-full rounded-lg border bg-base-100 p-4 shadow-sm">
		<div class="mb-4 flex items-center gap-3 justify-between">
			<form method="GET" action="{{ route('teams.index') }}" class="flex items-center gap-2">
				<div class="input input-sm flex max-w-[250px] items-center space-x-4 rounded-sm">
					<span class="icon-[tabler--search] text-base-content/80 size-5 shrink-0"></span>
					<input type="search" name="search" class="grow bg-transparent outline-none" placeholder="Search groups..."
						value="{{ request('search') }}" />
				</div>
				<button type="submit" class="btn btn-sm btn-outline btn-primary rounded-sm">Search</button>
				@if (request('search'))
					<a href="{{ route('teams.index') }}"
						class="btn btn-sm btn-text flex items-center gap-1 p-0 h-auto min-h-0 font-normal normal-case text-base-content/60 hover:text-primary hover:bg-transparent shadow-none border-none">
						<span class="icon-[tabler--x] size-4"></span>Clear
					</a>
				@endif
			</form>
			@can('create-teams')
				<a href="{{ route('teams.create') }}" class="btn btn-sm btn-primary rounded-sm">
					<span class="icon-[tabler--plus] size-5"></span>
					Create Group
				</a>
			@endcan
		</div>
		<div class="overflow-x-auto">
			<table class="table" id="main-table">
				<thead>
					<tr>
						<th>#</th>
						<th>Actions</th>
						<th>Group Name</th>
						<th>Description</th>
						<th>Owner</th>
						<th>Members</th>
						<th>Your Role</th>
					</tr>
				</thead>
				<tbody>
					@forelse($teams as $team)
						@php
							$myPivot = $team->members->firstWhere('id', auth()->id());
							$myRole = $myPivot?->pivot->role ?? '-';
						@endphp
						<tr class="row-hover">
							<td>{{ $teams->firstItem() + $loop->index }}</td>
							<td class="flex items-center gap-1">
								<a href="{{ route('teams.show', $team->id) }}" class="btn btn-circle btn-text btn-sm hover:text-info"
									aria-label="Detail">
									<span class="icon-[tabler--eye] size-5"></span>
								</a>
								@can('update', $team)
									<a href="{{ route('teams.edit', $team->id) }}" class="btn btn-circle btn-text btn-sm hover:text-warning"
										aria-label="Edit">
										<span class="icon-[tabler--pencil] size-5"></span>
									</a>
								@endcan
								@include('teams.delete.button-icon', ['team' => $team])
							</td>
							<td class="font-medium">{{ $team->name }}</td>
							<td class="text-base-content/70 text-sm max-w-xs truncate">{{ $team->description ?? '-' }}</td>
							<td>
								<div class="flex items-center gap-2">
									<div
										class="bg-primary text-primary-content rounded-full w-7 h-7 flex items-center justify-center text-xs font-semibold shrink-0">
										{{ strtoupper(substr($team->owner->name ?? 'U', 0, 1)) }}
									</div>
									<span class="text-sm">{{ $team->owner->name ?? '-' }}</span>
								</div>
							</td>
							<td>
								<span class="badge badge-soft badge-neutral text-xs">
									<span class="icon-[tabler--users] size-3 me-1"></span>
									{{ $team->members->count() }} Person
								</span>
							</td>
							<td>
								@if ($myRole === 'owner')
									<span class="badge badge-soft badge-warning text-xs">Owner</span>
								@elseif($myRole === 'member')
									<span class="badge badge-soft badge-info text-xs">Member</span>
								@else
									<span class="badge badge-soft badge-ghost text-xs">-</span>
								@endif
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="7" class="text-center text-base-content/50 py-8">
								No groups found.
								@can('create-teams')
									<a href="{{ route('teams.create') }}" class="link link-primary">Create a new group</a>
								@endcan
							</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
	@include('teams.delete.modal')
	<div class="mt-4">
		{{ $teams->links('template.pagination') }}
	</div>
@endsection
@include('teams.delete.script')
