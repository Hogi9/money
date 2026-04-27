@extends('template.master')
@section('content')
	<div class="pb-4">
		<h1 class="text-2xl font-semibold tracking-tight">Edit Dompet</h1>
		<p class="text-base-content/70 mt-1">Perbarui informasi dompet. Saldo dikelola otomatis melalui transaksi.</p>
	</div>
	<div class="border-base-content/25 w-full rounded-lg border bg-base-100 p-6 shadow-sm max-w-2xl">
		<div class="alert alert-soft alert-info mb-4">
			<span class="icon-[tabler--info-circle] size-5 shrink-0"></span>
			<span>Saldo saat ini: <strong>Rp {{ number_format($wallet->balance, 0, ',', '.') }}</strong>. Untuk mengubah saldo, gunakan menu Transaksi.</span>
		</div>
		<form action="{{ route('wallets.update', $wallet) }}" method="POST">
			@csrf
			@method('PUT')
			<div class="grid grid-cols-1 gap-4 mb-4">
				<div>
					<label class="label mb-1 text-sm font-medium" for="name">Nama Dompet</label>
					<input type="text" id="name" name="name" value="{{ old('name', $wallet->name) }}"
						class="input input-bordered w-full rounded-sm @error('name') input-error @enderror"
						placeholder="Nama dompet..." />
					@error('name')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>
				<div>
					<label class="label mb-1 text-sm font-medium" for="description">Deskripsi <span class="text-base-content/40">(opsional)</span></label>
					<textarea id="description" name="description" rows="3"
						class="textarea textarea-bordered w-full rounded-sm @error('description') textarea-error @enderror"
						placeholder="Keterangan singkat dompet...">{{ old('description', $wallet->description) }}</textarea>
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
