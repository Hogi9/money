<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Fulgenz — Kelola Keuangan Anda dengan Mudah</title>
		<script>
			document.documentElement.setAttribute('data-theme', localStorage.getItem('theme') || 'mintlify');
		</script>
		@vite(['resources/css/app.css', 'resources/js/app.js'])
		<link rel="icon" type="image/png" href="{{ asset('storage/img/favicon.png') }}">
		<style>
			html {
				scroll-behavior: smooth;
			}

			@keyframes float {

				0%,
				100% {
					transform: translateY(0px);
				}

				50% {
					transform: translateY(-12px);
				}
			}

			@keyframes float-slow {

				0%,
				100% {
					transform: translateY(0px) rotate(0deg);
				}

				50% {
					transform: translateY(-8px) rotate(3deg);
				}
			}

			@keyframes soot-drift {

				0%,
				100% {
					transform: translate(0, 0);
					opacity: 0.08;
				}

				33% {
					transform: translate(6px, -10px);
					opacity: 0.14;
				}

				66% {
					transform: translate(-4px, -6px);
					opacity: 0.10;
				}
			}

			.animate-float {
				animation: float 5s ease-in-out infinite;
			}

			.animate-float-slow {
				animation: float-slow 7s ease-in-out infinite;
			}

			.animate-soot {
				animation: soot-drift 8s ease-in-out infinite;
			}

			.feature-card:hover .feature-icon {
				transform: scale(1.15) rotate(-5deg);
				transition: transform 0.3s ease;
			}

			.feature-icon {
				transition: transform 0.3s ease;
			}
		</style>
	</head>

	<body class="min-h-screen bg-base-100 overflow-x-hidden">

		{{-- ═══════════════════ FIXED BACKGROUND ═══════════════════ --}}
		<div class="fixed inset-0 z-0 overflow-hidden pointer-events-none select-none">
			<div class="absolute inset-0 bg-gradient-to-b from-accent/15 via-base-100 to-base-200"></div>

			{{-- Totoro silhouette --}}
			<svg class="absolute bottom-0 left-0 opacity-[0.07] w-72 h-72" viewBox="0 0 200 200" fill="currentColor">
				<ellipse cx="100" cy="150" rx="60" ry="50" class="text-primary" />
				<circle cx="100" cy="90" r="45" class="text-primary" />
				<circle cx="80" cy="75" r="8" class="text-base-100" />
				<circle cx="120" cy="75" r="8" class="text-base-100" />
				<ellipse cx="75" cy="35" rx="8" ry="20" class="text-primary"
					transform="rotate(-15 75 35)" />
				<ellipse cx="125" cy="35" rx="8" ry="20" class="text-primary"
					transform="rotate(15 125 35)" />
			</svg>

			{{-- Soot sprites --}}
			<div class="animate-soot absolute top-16 right-24 w-4 h-4 rounded-full bg-neutral opacity-10"
				style="animation-delay:0s"></div>
			<div class="animate-soot absolute top-32 right-48 w-3 h-3 rounded-full bg-neutral opacity-10"
				style="animation-delay:2s"></div>
			<div class="animate-soot absolute top-10 right-72 w-5 h-5 rounded-full bg-neutral opacity-10"
				style="animation-delay:4s"></div>
			<div class="animate-soot absolute bottom-48 right-20 w-3 h-3 rounded-full bg-neutral opacity-10"
				style="animation-delay:1s"></div>
			<div class="animate-soot absolute bottom-64 left-1/3 w-2 h-2 rounded-full bg-neutral opacity-10"
				style="animation-delay:3s"></div>

			{{-- Hills --}}
			<svg class="absolute bottom-0 right-0 opacity-10 w-[600px] h-64" viewBox="0 0 600 260" preserveAspectRatio="none">
				<ellipse cx="500" cy="280" rx="280" ry="130" class="text-primary" fill="currentColor" />
				<ellipse cx="150" cy="300" rx="250" ry="110" class="text-secondary" fill="currentColor"
					opacity="0.5" />
			</svg>
		</div>

		{{-- ═══════════════════ NAVBAR ═══════════════════ --}}
		<nav
			class="fixed top-0 inset-x-0 z-50 h-16 bg-base-100/80 backdrop-blur-md border-b border-base-content/10 flex items-center px-4 sm:px-8">
			<div class="max-w-6xl mx-auto w-full flex items-center justify-between">
				{{-- Logo --}}
				<a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
					<div
						class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center shadow group-hover:scale-105 transition-transform">
						<span class="icon-[tabler--leaf] text-primary-content text-lg"></span>
					</div>
					<span class="text-lg font-bold text-base-content tracking-tight">Fulgenz</span>
				</a>

				{{-- Nav links (desktop) --}}
				<div class="hidden md:flex items-center gap-6 text-sm font-medium text-base-content/70">
					<a href="#features" class="hover:text-primary transition-colors">Fitur</a>
					<a href="#how-it-works" class="hover:text-primary transition-colors">Cara Kerja</a>
					<a href="#preview" class="hover:text-primary transition-colors">Pratinjau</a>
				</div>

				{{-- CTA buttons --}}
				<div class="flex items-center gap-2">
					{{-- Theme toggle --}}
					<div
						class="dropdown relative inline-flex [--auto-close:inside] [--offset:8] [--placement:bottom] sm:[--placement:bottom-end]">
						<button type="button"
							class="dropdown-toggle btn btn-text btn-circle size-9 text-base-content/40 hover:text-base-content/70"
							aria-haspopup="menu" aria-expanded="false">
							<span class="icon-[tabler--palette] size-5"></span>
						</button>
						<div class="dropdown-menu dropdown-open:opacity-100 hidden w-64 p-3" role="menu">
							<div class="dropdown-header">
								<h6 class="text-sm font-semibold">Pilih Tema</h6>
							</div>
							<div class="grid grid-cols-3 gap-1.5 mt-1 max-h-60 overflow-y-auto p-0.5">
								@foreach ([['light', 'Light'], ['dark', 'Dark'], ['claude', 'Claude'], ['corporate', 'Corporate'], ['ghibli', 'Ghibli'], ['gourmet', 'Gourmet'], ['luxury', 'Luxury'], ['mintlify', 'Mintlify'], ['pastel', 'Pastel'], ['perplexity', 'Perplexity'], ['shadcn', 'Shadcn'], ['slack', 'Slack'], ['soft', 'Soft'], ['spotify', 'Spotify'], ['valorant', 'Valorant'], ['vscode', 'Vscode']] as [$id, $label])
									<button type="button" onclick="setTheme('{{ $id }}')" data-theme-option="{{ $id }}"
										class="theme-option flex flex-col items-start gap-1 rounded-md border border-base-content/10 p-2 text-left hover:border-primary hover:bg-base-200 cursor-pointer transition">
										<div class="flex gap-1 pointer-events-none" data-theme="{{ $id }}">
											<span class="size-3 rounded-full bg-primary"></span>
											<span class="size-3 rounded-full bg-secondary"></span>
											<span class="size-3 rounded-full bg-accent"></span>
										</div>
										<span class="text-xs font-medium truncate w-full">{{ $label }}</span>
									</button>
								@endforeach
							</div>
						</div>
					</div>

					<a href="{{ route('login') }}" class="btn btn-ghost btn-sm hidden sm:inline-flex">Masuk</a>
					<a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
				</div>
			</div>
		</nav>

		{{-- ═══════════════════ HERO ═══════════════════ --}}
		<section class="relative z-10 min-h-screen flex items-center pt-16">
			<div class="max-w-6xl mx-auto px-4 sm:px-8 py-20 grid md:grid-cols-2 gap-12 items-center w-full">

				{{-- Left: text --}}
				<div class="space-y-6">
					<div
						class="inline-flex items-center gap-2 bg-primary/10 text-primary text-xs font-semibold px-3 py-1.5 rounded-full border border-primary/20">
						<span class="icon-[tabler--sparkles] size-3.5"></span>
						Manajemen Keuangan Pribadi & Tim
					</div>

					<h1 class="text-4xl sm:text-5xl font-extrabold text-base-content leading-tight">
						Kendalikan <span class="text-primary">Keuangan</span> Anda dengan Penuh Kesadaran
					</h1>

					<p class="text-base-content/60 text-lg leading-relaxed">
						Catat pemasukan, pengeluaran, dan transfer antar dompet. Kelola bersama tim, pantau tren bulanan, dan raih
						ketenangan finansial — seperti berjalan di padang bunga Ghibli.
					</p>

					<div class="flex flex-wrap gap-3 pt-2">
						<a href="{{ route('register') }}"
							class="btn btn-primary btn-lg gap-2 shadow-lg hover:shadow-primary/30 transition-shadow">
							<span class="icon-[tabler--pencil] size-5"></span>
							Mulai Mencatat
						</a>
						<a href="{{ route('login') }}" class="btn btn-outline btn-lg gap-2">
							<span class="icon-[tabler--login] size-5"></span>
							Masuk
						</a>
					</div>

					{{-- Mini trust badges --}}
					<div class="flex flex-wrap items-center gap-4 pt-2 text-sm text-base-content/50">
						<span class="flex items-center gap-1.5">
							<span class="icon-[tabler--shield-check] text-success size-4"></span> Aman & Terenkripsi
						</span>
						<span class="flex items-center gap-1.5">
							<span class="icon-[tabler--users] text-primary size-4"></span> Multi-pengguna
						</span>
						<span class="flex items-center gap-1.5">
							<span class="icon-[tabler--device-mobile] text-secondary size-4"></span> Responsif
						</span>
					</div>
				</div>

				{{-- Right: illustration --}}
				<div class="flex justify-center items-center">
					<div class="relative animate-float-slow">
						{{--
                        IMAGE DIBUTUHKAN:
                        Path: public/storage/img/hero-illustration.png
                        Deskripsi: Ilustrasi bergaya Ghibli — karakter duduk di bukit hijau sambil memegang buku catatan/tablet,
                                   dengan elemen keuangan mengambang di sekitarnya (koin, grafik, dompet).
                                   Background transparan (PNG). Ukuran ideal: 600×500px.
                        Jika belum ada, placeholder akan ditampilkan.
                    --}}
						@if (file_exists(public_path('storage/img/hero-illustration.png')))
							<img src="{{ asset('storage/img/hero-illustration.png') }}" alt="Hero Illustration"
								class="w-full max-w-sm sm:max-w-md drop-shadow-2xl" />
						@else
							{{-- Placeholder card --}}
							<div
								class="w-80 h-72 rounded-2xl bg-base-200 border-2 border-dashed border-base-content/20 flex flex-col items-center justify-center gap-3 text-base-content/30">
								<span class="icon-[tabler--photo] size-12"></span>
								<span class="text-xs text-center px-4">Tambahkan gambar di:<br><code
										class="text-xs">public/storage/img/hero-illustration.png</code></span>
							</div>
						@endif

						{{-- Floating stat pill 1 --}}
						<div
							class="absolute -top-4 -right-4 bg-base-100 rounded-xl shadow-lg border border-base-content/10 px-3 py-2 flex items-center gap-2 text-sm animate-float"
							style="animation-delay:1s">
							<span class="icon-[tabler--trending-up] text-success size-4"></span>
							<span class="font-semibold text-success">+Rp 2,4 jt</span>
							<span class="text-base-content/40 text-xs">bulan ini</span>
						</div>

						{{-- Floating stat pill 2 --}}
						<div
							class="absolute -bottom-4 -left-4 bg-base-100 rounded-xl shadow-lg border border-base-content/10 px-3 py-2 flex items-center gap-2 text-sm animate-float"
							style="animation-delay:2.5s">
							<span class="icon-[tabler--wallet] text-primary size-4"></span>
							<span class="font-semibold">3 Dompet</span>
							<span class="text-base-content/40 text-xs">aktif</span>
						</div>
					</div>
				</div>
			</div>

			{{-- Scroll indicator --}}
			<div
				class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-1 text-base-content/30 animate-bounce">
				<span class="text-xs">Gulir ke bawah</span>
				<span class="icon-[tabler--chevron-down] size-5"></span>
			</div>
		</section>

		{{-- ═══════════════════ STATS BAR ═══════════════════ --}}
		<section id="stats" class="relative z-10 py-10 border-y border-base-content/10 bg-base-100/50 backdrop-blur-sm">
			<div class="max-w-6xl mx-auto px-4 sm:px-8 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
				<div>
					<span class="icon-[tabler--wallet] text-primary size-7 mx-auto mb-1 block"></span>
					<p class="text-sm text-base-content/60">Banyak dompet</p>
				</div>
				<div>
					<span class="icon-[tabler--arrows-exchange] text-secondary size-7 mx-auto mb-1 block"></span>
					<p class="text-sm text-base-content/60">Catat transaksi</p>
				</div>
				<div>
					<span class="icon-[tabler--users-group] text-accent size-7 mx-auto mb-1 block"></span>
					<p class="text-sm text-base-content/60">Kelola bersama tim</p>
				</div>
				<div>
					<span class="icon-[tabler--chart-bar] text-success size-7 mx-auto mb-1 block"></span>
					<p class="text-sm text-base-content/60">Pantau grafik bulanan</p>
				</div>
			</div>
		</section>

		{{-- ═══════════════════ FEATURES ═══════════════════ --}}
		<section id="features" class="relative z-10 py-20">
			<div class="max-w-6xl mx-auto px-4 sm:px-8">

				<div class="text-center mb-14 space-y-3">
					<div
						class="inline-flex items-center gap-2 bg-secondary/10 text-secondary text-xs font-semibold px-3 py-1.5 rounded-full border border-secondary/20">
						<span class="icon-[tabler--star] size-3.5"></span>
						Feature List
					</div>
					<h2 class="text-3xl sm:text-4xl font-extrabold text-base-content">Yang Ada di Dalamnya</h2>
					<p class="text-base-content/60 max-w-xl mx-auto">Pencatatan harian, analitik, dan kolaborasi tim — dalam satu
						tempat yang mudah digunakan.</p>
				</div>

				<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">

					{{-- Feature 1 --}}
					<div
						class="feature-card group bg-base-100 rounded-2xl border border-base-content/10 p-6 shadow-sm hover:shadow-md hover:border-primary/30 transition-all">
						<div class="feature-icon w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
							<span class="icon-[tabler--wallet] text-primary size-6"></span>
						</div>
						<h3 class="font-bold text-base-content text-lg mb-2">Multi-Dompet</h3>
						<p class="text-base-content/60 text-sm leading-relaxed">Kelola beberapa dompet sekaligus — tunai, rekening,
							e-wallet. Saldo otomatis terupdate dari setiap transaksi.</p>
					</div>

					{{-- Feature 2 --}}
					<div
						class="feature-card group bg-base-100 rounded-2xl border border-base-content/10 p-6 shadow-sm hover:shadow-md hover:border-success/30 transition-all">
						<div class="feature-icon w-12 h-12 rounded-xl bg-success/10 flex items-center justify-center mb-4">
							<span class="icon-[tabler--arrows-exchange] text-success size-6"></span>
						</div>
						<h3 class="font-bold text-base-content text-lg mb-2">Transaksi Lengkap</h3>
						<p class="text-base-content/60 text-sm leading-relaxed">Catat pemasukan, pengeluaran, dan transfer antar dompet.
							Setiap transaksi dilindungi database transaction untuk konsistensi data.</p>
					</div>

					{{-- Feature 3 --}}
					<div
						class="feature-card group bg-base-100 rounded-2xl border border-base-content/10 p-6 shadow-sm hover:shadow-md hover:border-secondary/30 transition-all">
						<div class="feature-icon w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center mb-4">
							<span class="icon-[tabler--category] text-secondary size-6"></span>
						</div>
						<h3 class="font-bold text-base-content text-lg mb-2">Kategori Cerdas</h3>
						<p class="text-base-content/60 text-sm leading-relaxed">Organisir pengeluaran dengan kategori kustom. Nama
							transaksi otomatis mengisi kategori untuk pencatatan yang lebih cepat.</p>
					</div>

					{{-- Feature 4 --}}
					<div
						class="feature-card group bg-base-100 rounded-2xl border border-base-content/10 p-6 shadow-sm hover:shadow-md hover:border-accent/30 transition-all">
						<div class="feature-icon w-12 h-12 rounded-xl bg-accent/10 flex items-center justify-center mb-4">
							<span class="icon-[tabler--chart-bar] text-accent size-6"></span>
						</div>
						<h3 class="font-bold text-base-content text-lg mb-2">Dashboard Analitik</h3>
						<p class="text-base-content/60 text-sm leading-relaxed">Grafik batang 6 bulan dan donut chart kategori yang
							diperbarui real-time. Pantau tren keuangan Anda dalam sekejap.</p>
					</div>

					{{-- Feature 5 --}}
					<div
						class="feature-card group bg-base-100 rounded-2xl border border-base-content/10 p-6 shadow-sm hover:shadow-md hover:border-warning/30 transition-all">
						<div class="feature-icon w-12 h-12 rounded-xl bg-warning/10 flex items-center justify-center mb-4">
							<span class="icon-[tabler--users-group] text-warning size-6"></span>
						</div>
						<h3 class="font-bold text-base-content text-lg mb-2">Kolaborasi Tim</h3>
						<p class="text-base-content/60 text-sm leading-relaxed">Buat tim, undang anggota, dan kelola keuangan bersama.
							Ideal untuk keluarga, bisnis kecil, atau komunitas.</p>
					</div>

					{{-- Feature 6 --}}
					<div
						class="feature-card group bg-base-100 rounded-2xl border border-base-content/10 p-6 shadow-sm hover:shadow-md hover:border-error/30 transition-all">
						<div class="feature-icon w-12 h-12 rounded-xl bg-error/10 flex items-center justify-center mb-4">
							<span class="icon-[tabler--shield-lock] text-error size-6"></span>
						</div>
						<h3 class="font-bold text-base-content text-lg mb-2">Kontrol Akses (RBAC)</h3>
						<p class="text-base-content/60 text-sm leading-relaxed">Sistem peran dan izin granular berbasis Spatie. Admin,
							anggota, owner — setiap orang hanya mengakses apa yang mereka butuhkan.</p>
					</div>

				</div>
			</div>
		</section>

		{{-- ═══════════════════ HOW IT WORKS ═══════════════════ --}}
		<section id="how-it-works" class="relative z-10 py-20 bg-base-200/50">
			<div class="max-w-6xl mx-auto px-4 sm:px-8">

				<div class="text-center mb-14 space-y-3">
					<div
						class="inline-flex items-center gap-2 bg-accent/10 text-accent text-xs font-semibold px-3 py-1.5 rounded-full border border-accent/20">
						<span class="icon-[tabler--map] size-3.5"></span>
						Cara Kerja
					</div>
					<h2 class="text-3xl sm:text-4xl font-extrabold text-base-content">Mulai dalam 3 Langkah</h2>
					<p class="text-base-content/60 max-w-xl mx-auto">Daftar, siapkan dompet, lalu mulai mencatat. Sesederhana itu.</p>
				</div>

				<div class="grid md:grid-cols-3 gap-8 relative">
					{{-- Connector line (desktop) --}}
					<div
						class="hidden md:block absolute top-10 left-1/6 right-1/6 h-0.5 bg-gradient-to-r from-primary/0 via-primary/30 to-primary/0">
					</div>

					{{-- Step 1 --}}
					<div class="flex flex-col items-center text-center gap-4">
						<div class="relative">
							<div
								class="w-20 h-20 rounded-full bg-primary/15 border-2 border-primary/30 flex items-center justify-center shadow-lg">
								<span class="icon-[tabler--user-plus] text-primary size-9"></span>
							</div>
							<div
								class="absolute -top-1 -right-1 w-6 h-6 rounded-full bg-primary text-primary-content text-xs font-bold flex items-center justify-center shadow">
								1</div>
						</div>
						<div>
							<h3 class="font-bold text-lg text-base-content">Daftar Akun</h3>
							<p class="text-base-content/60 text-sm mt-1 leading-relaxed">Buat akun gratis dengan email atau username. Tidak
								perlu kartu kredit.</p>
						</div>
					</div>

					{{-- Step 2 --}}
					<div class="flex flex-col items-center text-center gap-4">
						<div class="relative">
							<div
								class="w-20 h-20 rounded-full bg-secondary/15 border-2 border-secondary/30 flex items-center justify-center shadow-lg">
								<span class="icon-[tabler--wallet] text-secondary size-9"></span>
							</div>
							<div
								class="absolute -top-1 -right-1 w-6 h-6 rounded-full bg-secondary text-secondary-content text-xs font-bold flex items-center justify-center shadow">
								2</div>
						</div>
						<div>
							<h3 class="font-bold text-lg text-base-content">Buat Dompet</h3>
							<p class="text-base-content/60 text-sm mt-1 leading-relaxed">Tambahkan dompet sesuai kebutuhan — rekening
								tabungan, uang tunai, atau e-wallet favoritmu.</p>
						</div>
					</div>

					{{-- Step 3 --}}
					<div class="flex flex-col items-center text-center gap-4">
						<div class="relative">
							<div
								class="w-20 h-20 rounded-full bg-accent/15 border-2 border-accent/30 flex items-center justify-center shadow-lg">
								<span class="icon-[tabler--chart-line] text-accent size-9"></span>
							</div>
							<div
								class="absolute -top-1 -right-1 w-6 h-6 rounded-full bg-accent text-accent-content text-xs font-bold flex items-center justify-center shadow">
								3</div>
						</div>
						<div>
							<h3 class="font-bold text-lg text-base-content">Catat & Pantau</h3>
							<p class="text-base-content/60 text-sm mt-1 leading-relaxed">Catat transaksi harian dan nikmati laporan grafik
								yang memvisualisasikan kondisi keuangan Anda.</p>
						</div>
					</div>
				</div>
			</div>
		</section>

		{{-- ═══════════════════ DASHBOARD PREVIEW ═══════════════════ --}}
		<section class="relative z-10 py-20" id="preview">
			<div class="max-w-6xl mx-auto px-4 sm:px-8">
				<div
					class="rounded-3xl bg-gradient-to-br from-primary/10 via-base-200 to-secondary/10 border border-base-content/10 p-8 md:p-12 flex flex-col md:flex-row items-center gap-10">

					<div class="flex-1 space-y-5">
						<div
							class="inline-flex items-center gap-2 bg-primary/10 text-primary text-xs font-semibold px-3 py-1.5 rounded-full border border-primary/20">
							<span class="icon-[tabler--layout-dashboard] size-3.5"></span>
							Dashboard Real-time
						</div>
						<h2 class="text-2xl sm:text-3xl font-extrabold text-base-content leading-tight">
							Semua ringkasan keuangan<br>dalam satu tampilan
						</h2>
						<ul class="space-y-3 text-sm text-base-content/70">
							<li class="flex items-start gap-2.5">
								<span class="icon-[tabler--circle-check] text-success size-4 mt-0.5 shrink-0"></span>
								Grafik batang pemasukan vs pengeluaran 6 bulan terakhir
							</li>
							<li class="flex items-start gap-2.5">
								<span class="icon-[tabler--circle-check] text-success size-4 mt-0.5 shrink-0"></span>
								Donut chart breakdown pengeluaran per kategori
							</li>
							<li class="flex items-start gap-2.5">
								<span class="icon-[tabler--circle-check] text-success size-4 mt-0.5 shrink-0"></span>
								Daftar transaksi terbaru dengan paginasi
							</li>
							<li class="flex items-start gap-2.5">
								<span class="icon-[tabler--circle-check] text-success size-4 mt-0.5 shrink-0"></span>
								Ringkasan saldo semua dompet sekaligus
							</li>
						</ul>
						<a href="{{ route('register') }}"
							class="btn btn-primary gap-2 shadow hover:shadow-primary/30 transition-shadow">
							<span class="icon-[tabler--arrow-right] size-4"></span>
							Buat Akun
						</a>
					</div>

					<div class="flex-1 w-full">
						{{--
                        IMAGE DIBUTUHKAN:
                        Path: public/storage/img/dashboard-preview.png
                        Deskripsi: Screenshot atau mockup halaman dashboard aplikasi ini.
                                   Tampilkan stat cards + grafik ApexCharts.
                                   Ukuran ideal: 800×500px. Background transparan atau putih.
                        Jika belum ada, placeholder akan ditampilkan.
                    --}}
						@if (file_exists(public_path('storage/img/dashboard-preview.png')))
							<img src="{{ asset('storage/img/dashboard-preview.png') }}" alt="Dashboard Preview"
								class="w-full rounded-xl shadow-2xl border border-base-content/10" />
						@else
							<div
								class="w-full h-56 rounded-xl bg-base-100 border-2 border-dashed border-base-content/20 flex flex-col items-center justify-center gap-3 text-base-content/30">
								<span class="icon-[tabler--screenshot] size-10"></span>
								<span class="text-xs text-center px-4">Tambahkan screenshot dashboard di:<br><code
										class="text-xs">public/storage/img/dashboard-preview.png</code></span>
							</div>
						@endif
					</div>

				</div>
			</div>
		</section>

		{{-- ═══════════════════ FINAL CTA ═══════════════════ --}}
		<section class="relative z-10 py-20">
			<div class="max-w-3xl mx-auto px-4 sm:px-8 text-center space-y-6">
				{{-- Floating totoro-inspired illustration --}}
				<div class="flex justify-center mb-2">
					<svg class="w-20 h-20 opacity-80 animate-float" viewBox="0 0 200 200" fill="currentColor">
						<ellipse cx="100" cy="150" rx="55" ry="45" class="text-primary" />
						<circle cx="100" cy="95" r="42" class="text-primary" />
						<circle cx="82" cy="80" r="7" class="text-base-100" />
						<circle cx="118" cy="80" r="7" class="text-base-100" />
						<ellipse cx="78" cy="38" rx="7" ry="18" class="text-primary"
							transform="rotate(-15 78 38)" />
						<ellipse cx="122" cy="38" rx="7" ry="18" class="text-primary"
							transform="rotate(15 122 38)" />
						{{-- Smile --}}
						<path d="M 88 108 Q 100 118 112 108" stroke="currentColor" stroke-width="3" fill="none"
							class="text-base-100" stroke-linecap="round" />
					</svg>
				</div>

				<h2 class="text-3xl sm:text-4xl font-extrabold text-base-content">
					Mulai dari mana saja,<br>kapan saja.
				</h2>
				<p class="text-base-content/60 text-lg">
					Daftar, buat dompet pertama, dan catat transaksi pertama Anda — setenang angin di padang bunga.
				</p>

				<div class="flex flex-wrap justify-center gap-3 pt-2">
					<a href="{{ route('register') }}"
						class="btn btn-primary btn-lg gap-2 shadow-lg hover:shadow-primary/30 transition-shadow">
						<span class="icon-[tabler--pencil] size-5"></span>
						Buat Akun
					</a>
					<a href="{{ route('login') }}" class="btn btn-outline btn-lg gap-2">
						<span class="icon-[tabler--login] size-5"></span>
						Sudah punya akun? Masuk
					</a>
				</div>
			</div>
		</section>

		{{-- ═══════════════════ FOOTER ═══════════════════ --}}
		<footer class="relative z-10 border-t border-base-content/10 bg-base-100/60 backdrop-blur-sm py-8">
			<div
				class="max-w-6xl mx-auto px-4 sm:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-base-content/40">
				<div class="flex items-center gap-2">
					<div class="w-6 h-6 rounded bg-primary flex items-center justify-center">
						<span class="icon-[tabler--leaf] text-primary-content text-xs"></span>
					</div>
					<span class="font-semibold text-base-content/60">Fulgenz</span>
					<span>— Kelola keuangan, nikmati hidup.</span>
				</div>
				<div class="flex items-center gap-4">
					<a href="{{ route('login') }}" class="hover:text-primary transition-colors">Masuk</a>
					<a href="{{ route('register') }}" class="hover:text-primary transition-colors">Daftar</a>
					<span>© {{ date('Y') }}</span>
				</div>
			</div>
		</footer>

	</body>

</html>
