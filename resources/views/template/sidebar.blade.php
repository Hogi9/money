<script>
	(function() {
		try {
			if (localStorage.getItem('sidebar-state') === 'minified') {
				document.body.classList.add('overlay-minified');
			}
		} catch (error) {
			// Ignore storage access error.
		}
	})();
</script>

<aside id="app-sidebar"
	class="overlay [--overlay-backdrop:true] [--auto-close:md] overlay-minified:w-17 top-16 h-full z-40 md:shadow-none -translate-x-full overlay-open:translate-x-0 drawer drawer-start hidden w-66 md:absolute md:z-30 md:flex md:translate-x-0 border-e border-base-content/20"
	role="dialog" tabindex="-1">

	<div class="drawer-header  overlay-minified:px-3.75 py-2 w-full flex items-center justify-between gap-3">
		<h3 class="drawer-title text-xl font-semibold overlay-minified:hidden">Menus</h3>
		<div class="hidden md:block">
			<!-- Toggle Button -->
			<button type="button" class="btn btn-circle btn-text" aria-haspopup="dialog" aria-expanded="false"
				aria-controls="app-sidebar" aria-label="Minify navigation" data-overlay-minifier="#app-sidebar">
				<span class="icon-[tabler--menu-2] size-5"></span>
				<span class="sr-only">Navigation Toggle</span>
			</button>
			<!-- End Toggle Button -->
		</div>
	</div>
	<div class="drawer-body px-2 pt-4">
		<ul class="menu p-0">
			@php $lastGroup = null; @endphp
			@foreach($sidebarMenus as $menu)
				@if(!$menu->is_active)
					@continue
				@endif

				@php
					$canSee = !$menu->permission_name || (auth()->check() && auth()->user()->can($menu->permission_name));
					$hasVisibleChild = $menu->children->filter(
						fn($c) => $c->is_active && (!$c->permission_name || (auth()->check() && auth()->user()->can($c->permission_name)))
					)->isNotEmpty();
					$isParent = $menu->children->where('is_active', true)->isNotEmpty();
					$showMenu = $isParent ? $hasVisibleChild : $canSee;
				@endphp

				@if(!$showMenu)
					@continue
				@endif

				{{-- Group label --}}
				@if($menu->group && $menu->group !== $lastGroup)
					@php $lastGroup = $menu->group; @endphp
					<li class="menu-title overlay-minified:hidden mt-2">
						<span class="text-xs uppercase text-base-content/40 font-semibold tracking-wider">{{ $menu->group }}</span>
					</li>
				@endif

				@if(!$isParent)
					{{-- Simple link --}}
					<li>
						<a href="{{ $menu->route_name ? route($menu->route_name) : '#' }}"
							class="{{ $menu->route_pattern && request()->routeIs($menu->route_pattern) ? 'active bg-primary/10 text-primary' : '' }}">
							<iconify-icon icon="{{ $menu->icon }}" width="20" height="20"></iconify-icon>
							<span class="overlay-minified:hidden">{{ $menu->name }}</span>
						</a>
					</li>
				@else
					{{-- Dropdown --}}
					@php
						$childPatterns = $menu->children
							->where('is_active', true)
							->pluck('route_pattern')
							->filter()
							->values()
							->toArray();
						$isDropdownActive = !empty($childPatterns) && request()->routeIs($childPatterns);
					@endphp
					<li data-dropdown-sync data-active="{{ $isDropdownActive ? 'true' : 'false' }}"
						class="dropdown relative [--auto-close:inside] [--adaptive:none] [--strategy:static] overlay-minified:[--adaptive:adaptive] overlay-minified:[--strategy:fixed] overlay-minified:[--offset:15] overlay-minified:[--trigger:click] overlay-minified:[--placement:right-start]">
						<button data-dropdown-sync-trigger type="button"
							class="dropdown-toggle {{ $isDropdownActive ? 'active bg-primary/10 text-primary' : '' }}"
							aria-haspopup="menu" aria-expanded="false" aria-label="{{ $menu->name }}">
							<iconify-icon icon="{{ $menu->icon }}" width="20" height="20"></iconify-icon>
							<span class="overlay-minified:hidden">{{ $menu->name }}</span>
							<span class="icon-[tabler--chevron-down] dropdown-open:rotate-180 size-4 ms-auto"></span>
						</button>
						<ul data-dropdown-sync-menu
							class="dropdown-menu transition-none duration-0 mt-0 shadow-none overlay-minified:shadow-md overlay-minified:shadow-base-300/20 dropdown-open:opacity-100 hidden min-w-60 overlay-minified:before:absolute overlay-minified:before:-start-4 overlay-minified:before:top-0 overlay-minified:before:h-full overlay-minified:before:w-4 before:bg-transparent"
							role="menu" aria-orientation="vertical">
							@foreach($menu->children->where('is_active', true) as $child)
								@if(!$child->permission_name || (auth()->check() && auth()->user()->can($child->permission_name)))
									<li>
										<a href="{{ $child->route_name ? route($child->route_name) : '#' }}"
											class="{{ $child->route_pattern && request()->routeIs($child->route_pattern) ? 'active bg-primary/10 text-primary' : 'text-base-content' }}">
											<iconify-icon icon="{{ $child->icon }}" width="20" height="20"></iconify-icon>
											{{ $child->name }}
										</a>
									</li>
								@endif
							@endforeach
						</ul>
					</li>
				@endif
			@endforeach

		</ul>
	</div>
