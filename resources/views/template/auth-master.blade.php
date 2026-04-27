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
		<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
	</head>

	<body class="min-h-screen bg-base-100 flex items-center justify-center relative">
		{{-- Theme Switcher --}}
		<div class="fixed top-4 right-4 z-50">
			<div class="dropdown relative inline-flex [--auto-close:inside] [--offset:8] [--placement:bottom-end]">
				<button id="theme-dropdown-auth" type="button"
					class="dropdown-toggle btn btn-text btn-circle size-10 text-base-content/40 hover:text-base-content/70"
					aria-haspopup="menu" aria-expanded="false" aria-label="Change theme">
					<span class="icon-[tabler--palette] text-base-content size-5"></span>
				</button>
				<div class="dropdown-menu dropdown-open:opacity-100 hidden w-72 p-3" role="menu" aria-orientation="vertical"
					aria-labelledby="theme-dropdown-auth">
					<div class="dropdown-header">
						<h6 class="text-sm font-semibold">Choose Theme</h6>
					</div>
					<div class="grid grid-cols-3 gap-1.5 mt-1 max-h-72 overflow-y-auto p-0.5">
						@foreach ([['light', 'Light'], ['dark', 'Dark'], ['claude', 'Claude'], ['corporate', 'Corporate'], ['ghibli', 'Ghibli'], ['gourmet', 'Gourmet'], ['luxury', 'Luxury'], ['mintlify', 'Mintlify'], ['pastel', 'Pastel'], ['perplexity', 'Perplexity'], ['shadcn', 'Shadcn'], ['slack', 'Slack'], ['soft', 'Soft'], ['spotify', 'Spotify'], ['valorant', 'Valorant'], ['vscode', 'Vscode']] as [$id, $label])
							<button type="button" onclick="setTheme('{{ $id }}')" data-theme-option="{{ $id }}"
								class="theme-option flex flex-col items-start gap-1 rounded-md border border-base-content/10 p-2 text-left transition hover:border-primary hover:bg-base-200 cursor-pointer">
								<div class="flex gap-1 pointer-events-none" data-theme="{{ $id }}">
									<span class="size-3.5 rounded-full bg-primary"></span>
									<span class="size-3.5 rounded-full bg-secondary"></span>
									<span class="size-3.5 rounded-full bg-accent"></span>
								</div>
								<span class="text-xs font-medium leading-tight truncate w-full">{{ $label }}</span>
							</button>
						@endforeach
					</div>
				</div>
			</div>
		</div>

		{{-- Ghibli Background Decorations --}}
		<div class="fixed inset-0 overflow-hidden pointer-events-none select-none">
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

		<div class="relative z-10 w-full max-w-md px-4 py-8">
			@yield('content')
		</div>

		<script>
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
