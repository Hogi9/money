@extends('template.master')
@section('content')
	<div class="pb-4">
		<h1 class="text-2xl font-semibold tracking-tight">Tambah Dompet</h1>
		<p class="text-base-content/70 mt-1">Buat dompet atau akun keuangan baru.</p>
	</div>
	<div class="border-base-content/25 w-full rounded-lg border bg-base-100 p-6 shadow-sm max-w-2xl">
		<form action="{{ route('wallets.store') }}" method="POST">
			@csrf
			<div class="grid grid-cols-1 gap-4 mb-4">
				<div>
					<label class="label mb-1 text-sm font-medium" for="name">Nama Dompet</label>
					<input type="text" id="name" name="name" value="{{ old('name') }}"
						class="input input-bordered w-full rounded-sm @error('name') input-error @enderror"
						placeholder="Contoh: Cash, BCA Tabungan, GoPay..." />
					@error('name')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>
				{{-- <div>
					<label class="label mb-1 text-sm font-medium" for="initial_balance">Saldo Awal <span class="text-base-content/40">(opsional)</span></label>
					<div class="input input-bordered rounded-sm flex items-center gap-2 @error('initial_balance') input-error @enderror">
						<span class="text-base-content/50 text-sm font-medium shrink-0">Rp</span>
						<input type="number" id="initial_balance" name="initial_balance" value="{{ old('initial_balance', 0) }}"
							class="grow bg-transparent outline-none" min="0" step="1000" />
					</div>
					@error('initial_balance')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div> --}}
				<div>
					<label class="label mb-1 text-sm font-medium" for="description">Deskripsi <span
							class="text-base-content/40">(opsional)</span></label>
					<textarea id="description" name="description" rows="3"
					 class="textarea textarea-bordered w-full rounded-sm @error('description') textarea-error @enderror"
					 placeholder="Keterangan singkat dompet...">{{ old('description') }}</textarea>
					@error('description')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>
			</div>
			<div class="flex items-center gap-3 justify-between">
				<a href="{{ route('wallets.index') }}" class="btn btn-ghost rounded-sm">Batal</a>
				<button type="submit" class="btn btn-primary rounded-sm">
					<span class="icon-[tabler--device-floppy] size-5"></span>
					Simpan
				</button>
			</div>
		</form>
	</div>
@endsection
