@extends('template.master')
@section('content')
	<div class="pb-4">
		<h1 class="text-2xl font-semibold tracking-tight">Kategori</h1>
		<p class="text-base-content/70 mt-1">Kelola master kategori pemasukan dan pengeluaran.</p>
	</div>
	<div class="border-base-content/25 w-full rounded-lg border bg-base-100 p-4 shadow-sm">
		<div class="mb-4 grid grid-cols-1 gap-3 lg:flex lg:items-center lg:justify-between">
			<form method="GET" action="{{ route('categories.index') }}"
				class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:flex lg:items-center lg:flex-1">

				<select name="type" class="select select-sm rounded-sm w-full lg:w-40">
					<option value="">Semua Tipe</option>
					<option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Income</option>
					<option value="outcome" {{ request('type') === 'outcome' ? 'selected' : '' }}>Outcome</option>
				</select>

				<div class="input input-sm flex items-center space-x-2 rounded-sm w-full lg:max-w-[220px]">
					<span class="icon-[tabler--search] text-base-content/80 size-5 shrink-0"></span>
					<input type="search" name="search" class="grow bg-transparent outline-none" placeholder="Cari kategori..."
						value="{{ request('search') }}" />
				</div>

				<div class="flex items-center gap-2">
					<button type="submit" class="btn btn-sm btn-outline btn-primary rounded-sm w-[80vw] sm:w-full lg:w-auto">Filter</button>

					@if (request()->hasAny(['search', 'type']))
						<a href="{{ route('categories.index') }}"
							class="btn btn-sm btn-text flex items-center gap-1 p-0 h-auto min-h-0 font-normal normal-case text-base-content/60 hover:text-primary hover:bg-transparent shadow-none border-none">
							<span class="icon-[tabler--x] size-4"></span>Clear
						</a>
					@endif
				</div>
			</form>

			@can('create-categories')
				<div class="w-full lg:w-auto">
					<a href="{{ route('categories.create') }}" class="btn btn-sm btn-primary rounded-sm w-full lg:w-auto justify-center">
						<span class="icon-[tabler--plus] size-5"></span>
						Tambah Kategori
					</a>
				</div>
			@endcan
		</div>

		<div class="overflow-x-auto">
			<table class="table" id="main-table">
				<thead>
					<tr>
						<th>#</th>
						<th>Nama</th>
						<th>Tipe</th>
						<th>Deskripsi</th>
						<th>Jml. Nama Transaksi</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					@forelse($categories as $category)
						<tr class="row-hover">
							<td>{{ $categories->firstItem() + $loop->index }}</td>
							<td class="font-medium">{{ $category->name }}</td>
							<td>
								@if ($category->type === 'income')
									<span class="badge badge-soft badge-success text-xs">Income</span>
								@else
									<span class="badge badge-soft badge-error text-xs">Outcome</span>
								@endif
							</td>
							<td class="text-base-content/70">{{ $category->description ?? '-' }}</td>
							<td>{{ $category->transaction_names_count ?? $category->transactionNames()->count() }}</td>
							<td>
								@can('edit-categories')
									<a href="{{ route('categories.edit', $category) }}"
										class="btn btn-circle btn-text btn-sm hover:text-warning" aria-label="Edit">
										<span class="icon-[tabler--pencil] size-5"></span>
									</a>
								@endcan
								@can('delete-categories')
									<button type="button" class="delete-btn btn btn-circle btn-text btn-sm hover:text-error"
										aria-haspopup="dialog" aria-expanded="false" aria-controls="modal-delete"
										data-overlay="#modal-delete"
										data-action="{{ route('categories.destroy', $category) }}">
										<span class="icon-[tabler--trash] size-5"></span>
									</button>
								@endcan
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="6" class="text-center text-base-content/50 py-8">Belum ada kategori.</td>
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
					<h3 class="modal-title">Konfirmasi Hapus</h3>
					<button type="button" class="btn btn-text btn-circle btn-sm absolute end-3 top-3" aria-label="Close"
						data-overlay="#modal-delete">
						<span class="icon-[tabler--x] size-4"></span>
					</button>
				</div>
				<div class="modal-body">
					Yakin ingin menghapus kategori ini? Semua nama transaksi terkait juga akan dihapus.
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-soft btn-secondary" data-overlay="#modal-delete">Batal</button>
					<form id="delete-form" method="POST" class="inline">
						@csrf
						@method('DELETE')
						<button type="submit" class="btn btn-error">Hapus</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="mt-4">
		{{ $categories->links('template.pagination') }}
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
					if (deleteForm) deleteForm.setAttribute('action', action);
				});
			});
		});
	</script>
@endpush
