(function () {
    'use strict';

    const R = window.MetrajRoutes || {};

    /* ===== STATE ===== */
    let isLoading = false;
    let activeTab = 'rayonTab';

    /* ===== BUILD CLEAN FILTER URL PARAMS ===== */
    function buildFilterParams() {
        const form = document.getElementById('filterForm');
        const params = new URLSearchParams();
        const seenSingle = {};
        const multiArrays = {};

        if (!form) return params;

        Array.from(form.elements).forEach(function (el) {
            if (!el.name) return;
            if (el.disabled) return;

            let val;
            if (el.type === 'checkbox' || el.type === 'radio') {
                if (!el.checked) return;
                val = el.value;
            } else {
                val = el.value;
            }
            val = (val === null || val === undefined) ? '' : val.toString().trim();
            if (val === '') return;

            /* Modal's adType radios are secondary; the main toggle (#adTypeInput) decides */
            if (el.name === 'adType' && el.type === 'radio') return;
            /* "all" = no type filter → drop it from the URL */
            if (el.name === 'adType' && val === 'all') return;

            const name = el.name;
            if (el.type === 'checkbox' || name.endsWith('[]')) {
                const cleanKey = name.replace(/\[\]$/, '');
                if (!multiArrays[cleanKey]) multiArrays[cleanKey] = [];
                multiArrays[cleanKey].push(val);
            } else {
                if (!(name in seenSingle)) seenSingle[name] = val;
            }
        });

        for (let pk in seenSingle) params.append(pk, seenSingle[pk]);
        for (let mk in multiArrays) {
            multiArrays[mk].forEach(function (v) {
                params.append(mk + '[]', v);
            });
        }
        return params;
    }

    /* ===== SEO URL PATH ===== */
    function buildSeoPath(params) {
        const citySlugs = (R && R.citySlugs) || {};
        const adType = params.get('adType') || '';
        const cityId = params.get('cityId') || '';

        let path = '';

        if (adType === 'sale') {
            path = 'satilik';
        } else if (adType === 'rent_monthly') {
            path = 'kira/ayliq';
        } else if (adType === 'rent_daily') {
            path = 'kira/gunluk';
        } else if (adType === 'rent') {
            path = 'kira';
        }

        if (cityId && citySlugs[cityId]) {
            path = path ? citySlugs[cityId] + '/' + path : citySlugs[cityId];
        }

        return path ? '/' + path : '/listing';
    }

    /* ===== RESTORE PATH SEGMENTS BACK TO FORM (popstate) ===== */
    function restorePathToForm(pathname, form) {
        const citySlugs = (R && R.citySlugs) || {};
        const segments = pathname.split('/').filter(Boolean);
        if (!segments.length) return;

        const slugToCity = {};
        Object.keys(citySlugs).forEach(function (id) {
            slugToCity[citySlugs[id]] = id;
        });

        /* Şəhər: ilk və ya ikinci segment şəhər slug'ı ola bilər */
        const cityInput = form ? form.querySelector('input[name="cityId"]') : null;
        let foundCity = false;
        for (let i = 0; i < segments.length; i++) {
            if (slugToCity[segments[i]]) {
                if (cityInput) cityInput.value = slugToCity[segments[i]];
                foundCity = true;
                break;
            }
        }

        /* Deal tipi: satilik / kira + alt tip */
        const adTypeInput = document.getElementById('adTypeInput');
        if (adTypeInput) {
            let adType = '';
            const idx = foundCity ? 1 : 0;
            const dealSeg = segments[idx] || '';
            const rentSeg = segments[idx + 1] || '';

            if (dealSeg === 'satilik') {
                adType = 'sale';
            } else if (dealSeg === 'kira' || dealSeg === 'kiralik') {
                if (rentSeg === 'gunluk') adType = 'rent_daily';
                else if (rentSeg === 'ayliq') adType = 'rent_monthly';
                else adType = 'rent';
            }

            if (adType) adTypeInput.value = adType;
        }
    }

    /* ===== FETCH LISTINGS VIA AJAX ===== */
    function fetchListings() {
        if (isLoading) return;
        isLoading = true;
        showLoading(true);

        const params = buildFilterParams();
        const seoPath = buildSeoPath(params);
        params.set('json', '1');

        /* Şəhər və deal tipi artıq path-də olduğu üçün query-dən çıxarılır */
        params.delete('adType');
        params.delete('cityId');

        const url = seoPath + '?' + params.toString();

        window.history.pushState({}, '', url);

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            updateListings(data);
        })
        .catch(function () {
            window.location.reload();
        })
        .finally(function () {
            isLoading = false;
            showLoading(false);
        });
    }
    window.fetchListings = fetchListings;

    /* ===== UPDATE DOM ===== */
    function updateListings(data) {
        const propertyContainer = document.getElementById('propertyContainer');
        const paginationContainer = document.getElementById('paginationContainer');

        if (propertyContainer) propertyContainer.innerHTML = data.properties;
        if (paginationContainer) paginationContainer.innerHTML = data.pagination;

        initHoverImages();
        if (typeof window.syncCardStates === 'function') window.syncCardStates();
    }

    /* ===== LOADING ===== */
    function showLoading(show) {
        const loader = document.getElementById('listingLoader');
        if (loader) loader.classList.toggle('hidden', !show);
    }

    /* ===== DROPDOWN SELECTS (NAVBAR STYLE) ===== */
    function initDropdowns() {
        function setupFilterDropdown(btnId, menuId, chevronId, hiddenInputId) {
            const btn = document.getElementById(btnId);
            const menu = document.getElementById(menuId);
            const chevron = document.getElementById(chevronId);
            const hiddenInput = document.getElementById(hiddenInputId);
            if (!btn || !menu) return;

            btn.addEventListener('click', function (e) {
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
            });

            menu.querySelectorAll('[data-val]').forEach(function (item) {
                item.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const val = this.getAttribute('data-val') || '';
                    const labelElem = this.querySelector('.item-label');
                    const text = labelElem ? labelElem.textContent.trim() : this.textContent.trim();

                    const prev = hiddenInput ? hiddenInput.value : '';
                    if (hiddenInput) hiddenInput.value = val;

                    const displaySpan = btn.querySelector('.btn-display-text');
                    if (displaySpan) displaySpan.textContent = text;

                    // Update active checkmarks and colors
                    menu.querySelectorAll('[data-val]').forEach(function (i) {
                        const isActive = (i.getAttribute('data-val') || '') === val;
                        i.classList.toggle('text-[#f1913d]', isActive);
                        i.classList.toggle('bg-orange-50/60', isActive);
                        i.classList.toggle('font-bold', isActive);
                        i.classList.toggle('text-gray-700', !isActive);
                        const check = i.querySelector('.item-check');
                        if (check) check.classList.toggle('hidden', !isActive);
                    });

                    menu.classList.add('hidden');
                    if (chevron) chevron.classList.remove('rotate-180');

                    if (prev !== val) fetchListings();
                });
            });
        }

        setupFilterDropdown('filterRoomBtn', 'filterRoomDropdown', 'filterRoomChevron', 'roomCountInput');
        setupFilterDropdown('filterBuildingBtn', 'filterBuildingDropdown', 'filterBuildingChevron', 'buildingTypeInput');

        document.addEventListener('click', function () {
            document.querySelectorAll('.filter-custom-menu').forEach(function (m) {
                m.classList.add('hidden');
            });
            document.querySelectorAll('.filter-custom-chevron').forEach(function (c) {
                c.classList.remove('rotate-180');
            });
        });
    }

    /* ===== ADD-TYPE TOGGLE ===== */
    function initAddTypeToggle() {
        const toggle = document.querySelector('[data-role="add-type-toggle"]');
        if (!toggle) return;

        const buttons = toggle.querySelectorAll('button[data-add-type]');
        const hiddenInput = document.getElementById('adTypeInput');

        buttons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                const val = this.getAttribute('data-value');
                const prev = hiddenInput.value;
                hiddenInput.value = val;

                buttons.forEach(function (b) {
                    b.classList.remove('bg-white', 'text-orange-500', 'shadow-sm');
                    b.classList.add('text-gray-600', 'hover:text-gray-900', 'hover:bg-white/50');
                });
                this.classList.remove('text-gray-600', 'hover:text-gray-900', 'hover:bg-white/50');
                this.classList.add('bg-white', 'text-orange-500', 'shadow-sm');

                /* Toggle rent type section in the modal */
                const rentWrapper = document.getElementById('rentTypeWrapper');
                if (rentWrapper) {
                    rentWrapper.classList.toggle('hidden', val !== 'rent');
                }

                /* Main page filter: apply immediately on change */
                if (prev !== val) fetchListings();
            });

            if (hiddenInput.value === btn.getAttribute('data-value')) {
                btn.click();
            }
        });
    }

    /* ===== FORM SUBMIT ===== */
    function initFormSubmit() {
        const form = document.getElementById('filterForm');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            // Close modals when filter is applied
            const moreFiltersModal = document.getElementById('moreFiltersModal');
            if (moreFiltersModal) moreFiltersModal.classList.add('hidden');

            const cityFilterModal = document.getElementById('cityFilterModal');
            if (cityFilterModal) cityFilterModal.classList.add('hidden');

            fetchListings();
        });

        /* Search on Enter key press (not keyup/keydown auto-search) */
        const textInputs = form.querySelectorAll('input[type="text"]');
        textInputs.forEach(function (input) {
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    
                    const moreFiltersModal = document.getElementById('moreFiltersModal');
                    if (moreFiltersModal) moreFiltersModal.classList.add('hidden');

                    const cityFilterModal = document.getElementById('cityFilterModal');
                    if (cityFilterModal) cityFilterModal.classList.add('hidden');

                    fetchListings();
                }
            });
        });

        /* Search button triggers immediate fetch */
        const searchBtn = form.querySelector('button[type="submit"]');
        if (searchBtn) {
            searchBtn.addEventListener('click', function (e) {
                e.preventDefault();

                const moreFiltersModal = document.getElementById('moreFiltersModal');
                if (moreFiltersModal) moreFiltersModal.classList.add('hidden');

                const cityFilterModal = document.getElementById('cityFilterModal');
                if (cityFilterModal) cityFilterModal.classList.add('hidden');

                fetchListings();
            });
        }

        /* Main page select filters (roomCount, buildingType): apply on change */
        const selects = form.querySelectorAll('select');
        selects.forEach(function (sel) {
            sel.addEventListener('change', function () {
                fetchListings();
            });
        });

        /* Main page text filters (price/area): apply on change (blur/commit) — NOT inside modals */
        textInputs.forEach(function (input) {
            if (input.closest('#cityFilterModal, #moreFiltersModal')) return;
            input.addEventListener('change', function () {
                fetchListings();
            });
        });
    }

    /* ===== RESET FILTERS ===== */
    function initResetFilters() {
        const btn = document.getElementById('resetFiltersBtn');
        if (!btn) return;

        btn.addEventListener('click', function () {
            const form = document.getElementById('filterForm');
            const modal = document.getElementById('moreFiltersModal');

            /* Reset main form hidden inputs */
            form.querySelectorAll('input[type=hidden]').forEach(function (input) {
                input.value = '';
            });

            /* Reset text inputs */
            form.querySelectorAll('input[type="text"]').forEach(function (input) {
                input.value = '';
            });

            /* Reset chip radio groups — check the empty-value option (Hamısı / Fərqi yoxdur) */
            const radioGroupNames = ['buildingType', 'roomCount', 'adType', 'propertyCondition', 'advertiserType', 'rentType'];
            radioGroupNames.forEach(function (name) {
                const radios = (modal || form).querySelectorAll('input[type="radio"][name="' + name + '"]');
                radios.forEach(function (r) {
                    r.checked = r.value === '';
                });
            });

            /* Reset checkbox chips in modal */
            const chipCheckboxes = ['hasDeed', 'inCredit', 'hasVideo'];
            chipCheckboxes.forEach(function (name) {
                const cb = (modal || form).querySelector('input[type="checkbox"][name="' + name + '"]');
                if (cb) cb.checked = false;
            });

            /* Reset display values on main page */
            form.querySelectorAll('[data-role="display-value"]').forEach(function (el) {
                let filter = el.getAttribute('data-filter');
                const defaults = {
                    roomCount: 'Otaq sayi',
                    buildingType: 'Butun Kateqoriyalar',
                    city: 'Butun Seherler'
                };
                if (defaults[filter]) el.textContent = defaults[filter];
            });
            /* Reset add-type toggle */
            const allBtn = document.querySelector('[data-add-type="all"]');
            if (allBtn) {
                /* Pre-set the value so the click handler sees no change and skips its own fetch */
                const adTypeInput = document.getElementById('adTypeInput');
                if (adTypeInput) adTypeInput.value = 'all';
                allBtn.click();
            }

            /* Hide rent type wrapper */
            const rentWrapper = document.getElementById('rentTypeWrapper');
            if (rentWrapper) rentWrapper.classList.add('hidden');

            /* Hide input clear buttons */
            document.querySelectorAll('.modal-input-clear').forEach(function (btn) {
                btn.classList.add('hidden');
            });

            fetchListings();
        });
    }

    /* ===== SCROLL TO TOP ===== */
    function initScrollToTop() {
        const gotop = document.getElementById('scrollToTop');
        const progress = document.querySelector('.progress-circle .progress');
        const radius = 18;
        const circumference = 2 * Math.PI * radius;

        if (progress) {
            progress.style.strokeDasharray = circumference;
            progress.style.strokeDashoffset = circumference;
        }

        window.addEventListener('scroll', function () {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrollPercent = docHeight > 0 ? scrollTop / docHeight : 0;
            if (progress) {
                progress.style.strokeDashoffset = circumference - scrollPercent * circumference;
            }
            if (gotop) {
                gotop.style.display = scrollTop > window.innerHeight / 2 ? 'flex' : 'none';
            }
        });

        if (gotop) {
            gotop.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
        }
    }

    /* ===== GRID / LIST VIEW TOGGLE ===== */
    function initViewToggle() {
        const gridBtn = document.getElementById('gridViewBtn');
        const listBtn = document.getElementById('listViewBtn');
        const propertyContainer = document.getElementById('propertyContainer');

        if (!gridBtn || !listBtn) return;

        const gridClasses = [
            'grid', 'grid-cols-1', 'sm:grid-cols-1', 'md:grid-cols-2',
            'lg:grid-cols-2', 'xl:grid-cols-3', '2xl:grid-cols-4', 'gap-3', 'sm:gap-6'
        ];

        function setView(view) {
            if (!propertyContainer) return;
            if (view === 'list') {
                propertyContainer.classList.remove.apply(propertyContainer.classList, gridClasses);
                propertyContainer.classList.add('list-view');
            } else {
                propertyContainer.classList.remove('list-view');
                propertyContainer.classList.add.apply(propertyContainer.classList, gridClasses);
            }

            if (view === 'list') {
                gridBtn.classList.remove('bg-[var(--primary)]', 'text-white');
                gridBtn.classList.add('border', 'border-gray-300', 'text-gray-500');
                listBtn.classList.remove('border', 'border-gray-300', 'text-gray-500');
                listBtn.classList.add('bg-[var(--primary)]', 'text-white');
            } else {
                listBtn.classList.remove('bg-[var(--primary)]', 'text-white');
                listBtn.classList.add('border', 'border-gray-300', 'text-gray-500');
                gridBtn.classList.remove('border', 'border-gray-300', 'text-gray-500');
                gridBtn.classList.add('bg-[var(--primary)]', 'text-white');
            }
            try { localStorage.setItem('listingView', view); } catch (e) {}
        }

        gridBtn.addEventListener('click', function () { setView('grid'); });
        listBtn.addEventListener('click', function () { setView('list'); });

        try {
            const saved = localStorage.getItem('listingView');
            if (saved) setView(saved);
        } catch (e) {}
    }

    /* ===== MORE FILTERS MODAL (chip-based) ===== */
    function initMoreFilters() {
        const btn = document.getElementById('moreFiltersBtn');
        const modal = document.getElementById('moreFiltersModal');
        const close1 = document.getElementById('closeMoreFilters');
        const close2 = document.getElementById('closeMoreFiltersBtn');

        if (!btn || !modal) return;

        /* Modal filters only apply on "Nəticələri Göstər" (form submit) or when the modal closes */
        let modalChanged = false;

        function closeAndApply() {
            modal.classList.add('hidden');
            if (modalChanged) fetchListings();
        }

        if (close1) close1.addEventListener('click', closeAndApply);
        if (close2) close2.addEventListener('click', closeAndApply);

        /* Close on backdrop click + clear button handling */
        modal.addEventListener('click', function (e) {
            const clearBtn = e.target.closest('.modal-input-clear');
            if (clearBtn) {
                const input = clearBtn.parentElement.querySelector('input');
                if (input) {
                    input.value = '';
                    clearBtn.classList.add('hidden');
                    input.dispatchEvent(new Event('change'));
                }
                return;
            }
            if (e.target === modal) closeAndApply();
        });

        /* Show/hide clear buttons on input change */
        modal.addEventListener('input', function (e) {
            const input = e.target.closest('input');
            if (input) {
                const clearBtn = input.parentElement.querySelector('.modal-input-clear');
                if (clearBtn) {
                    clearBtn.classList.toggle('hidden', input.value.trim() === '');
                }
            }
        });

        /* Live sync: update hidden inputs AND main page display when modal chips change.
           Results only appear on submit or when the modal closes. */
        modal.addEventListener('change', function (e) {
            const input = e.target.closest('input');
            if (!input || !input.name) return;

            modalChanged = true;
            const form = document.getElementById('filterForm');

            /* Text inputs: sync to the matching main-page input so the applied filter matches */
            if (input.type === 'text') {
                const mainTextInput = form.querySelector('input[name="' + input.name + '"]');
                if (mainTextInput) mainTextInput.value = input.value;
                return;
            }

            /* Update main form hidden input */
            const hidden = form.querySelector('input[type="hidden"][name="' + input.name + '"]');
            if (input.type === 'radio' && input.checked) {
                if (hidden) hidden.value = input.value;
            } else if (input.type === 'checkbox') {
                if (hidden) hidden.value = input.checked ? input.value : '';
            }

            /* Update main page display values */
            updateMainDisplay(input.name, input);
        });

        /* Buy/Rent toggle in modal — show/hide rent period */
        modal.querySelectorAll('input[name="adType"]').forEach(function (radio) {
            radio.addEventListener('change', function () {
                const wrapper = document.getElementById('rentTypeWrapper');
                if (wrapper) wrapper.classList.toggle('hidden', this.value !== 'rent');
            });
        });

        /* Update main page display elements to reflect modal chip changes */
        function updateMainDisplay(name, input) {
            const form = document.getElementById('filterForm');
            const isChecked = input.type === 'radio' ? input.checked : true;

            if (name === 'buildingType') {
                const display = form.querySelector('[data-role="display-value"][data-filter="buildingType"]');
                if (display) {
                    display.textContent = (isChecked && input.value)
                        ? (input.parentElement.textContent.trim())
                        : 'Butun Kateqoriyalar';
                }
            } else if (name === 'roomCount') {
                const display = form.querySelector('[data-role="display-value"][data-filter="roomCount"]');
                if (display) {
                    display.textContent = (isChecked && input.value)
                        ? input.value + ' otaqli'
                        : 'Otaq sayi';
                }
            } else if (name === 'adType') {
                const adHidden = document.getElementById('adTypeInput');
                if (adHidden) adHidden.value = isChecked ? input.value : '';
                const toggleVal = (isChecked ? input.value : '') || 'all';
                document.querySelectorAll('[data-add-type]').forEach(function (btn) {
                    let bv = btn.getAttribute('data-add-type');
                    if (bv === toggleVal) {
                        btn.classList.remove('bg-white', 'text-gray-700', 'hover:bg-gray-100');
                        btn.classList.add('bg-[color:var(--primary)]', 'text-white');
                    } else {
                        btn.classList.remove('bg-[color:var(--primary)]', 'text-white');
                        btn.classList.add('bg-white', 'text-gray-700', 'hover:bg-gray-100');
                    }
                });
            }
        }

        /* On modal open: sync main form values TO modal chips */
        btn.addEventListener('click', function () {
            modal.classList.remove('hidden');
            modalChanged = false;

            /* Sync radio chips: check the radio matching the current hidden value (uncheck the rest) */
            const form = document.getElementById('filterForm');
            ['adType', 'buildingType', 'roomCount'].forEach(function (name) {
                const hidden = form.querySelector('input[type="hidden"][name="' + name + '"]');
                const curVal = hidden ? hidden.value : '';
                modal.querySelectorAll('input[name="' + name + '"]').forEach(function (r) {
                    r.checked = r.value === curVal;
                });
            });

            /* Sync text inputs: main → modal */
            ['minPrice', 'maxPrice', 'minArea', 'maxArea', 'fieldAreaMin', 'fieldAreaMax', 'floorMin', 'floorMax'].forEach(function (name) {
                const modalInput = modal.querySelector('input[name="' + name + '"]');
                const mainInput = form.querySelector('input[name="' + name + '"]');
                if (modalInput && mainInput) modalInput.value = mainInput.value;
            });
        });
    }

    /* ===== CITY FILTER MODAL ===== */
    function initCityModal() {
        const openBtn = document.getElementById('openModal');
        const modal = document.getElementById('cityFilterModal');
        const closeBtn = document.getElementById('closeCityModal');
        const applyBtn = document.getElementById('applyCityFilters');
        const resetBtn = document.getElementById('resetCityFilters');
        const applyCount = document.getElementById('applyCount');
        const citySelect = document.getElementById('citySelect');
        const placeholder = document.getElementById('rightPanelPlaceholder');

        if (!modal) return;

        let cityModalChanged = false;
        let dataLoaded = false;
        const cityBtns = modal.querySelectorAll('.city-btn');

        function setActiveCity(cId) {
            cityBtns.forEach(function (b) {
                const match = b.getAttribute('data-city-id') == cId;
                b.classList.toggle('border-orange-500', match);
                b.classList.toggle('bg-orange-50/50', match);
                b.classList.toggle('text-orange-600', match);
                b.classList.toggle('font-bold', match);
                b.classList.toggle('border-gray-200/60', !match);
                b.classList.toggle('bg-white', !match);
                b.classList.toggle('text-gray-700', !match);
            });

            if (placeholder) {
                placeholder.classList.toggle('hidden', !!cId);
            }
        }

        cityBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                const cId = this.getAttribute('data-city-id');
                if (citySelect) {
                    citySelect.value = cId;
                }
                setActiveCity(cId);
                cityModalChanged = true;
                dataLoaded = false;
                if (document.getElementById('rayonList')) document.getElementById('rayonList').innerHTML = '';
                loadCityData();
            });
        });

        function openModalFn() {
            modal.classList.remove('hidden');
            cityModalChanged = false;

            const form = document.getElementById('filterForm');
            const curCityInput = form ? form.querySelector('input[name=cityId]') : null;
            const curCityId = curCityInput ? curCityInput.value : (citySelect ? citySelect.value : '');

            if (curCityId) {
                if (citySelect) citySelect.value = curCityId;
                setActiveCity(curCityId);
            }

            loadCityData();
            updateApplyCount();
        }

        function closeModalFn() {
            modal.classList.add('hidden');
        }

        /* Apply current city selection to the form */
        function applyCitySelection() {
            const form = document.getElementById('filterForm');
            let cityInput = form.querySelector('input[name=cityId]');
            if (!cityInput) {
                cityInput = document.createElement('input');
                cityInput.type = 'hidden';
                cityInput.name = 'cityId';
                form.appendChild(cityInput);
            }
            if (citySelect) {
                cityInput.value = citySelect.value;
            }

            const cityDisplay = document.querySelector('[data-role="display-value"][data-filter="city"]');
            const checkedDistricts = Array.from(modal.querySelectorAll('input[name^="district"]:checked')).map(function (cb) {
                return cb.closest('label') ? cb.closest('label').textContent.trim() : '';
            }).filter(Boolean);

            const cityName = citySelect && citySelect.selectedOptions[0] && citySelect.value
                ? citySelect.selectedOptions[0].textContent.replace(/^.*?:\s*/, '').trim()
                : '';

            if (cityDisplay) {
                if (checkedDistricts.length === 1) {
                    cityDisplay.textContent = cityName ? cityName + ' (' + checkedDistricts[0] + ')' : checkedDistricts[0];
                } else if (checkedDistricts.length > 1) {
                    cityDisplay.textContent = cityName ? cityName + ' (' + checkedDistricts.length + ' rayon)' : checkedDistricts.length + ' rayon';
                } else if (cityName) {
                    cityDisplay.textContent = cityName;
                } else {
                    cityDisplay.textContent = 'Bütün Şəhərlər';
                }
            }
        }

        function closeAndApply() {
            applyCitySelection();
            closeModalFn();
            if (cityModalChanged) fetchListings();
        }

        if (openBtn) openBtn.addEventListener('click', openModalFn);
        const modalLocationBtn = document.getElementById('modalLocationBtn');
        if (modalLocationBtn) modalLocationBtn.addEventListener('click', openModalFn);
        if (closeBtn) closeBtn.addEventListener('click', closeAndApply);
        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeAndApply();
        });

        function loadCityData() {
            if (dataLoaded) return;
            const cityId = citySelect ? citySelect.value : '';

            const rayonList = document.getElementById('rayonList');

            // Read currently checked districts from form or URL params
            const form = document.getElementById('filterForm');
            const existingParams = new URLSearchParams(window.location.search);
            const currentCheckedDistricts = existingParams.getAll('district[]').concat(existingParams.getAll('district')).concat(
                Array.from(form.querySelectorAll('input[name^="district"]:checked')).map(c => c.value)
            );

            if (rayonList && cityId) {
                fetch(R.cities || '/api/cities')
                    .then(function (r) { return r.json(); })
                    .then(function (cities) {
                        const cityList = cities.data || cities;
                        const city = cityList.find(function (c) { return c.id == cityId; });
                        const districtList = city && (city.districts && city.districts.data ? city.districts.data : city.districts);
                        if (districtList && districtList.length) {
                            rayonList.innerHTML = '';
                            districtList.forEach(function (d) {
                                const isChecked = currentCheckedDistricts.includes(String(d.id));
                                rayonList.appendChild(createCheckbox(d.id, d.name, 'district', isChecked));
                            });
                            toggleEmpty(rayonList, document.getElementById('rayonEmpty'));
                            updateApplyCount();
                        }
                    }).catch(function () {});
            }

            dataLoaded = true;
        }

        function createCheckbox(value, label, name, checked) {
            const lbl = document.createElement('label');
            lbl.className = 'flex items-center gap-2.5 bg-gray-50 hover:bg-orange-50/50 px-3.5 py-2.5 rounded-xl border border-gray-200/60 cursor-pointer transition select-none';
            lbl.dataset.text = label.toLowerCase();

            const input = document.createElement('input');
            input.type = 'checkbox';
            input.className = 'accent-orange-600 rounded w-4 h-4 cursor-pointer';
            input.name = name + '[]';
            input.value = value;
            if (checked) input.checked = true;

            lbl.appendChild(input);
            
            const span = document.createElement('span');
            span.className = 'text-xs font-semibold text-gray-700';
            span.textContent = label;
            lbl.appendChild(span);

            input.addEventListener('change', function () {
                cityModalChanged = true;
                updateApplyCount();
            });
            return lbl;
        }

        function toggleEmpty(listEl, emptyEl) {
            if (!emptyEl) return;
            const hasItems = listEl && listEl.querySelector('label');
            emptyEl.classList.toggle('hidden', !!hasItems);
        }

        function updateApplyCount() {
            const count = modal.querySelectorAll('input[type=checkbox]:checked').length;
            if (applyCount) applyCount.textContent = count;
        }

        function wireSearch(inputId, listId, emptyId) {
            const input = document.getElementById(inputId);
            const list = document.getElementById(listId);
            const empty = document.getElementById(emptyId);
            if (!input || !list) return;

            input.addEventListener('input', function () {
                const q = this.value.trim().toLowerCase();
                let visible = 0;
                list.querySelectorAll('label').forEach(function (l) {
                    const show = l.dataset.text ? l.dataset.text.includes(q) : true;
                    l.classList.toggle('hidden', !show);
                    if (show) visible++;
                });
                if (empty) empty.classList.toggle('hidden', visible !== 0);
            });
        }

        wireSearch('rayonSearch', 'rayonList', 'rayonEmpty');

        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                cityModalChanged = true;
                if (citySelect) citySelect.value = '';
                setActiveCity('');
                modal.querySelectorAll('input[type=checkbox]').forEach(function (ch) {
                    ch.checked = false;
                });
                updateApplyCount();
                const el = document.getElementById('rayonSearch');
                if (el) el.value = '';
                const list = document.getElementById('rayonList');
                if (list) list.innerHTML = '';
                toggleEmpty(document.getElementById('rayonList'), document.getElementById('rayonEmpty'));
            });
        }

        if (applyBtn) {
            applyBtn.addEventListener('click', function () {
                applyCitySelection();
                closeModalFn();
                fetchListings();
            });
        }
    }



    function getCookie(name) {
        const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? decodeURIComponent(match[2]) : null;
    }

    /* ===== IMAGE NAVIGATION ===== */
    window.nextImage = function (btn) {
        let container = btn.closest('[data-images]');
        if (!container) return;
        const images = JSON.parse(container.getAttribute('data-images'));
        let current = parseInt(container.getAttribute('data-current')) || 0;
        const next = (current + 1) % images.length;
        showImage(container, images, next);
    };

    window.prevImage = function (btn) {
        let container = btn.closest('[data-images]');
        if (!container) return;
        const images = JSON.parse(container.getAttribute('data-images'));
        let current = parseInt(container.getAttribute('data-current')) || 0;
        const prev = (current - 1 + images.length) % images.length;
        showImage(container, images, prev);
    };

    function showImage(container, images, index) {
        const img = container.querySelector('.card-image');
        if (img) img.src = images[index];
        container.setAttribute('data-current', index);

        const dots = container.querySelectorAll('.absolute.bottom-2 > span');
        dots.forEach(function (dot, i) {
            dot.classList.toggle('bg-white', i === index);
            dot.classList.toggle('bg-white/50', i !== index);
        });
    }

    /* ===== HOVER IMAGE SWITCH (Disabled auto-rotation on hover) ===== */
    function initHoverImages() {
        // Şəkillər hover zamanı avtomatik çevrilmir, yalnız ox düymələrinə basıldıqda dəyişir
    }

    /* ===== PAGINATION CLICK DELEGATION ===== */
    function initPagination() {
        let container = document.getElementById('paginationContainer');
        if (!container) return;

        container.addEventListener('click', function (e) {
            const link = e.target.closest('a');
            if (!link) return;
            let href = link.getAttribute('href');
            if (!href || href === '#') return;

            e.preventDefault();

            /* Build current filter params and update page param */
            const params = buildFilterParams();

            const url = new URL(href, window.location.origin);
            params.set('page', url.searchParams.get('page') || '1');
            params.set('json', '1');

            const seoPath = buildSeoPath(params);
            params.delete('adType');
            params.delete('cityId');

            const fetchUrl = seoPath + '?' + params.toString();
            window.history.pushState({}, '', fetchUrl);

            if (isLoading) return;
            isLoading = true;
            showLoading(true);

            fetch(fetchUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                updateListings(data);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            })
            .catch(function () {
                window.location.href = href;
            })
            .finally(function () {
                isLoading = false;
                showLoading(false);
            });
        });
    }

    /* ===== POPSTATE (browser back/forward) ===== */
    function initPopState() {
        window.addEventListener('popstate', function () {
            const params = new URLSearchParams(window.location.search);
            const form = document.getElementById('filterForm');

            /* Restore form values from URL params */
            form.querySelectorAll('input, select').forEach(function (input) {
                let name = input.getAttribute('name');
                if (!name) return;
                if (input.type === 'hidden') {
                    input.value = params.get(name) || '';
                }
            });

            /* SEO path-dən şəhər və deal tipini bərpa et (query-də deyillər) */
            restorePathToForm(window.location.pathname, form);

            fetchListings();
        });
    }

    /* ===== INIT ===== */
    function initAll() {
        initDropdowns();
        initAddTypeToggle();
        initFormSubmit();
        initResetFilters();
        initScrollToTop();
        initViewToggle();
        initMoreFilters();
        initCityModal();
        initHoverImages();
        initPagination();
        initPopState();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }

})();