</aside>

@push('style')
	<style>
		/* Pre-open active synced dropdown on first paint to avoid hide/show flash after redirect. */
		body:not(.overlay-minified) #app-sidebar [data-dropdown-sync]:has([data-dropdown-sync-menu] a.active, [data-dropdown-sync-menu] .active)>[data-dropdown-sync-menu] {
			display: block;
			opacity: 1;
		}
	</style>
@endpush

@push('scripts')
	<script>
		const SIDEBAR_KEY = 'sidebar-state';
		const SIDEBAR_MINIFIED = 'minified';
		const SIDEBAR_OPEN = 'open';

		function applySidebarStateFromStorage() {
			const sidebar = document.getElementById('app-sidebar');
			if (!sidebar) return;

			const state = localStorage.getItem(SIDEBAR_KEY);
			if (state === SIDEBAR_MINIFIED) {
				sidebar.classList.add('minified');
				document.body.classList.add('overlay-minified');
				return;
			}

			sidebar.classList.remove('minified');
			document.body.classList.remove('overlay-minified');
		}

		function persistSidebarState() {
			const isMinified = document.body.classList.contains('overlay-minified');
			localStorage.setItem(SIDEBAR_KEY, isMinified ? SIDEBAR_MINIFIED : SIDEBAR_OPEN);
		}

		function isDropdownActive(wrapper) {
			if (wrapper.dataset.active === 'true') return true;
			return !!wrapper.querySelector('[data-dropdown-sync-menu] a.active, [data-dropdown-sync-menu] .active');
		}

		function syncAllDropdowns() {
			const isMinified = document.body.classList.contains('overlay-minified');

			document.querySelectorAll('[data-dropdown-sync]').forEach(wrapper => {
				const isActive = isDropdownActive(wrapper);

				const trigger = wrapper.querySelector('[data-dropdown-sync-trigger]');
				const menu = wrapper.querySelector('[data-dropdown-sync-menu]');
				if (!trigger || !menu) return;
				if (!isActive) {
					wrapper.dataset.forcedOpen = 'false';
					return;
				}

				if (!isMinified) {
					wrapper.classList.add('dropdown-open');
					trigger.setAttribute('aria-expanded', 'true');
					menu.classList.remove('hidden', 'opacity-0');
					menu.classList.add('opacity-100');
					wrapper.dataset.forcedOpen = 'true';
				} else {
					wrapper.classList.remove('dropdown-open');
					trigger.setAttribute('aria-expanded', 'false');
					menu.classList.add('hidden', 'opacity-0');
					menu.classList.remove('opacity-100');
					wrapper.dataset.forcedOpen = 'false';
				}
			});
		}

		function bindDropdownFirstCloseFix() {
			document.querySelectorAll('[data-dropdown-sync]').forEach(wrapper => {
				const trigger = wrapper.querySelector('[data-dropdown-sync-trigger]');
				const menu = wrapper.querySelector('[data-dropdown-sync-menu]');
				if (!trigger || !menu) return;
				if (trigger.dataset.closeFixBound === 'true') return;

				trigger.addEventListener('click', function(event) {
					if (!isDropdownActive(wrapper)) return;
					if (document.body.classList.contains('overlay-minified')) return;
					if (wrapper.dataset.forcedOpen !== 'true') return;
					if (!wrapper.classList.contains('dropdown-open')) return;

					event.preventDefault();
					event.stopImmediatePropagation();

					wrapper.classList.remove('dropdown-open');
					trigger.setAttribute('aria-expanded', 'false');
					menu.classList.add('hidden', 'opacity-0');
					menu.classList.remove('opacity-100');
					wrapper.dataset.forcedOpen = 'false';
				}, true);

				trigger.dataset.closeFixBound = 'true';
			});
		}

		const sidebar = document.getElementById('app-sidebar');
		if (sidebar) {
			applySidebarStateFromStorage();
			bindDropdownFirstCloseFix();
			syncAllDropdowns();

			// Simpan state sesudah FlyonUI selesai toggle agar nilainya akurat.
			sidebar.addEventListener('toggleMinifierClicked.overlay', function() {
				requestAnimationFrame(function() {
					persistSidebarState();
					syncAllDropdowns();
				});
			});
		}
	</script>
@endpush
