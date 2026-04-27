@extends('template.master')

@section('content')
	@php
		$fmt = fn($n) => ($n < 0 ? '-' : '') . number_format(abs($n), 0, ',', '.');
		$netPositive = $netMonthly >= 0;
	@endphp

	{{-- Header --}}
	<div class="pb-4">
		<h1 class="text-2xl font-semibold tracking-tight">Dashboard</h1>
		<p class="text-base-content/70 mt-1">Selamat datang, {{ auth()->user()->name ?? 'User' }}! Berikut ringkasan keuangan
			Anda.</p>
	</div>

	{{-- Stat Cards --}}
	@php $monthlyTotalOut = $monthlyOutcome + $monthlyTransferFee; @endphp
	<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-4">
		<div class="border-base-content/25 rounded-lg border bg-base-100 p-4 shadow-sm flex items-center gap-4">
			<div class="rounded-full bg-primary/10 p-3 shrink-0">
				<span class="icon-[tabler--wallet] text-primary size-6 block"></span>
			</div>
			<div class="flex-1 min-w-0">
				<p class="text-base-content/60 text-xs font-medium uppercase tracking-wide">Total Saldo</p>
				<p class="text-xl font-bold truncate">Rp {{ $fmt($totalBalance) }}</p>
				<p class="text-base-content/50 text-xs">{{ $walletCount }} dompet aktif</p>
			</div>
		</div>

		<div class="border-base-content/25 rounded-lg border bg-base-100 p-4 shadow-sm flex items-center gap-4">
			<div class="rounded-full bg-success/10 p-3 shrink-0">
				<span class="icon-[tabler--trending-up] text-success size-6 block"></span>
			</div>
			<div class="flex-1 min-w-0">
				<p class="text-base-content/60 text-xs font-medium uppercase tracking-wide">Pemasukan Bulan Ini</p>
				<p class="text-xl font-bold text-success truncate">Rp {{ $fmt($monthlyIncome) }}</p>
				<p class="text-base-content/50 text-xs">{{ now()->format('F Y') }}</p>
			</div>
		</div>

		<div class="border-base-content/25 rounded-lg border bg-base-100 p-4 shadow-sm flex items-center gap-4">
			<div class="rounded-full bg-error/10 p-3 shrink-0">
				<span class="icon-[tabler--trending-down] text-error size-6 block"></span>
			</div>
			<div class="flex-1 min-w-0">
				<p class="text-base-content/60 text-xs font-medium uppercase tracking-wide">Pengeluaran Bulan Ini</p>
				<p class="text-xl font-bold text-error truncate">Rp {{ $fmt($monthlyTotalOut) }}</p>
				{{-- @if ($monthlyTransferFee > 0)
					<p class="text-base-content/50 text-xs truncate">termasuk fee Rp {{ $fmt($monthlyTransferFee) }}</p>
				@else --}}
				<p class="text-base-content/50 text-xs">{{ now()->format('F Y') }}</p>
				{{-- @endif --}}
			</div>
		</div>
		<div class="border-base-content/25 rounded-lg border bg-base-100 p-4 shadow-sm flex items-center gap-4">
			<div
				class="flex items-center justify-center shrink-0 size-12 rounded-full {{ $netPositive ? 'bg-info/10' : 'bg-warning/10' }}">
				<iconify-icon id="icon-preview" icon="tabler:{{ $netPositive ? 'plus' : 'minus' }}"
					class="{{ $netPositive ? 'text-info' : 'text-warning' }}" width="24" height="24">
				</iconify-icon>
			</div>
			<div class="flex-1 min-w-0">
				<p class="text-base-content/60 text-xs font-medium uppercase tracking-wide">Bersih Bulan Ini</p>
				<p class="text-xl font-bold {{ $netPositive ? 'text-info' : 'text-warning' }} truncate">
					{{ $netPositive ? '+' : '-' }}Rp {{ $fmt($netMonthly) }}
				</p>
				<p class="text-base-content/50 text-xs">{{ $netPositive ? 'Surplus' : 'Defisit' }}</p>
			</div>
		</div>
	</div>

	{{-- Charts Row --}}
	<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
		<div class="border-base-content/25 rounded-lg border bg-base-100 p-4 shadow-sm lg:col-span-2">
			<h3 class="font-semibold text-sm mb-1">Pemasukan vs Pengeluaran</h3>
			<p class="text-base-content/50 text-xs mb-3">6 bulan terakhir</p>
			<div id="bar-chart"></div>
		</div>

		<div class="border-base-content/25 rounded-lg border bg-base-100 p-4 shadow-sm">
			<h3 class="font-semibold text-sm mb-1">Pengeluaran per Kategori</h3>
			<p class="text-base-content/50 text-xs mb-3">Bulan {{ now()->format('F Y') }}</p>
			@if ($categoryBreakdown->isNotEmpty())
				<div id="donut-chart"></div>
			@else
				<div class="flex flex-col items-center justify-center h-52 text-base-content/40">
					<span class="icon-[tabler--chart-donut] size-12 mb-2"></span>
					<p class="text-sm text-center">Belum ada pengeluaran berkategori bulan ini</p>
				</div>
			@endif
		</div>
	</div>

	{{-- Bottom Row --}}
	<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
		{{-- Wallets --}}
		<div class="border-base-content/25 rounded-lg border bg-base-100 p-4 shadow-sm">
			<div class="flex items-center justify-between mb-3">
				<h3 class="font-semibold text-sm">Dompet</h3>
				<a href="{{ route('wallets.index') }}" class="text-xs text-primary hover:underline">Lihat semua</a>
			</div>
			@if ($wallets->isEmpty())
				<div class="flex flex-col items-center justify-center py-8 text-base-content/40">
					<span class="icon-[tabler--wallet-off] size-10 mb-2"></span>
					<p class="text-sm">Belum ada dompet</p>
					<a href="{{ route('wallets.create') }}" class="btn btn-xs btn-primary mt-3">Tambah Dompet</a>
				</div>
			@else
				<div class="space-y-1" id="wallet-list">
					@include('dashboard.partials.wallets', ['wallets' => $wallets])
				</div>
				<div class="flex justify-center mt-3" id="wallet-nav" @if (!$wallets->hasPages()) style="display:none" @endif>
					<nav class="flex items-center gap-x-1">
						<button type="button" class="btn btn-text btn-square btn-sm" id="wallet-prev"
							@if ($wallets->onFirstPage()) disabled @endif aria-label="Previous Button">
							<span class="icon-[tabler--chevron-left] size-4 rtl:rotate-180"></span>
						</button>
						<div class="flex items-center gap-x-1 text-sm">
							<button type="button" class="btn btn-text btn-square btn-sm pointer-events-none" id="wallet-current"
								aria-current="page">{{ $wallets->currentPage() }}</button>
							<span class="text-base-content/60">of</span>
							<button type="button" class="btn btn-text btn-square btn-sm pointer-events-none"
								id="wallet-total">{{ $wallets->lastPage() }}</button>
						</div>
						<button type="button" class="btn btn-text btn-square btn-sm" id="wallet-next"
							@if (!$wallets->hasMorePages()) disabled @endif aria-label="Next Button">
							<span class="icon-[tabler--chevron-right] size-4 rtl:rotate-180"></span>
						</button>
					</nav>
				</div>
			@endif
		</div>

		{{-- Recent Transactions --}}
		<div class="border-base-content/25 rounded-lg border bg-base-100 p-4 shadow-sm lg:col-span-2">
			<div class="flex items-center justify-between mb-3">
				<h3 class="font-semibold text-sm">Transaksi Terkini</h3>
				<a href="{{ route('transactions.index') }}" class="text-xs text-primary hover:underline">Lihat semua</a>
			</div>
			@if ($recentTransactions->isEmpty())
				<div class="flex flex-col items-center justify-center py-8 text-base-content/40">
					<span class="icon-[tabler--receipt-off] size-10 mb-2"></span>
					<p class="text-sm">Belum ada transaksi</p>
					<a href="{{ route('transactions.create') }}" class="btn btn-xs btn-primary mt-3">Tambah Transaksi</a>
				</div>
			@else
				<div class="overflow-x-auto">
					<table class="table table-sm">
						<thead>
							<tr>
								<th>Tanggal</th>
								<th>Keterangan</th>
								<th>Dompet</th>
								<th class="text-right">Jumlah</th>
							</tr>
						</thead>
						<tbody id="tx-tbody">
							@include('dashboard.partials.transactions', ['transactions' => $recentTransactions])
						</tbody>
					</table>
				</div>
				<div class="flex justify-end mt-2" id="tx-nav" @if (!$recentTransactions->hasPages()) style="display:none" @endif>
					<nav class="flex items-center gap-x-1">
						<button type="button" class="btn btn-text btn-square btn-sm" id="tx-prev"
							@if ($recentTransactions->onFirstPage()) disabled @endif aria-label="Previous Button">
							<span class="icon-[tabler--chevron-left] size-4 rtl:rotate-180"></span>
						</button>
						<div class="flex items-center gap-x-1 text-sm">
							<button type="button" class="btn btn-text btn-square btn-sm pointer-events-none" id="tx-current"
								aria-current="page">{{ $recentTransactions->currentPage() }}</button>
							<span class="text-base-content/60">of</span>
							<button type="button" class="btn btn-text btn-square btn-sm pointer-events-none"
								id="tx-total">{{ $recentTransactions->lastPage() }}</button>
						</div>
						<button type="button" class="btn btn-text btn-square btn-sm" id="tx-next"
							@if (!$recentTransactions->hasMorePages()) disabled @endif aria-label="Next Button">
							<span class="icon-[tabler--chevron-right] size-4 rtl:rotate-180"></span>
						</button>
					</nav>
				</div>
			@endif
		</div>
	</div>
