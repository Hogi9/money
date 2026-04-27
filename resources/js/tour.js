import { driver } from 'driver.js';
import 'driver.js/dist/driver.css';
import '../css/tour.css';

const TOUR_KEY = 'fulgenz_tour_done';

function expandSidebar() {
    if (document.body.classList.contains('overlay-minified')) {
        document.body.classList.remove('overlay-minified');
        const sidebar = document.getElementById('app-sidebar');
        if (sidebar) sidebar.classList.remove('minified');
        localStorage.setItem('sidebar-state', 'open');
    }
}

// Dropdown "Keuangan" → Dompet, Transaksi
function getKeuanganDropdown() {
    const link = document.querySelector(
        '#app-sidebar a[href$="/wallets"], #app-sidebar a[href$="/transactions"]'
    );
    return link ? link.closest('[data-dropdown-sync]') : null;
}

// Dropdown "Master Keuangan" → Kategori, Nama Transaksi
function getMasterKeuanganDropdown() {
    const link = document.querySelector(
        '#app-sidebar a[href$="/categories"], #app-sidebar a[href*="/transaction-names"]'
    );
    return link ? link.closest('[data-dropdown-sync]') : null;
}

function openDropdown(dropdown) {
    if (!dropdown) return;
    const trigger = dropdown.querySelector('[data-dropdown-sync-trigger]');
    const menu = dropdown.querySelector('[data-dropdown-sync-menu]');
    if (!trigger || !menu) return;

    dropdown.classList.add('dropdown-open');
    trigger.setAttribute('aria-expanded', 'true');
    menu.classList.remove('hidden', 'opacity-0');
    menu.classList.add('opacity-100');
}

function openAllFinanceDropdowns() {
    openDropdown(getKeuanganDropdown());
    openDropdown(getMasterKeuanganDropdown());
}

// Cek apakah elemen yang di-highlight ada di salah satu dropdown finance
function isFinanceStep(el) {
    if (!el) return false;
    const kd = getKeuanganDropdown();
    const md = getMasterKeuanganDropdown();
    return (kd && kd.contains(el)) || (md && md.contains(el));
}

function buildSteps() {
    const sb = '#app-sidebar ';
    const steps = [
        {
            popover: {
                title: '👋 Selamat Datang di Fulgenz!',
                description: 'Mari kami tunjukkan fitur-fitur utama aplikasi keuangan ini. Klik <b>Selanjutnya</b> untuk memulai.',
                side: 'over',
                align: 'center',
            },
        },
        {
            element: 'header',
            popover: {
                title: '🔝 Navbar',
                description: 'Berisi logo aplikasi, pengatur tema, notifikasi, dan menu profil Anda.',
                side: 'bottom',
                align: 'center',
            },
        },
        {
            element: '#user-dropdown',
            popover: {
                title: '👤 Menu Profil',
                description: 'Klik avatar untuk mengakses dashboard, profil Anda, atau keluar dari aplikasi.<br><br>💡 Ada tombol <b>Mulai Tour</b> di sini untuk membuka panduan ini lagi kapan saja.',
                side: 'bottom',
                align: 'end',
            },
        },
        {
            element: '#app-sidebar',
            popover: {
                title: '📋 Menu Navigasi',
                description: 'Sidebar berisi semua navigasi aplikasi. Bisa diperkecil dengan tombol menu untuk ruang layar lebih luas.',
                side: 'right',
                align: 'start',
            },
        },
    ];

    // === Master Keuangan dropdown ===
    const masterDropdown = getMasterKeuanganDropdown();
    if (masterDropdown) {
        const masterTrigger = masterDropdown.querySelector('[data-dropdown-sync-trigger]');
        if (masterTrigger) {
            steps.push({
                element: masterTrigger,
                popover: {
                    title: '🗂️ Master Keuangan',
                    description: 'Berisi data master yang digunakan sebagai referensi transaksi: Kategori dan Nama Transaksi.',
                    side: 'right',
                    align: 'start',
                },
            });
        }

        const catEl = document.querySelector(sb + 'a[href$="/categories"]');
        if (catEl) {
            steps.push({
                element: catEl,
                popover: {
                    title: '🏷️ Kategori',
                    description: 'Buat kategori untuk mengelompokkan transaksi (misal: Makan, Transport, Belanja).',
                    side: 'right',
                    align: 'start',
                },
            });
        }

        const txNameEl = document.querySelector(sb + 'a[href*="/transaction-names"]');
        if (txNameEl) {
            steps.push({
                element: txNameEl,
                popover: {
                    title: '📝 Nama Transaksi',
                    description: 'Simpan nama transaksi yang sering digunakan agar lebih cepat saat mencatat.',
                    side: 'right',
                    align: 'start',
                },
            });
        }
    }

    // === Keuangan dropdown ===
    const keuanganDropdown = getKeuanganDropdown();
    if (keuanganDropdown) {
        const keuanganTrigger = keuanganDropdown.querySelector('[data-dropdown-sync-trigger]');
        if (keuanganTrigger) {
            steps.push({
                element: keuanganTrigger,
                popover: {
                    title: '💰 Keuangan',
                    description: 'Berisi fitur utama pencatatan keuangan: Dompet dan Transaksi.',
                    side: 'right',
                    align: 'start',
                },
            });
        }

        const walletEl = document.querySelector(sb + 'a[href$="/wallets"]');
        if (walletEl) {
            steps.push({
                element: walletEl,
                popover: {
                    title: '👛 Dompet',
                    description: 'Kelola dompet Anda — rekening bank, e-wallet, kas tunai, dan lainnya.',
                    side: 'right',
                    align: 'start',
                },
            });
        }

        const txEl = document.querySelector(sb + 'a[href$="/transactions"]');
        if (txEl) {
            steps.push({
                element: txEl,
                popover: {
                    title: '💸 Transaksi',
                    description: 'Catat pemasukan, pengeluaran, atau transfer antar dompet Anda di sini.',
                    side: 'right',
                    align: 'start',
                },
            });
        }
    }

    steps.push({
        popover: {
            title: '✅ Siap Digunakan!',
            description: 'Anda sudah mengenal fitur-fitur utama Fulgenz. Selamat mengelola keuangan!<br><br><small>💡 Buka kembali panduan ini lewat tombol <b>Mulai Tour</b> di avatar profil.</small>',
            side: 'over',
            align: 'center',
        },
    });

    return steps;
}

export function startTour() {
    expandSidebar();

    setTimeout(() => {
        openAllFinanceDropdowns();

        const driverObj = driver({
            showProgress: true,
            animate: true,
            overlayOpacity: 0.5,
            smoothScroll: true,
            allowClose: true,
            doneBtnText: 'Selesai',
            closeBtnText: 'Lewati',
            nextBtnText: 'Selanjutnya →',
            prevBtnText: '← Sebelumnya',
            progressText: '{{current}} / {{total}}',
            onHighlightStarted: (el) => {
                if (isFinanceStep(el)) openAllFinanceDropdowns();
            },
            onHighlighted: (el) => {
                if (isFinanceStep(el)) openAllFinanceDropdowns();
            },
            onDestroyStarted: () => {
                localStorage.setItem(TOUR_KEY, '1');
                driverObj.destroy();
            },
            steps: buildSteps(),
        });

        driverObj.drive();
    }, 300);
}

window.startTour = startTour;

document.addEventListener('DOMContentLoaded', () => {
    if (window.fulgenzShowTour && !localStorage.getItem(TOUR_KEY)) {
        startTour();
    }
});
