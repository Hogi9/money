@extends('template.auth-master')

@section('content')
	<div class="card bg-base-100 shadow-xl border border-base-300">
		<div class="card-body gap-5">

			{{-- Header --}}
			<div class="text-center space-y-2">
				<div class="flex justify-center">
					<div class="w-16 h-16 rounded-full bg-primary/15 flex items-center justify-center">
						<span class="icon-[tabler--mail] text-primary text-4xl"></span>
					</div>
				</div>
				<h1 class="text-2xl font-bold text-base-content">Verifikasi Email Kamu</h1>
				<p class="text-base-content/60 text-sm">
					Link verifikasi telah dikirim ke <strong>{{ auth()->user()->email }}</strong>.
					Silakan cek inbox kamu.
				</p>
			</div>

			@if (session('success'))
				<div class="alert alert-success text-sm">
					<span class="icon-[tabler--circle-check]"></span>
					{{ session('success') }}
				</div>
			@endif

			<p class="text-center text-base-content/50 text-sm">
				Tidak menerima email?
			</p>

			{{-- Resend --}}
			<form method="POST" action="{{ route('verification.send') }}">
				@csrf
				<button type="submit" class="btn btn-primary w-full">
					<span class="icon-[tabler--refresh]"></span>
					Kirim Ulang Link Verifikasi
				</button>
			</form>

			{{-- Logout --}}
			<form method="GET" action="{{ route('logout') }}">
				<button type="submit" class="btn btn-outline w-full text-base-content">
					Keluar
				</button>
			</form>

		</div>
	</div>
@endsection
