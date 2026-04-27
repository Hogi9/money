<header class="fixed inset-x-0 top-0 z-50 border-b border-base-content/10 bg-base-100/95 backdrop-blur-sm">
	<div class="mx-auto flex h-16 w-full items-center justify-between px-4 sm:px-6 lg:px-8">
		<div class="flex items-center gap-3">
			<button type="button" class="btn btn-text btn-square md:hidden" aria-haspopup="dialog" aria-expanded="false"
				aria-controls="app-sidebar" data-overlay="#app-sidebar" aria-label="Buka sidebar">
				<span class="icon-[tabler--menu-2] size-5"></span>
			</button>

			<a class="link link-neutral text-base-content text-xl font-bold no-underline" href="{{ url('/') }}">
				Fulgenz
			</a>
		</div>

		<div class="flex items-center gap-3">
			{{-- Theme Switcher --}}
			<div
				class="dropdown relative inline-flex [--auto-close:inside] [--offset:8] [--placement:bottom] sm:[--placement:bottom-end]">
				<button id="theme-dropdown" type="button" class="dropdown-toggle btn btn-text btn-circle size-10"
					aria-haspopup="menu" aria-expanded="false" aria-label="Change theme">
					<span class="icon-[tabler--palette] text-base-content size-5"></span>
				</button>
				<div class="dropdown-menu dropdown-open:opacity-100 hidden w-72 p-3" role="menu" aria-orientation="vertical"
					aria-labelledby="theme-dropdown">
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

			<div class="dropdown relative inline-flex [--auto-close:inside] [--offset:8] [--placement:bottom-end]">
				<button id="notif-dropdown" type="button" class="dropdown-toggle btn btn-text btn-circle size-10"
					aria-haspopup="menu" aria-expanded="false" aria-label="Notifikasi">
					<div class="indicator">
						{{-- <span class="indicator-item bg-error size-2 rounded-full"></span> --}}
						<span class="icon-[tabler--bell] text-base-content size-5"></span>
					</div>
				</button>
				<div class="dropdown-menu dropdown-open:opacity-100 hidden min-w-64" role="menu" aria-orientation="vertical"
					aria-labelledby="notif-dropdown">
					<div class="dropdown-header justify-center">
						<h6 class="text-base">Notifications</h6>
					</div>
					<div class="max-h-56 overflow-auto">
						<a href="#" class="dropdown-item">No notifications yet</a>
					</div>
				</div>
			</div>

			<div class="dropdown relative inline-flex [--auto-close:inside] [--offset:8] [--placement:bottom-end]">
				<button id="user-dropdown" type="button" class="dropdown-toggle flex items-center gap-2 rounded-btn p-1"
					aria-haspopup="menu" aria-expanded="false" aria-label="Menu user">
					<div class="avatar avatar-placeholder">
						<div class="bg-primary text-primary-content w-9 rounded-full">
							<span class="text-sm font-medium">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</span>
						</div>
					</div>
				</button>

				<ul class="dropdown-menu dropdown-open:opacity-100 hidden min-w-56" role="menu" aria-orientation="vertical"
					aria-labelledby="user-dropdown">
					<li class="dropdown-header">
						<div>
							<h6 class="text-base font-semibold">{{ auth()->user()->name ?? 'User' }}</h6>
							<small class="text-base-content/60">{{ auth()->user()->email ?? '-' }}</small>
						</div>
					</li>
					<li>
						<a class="dropdown-item" href="{{ route('dashboard') }}">
							<span class="icon-[tabler--layout-dashboard] size-4"></span>
							Dashboard
						</a>
					</li>
					<li>
						<button type="button" class="dropdown-item" onclick="localStorage.removeItem('fulgenz_tour_done'); startTour();">
							<span class="icon-[tabler--route] size-4"></span>
							Mulai Tour
						</button>
					</li>
					<li class="dropdown-footer">
						<a class="btn btn-error btn-soft btn-block" href="{{ route('logout') }}">
							<span class="icon-[tabler--logout] size-4"></span>
							Logout
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</header>
