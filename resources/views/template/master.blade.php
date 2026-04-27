<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>{{ $title ?? 'Money' }}</title>
		<script>
			document.documentElement.setAttribute('data-theme', localStorage.getItem('theme') || 'mintlify');
		</script>
		@vite(['resources/css/app.css', 'resources/js/app.js'])
		<link rel="icon" type="image/png" href="{{ asset('storage/img/favicon.png') }}">
		@stack('style')
	</head>

	<body class="min-h-screen bg-base-100 relative">
		{{-- Ghibli Background Decorations --}}
		<div class="fixed inset-0 z-0 overflow-hidden pointer-events-none select-none">
			{{-- Sky gradient --}}
			<div class="absolute inset-0 bg-gradient-to-b from-accent/20 via-base-100 to-base-200"></div>

			{{-- Floating totoro-inspired silhouette (simple shapes) --}}
			<svg class="absolute bottom-0 left-0 opacity-10 w-64 h-64" viewBox="0 0 200 200" fill="currentColor">
				<ellipse cx="100" cy="150" rx="60" ry="50" class="text-primary" />
				<circle cx="100" cy="90" r="45" class="text-primary" />
				<circle cx="80" cy="75" r="8" class="text-base-100" />
				<circle cx="120" cy="75" r="8" class="text-base-100" />
				<ellipse cx="75" cy="35" rx="8" ry="20" class="text-primary"
					transform="rotate(-15 75 35)" />
				<ellipse cx="125" cy="35" rx="8" ry="20" class="text-primary"
					transform="rotate(15 125 35)" />
			</svg>

			{{-- Soot sprites (kodama-like dots) --}}
			<div class="absolute top-12 right-20 w-4 h-4 rounded-full bg-neutral opacity-10"></div>
			<div class="absolute top-24 right-40 w-3 h-3 rounded-full bg-neutral opacity-10"></div>
			<div class="absolute top-8 right-60 w-5 h-5 rounded-full bg-neutral opacity-10"></div>
			<div class="absolute bottom-40 right-16 w-3 h-3 rounded-full bg-neutral opacity-10"></div>

			{{-- Grass / hills --}}
			<svg class="absolute bottom-0 right-0 opacity-15 w-96 h-48" viewBox="0 0 400 200" preserveAspectRatio="none">
				<ellipse cx="350" cy="200" rx="200" ry="100" class="text-primary" fill="currentColor" />
				<ellipse cx="100" cy="220" rx="180" ry="90" class="text-secondary" fill="currentColor"
					opacity="0.5" />
			</svg>
		</div>

		@include('template.navbar')
		<div class="relative flex min-h-screen w-full pt-16">
			@include('template.sidebar')
			{{-- <main class="min-w-0 flex-1 overflow-auto p-4 sm:ml-[16.5rem]"> --}}
			<main
				class="min-w-0 flex-1 overflow-auto p-4 pb-0 mb-0 transition-[margin] duration-300 md:ml-[16.5rem] md:overlay-minified:ml-[4.25rem]">
				<div class="max-w-7xl mx-auto mb-4">
					@yield('content')
				</div>
			</main>
		</div>

		<script>
			window.fulgenzShowTour = @json(session()->pull('show_tour', false));

			document.addEventListener('DOMContentLoaded', function() {
				@if (session('success'))
					window.notyf.success("{{ session('success') }}");
				@endif

				@if (session('error'))
					window.notyf.error("{{ session('error') }}");
				@endif
			});
		</script>
		@stack('scripts')
	</body>

</html>
