@extends('template.master')
@section('content')
	<div class="pb-4">
		<h1 class="text-2xl font-semibold tracking-tight">Edit Kategori</h1>
		<p class="text-base-content/70 mt-1">Perbarui informasi kategori.</p>
	</div>
	<div class="border-base-content/25 w-full rounded-lg border bg-base-100 p-6 shadow-sm max-w-2xl">
		<form action="{{ route('categories.update', $category) }}" method="POST">
			@csrf
			@method('PUT')
			<div class="grid grid-cols-1 gap-4 mb-4">
				<div>
					<label class="label mb-1 text-sm font-medium" for="name">Nama Kategori</label>
					<input type="text" id="name" name="name" value="{{ old('name', $category->name) }}"
						class="input input-bordered w-full rounded-sm @error('name') input-error @enderror"
						placeholder="Nama kategori..." />
					@error('name')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>
				<div>
					<label class="label mb-1 text-sm font-medium" for="type">Tipe</label>
					<select id="type" name="type"
						class="select select-bordered w-full rounded-sm @error('type') select-error @enderror">
						<option value="income" {{ old('type', $category->type) === 'income' ? 'selected' : '' }}>Income (Pemasukan)</option>
						<option value="outcome" {{ old('type', $category->type) === 'outcome' ? 'selected' : '' }}>Outcome (Pengeluaran)</option>
					</select>
					@error('type')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>
				<div>
					<label class="label mb-1 text-sm font-medium" for="description">Deskripsi <span class="text-base-content/40">(opsional)</span></label>
					<textarea id="description" name="description" rows="3"
						class="textarea textarea-bordered w-full rounded-sm @error('description') textarea-error @enderror"
						placeholder="Deskripsi singkat kategori...">{{ old('description', $category->description) }}</textarea>
					@error('description')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>
			</div>
			<div class="flex items-center gap-3 justify-between">
				<a href="{{ route('categories.index') }}" class="btn btn-ghost rounded-sm">Batal</a>
				<button type="submit" class="btn btn-primary rounded-sm">
					<span class="icon-[tabler--device-floppy] size-5"></span>
					Simpan
				</button>
			</div>
		</form>
	</div>
@endsection
