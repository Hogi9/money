@extends('template.master')
@section('content')
	<div class="pb-4">
		<h1 class="text-2xl font-semibold tracking-tight">Kelola Feedback</h1>
		<p class="text-base-content/70 mt-1">Daftar seluruh kritik dan saran dari pengguna.</p>
	</div>
	<div class="border-base-content/25 w-full rounded-lg border bg-base-100 p-4 shadow-sm">
		<div class="mb-4 grid grid-cols-1 gap-3 lg:flex lg:items-center lg:justify-between">
			<form method="GET" action="{{ route('feedbacks.index') }}"
				class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:flex lg:items-center lg:flex-1">

				<select name="type" class="select select-sm rounded-sm w-full lg:w-36">
					<option value="">Semua Jenis</option>
					<option value="kritik" {{ request('type') === 'kritik' ? 'selected' : '' }}>Kritik</option>
					<option value="saran" {{ request('type') === 'saran' ? 'selected' : '' }}>Saran</option>
				</select>

				<select name="status" class="select select-sm rounded-sm w-full lg:w-40">
					<option value="">Semua Status</option>
					<option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
					<option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Ditinjau</option>
					<option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Selesai</option>
				</select>

				<div class="input input-sm flex items-center space-x-2 rounded-sm w-full lg:max-w-[220px]">
					<span class="icon-[tabler--search] text-base-content/80 size-5 shrink-0"></span>
					<input type="search" name="search" class="grow bg-transparent outline-none" placeholder="Cari feedback..."
						value="{{ request('search') }}" />
				</div>

				<div class="flex items-center gap-2">
					<button type="submit" class="btn btn-sm btn-outline btn-primary rounded-sm w-[80vw] sm:w-full lg:w-auto">Filter</button>
					@if (request()->hasAny(['search', 'type', 'status']))
						<a href="{{ route('feedbacks.index') }}"
							class="btn btn-sm btn-text flex items-center gap-1 p-0 h-auto min-h-0 font-normal normal-case text-base-content/60 hover:text-primary hover:bg-transparent shadow-none border-none">
							<span class="icon-[tabler--x] size-4"></span>Clear
						</a>
					@endif
				</div>
			</form>
		</div>

		<div class="overflow-x-auto">
			<table class="table" id="main-table">
				<thead>
					<tr>
						<th>#</th>
						<th>Actions</th>
						<th>Pengirim</th>
						<th>Jenis</th>
						<th>Judul</th>
						<th>Status</th>
						<th>Tanggal</th>
					</tr>
				</thead>
				<tbody>
					@forelse($feedbacks as $feedback)
						<tr class="row-hover">
							<td>{{ $feedbacks->firstItem() + $loop->index }}</td>
							<td>
								<a href="{{ route('feedbacks.show', $feedback) }}"
									class="btn btn-circle btn-text btn-sm hover:text-info" aria-label="Detail">
									<span class="icon-[tabler--eye] size-5"></span>
								</a>
								@can('delete-feedbacks')
									<button type="button" class="delete-btn btn btn-circle btn-text btn-sm hover:text-error"
										aria-haspopup="dialog" aria-expanded="false" aria-controls="modal-delete"
										data-overlay="#modal-delete"
										data-action="{{ route('feedbacks.destroy', $feedback) }}">
										<span class="icon-[tabler--trash] size-5"></span>
									</button>
								@endcan
							</td>
							<td class="font-medium">{{ $feedback->user->name }}</td>
							<td>
								@if ($feedback->type === 'kritik')
									<span class="badge badge-soft badge-error text-xs">Kritik</span>
								@else
									<span class="badge badge-soft badge-info text-xs">Saran</span>
								@endif
							</td>
							<td>{{ $feedback->title }}</td>
							<td>
								@if ($feedback->status === 'pending')
									<span class="badge badge-soft badge-warning text-xs">Menunggu</span>
								@elseif ($feedback->status === 'reviewed')
									<span class="badge badge-soft badge-primary text-xs">Ditinjau</span>
								@else
									<span class="badge badge-soft badge-success text-xs">Selesai</span>
								@endif
							</td>
							<td class="text-base-content/70 text-sm">{{ $feedback->created_at->format('d M Y') }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="7" class="text-center text-base-content/50 py-8">Belum ada feedback masuk.</td>
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
					Yakin ingin menghapus feedback ini?
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
		{{ $feedbacks->links('template.pagination') }}
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
