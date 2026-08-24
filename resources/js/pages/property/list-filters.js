/* Lista səhifəsi — filter dropdown-ları (list.blade.php-dən çıxarılıb) */
function setupFilterDropdown(btnId, menuId, chevronId, hiddenInputId) {
    const btn = document.getElementById(btnId);
    const menu = document.getElementById(menuId);
    const chevron = document.getElementById(chevronId);
    const hiddenInput = document.getElementById(hiddenInputId);
    if (!btn || !menu) return;

    btn.onclick = function (e) {
        e.stopPropagation();
        // Close other filter dropdowns
        document.querySelectorAll('.filter-custom-menu').forEach(function (m) {
            if (m !== menu) m.classList.add('hidden');
        });
        document.querySelectorAll('.filter-custom-chevron').forEach(function (c) {
            if (c !== chevron) c.classList.remove('rotate-180');
        });

        const isHidden = menu.classList.contains('hidden');
        menu.classList.toggle('hidden', !isHidden);
        if (chevron) chevron.classList.toggle('rotate-180', isHidden);
    };

    menu.querySelectorAll('[data-val]').forEach(function (item) {
        item.onclick = function (e) {
            e.stopPropagation();
            const val = this.getAttribute('data-val') || '';
            const labelElem = this.querySelector('.item-label');
            const text = labelElem ? labelElem.textContent.trim() : this.textContent.trim();

            if (hiddenInput) hiddenInput.value = val;

            const displaySpan = btn.querySelector('.btn-display-text');
            if (displaySpan) displaySpan.textContent = text;

            menu.querySelectorAll('[data-val]').forEach(function (i) {
                const isActive = (i.getAttribute('data-val') || '') === val;
                i.classList.toggle('text-[#f1913d]', isActive);
                i.classList.toggle('bg-orange-50/60', isActive);
                i.classList.toggle('font-semibold', isActive);
                i.classList.toggle('text-gray-700', !isActive);
                const check = i.querySelector('.item-check');
                if (check) check.classList.toggle('hidden', !isActive);
            });

            menu.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180');

            if (typeof window.fetchListings === 'function') {
                window.fetchListings();
            } else {
                const form = document.getElementById('filterForm');
                if (form) form.dispatchEvent(new Event('submit'));
            }
        };
    });
}

document.addEventListener('click', function () {
    document.querySelectorAll('.filter-custom-menu').forEach(function (m) {
        m.classList.add('hidden');
    });
    document.querySelectorAll('.filter-custom-chevron').forEach(function (c) {
        c.classList.remove('rotate-180');
    });
});

function initFilterDropdowns() {
    setupFilterDropdown('filterRoomBtn', 'filterRoomDropdown', 'filterRoomChevron', 'roomCountInput');
    setupFilterDropdown('filterBuildingBtn', 'filterBuildingDropdown', 'filterBuildingChevron', 'buildingTypeInput');
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initFilterDropdowns);
} else {
    initFilterDropdowns();
}
