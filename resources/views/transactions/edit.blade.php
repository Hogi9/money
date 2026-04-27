@extends('template.master')
@section('content')
	<div class="pb-4">
		<h1 class="text-2xl font-semibold tracking-tight">Edit Transaksi</h1>
		<p class="text-base-content/70 mt-1">Perbarui data transaksi. Saldo dompet akan disesuaikan secara otomatis.</p>
	</div>
	<div class="border-base-content/25 w-full rounded-lg border bg-base-100 p-6 shadow-sm max-w-3xl">
		<form action="{{ route('transactions.update', $transaction) }}" method="POST" id="transaction-form">
			@csrf
			@method('PUT')
			<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
				{{-- Tipe Transaksi --}}
				<div class="md:col-span-2">
					<label class="label mb-1 text-sm font-medium">Tipe Transaksi</label>
					<div class="flex gap-2 flex-wrap">
						<label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-sm border border-base-content/20 has-[:checked]:border-success has-[:checked]:bg-success/10 transition-colors">
							<input type="radio" name="type" value="income" class="radio radio-success radio-sm"
								{{ old('type', $transaction->type) === 'income' ? 'checked' : '' }} />
							<span class="icon-[tabler--trending-up] size-4 text-success"></span>
							<span class="text-sm font-medium">Balance In (Pemasukan)</span>
						</label>
						<label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-sm border border-base-content/20 has-[:checked]:border-error has-[:checked]:bg-error/10 transition-colors">
							<input type="radio" name="type" value="outcome" class="radio radio-error radio-sm"
								{{ old('type', $transaction->type) === 'outcome' ? 'checked' : '' }} />
							<span class="icon-[tabler--trending-down] size-4 text-error"></span>
							<span class="text-sm font-medium">Balance Out (Pengeluaran)</span>
						</label>
						<label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-sm border border-base-content/20 has-[:checked]:border-warning has-[:checked]:bg-warning/10 transition-colors">
							<input type="radio" name="type" value="transfer" class="radio radio-warning radio-sm"
								{{ old('type', $transaction->type) === 'transfer' ? 'checked' : '' }} />
							<span class="icon-[tabler--transfer] size-4 text-warning"></span>
							<span class="text-sm font-medium">Moving Balance (Transfer)</span>
						</label>
					</div>
					@error('type')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>

				{{-- Tanggal --}}
				<div>
					<label class="label mb-1 text-sm font-medium" for="date">Tanggal</label>
					<input type="date" id="date" name="date"
						value="{{ old('date', $transaction->date->format('Y-m-d')) }}"
						class="input input-bordered w-full rounded-sm @error('date') input-error @enderror" />
					@error('date')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>

				{{-- Jumlah --}}
				<div>
					<label class="label mb-1 text-sm font-medium" for="amount">Jumlah</label>
					<div class="input input-bordered rounded-sm flex items-center gap-2 @error('amount') input-error @enderror">
						<span class="text-base-content/50 text-sm font-medium shrink-0">Rp</span>
						<input type="number" id="amount" name="amount" value="{{ old('amount', $transaction->amount) }}"
							class="grow bg-transparent outline-none" min="1" step="1" placeholder="0" />
					</div>
					@error('amount')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>

				{{-- Dompet Asal --}}
				<div>
					<label class="label mb-1 text-sm font-medium" for="wallet_id">Dompet</label>
					<select id="wallet_id" name="wallet_id"
						class="select select-bordered w-full rounded-sm @error('wallet_id') select-error @enderror">
						<option value="">-- Pilih Dompet --</option>
						@foreach ($wallets as $wallet)
							<option value="{{ $wallet->id }}"
								{{ old('wallet_id', $transaction->wallet_id) == $wallet->id ? 'selected' : '' }}>
								{{ $wallet->name }} (Rp {{ number_format($wallet->balance, 0, ',', '.') }})
							</option>
						@endforeach
					</select>
					@error('wallet_id')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>

				{{-- Dompet Tujuan (transfer only) --}}
				<div id="to-wallet-wrapper" class="{{ old('type', $transaction->type) === 'transfer' ? '' : 'hidden' }}">
					<label class="label mb-1 text-sm font-medium" for="to_wallet_id">Dompet Tujuan</label>
					<select id="to_wallet_id" name="to_wallet_id"
						class="select select-bordered w-full rounded-sm @error('to_wallet_id') select-error @enderror">
						<option value="">-- Pilih Dompet Tujuan --</option>
						@foreach ($wallets as $wallet)
							<option value="{{ $wallet->id }}"
								{{ old('to_wallet_id', $transaction->to_wallet_id) == $wallet->id ? 'selected' : '' }}>
								{{ $wallet->name }} (Rp {{ number_format($wallet->balance, 0, ',', '.') }})
							</option>
						@endforeach
					</select>
					@error('to_wallet_id')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>

				{{-- Biaya Admin (transfer only) --}}
				<div id="fee-wrapper" class="{{ old('type', $transaction->type) === 'transfer' ? '' : 'hidden' }}">
					<label class="label mb-1 text-sm font-medium" for="transfer_fee">Biaya Admin <span class="text-base-content/40">(opsional)</span></label>
					<div class="input input-bordered rounded-sm flex items-center gap-2 @error('transfer_fee') input-error @enderror">
						<span class="text-base-content/50 text-sm font-medium shrink-0">Rp</span>
						<input type="number" id="transfer_fee" name="transfer_fee"
							value="{{ old('transfer_fee', $transaction->transfer_fee) }}"
							class="grow bg-transparent outline-none" min="0" step="1" placeholder="0" />
					</div>
					@error('transfer_fee')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>

				{{-- Penanggung Biaya Admin (transfer only) --}}
				<div id="fee-bearer-wrapper" class="{{ old('type', $transaction->type) === 'transfer' ? '' : 'hidden' }}">
					<label class="label mb-1 text-sm font-medium">Dibebankan ke</label>
					<div class="flex gap-2">
						<label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-sm border border-base-content/20 has-[:checked]:border-warning has-[:checked]:bg-warning/10 transition-colors">
							<input type="radio" name="fee_bearer" value="sender" class="radio radio-warning radio-sm"
								{{ old('fee_bearer', $transaction->fee_bearer ?? 'sender') === 'sender' ? 'checked' : '' }} />
							<span class="icon-[tabler--arrow-up-right] size-4 text-warning"></span>
							<span class="text-sm font-medium">Pengirim</span>
						</label>
						<label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-sm border border-base-content/20 has-[:checked]:border-info has-[:checked]:bg-info/10 transition-colors">
							<input type="radio" name="fee_bearer" value="receiver" class="radio radio-info radio-sm"
								{{ old('fee_bearer', $transaction->fee_bearer) === 'receiver' ? 'checked' : '' }} />
							<span class="icon-[tabler--arrow-down-left] size-4 text-info"></span>
							<span class="text-sm font-medium">Penerima</span>
						</label>
					</div>
					@error('fee_bearer')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>

				{{-- Kategori (non-transfer) --}}
				<div id="category-wrapper" class="{{ old('type', $transaction->type) === 'transfer' ? 'hidden' : '' }}">
					<label class="label mb-1 text-sm font-medium" for="category_id">Kategori <span class="text-base-content/40">(opsional)</span></label>
					<select id="category_id" name="category_id"
						class="select select-bordered w-full rounded-sm @error('category_id') select-error @enderror">
						<option value="">-- Pilih Kategori --</option>
						@foreach ($categories as $cat)
							<option value="{{ $cat->id }}" data-type="{{ $cat->type }}"
								{{ old('category_id', $transaction->category_id) == $cat->id ? 'selected' : '' }}>
								{{ $cat->name }}
							</option>
						@endforeach
					</select>
					@error('category_id')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>

				{{-- Nama Transaksi (non-transfer) --}}
				<div id="txname-wrapper" class="{{ old('type', $transaction->type) === 'transfer' ? 'hidden' : '' }}">
					<label class="label mb-1 text-sm font-medium" for="transaction_name_id">Nama Transaksi <span class="text-base-content/40">(opsional)</span></label>
					<select id="transaction_name_id" name="transaction_name_id"
						class="select select-bordered w-full rounded-sm @error('transaction_name_id') select-error @enderror">
						<option value="">-- Pilih Nama --</option>
						@foreach ($transactionNames as $tn)
							<option value="{{ $tn->id }}" data-type="{{ $tn->category?->type }}"
								data-category-id="{{ $tn->category_id }}"
								{{ old('transaction_name_id', $transaction->transaction_name_id) == $tn->id ? 'selected' : '' }}>
								{{ $tn->name }} ({{ $tn->category?->name }})
							</option>
						@endforeach
					</select>
					@error('transaction_name_id')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>

				{{-- Deskripsi --}}
				<div class="md:col-span-2">
					<label class="label mb-1 text-sm font-medium" for="description">Deskripsi <span class="text-base-content/40">(opsional)</span></label>
					<textarea id="description" name="description" rows="2"
						class="textarea textarea-bordered w-full rounded-sm @error('description') textarea-error @enderror"
						placeholder="Catatan tambahan...">{{ old('description', $transaction->description) }}</textarea>
					@error('description')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>
			</div>

			<div class="flex items-center gap-3 justify-between">
				<a href="{{ route('transactions.index') }}" class="btn btn-ghost rounded-sm">Batal</a>
				<button type="submit" class="btn btn-primary rounded-sm">
					<span class="icon-[tabler--device-floppy] size-5"></span>
					Simpan
				</button>
			</div>
		</form>
	</div>
