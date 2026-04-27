@foreach ($wallets as $wallet)
	<div class="flex items-center justify-between py-2 border-b border-base-content/10 last:border-0">
		<div class="flex items-center gap-2 min-w-0">
			<div class="rounded-full bg-primary/10 p-1.5 shrink-0">
				<span class="icon-[tabler--wallet] text-primary size-3.5 block"></span>
			</div>
			<span class="text-sm truncate">{{ $wallet->name }}</span>
		</div>
		<span class="text-sm font-semibold shrink-0 ml-2 tabular-nums {{ $wallet->balance < 0 ? 'text-error' : '' }}">
			{{ $wallet->balance < 0 ? '-' : '' }}Rp {{ number_format(abs($wallet->balance), 0, ',', '.') }}
		</span>
	</div>
@endforeach
