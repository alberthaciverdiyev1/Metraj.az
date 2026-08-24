/* Axtarıram — filtrləmə & AJAX (requests/index.blade.php-dən çıxarılıb) */
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('requestFilterForm');
    const typeInput = document.getElementById('filterTypeInput');
    const tabBtns = document.querySelectorAll('.cat-tab-btn');

    const propTypeCol = document.getElementById('filterPropertyTypeCol');
    const roommateGenderCol = document.getElementById('filterRoommateGenderCol');
    const budgetLabel = document.getElementById('budgetLabel');
    const buyCheckboxes = document.getElementById('buyCheckboxes');
    const rentCheckboxes = document.getElementById('rentCheckboxes');
    const roommateCheckboxes = document.getElementById('roommateCheckboxes');

    const listingsContainer = document.getElementById('requestListingsContainer');
    const paginationContainer = document.getElementById('requestPaginationContainer');
    const loadingIndicator = document.getElementById('requestLoadingIndicator');

    let debounceTimer = null;
    let currentAbortController = null;

    function updateFilterUI(type) {
        tabBtns.forEach(btn => {
            const btnType = btn.getAttribute('data-type');
            btn.className = 'cat-tab-btn px-4 py-2.5 text-xs sm:text-sm font-semibold rounded-xl whitespace-nowrap transition cursor-pointer text-gray-600 hover:text-gray-900';

            if (btnType === type) {
                if (type === 'buy') btn.className = 'cat-tab-btn px-4 py-2.5 text-xs sm:text-sm font-semibold rounded-xl whitespace-nowrap transition cursor-pointer bg-emerald-600 text-white shadow-xs';
                else if (type === 'rent') btn.className = 'cat-tab-btn px-4 py-2.5 text-xs sm:text-sm font-semibold rounded-xl whitespace-nowrap transition cursor-pointer bg-blue-600 text-white shadow-xs';
                else if (type === 'daily') btn.className = 'cat-tab-btn px-4 py-2.5 text-xs sm:text-sm font-semibold rounded-xl whitespace-nowrap transition cursor-pointer bg-amber-600 text-white shadow-xs';
                else if (type === 'roommate') btn.className = 'cat-tab-btn px-4 py-2.5 text-xs sm:text-sm font-semibold rounded-xl whitespace-nowrap transition cursor-pointer bg-purple-600 text-white shadow-xs';
                else btn.className = 'cat-tab-btn px-4 py-2.5 text-xs sm:text-sm font-semibold rounded-xl whitespace-nowrap transition cursor-pointer bg-white text-gray-900 shadow-xs';
            }
        });

        if (type === 'roommate') {
            propTypeCol.classList.add('hidden');
            roommateGenderCol.classList.remove('hidden');
            budgetLabel.textContent = 'Aylıq Pay Büdcəsi (₼)';
            buyCheckboxes.classList.add('hidden');
            rentCheckboxes.classList.remove('hidden');
            roommateCheckboxes.classList.remove('hidden');
        } else if (type === 'rent') {
            propTypeCol.classList.remove('hidden');
            roommateGenderCol.classList.add('hidden');
            budgetLabel.textContent = 'Aylıq Kirayə Büdcəsi (₼)';
            buyCheckboxes.classList.add('hidden');
            rentCheckboxes.classList.remove('hidden');
            roommateCheckboxes.classList.add('hidden');
        } else if (type === 'daily') {
            propTypeCol.classList.remove('hidden');
            roommateGenderCol.classList.add('hidden');
            budgetLabel.textContent = 'Günlük Büdcə (₼)';
            buyCheckboxes.classList.add('hidden');
            rentCheckboxes.classList.add('hidden');
            roommateCheckboxes.classList.add('hidden');
        } else if (type === 'buy') {
            propTypeCol.classList.remove('hidden');
            roommateGenderCol.classList.add('hidden');
            budgetLabel.textContent = 'Alış Büdcəsi (₼)';
            buyCheckboxes.classList.remove('hidden');
            rentCheckboxes.classList.add('hidden');
            roommateCheckboxes.classList.add('hidden');
        } else {
            propTypeCol.classList.remove('hidden');
            roommateGenderCol.classList.add('hidden');
            budgetLabel.textContent = 'Maksimum Büdcə (₼)';
            buyCheckboxes.classList.remove('hidden');
            rentCheckboxes.classList.remove('hidden');
            roommateCheckboxes.classList.add('hidden');
        }
    }

    function buildQueryString(page = null) {
        const formData = new FormData(form);
        const params = new URLSearchParams();

        for (const [key, value] of formData.entries()) {
            if (value !== '' && value !== null) {
                params.append(key, value);
            }
        }

        if (page && page > 1) {
            params.set('page', page);
        }

        return params.toString();
    }

    function fetchFilteredResults(page = 1, updateUrl = true) {
        if (currentAbortController) {
            currentAbortController.abort();
        }
        currentAbortController = new AbortController();

        const queryString = buildQueryString(page);
        const url = `/axtariram${queryString ? '?' + queryString : ''}`;

        if (updateUrl) {
            window.history.pushState({ path: url }, '', url);
        }

        loadingIndicator.classList.remove('hidden');
        listingsContainer.style.opacity = '0.5';

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            signal: currentAbortController.signal
        })
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                listingsContainer.innerHTML = data.html;
                paginationContainer.innerHTML = data.pagination;
                bindPaginationEvents();
            }
        })
        .catch(err => {
            if (err.name !== 'AbortError') {
                console.error('Filter AJAX error:', err);
            }
        })
        .finally(() => {
            loadingIndicator.classList.add('hidden');
            listingsContainer.style.opacity = '1';
        });
    }

    function bindPaginationEvents() {
        if (!paginationContainer) return;
        const links = paginationContainer.querySelectorAll('a');
        links.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                if (href) {
                    try {
                        const urlObj = new URL(href, window.location.origin);
                        const page = urlObj.searchParams.get('page') || 1;
                        fetchFilteredResults(page, true);
                        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    } catch (e) {
                        window.location.href = href;
                    }
                }
            });
        });
    }

    // Category Tabs click
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const selectedType = btn.getAttribute('data-type');
            typeInput.value = selectedType;
            updateFilterUI(selectedType);
            fetchFilteredResults(1, true);
        });
    });

    // Inputs with instant change
    const instantInputs = form.querySelectorAll('select, input[type="checkbox"]');
    instantInputs.forEach(input => {
        input.addEventListener('change', function () {
            fetchFilteredResults(1, true);
        });
    });

    // Text & Number inputs with debounce
    const textInputs = form.querySelectorAll('input[type="text"], input[type="number"]');
    textInputs.forEach(input => {
        input.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                fetchFilteredResults(1, true);
            }, 350);
        });
    });

    // Reset button
    const resetBtn = document.getElementById('resetFiltersBtn');
    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            form.reset();
            typeInput.value = '';
            updateFilterUI('');
            fetchFilteredResults(1, true);
        });
    }

    // Search button
    const submitBtn = document.getElementById('submitFilterBtn');
    if (submitBtn) {
        submitBtn.addEventListener('click', function () {
            fetchFilteredResults(1, true);
        });
    }

    // Handle browser Back/Forward navigation
    window.addEventListener('popstate', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const currentType = urlParams.get('type') || '';
        typeInput.value = currentType;
        updateFilterUI(currentType);

        // Fill form fields from URL params
        const searchInput = document.getElementById('filterSearchInput');
        if (searchInput) searchInput.value = urlParams.get('search') || '';

        const citySelect = document.getElementById('filterCitySelect');
        if (citySelect) citySelect.value = urlParams.get('city_id') || '';

        const propTypeSelect = document.getElementById('filterPropertyTypeSelect');
        if (propTypeSelect) propTypeSelect.value = urlParams.get('property_type') || '';

        const budgetInput = document.getElementById('filterMaxBudgetInput');
        if (budgetInput) budgetInput.value = urlParams.get('max_budget') || '';

        fetchFilteredResults(urlParams.get('page') || 1, false);
    });

    // Initial setup
    updateFilterUI(typeInput.value);
    bindPaginationEvents();
});