@endsection
@push('scripts')
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const typeRadios = document.querySelectorAll('input[name="type"]');
			const toWalletWrapper = document.getElementById('to-wallet-wrapper');
			const feeWrapper = document.getElementById('fee-wrapper');
			const feeBearerWrapper = document.getElementById('fee-bearer-wrapper');
			const categoryWrapper = document.getElementById('category-wrapper');
			const txnameWrapper = document.getElementById('txname-wrapper');
			const categorySelect = document.getElementById('category_id');
			const txnameSelect = document.getElementById('transaction_name_id');

			function updateFormForType(type) {
				const isTransfer = type === 'transfer';
				toWalletWrapper.classList.toggle('hidden', !isTransfer);
				feeWrapper.classList.toggle('hidden', !isTransfer);
				feeBearerWrapper.classList.toggle('hidden', !isTransfer);
				categoryWrapper.classList.toggle('hidden', isTransfer);
				txnameWrapper.classList.toggle('hidden', isTransfer);

				Array.from(categorySelect.options).forEach(opt => {
					if (!opt.value) return;
					opt.hidden = isTransfer ? true : (opt.dataset.type !== type);
				});

				Array.from(txnameSelect.options).forEach(opt => {
					if (!opt.value) return;
					opt.hidden = isTransfer ? true : (opt.dataset.type !== type);
				});

				if (categorySelect.selectedOptions[0]?.hidden) categorySelect.value = '';
				if (txnameSelect.selectedOptions[0]?.hidden) txnameSelect.value = '';
			}

			typeRadios.forEach(radio => {
				radio.addEventListener('change', () => updateFormForType(radio.value));
			});

			// Auto-select category when transaction name is picked
			txnameSelect.addEventListener('change', function() {
				const categoryId = this.options[this.selectedIndex]?.dataset?.categoryId;
				if (categoryId) categorySelect.value = categoryId;
			});

			const checked = document.querySelector('input[name="type"]:checked');
			if (checked) updateFormForType(checked.value);
		});
	</script>
@endpush
