/* Agentlik lista səhifəsi — filtrləmə & axtarış (agency/list.blade.php-dən çıxarılıb) */
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('agencyFilterForm');
    const searchInput = document.getElementById('agencySearch');
    const filterTypeInput = document.getElementById('filterTypeInput');
    const filterBtns = document.querySelectorAll('#entityFilter .filter-tab');
    const entityGrid = document.getElementById('entityGrid');
    const gridLoading = document.getElementById('gridLoading');

    const listUrl = window.agencyListConfig?.url || '/agencies';

    let currentType = filterTypeInput ? filterTypeInput.value : 'all';
    let searchDebounceTimer = null;

    function fetchEntities() {
        const query = searchInput ? searchInput.value.trim() : '';
        const params = new URLSearchParams();
        if (currentType && currentType !== 'all') {
            params.set('type', currentType);
        }
        if (query) {
            params.set('search', query);
        }

        const queryString = params.toString();
        const requestUrl = listUrl + (queryString ? '?' + queryString : '');

        // Update URL in address bar
        window.history.pushState({ type: currentType, search: query }, '', requestUrl);

        if (gridLoading) gridLoading.classList.remove('hidden');

        fetch(requestUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (entityGrid && data.html !== undefined) {
                entityGrid.innerHTML = data.html;
            }
        })
        .catch(err => {
            console.error('Fetch error:', err);
        })
        .finally(() => {
            if (gridLoading) gridLoading.classList.add('hidden');
        });
    }

    filterBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            filterBtns.forEach(function(b) {
                b.classList.remove('bg-white', 'text-orange-500', 'shadow-sm');
                b.classList.add('text-gray-600', 'hover:text-gray-900', 'hover:bg-white/50');
            });
            this.classList.remove('text-gray-600', 'hover:text-gray-900', 'hover:bg-white/50');
            this.classList.add('bg-white', 'text-orange-500', 'shadow-sm');

            currentType = this.getAttribute('data-filter') || 'all';
            if (filterTypeInput) filterTypeInput.value = currentType;
            fetchEntities();
        });
    });

    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            clearTimeout(searchDebounceTimer);
            fetchEntities();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchDebounceTimer);
            searchDebounceTimer = setTimeout(fetchEntities, 350);
        });
    }

    // Handle browser back/forward buttons
    window.addEventListener('popstate', function(e) {
        if (e.state) {
            currentType = e.state.type || 'all';
            if (filterTypeInput) filterTypeInput.value = currentType;
            if (searchInput) searchInput.value = e.state.search || '';

            filterBtns.forEach(function(b) {
                const f = b.getAttribute('data-filter');
                if (f === currentType) {
                    b.classList.remove('text-gray-600', 'hover:text-gray-900', 'hover:bg-white/50');
                    b.classList.add('bg-white', 'text-orange-500', 'shadow-sm');
                } else {
                    b.classList.remove('bg-white', 'text-orange-500', 'shadow-sm');
                    b.classList.add('text-gray-600', 'hover:text-gray-900', 'hover:bg-white/50');
                }
            });

            fetch(window.location.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (entityGrid && data.html !== undefined) {
                    entityGrid.innerHTML = data.html;
                }
            });
        }
    });
});
