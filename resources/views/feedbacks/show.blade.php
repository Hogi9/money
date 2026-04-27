@extends('template.master')
@section('content')
	<div class="pb-4 flex items-center justify-between">
		<div>
			<h1 class="text-2xl font-semibold tracking-tight">Detail Feedback</h1>
			<p class="text-base-content/70 mt-1">Tinjau dan perbarui status feedback pengguna.</p>
		</div>
		<a href="{{ route('feedbacks.index') }}" class="btn btn-ghost rounded-sm">
			<span class="icon-[tabler--arrow-left] size-5"></span>
			Kembali
		</a>
	</div>

	<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
		{{-- Detail Feedback --}}
		<div class="lg:col-span-2 border-base-content/25 rounded-lg border bg-base-100 p-6 shadow-sm">
			<div class="flex items-center gap-3 mb-4">
				@if ($feedback->type === 'kritik')
					<span class="badge badge-soft badge-error">Kritik</span>
				@else
					<span class="badge badge-soft badge-info">Saran</span>
				@endif
				@if ($feedback->status === 'pending')
					<span class="badge badge-soft badge-warning">Menunggu</span>
				@elseif ($feedback->status === 'reviewed')
					<span class="badge badge-soft badge-primary">Ditinjau</span>
				@else
					<span class="badge badge-soft badge-success">Selesai</span>
				@endif
			</div>

			<h2 class="text-xl font-semibold mb-2">{{ $feedback->title }}</h2>
			<p class="text-base-content/80 whitespace-pre-wrap leading-relaxed">{{ $feedback->message }}</p>

			@if ($feedback->admin_notes)
				<div class="mt-6 p-4 bg-base-200 rounded-lg border-l-4 border-primary">
					<p class="text-xs font-semibold text-base-content/50 uppercase tracking-wider mb-1">Catatan Admin</p>
					<p class="text-base-content/80 whitespace-pre-wrap">{{ $feedback->admin_notes }}</p>
				</div>
			@endif
		</div>

		{{-- Info & Update Status --}}
		<div class="flex flex-col gap-4">
			<div class="border-base-content/25 rounded-lg border bg-base-100 p-4 shadow-sm">
				<h3 class="font-semibold mb-3 text-sm uppercase tracking-wider text-base-content/50">Informasi</h3>
				<div class="space-y-2 text-sm">
					<div class="flex items-center gap-2">
						<span class="icon-[tabler--user] size-4 text-base-content/50"></span>
						<span class="font-medium">{{ $feedback->user->name }}</span>
					</div>
					<div class="flex items-center gap-2">
						<span class="icon-[tabler--mail] size-4 text-base-content/50"></span>
						<span class="text-base-content/70">{{ $feedback->user->email }}</span>
					</div>
					<div class="flex items-center gap-2">
						<span class="icon-[tabler--calendar] size-4 text-base-content/50"></span>
						<span class="text-base-content/70">{{ $feedback->created_at->format('d M Y, H:i') }}</span>
					</div>
				</div>
			</div>

			@can('edit-feedbacks')
				<div class="border-base-content/25 rounded-lg border bg-base-100 p-4 shadow-sm">
					<h3 class="font-semibold mb-3 text-sm uppercase tracking-wider text-base-content/50">Perbarui Status</h3>
					<form action="{{ route('feedbacks.update', $feedback) }}" method="POST">
						@csrf
						@method('PUT')
						<div class="grid gap-3">
							<div>
								<label class="label mb-1 text-sm font-medium" for="status">Status</label>
								<select id="status" name="status"
									class="select select-bordered select-sm w-full rounded-sm @error('status') select-error @enderror">
									<option value="pending" {{ $feedback->status === 'pending' ? 'selected' : '' }}>Menunggu</option>
									<option value="reviewed" {{ $feedback->status === 'reviewed' ? 'selected' : '' }}>Ditinjau</option>
									<option value="resolved" {{ $feedback->status === 'resolved' ? 'selected' : '' }}>Selesai</option>
								</select>
								@error('status')
									<p class="text-error text-xs mt-1">{{ $message }}</p>
								@enderror
							</div>
							<div>
								<label class="label mb-1 text-sm font-medium" for="admin_notes">
									Catatan Admin <span class="text-base-content/40">(opsional)</span>
								</label>
								<textarea id="admin_notes" name="admin_notes" rows="4"
									class="textarea textarea-bordered textarea-sm w-full rounded-sm @error('admin_notes') textarea-error @enderror"
									placeholder="Tanggapan atau catatan untuk pengirim...">{{ old('admin_notes', $feedback->admin_notes) }}</textarea>
								@error('admin_notes')
									<p class="text-error text-xs mt-1">{{ $message }}</p>
								@enderror
							</div>
							<button type="submit" class="btn btn-sm btn-primary rounded-sm w-full">
								<span class="icon-[tabler--device-floppy] size-4"></span>
								Simpan
							</button>
						</div>
					</form>
				</div>
			@endcan
		</div>
	</div>
@endsection
