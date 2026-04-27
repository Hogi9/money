@extends('template.master')
@section('content')
	<div class="pb-4">
		<h1 class="text-2xl font-semibold tracking-tight">Transaksi</h1>
		<p class="text-base-content/70 mt-1">Catat dan kelola semua transaksi keuangan Anda.</p>
	</div>

	{{-- Summary Cards --}}
	@php
		$totalIncome = $summary->total_income ?? 0;
		$totalOutcome = $summary->total_outcome ?? 0;
		$totalTransferFee = $summary->total_transfer_fee ?? 0;
		$totalOut = $totalOutcome + $totalTransferFee;
		$net = $totalIncome - $totalOut;
	@endphp
	<div class="mb-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
		<div class="border-base-content/25 rounded-lg border bg-base-100 p-4 shadow-sm">
			<div class="flex items-center gap-2 mb-1">
				<span class="icon-[tabler--trending-up] size-5 text-success"></span>
				<p class="text-sm text-base-content/60">
					Balance In
					@if ($isFiltered)
						<span class="badge badge-soft badge-xs badge-warning ml-1">filtered</span>
					@endif
				</p>
			</div>
			<p class="text-xl font-bold text-success">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
		</div>
		<div class="border-base-content/25 rounded-lg border bg-base-100 p-4 shadow-sm">
			<div class="flex items-center gap-2 mb-1">
				<span class="icon-[tabler--trending-down] size-5 text-error"></span>
				<p class="text-sm text-base-content/60">
					Balance Out
					@if ($isFiltered)
						<span class="badge badge-soft badge-xs badge-warning ml-1">filtered</span>
					@endif
				</p>
			</div>
			<p class="text-xl font-bold text-error">Rp {{ number_format($totalOut, 0, ',', '.') }}</p>
			{{-- @if ($totalTransferFee > 0)
				<p class="text-xs text-base-content/50 mt-0.5">termasuk fee Rp {{ number_format($totalTransferFee, 0, ',', '.') }}</p>
			@endif --}}
		</div>
		<div class="border-base-content/25 rounded-lg border bg-base-100 p-4 shadow-sm">
			<div class="flex items-center gap-2 mb-1">
				<span class="icon-[tabler--wallet] size-5 text-primary"></span>
				<p class="text-sm text-base-content/60">
					Net Balance
					@if ($isFiltered)
						<span class="badge badge-soft badge-xs badge-warning ml-1">filtered</span>
					@endif
				</p>
			</div>
			<p class="text-xl font-bold {{ $net >= 0 ? 'text-primary' : 'text-error' }}">
				Rp {{ number_format($net, 0, ',', '.') }}
			</p>
		</div>
	</div>

	<div class="border-base-content/25 w-full rounded-lg border bg-base-100 p-4 shadow-sm">
		<div class="mb-4 grid grid-cols-1 gap-3">
			<form method="GET" action="{{ route('transactions.index') }}"
				class="grid grid-cols-2 gap-2 lg:flex lg:items-center lg:flex-wrap">

				<select name="type" class="select select-sm rounded-sm w-full lg:w-40">
					<option value="">Semua Tipe</option>
					<option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Balance In</option>
					<option value="outcome" {{ request('type') === 'outcome' ? 'selected' : '' }}>Balance Out</option>
					<option value="transfer" {{ request('type') === 'transfer' ? 'selected' : '' }}>Moving Balance</option>
				</select>

				<select name="wallet_id" class="select select-sm rounded-sm w-full lg:w-44">
					<option value="">Semua Dompet</option>
					@foreach ($wallets as $wallet)
						<option value="{{ $wallet->id }}" {{ request('wallet_id') == $wallet->id ? 'selected' : '' }}>
							{{ $wallet->name }}
						</option>
					@endforeach
				</select>

				<select name="category_id" class="select select-sm rounded-sm w-full lg:w-44">
					<option value="">Semua Kategori</option>
					@foreach ($categories as $cat)
						<option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
							{{ $cat->name }}
						</option>
					@endforeach
				</select>

				<div class="input input-sm flex items-center space-x-2 rounded-sm w-full lg:max-w-[180px]">
					<span class="icon-[tabler--calendar] text-base-content/80 size-5 shrink-0"></span>
					<input type="date" name="date_from" class="grow bg-transparent outline-none text-sm"
						value="{{ request('date_from') }}" placeholder="Dari tanggal" />
				</div>

				<div class="input input-sm flex items-center space-x-2 rounded-sm w-full lg:max-w-[180px]">
					<span class="icon-[tabler--calendar] text-base-content/80 size-5 shrink-0"></span>
					<input type="date" name="date_to" class="grow bg-transparent outline-none text-sm"
						value="{{ request('date_to') }}" placeholder="Sampai tanggal" />
				</div>

				<div class="input input-sm flex items-center space-x-2 rounded-sm w-full lg:max-w-[200px]">
					<span class="icon-[tabler--search] text-base-content/80 size-5 shrink-0"></span>
					<input type="search" name="search" class="grow bg-transparent outline-none" placeholder="Cari deskripsi..."
						value="{{ request('search') }}" />
				</div>

				<div class="flex items-center gap-2 col-span-2 lg:col-span-1">
					<button type="submit" class="btn btn-sm btn-outline btn-primary rounded-sm">Filter</button>
					@if (request()->hasAny(['type', 'wallet_id', 'category_id', 'date_from', 'date_to', 'search']))
						<a href="{{ route('transactions.index') }}"
							class="btn btn-sm btn-text flex items-center gap-1 p-0 h-auto min-h-0 font-normal normal-case text-base-content/60 hover:text-primary hover:bg-transparent shadow-none border-none">
							<span class="icon-[tabler--x] size-4"></span>Clear
						</a>
					@endif
				</div>

				@can('create-transactions')
					<div class="col-span-2 lg:col-span-1 lg:ml-auto">
						<a href="{{ route('transactions.create') }}"
							class="btn btn-sm btn-primary rounded-sm w-full lg:w-auto justify-center">
							<span class="icon-[tabler--plus] size-5"></span>
							Tambah Transaksi
						</a>
					</div>
				@endcan
			</form>
		</div>

		<div class="overflow-x-auto">
			<table class="table" id="main-table">
				<thead>
					<tr>
						<th>#</th>
						<th>Actions</th>
						<th>Tanggal</th>
						<th>Tipe</th>
						<th>Jumlah</th>
						<th>Dompet</th>
						<th>Kategori / Nama</th>
						<th>Deskripsi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($transactions as $trx)
						<tr class="row-hover">
							<td>{{ $transactions->firstItem() + $loop->index }}</td>
							<td>
								@can('edit-transactions')
									<a href="{{ route('transactions.edit', $trx) }}" class="btn btn-circle btn-text btn-sm hover:text-warning"
										aria-label="Edit">
										<span class="icon-[tabler--pencil] size-5"></span>
									</a>
								@endcan
								@can('delete-transactions')
									<button type="button" class="delete-btn btn btn-circle btn-text btn-sm hover:text-error" aria-haspopup="dialog"
										aria-expanded="false" aria-controls="modal-delete" data-overlay="#modal-delete"
										data-action="{{ route('transactions.destroy', $trx) }}">
										<span class="icon-[tabler--trash] size-5"></span>
									</button>
								@endcan
							</td>
							<td class="whitespace-nowrap">{{ $trx->date->format('d M Y') }}</td>
							<td>
								@if ($trx->type === 'income')
									<span class="badge badge-soft badge-success text-xs flex items-center gap-1">
										<span class="icon-[tabler--trending-up] size-3"></span>Balance In
									</span>
								@elseif ($trx->type === 'outcome')
									<span class="badge badge-soft badge-error text-xs flex items-center gap-1">
										<span class="icon-[tabler--trending-down] size-3"></span>Balance Out
									</span>
								@else
									<span class="badge badge-soft badge-warning text-xs flex items-center gap-1">
										<span class="icon-[tabler--transfer] size-3"></span>Moving
									</span>
								@endif
							</td>
							<td class="font-semibold whitespace-nowrap">
								<span
									class="{{ $trx->type === 'income' ? 'text-success' : ($trx->type === 'outcome' ? 'text-error' : 'text-warning') }}">
									{{ $trx->type === 'income' ? '+' : ($trx->type === 'outcome' ? '-' : '↔') }}
									Rp {{ number_format($trx->amount, 0, ',', '.') }}
								</span>
								@if ($trx->type === 'transfer' && $trx->transfer_fee > 0)
									<br>
									<span class="text-xs text-base-content/50">
										fee: Rp {{ number_format($trx->transfer_fee, 0, ',', '.') }}
										({{ $trx->fee_bearer === 'sender' ? 'pengirim' : 'penerima' }})
									</span>
								@endif
							</td>
							<td>
								<span class="text-sm">{{ $trx->wallet->name ?? '-' }}</span>
								@if ($trx->type === 'transfer' && $trx->toWallet)
									<span class="text-base-content/40 mx-1">→</span>
									<span class="text-sm">{{ $trx->toWallet->name }}</span>
								@endif
							</td>
							<td>
								<span class="text-sm text-base-content/80">{{ $trx->category->name ?? '-' }}</span>
								@if ($trx->transactionName)
									<br><span class="text-xs text-base-content/50">{{ $trx->transactionName->name }}</span>
								@endif
							</td>
							<td class="text-base-content/70 max-w-xs truncate">{{ $trx->description ?? '-' }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="8" class="text-center text-base-content/50 py-8">Belum ada transaksi.</td>
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
				<div class="modal-body">Yakin ingin menghapus transaksi ini? Saldo dompet akan dikembalikan secara otomatis.</div>
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
		{{ $transactions->links('template.pagination') }}
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
