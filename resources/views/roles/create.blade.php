@extends('template.master')
@section('content')
	<div class="pb-4">
		<h1 class="text-2xl font-semibold tracking-tight">Add Role</h1>
		<p class="text-base-content/70 mt-1">Create a new role and define the permissions it has.</p>
	</div>
	<div class="border-base-content/25 w-full rounded-lg border bg-base-100 p-6 shadow-sm">
		<form action="{{ route('roles.store') }}" method="POST">
			@csrf
			<div class="mb-4">
				<label class="label mb-1 text-sm font-medium" for="name">Role Name</label>
				<input type="text" id="name" name="name" value="{{ old('name') }}"
					class="input input-bordered w-full rounded-sm @error('name') input-error @enderror"
					placeholder="Example: admin, editor, viewer" />
				@error('name')
					<p class="text-error text-xs mt-1">{{ $message }}</p>
				@enderror
			</div>

			@php
				$actions = ['view', 'create', 'edit', 'delete'];
				$grouped = [];
				foreach ($permissions as $perm) {
					$matched = false;
					foreach ($actions as $action) {
						if (str_starts_with($perm->name, $action . '-')) {
							$resource = substr($perm->name, strlen($action) + 1);
							$grouped[$resource][$action] = $perm->name;
							$matched = true;
							break;
						}
					}
					if (!$matched) {
						$grouped[$perm->name]['other'] = $perm->name;
					}
				}
				uksort($grouped, fn($a, $b) => $a === 'dashboard' ? -1 : ($b === 'dashboard' ? 1 : strcmp($a, $b)));
				$selectedPerms = old('permissions', []);
			@endphp

			<div class="mb-6">
				<div class="flex flex-wrap items-center justify-between gap-2 mb-3">
					<label class="label text-sm font-medium">Permissions</label>
					<div class="flex items-center gap-2">
						<span class="badge badge-primary badge-outline text-xs font-mono" id="perm-count">0 selected</span>
						<button type="button" id="check-all" class="btn btn-xs btn-outline rounded-sm">Select All</button>
						<button type="button" id="uncheck-all" class="btn btn-xs btn-outline rounded-sm">Clear All</button>
					</div>
				</div>

				<div class="mb-2">
					<label class="input input-bordered input-sm flex items-center gap-2 rounded-sm">
						<span class="icon-[tabler--search] text-base-content/40 size-4 shrink-0"></span>
						<input type="text" id="perm-search" placeholder="Search module..." class="grow" />
					</label>
				</div>

				@forelse($grouped as $resource => $actionMap)
					<div class="perm-row" data-resource="{{ $resource }}"></div>
				@empty
				@endforelse

				<div class="border border-base-content/20 rounded-sm overflow-hidden">
					<div class="overflow-x-auto max-h-96 overflow-y-auto">
						<table class="table table-sm w-full">
							<thead class="sticky top-0 bg-base-200 z-10">
								<tr>
									<th class="text-left font-semibold text-xs uppercase tracking-wide w-40">Module</th>
									@foreach($actions as $action)
										<th class="text-center font-semibold text-xs uppercase tracking-wide capitalize">{{ $action }}</th>
									@endforeach
									<th class="text-center font-semibold text-xs uppercase tracking-wide w-16">All</th>
								</tr>
							</thead>
							<tbody id="perm-table-body">
								@forelse($grouped as $resource => $actionMap)
									<tr class="perm-row hover:bg-base-200/40 transition-colors" data-resource="{{ strtolower(str_replace('-', ' ', $resource)) }}">
										<td class="font-medium text-sm capitalize">
											{{ str_replace('-', ' ', $resource) }}
										</td>
										@foreach($actions as $action)
											<td class="text-center">
												@if(isset($actionMap[$action]))
													<input type="checkbox"
														name="permissions[]"
														value="{{ $actionMap[$action] }}"
														class="checkbox checkbox-sm checkbox-primary perm-check row-check"
														data-row="{{ $resource }}"
														{{ in_array($actionMap[$action], $selectedPerms) ? 'checked' : '' }} />
												@else
													<span class="text-base-content/20 text-sm">—</span>
												@endif
											</td>
										@endforeach
										<td class="text-center">
											@if(count($actionMap) > 1)
												<input type="checkbox"
													class="checkbox checkbox-sm checkbox-secondary row-all-check"
													data-row="{{ $resource }}"
													title="Select all for {{ str_replace('-', ' ', $resource) }}" />
											@endif
										</td>
									</tr>
								@empty
									<tr>
										<td colspan="6" class="text-center text-base-content/50 py-6 text-sm">
											No permissions found.
											<a href="{{ route('permissions.create') }}" class="link link-primary">Add a permission</a> first.
										</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
				@error('permissions')
					<p class="text-error text-xs mt-1">{{ $message }}</p>
				@enderror
			</div>

			<div class="flex items-center gap-3 justify-between">
				<a href="{{ route('roles.index') }}" class="btn btn-ghost rounded-sm">Cancel</a>
				<button type="submit" class="btn btn-primary rounded-sm">
					<span class="icon-[tabler--device-floppy] size-5"></span>
					Save
				</button>
			</div>
		</form>
	</div>
@endsection
@push('scripts')
	<script>
		const permChecks = () => document.querySelectorAll('.perm-check');
		const rowChecks = (row) => document.querySelectorAll(`.row-check[data-row="${row}"]`);

		function updateCount() {
			const count = document.querySelectorAll('.perm-check:checked').length;
			document.getElementById('perm-count').textContent = count + ' selected';
		}

		function syncRowAllCheck(row) {
			const checks = rowChecks(row);
			const allCheck = document.querySelector(`.row-all-check[data-row="${row}"]`);
			if (!allCheck) return;
			const allChecked = Array.from(checks).every(c => c.checked);
			const someChecked = Array.from(checks).some(c => c.checked);
			allCheck.checked = allChecked;
			allCheck.indeterminate = someChecked && !allChecked;
		}

		function syncAllRowChecks() {
			document.querySelectorAll('.row-all-check').forEach(c => syncRowAllCheck(c.dataset.row));
		}

		document.getElementById('check-all').addEventListener('click', () => {
			permChecks().forEach(c => c.checked = true);
			syncAllRowChecks();
			updateCount();
		});

		document.getElementById('uncheck-all').addEventListener('click', () => {
			permChecks().forEach(c => c.checked = false);
			document.querySelectorAll('.row-all-check').forEach(c => { c.checked = false; c.indeterminate = false; });
			updateCount();
		});

		document.querySelectorAll('.row-all-check').forEach(allCheck => {
			allCheck.addEventListener('change', () => {
				rowChecks(allCheck.dataset.row).forEach(c => c.checked = allCheck.checked);
				allCheck.indeterminate = false;
				updateCount();
			});
		});

		document.querySelectorAll('.row-check').forEach(check => {
			check.addEventListener('change', () => {
				syncRowAllCheck(check.dataset.row);
				updateCount();
			});
		});

		document.getElementById('perm-search').addEventListener('input', function() {
			const query = this.value.toLowerCase().trim();
			document.querySelectorAll('#perm-table-body .perm-row').forEach(row => {
				row.style.display = row.dataset.resource.includes(query) ? '' : 'none';
			});
		});

		syncAllRowChecks();
		updateCount();
	</script>
@endpush
