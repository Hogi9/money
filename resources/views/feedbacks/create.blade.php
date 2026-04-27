@extends('template.master')
@section('content')
	<div class="pb-4">
		<h1 class="text-2xl font-semibold tracking-tight">Kritik & Saran</h1>
		<p class="text-base-content/70 mt-1">Sampaikan kritik atau saran Anda untuk membantu kami berkembang.</p>
	</div>
	<div class="border-base-content/25 w-full rounded-lg border bg-base-100 p-6 shadow-sm max-w-2xl">
		<form action="{{ route('feedbacks.store') }}" method="POST">
			@csrf
			<div class="grid grid-cols-1 gap-4 mb-4">
				<div>
					<label class="label mb-1 text-sm font-medium" for="type">Jenis</label>
					<select id="type" name="type"
						class="select select-bordered w-full rounded-sm @error('type') select-error @enderror">
						<option value="">-- Pilih Jenis --</option>
						<option value="kritik" {{ old('type') === 'kritik' ? 'selected' : '' }}>Kritik</option>
						<option value="saran" {{ old('type') === 'saran' ? 'selected' : '' }}>Saran</option>
					</select>
					@error('type')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>
				<div>
					<label class="label mb-1 text-sm font-medium" for="title">Judul</label>
					<input type="text" id="title" name="title" value="{{ old('title') }}"
						class="input input-bordered w-full rounded-sm @error('title') input-error @enderror"
						placeholder="Ringkasan kritik atau saran Anda..." />
					@error('title')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>
				<div>
					<label class="label mb-1 text-sm font-medium" for="message">Pesan</label>
					<textarea id="message" name="message" rows="6"
						class="textarea textarea-bordered w-full rounded-sm @error('message') textarea-error @enderror"
						placeholder="Tuliskan kritik atau saran Anda secara lengkap...">{{ old('message') }}</textarea>
					@error('message')
						<p class="text-error text-xs mt-1">{{ $message }}</p>
					@enderror
				</div>
			</div>
			<div class="flex items-center justify-end">
				<button type="submit" class="btn btn-primary rounded-sm">
					<span class="icon-[tabler--send] size-5"></span>
					Kirim
				</button>
			</div>
		</form>
	</div>
@endsection
