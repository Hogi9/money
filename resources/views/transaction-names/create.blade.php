@extends('template.master')
@section('content')
	<div class="pb-4">
		<h1 class="text-2xl font-semibold tracking-tight">Tambah Nama Transaksi</h1>
		<p class="text-base-content/70 mt-1">Buat nama transaksi baru dan hubungkan ke kategori.</p>
	</div>
	<div class="border-base-content/25 w-full rounded-lg border bg-base-100 p-6 shadow-sm max-w-2xl">
		<form action="{{ route('transaction-names.store') }}" method="POST">
			@csrf
			<div class="grid grid-cols-1 gap-4 mb-4">
				<div>
					<label class="label mb-1 text-sm font-medium" for="name">Nama Transaksi</label>
					<input type="text" id="name" name="name" value="{{ old('name') }}"
						class="input input-bordered w-full rounded-sm @error('name') input-error @enderror"
						placeholder="Contoh: Gaji Bulanan, Makan Siang, Bensin..." />
					@error('name')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>
				<div>
					<label class="label mb-1 text-sm font-medium" for="category_id">Kategori</label>
					<select id="category_id" name="category_id"
						class="select select-bordered w-full rounded-sm @error('category_id') select-error @enderror">
						<option value="">-- Pilih Kategori --</option>
						@foreach ($categories->groupBy('type') as $type => $cats)
							<optgroup label="{{ $type === 'income' ? '📈 Income' : '📉 Outcome' }}">
								@foreach ($cats as $cat)
									<option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
										{{ $cat->name }}
									</option>
								@endforeach
							</optgroup>
						@endforeach
					</select>
					@error('category_id')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>
			</div>
			<div class="flex items-center gap-3 justify-between">
				<a href="{{ route('transaction-names.index') }}" class="btn btn-ghost rounded-sm">Batal</a>
				<button type="submit" class="btn btn-primary rounded-sm">
					<span class="icon-[tabler--device-floppy] size-5"></span>
					Simpan
				</button>
			</div>
		</form>
	</div>
@endsection