@endsection

@push('scripts')
	<script>
		function setupAjaxPagination({
			containerId,
			navId,
			url,
			prevId,
			nextId,
			currentId,
			totalId
		}) {
			const container = document.getElementById(containerId);
			const nav = document.getElementById(navId);
			const prevBtn = document.getElementById(prevId);
			const nextBtn = document.getElementById(nextId);
			const currentEl = document.getElementById(currentId);
			const totalEl = document.getElementById(totalId);

			if (!container || !prevBtn || !nextBtn) return;

			let currentPage = parseInt(currentEl?.textContent) || 1;
			let lastPage = parseInt(totalEl?.textContent) || 1;

			function updateNav() {
				if (currentEl) currentEl.textContent = currentPage;
				if (totalEl) totalEl.textContent = lastPage;
				prevBtn.disabled = currentPage <= 1;
				nextBtn.disabled = currentPage >= lastPage;
				if (nav) nav.style.display = lastPage > 1 ? '' : 'none';
			}

			async function fetchPage(page) {
				prevBtn.disabled = true;
				nextBtn.disabled = true;
				try {
					const {
						data
					} = await axios.post(url, {
						page
					});
					container.innerHTML = data.html;
					currentPage = data.current_page;
					lastPage = data.last_page;
				} catch (e) {
					console.error('Pagination error:', e);
				} finally {
					updateNav();
				}
			}

			prevBtn.addEventListener('click', () => {
				if (currentPage > 1) fetchPage(currentPage - 1);
			});
			nextBtn.addEventListener('click', () => {
				if (currentPage < lastPage) fetchPage(currentPage + 1);
			});

			updateNav();
		}

		document.addEventListener('DOMContentLoaded', function() {
			setupAjaxPagination({
				containerId: 'wallet-list',
				navId: 'wallet-nav',
				url: '{{ route('dashboard.wallets') }}',
				prevId: 'wallet-prev',
				nextId: 'wallet-next',
				currentId: 'wallet-current',
				totalId: 'wallet-total',
			});

			setupAjaxPagination({
				containerId: 'tx-tbody',
				navId: 'tx-nav',
				url: '{{ route('dashboard.transactions') }}',
				prevId: 'tx-prev',
				nextId: 'tx-next',
				currentId: 'tx-current',
				totalId: 'tx-total',
			});

			const fmtRupiah = (val) => {
				if (val >= 1_000_000_000) return 'Rp ' + (val / 1_000_000_000).toFixed(1) + 'M';
				if (val >= 1_000_000) return 'Rp ' + (val / 1_000_000).toFixed(1) + 'jt';
				if (val >= 1_000) return 'Rp ' + (val / 1_000).toFixed(0) + 'rb';
				return 'Rp ' + val;
			};

			// Bar chart: Income vs Outcome (6 months)
			new ApexCharts(document.getElementById('bar-chart'), {
				series: [{
						name: 'Pemasukan',
						data: @json($chartIncome)
					},
					{
						name: 'Pengeluaran',
						data: @json($chartOutcome)
					},
				],
				chart: {
					type: 'bar',
					height: 260,
					background: 'transparent',
					toolbar: {
						show: false
					},
					fontFamily: 'inherit',
				},
				colors: ['#10b981', '#f43f5e'],
				dataLabels: {
					enabled: false
				},
				plotOptions: {
					bar: {
						borderRadius: 4,
						columnWidth: '60%'
					},
				},
				xaxis: {
					categories: @json($chartMonths),
					labels: {
						style: {
							fontSize: '11px'
						}
					},
					axisBorder: {
						show: false
					},
					axisTicks: {
						show: false
					},
				},
				yaxis: {
					labels: {
						formatter: fmtRupiah,
						style: {
							fontSize: '11px'
						},
					},
				},
				legend: {
					position: 'top',
					fontSize: '12px'
				},
				grid: {
					borderColor: '#e5e7eb',
					strokeDashArray: 4
				},
				tooltip: {
					y: {
						formatter: (val) => 'Rp ' + val.toLocaleString('id-ID')
					},
				},
			}).render();

			@if ($categoryBreakdown->isNotEmpty())
				// Donut chart: Outcome by category
				new ApexCharts(document.getElementById('donut-chart'), {
					series: @json($categoryBreakdown->pluck('total')->values()),
					labels: @json($categoryBreakdown->pluck('name')->values()),
					chart: {
						type: 'donut',
						height: 260,
						background: 'transparent',
						fontFamily: 'inherit',
					},
					colors: ['#6366f1', '#ec4899', '#f59e0b', '#10b981', '#06b6d4', '#8b5cf6', '#ef4444',
						'#14b8a6'
					],
					dataLabels: {
						enabled: false
					},
					legend: {
						position: 'bottom',
						fontSize: '11px'
					},
					plotOptions: {
						pie: {
							donut: {
								size: '58%',
								labels: {
									show: true,
									value: {
										show: true,
										fontSize: '16px',
										fontWeight: 600,
										formatter: (w) => {
											return fmtRupiah(w);
										},
									},
									total: {
										show: true,
										label: 'Total',
										fontSize: '16px',
										fontWeight: 600,
										formatter: (w) => {
											const total = w.globals.seriesTotals.reduce((a, b) => a + b,
												0);
											return fmtRupiah(total);
										},
									},
								},
							},
						},
					},
					tooltip: {
						y: {
							formatter: (val) => 'Rp ' + val.toLocaleString('id-ID')
						},
					},
				}).render();
			@endif
		});
	</script>
@endpush
