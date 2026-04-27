import './bootstrap';
import { Notyf } from 'notyf';
import 'flyonui/flyonui.js';
import 'iconify-icon';
import './tour';
import ApexCharts from 'apexcharts';
window.ApexCharts = ApexCharts;

function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
    document.querySelectorAll('[data-theme-option]').forEach(el => {
        const isActive = el.dataset.themeOption === theme;
        el.classList.toggle('border-primary', isActive);
        el.classList.toggle('bg-base-200', isActive);
    });
}
window.setTheme = setTheme;

document.addEventListener('DOMContentLoaded', () => {
    const current = localStorage.getItem('theme') || 'mintlify';
    document.querySelectorAll('[data-theme-option]').forEach(el => {
        if (el.dataset.themeOption === current) {
            el.classList.add('border-primary', 'bg-base-200');
        }
    });
});

const notyf = new Notyf({
    duration: 3000,
    position: { x: 'right', y: 'top' },
    dismissible: true,
    types: [
        {
            type: 'success',
            background: 'var(--color-primary)',
            icon: {
                className: 'icon-[tabler--circle-check-filled] text-white',
                tagName: 'i',
                color: 'white'
            }
        },
        {
            type: 'error',
            background: 'var(--color-error)', //#C05641 is a red color
            icon: {
                className: 'icon-[tabler--circle-x-filled] text-white',
                tagName: 'i',
                color: 'white'
            }
        }
    ]
});

window.notyf = notyf;
