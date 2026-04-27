{{-- Icon Picker Modal — shared by menus/create and menus/edit --}}
<div id="icon-picker-modal"
    class="overlay modal overlay-open:opacity-100 overlay-open:duration-300 modal-middle hidden"
    role="dialog" tabindex="-1">
    <div class="modal-dialog max-w-2xl w-full">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title flex items-center gap-2">
                    <span class="icon-[tabler--icons] size-5"></span>
                    Pick an Icon
                </h3>
                <button type="button" class="btn btn-text btn-circle btn-sm absolute end-3 top-3"
                    data-overlay="#icon-picker-modal">
                    <span class="icon-[tabler--x] size-4"></span>
                </button>
            </div>
            <div class="modal-body">
                <label class="input input-bordered input-sm flex items-center gap-2 rounded-sm mb-3">
                    <span class="icon-[tabler--search] text-base-content/40 size-4 shrink-0"></span>
                    <input type="text" id="icon-search" placeholder="Search icons…" class="grow" autocomplete="off" />
                </label>
                <div id="icon-grid"
                    class="grid grid-cols-5 sm:grid-cols-7 md:grid-cols-9 gap-1 max-h-72 overflow-y-auto pr-1">
                </div>
                <p id="icon-no-results" class="hidden text-center text-base-content/50 text-sm py-6">No icons found.</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const ICONS = [
        // Navigation & Layout
        'home', 'home-2', 'dashboard', 'layout', 'layout-sidebar', 'layout-grid',
        'apps', 'menu', 'menu-2', 'grid-dots', 'layers', 'stack', 'section',
        // Users & People
        'user', 'users', 'user-circle', 'user-plus', 'user-check', 'user-cog',
        'user-shield', 'user-star', 'people', 'id-badge-2',
        // Security & Auth
        'shield', 'shield-check', 'lock', 'lock-open', 'key', 'fingerprint', 'eye', 'eye-off',
        // Settings
        'settings', 'settings-2', 'adjustments', 'adjustments-horizontal',
        'tool', 'tools', 'sliders', 'filter', 'toggle-left',
        // Charts & Analytics
        'chart-bar', 'chart-line', 'chart-pie', 'chart-area',
        'trending-up', 'trending-down', 'activity', 'pulse',
        // Reports & Data
        'report', 'report-analytics', 'table', 'database', 'server', 'api',
        // Finance
        'wallet', 'cash', 'coin', 'coins', 'currency-dollar', 'currency-euro',
        'credit-card', 'receipt', 'receipt-2', 'moneybag', 'pig-money',
        'transfer', 'arrows-exchange', 'banknote',
        // Files & Documents
        'file', 'files', 'file-text', 'file-invoice', 'file-spreadsheet',
        'folder', 'folder-open', 'book', 'book-open',
        'clipboard', 'notes', 'notebook',
        // Communication
        'bell', 'bell-ringing', 'mail', 'mail-opened', 'message', 'message-circle', 'phone',
        // Shopping & Inventory
        'shopping-cart', 'shopping-bag', 'package', 'box', 'boxes', 'tag', 'tags', 'truck', 'barcode',
        // Time & Schedule
        'calendar', 'calendar-event', 'clock', 'clock-hour-4', 'history', 'timeline',
        // Maps & Location
        'map', 'map-pin', 'globe', 'compass', 'navigation',
        // Media
        'photo', 'photos', 'camera', 'music', 'player-play', 'video',
        // Development
        'code', 'terminal', 'bug', 'git-branch', 'webhook', 'cloud',
        // List & Category
        'list', 'list-check', 'list-details', 'category', 'category-2',
        // Business & Organization
        'building', 'building-store', 'briefcase', 'affiliate',
        // Nature & Misc
        'leaf', 'plant', 'sun', 'moon', 'star', 'heart', 'bookmark', 'flag',
        // Actions
        'link', 'external-link', 'download', 'upload', 'refresh', 'search',
        // Status
        'help', 'info-circle', 'circle-check', 'alert-circle', 'circle-x',
    ];

    const iconGrid = document.getElementById('icon-grid');
    const iconSearch = document.getElementById('icon-search');
    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('icon-preview');
    const noResults = document.getElementById('icon-no-results');

    function toIconifyName(value) {
        const idx = value.indexOf('--');
        if (idx === -1) return 'tabler:' + value;
        return value.slice(0, idx) + ':' + value.slice(idx + 2);
    }

    function updatePreview(value) {
        if (!value || !iconPreview) return;
        iconPreview.setAttribute('icon', toIconifyName(value.trim()));
    }

    function renderIcons(filter) {
        const query = (filter || '').toLowerCase().trim();
        const filtered = query ? ICONS.filter(n => n.includes(query)) : ICONS;

        iconGrid.innerHTML = '';

        if (filtered.length === 0) {
            noResults.classList.remove('hidden');
            return;
        }
        noResults.classList.add('hidden');

        filtered.forEach(name => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.title = 'tabler--' + name;
            btn.className = 'flex flex-col items-center gap-1 p-2 rounded cursor-pointer transition-colors hover:bg-base-200 group';
            btn.innerHTML =
                `<iconify-icon icon="tabler:${name}" width="22" height="22" class="transition-colors group-hover:text-primary"></iconify-icon>` +
                `<span class="text-[9px] text-base-content/50 truncate w-full text-center leading-tight">${name}</span>`;
            btn.addEventListener('click', () => {
                iconInput.value = 'tabler--' + name;
                updatePreview('tabler--' + name);
                document.querySelector('#icon-picker-modal .btn[data-overlay="#icon-picker-modal"]').click();
            });
            iconGrid.appendChild(btn);
        });
    }

    iconSearch.addEventListener('input', function () { renderIcons(this.value); });
    iconInput.addEventListener('input', function () { updatePreview(this.value); });

    // Clear search when modal opens (attach to all triggers)
    document.querySelectorAll('[data-overlay="#icon-picker-modal"]').forEach(trigger => {
        trigger.addEventListener('click', function () {
            setTimeout(() => { iconSearch.value = ''; renderIcons(''); }, 50);
        });
    });

    updatePreview(iconInput.value);
    renderIcons('');
</script>
@endpush
