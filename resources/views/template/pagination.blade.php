@if ($paginator->hasPages())
	<div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6">
		<div class="text-sm text-base-content/60">
			Showing
			<span class="font-medium text-base-content">{{ $paginator->firstItem() }}</span>
			to
			<span class="font-medium text-base-content">{{ $paginator->lastItem() }}</span>
			of
			<span class="font-medium text-base-content">{{ $paginator->total() }}</span>
			results
		</div>

		<div>
			<nav class="flex items-center gap-x-1" role="navigation" aria-label="Pagination">
				{{-- Button Previous --}}
				@if ($paginator->onFirstPage())
					<button type="button" class="btn btn-soft opacity-50 btn-disabled btn-xs md:btn-sm lg:btn-md">
						<span class="icon-[tabler--chevron-left] size-5 rtl:rotate-180 lg:hidden"></span>
						<span class="hidden lg:inline">Previous</span>
					</button>
				@else
					<a href="{{ $paginator->previousPageUrl() }}" class="btn btn-soft btn-xs md:btn-sm lg:btn-md">
						<span class="icon-[tabler--chevron-left] size-5 rtl:rotate-180 lg:hidden"></span>
						<span class="hidden lg:inline">Previous</span>
					</a>
				@endif

				<div class="flex items-center gap-x-1">
					@foreach ($elements as $element)
						{{-- Separator "..." --}}
						@if (is_string($element))
							<div class="tooltip inline-block">
								<button type="button" class="tooltip-toggle btn btn-soft btn-square group btn-xs md:btn-sm lg:btn-md"
									aria-label="More Pages">
									<span class="icon-[tabler--dots] size-5"></span>
								</button>
							</div>
						@endif

						{{-- Link Nomor Halaman --}}
						@if (is_array($element))
							@foreach ($element as $page => $url)
								@if ($page == $paginator->currentPage())
									{{-- Halaman Aktif --}}
									<button type="button" class="btn btn-soft btn-square text-primary bg-primary/10 btn-xs md:btn-sm lg:btn-md"
										aria-current="page">
										{{ $page }}
									</button>
								@else
									{{-- Halaman Lain --}}
									<a href="{{ $url }}"
										class="btn btn-soft btn-square aria-[current='page']:text-bg-soft-primary btn-xs md:btn-sm lg:btn-md">
										{{ $page }}
									</a>
								@endif
							@endforeach
						@endif
					@endforeach
				</div>

				{{-- Button Next --}}
				@if ($paginator->hasMorePages())
					<a href="{{ $paginator->nextPageUrl() }}" class="btn btn-soft btn-xs md:btn-sm lg:btn-md">
						<span class="hidden lg:inline">Next</span>
						<span class="icon-[tabler--chevron-right] size-5 rtl:rotate-180 lg:hidden"></span>
					</a>
				@else
					<button type="button" class="btn btn-soft opacity-50 btn-disabled btn-xs md:btn-sm lg:btn-md">
						<span class="hidden lg:inline">Next</span>
						<span class="icon-[tabler--chevron-right] size-5 rtl:rotate-180 lg:hidden"></span>
					</button>
				@endif
			</nav>
		</div>
	</div>
@endif
