@extends('template.master')
@section('content')
	<div class="pb-4">
		<h1 class="text-2xl font-semibold tracking-tight">Dompet</h1>
		<p class="text-base-content/70 mt-1">Kelola dompet dan akun keuangan Anda.</p>
	</div>

	{{-- Total Balance Card --}}
	<div class="mb-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
		<div class="border-base-content/25 rounded-lg border bg-base-100 p-4 shadow-sm">
			<p class="text-sm text-base-content/60 mb-1">Total Saldo Semua Dompet</p>
			<p class="text-2xl font-bold text-primary">Rp {{ number_format($totalBalance, 0, ',', '.') }}</p>
		</div>
	</div>

	<div class="border-base-content/25 w-full rounded-lg border bg-base-100 p-4 shadow-sm">
		<div class="mb-4 grid grid-cols-1 gap-3 lg:flex lg:items-center lg:justify-between">
			<form method="GET" action="{{ route('wallets.index') }}"
				class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:flex lg:items-center lg:flex-1">
				<div class="input input-sm flex items-center space-x-2 rounded-sm w-full lg:max-w-[220px]">
					<span class="icon-[tabler--search] text-base-content/80 size-5 shrink-0"></span>
					<input type="search" name="search" class="grow bg-transparent outline-none" placeholder="Cari dompet..."
						value="{{ request('search') }}" />
				</div>
				<div class="flex items-center gap-2">
					<button type="submit" class="btn btn-sm btn-outline btn-primary rounded-sm w-[80vw] sm:w-full lg:w-auto">Filter</button>
					@if (request()->filled('search'))
						<a href="{{ route('wallets.index') }}"
							class="btn btn-sm btn-text flex items-center gap-1 p-0 h-auto min-h-0 font-normal normal-case text-base-content/60 hover:text-primary hover:bg-transparent shadow-none border-none">
							<span class="icon-[tabler--x] size-4"></span>Clear
						</a>
					@endif
				</div>
			</form>

			@can('create-wallets')
				<div class="w-full lg:w-auto">
					<a href="{{ route('wallets.create') }}" class="btn btn-sm btn-primary rounded-sm w-full lg:w-auto justify-center">
						<span class="icon-[tabler--plus] size-5"></span>
						Tambah Dompet
					</a>
				</div>
			@endcan
		</div>

		<div class="overflow-x-auto">
			<table class="table" id="main-table">
				<thead>
					<tr>
						<th>#</th>
						<th>Nama Dompet</th>
						<th>Saldo</th>
						<th>Deskripsi</th>
						<th>Dibuat</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					@forelse($wallets as $wallet)
						<tr class="row-hover">
							<td>{{ $wallets->firstItem() + $loop->index }}</td>
							<td>
								<div class="flex items-center gap-2">
									<span class="icon-[tabler--wallet] size-5 text-primary shrink-0"></span>
									<span class="font-medium">{{ $wallet->name }}</span>
								</div>
							</td>
							<td>
								<span class="{{ $wallet->balance >= 0 ? 'text-success' : 'text-error' }} font-semibold">
									Rp {{ number_format($wallet->balance, 0, ',', '.') }}
								</span>
							</td>
							<td class="text-base-content/70">{{ $wallet->description ?? '-' }}</td>
							<td>{{ $wallet->created_at->format('d M Y') }}</td>
							<td>
								@can('edit-wallets')
									<a href="{{ route('wallets.edit', $wallet) }}"
										class="btn btn-circle btn-text btn-sm hover:text-warning" aria-label="Edit">
										<span class="icon-[tabler--pencil] size-5"></span>
									</a>
								@endcan
								@can('delete-wallets')
									<button type="button" class="delete-btn btn btn-circle btn-text btn-sm hover:text-error"
										aria-haspopup="dialog" aria-expanded="false" aria-controls="modal-delete"
										data-overlay="#modal-delete"
										data-action="{{ route('wallets.destroy', $wallet) }}">
										<span class="icon-[tabler--trash] size-5"></span>
									</button>
								@endcan
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="6" class="text-center text-base-content/50 py-8">Belum ada dompet.</td>
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
				<div class="modal-body">Yakin ingin menghapus dompet ini? Dompet yang memiliki transaksi tidak dapat dihapus.</div>
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
		{{ $wallets->links('template.pagination') }}
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
