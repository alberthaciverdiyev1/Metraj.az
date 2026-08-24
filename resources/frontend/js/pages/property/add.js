(function () {
    'use strict';

    const DATA = window.addPropertyData || {};

    document.addEventListener('DOMContentLoaded', function () {
        if (!document.getElementById('add-property')) return;

        const fileInput = document.getElementById('fileInput');
        const galleryEl = document.getElementById('gallery');
        const dropzone = document.getElementById('dropzone');
        const termsCheckbox = document.getElementById('terms');
        const submitBtn = document.getElementById('add-property-btn');
        const form = document.querySelector('#add-property form');

        initAllCustomSelects();
        initMap();
        initImageUpload();
        renderFeatures();
        renderNearbyObjects();
        initToggle('toggle-features', 'features-container');
        initToggle('toggle-nearby-objects', 'nearby-objects-container');
        initSearch('featureSearch', 'features');
        initSearch('nearbySearch', 'nearby-objects');
        initTermsToggle();
        initUnsavedDataModal();

        /* ─────────────────────────── CUSTOM SELECTS ─────────────────────────── */

        function initAllCustomSelects() {
            // Populate option lists
            populateOptions('building-type-container', DATA.propertyTypes);
            populateOptions('repair-type-container', DATA.repairTypes);
            populateOptions('room-count-container', DATA.roomCounts);
            populateOptions('currency-container', DATA.currencies, 'AZN');
            populateOptions('city-container', DATA.cities);

            // Init each container
            initCustomSelect('building-type-container', onBuildingTypeChange);
            initCustomSelect('add-type-container', onAddTypeChange);
            initCustomSelect('rent-type-container');
            initCustomSelect('repair-type-container');
            initCustomSelect('room-count-container');
            initCustomSelect('floor-container');
            initCustomSelect('currency-container');
            initCustomSelect('city-container', onCityChange);
            initCustomSelect('district-container', onDistrictChange);
            initCustomSelect('town-container');
            initCustomSelect('advertiser-container');
        }

        function populateOptions(containerId, items, defaultKey) {
            const container = document.getElementById(containerId);
            if (!container || !items) return;
            const list = container.querySelector('.custom-select-options');
            if (!list) return;
            let html = '';
            items.forEach(function (item) {
                let value = item.id || item.value || item;
                const label = item.name || item.label || item;
                html += '<li data-value="' + value + '" class="px-4 py-2 hover:bg-orange-100 cursor-pointer">' + label + '</li>';
            });
            list.innerHTML = html;

            // Set default if provided
            if (defaultKey) {
                const textSpan = container.querySelector('.custom-select-text');
                const hiddenInput = container.querySelector('input[type="hidden"]');
                if (textSpan) textSpan.textContent = defaultKey;
                if (hiddenInput) hiddenInput.value = defaultKey;
            }
        }

        function initCustomSelect(containerId, onChange) {
            const container = document.getElementById(containerId);
            if (!container) return;

            const button = container.querySelector('.custom-select-button');
            const optionsList = container.querySelector('.custom-select-options');
            const textSpan = container.querySelector('.custom-select-text');
            const hiddenInput = container.querySelector('input[type="hidden"]');
            const icon = container.querySelector('svg');

            if (!button || !optionsList) return;

            button.addEventListener('click', function (e) {
                e.stopPropagation();
                // close all other selects
                document.querySelectorAll('.custom-select-options').forEach(function (ul) {
                    if (ul !== optionsList) {
                        ul.classList.add('hidden');
                        const otherIcon = ul.closest('.custom-select-container')?.querySelector('svg');
                        if (otherIcon) otherIcon.classList.remove('rotate-180');
                    }
                });
                optionsList.classList.toggle('hidden');
                if (icon) icon.classList.toggle('rotate-180');
            });

            optionsList.addEventListener('click', function (e) {
                const li = e.target.closest('li');
                if (!li) return;

                let value = li.getAttribute('data-value');
                const text = li.textContent.trim();

                if (textSpan) textSpan.textContent = text;
                if (hiddenInput) hiddenInput.value = value;

                optionsList.classList.add('hidden');
                if (icon) icon.classList.remove('rotate-180');

                if (typeof onChange === 'function') {
                    onChange(value, text, container);
                }
            });

            document.addEventListener('click', function (e) {
                if (!container.contains(e.target)) {
                    optionsList.classList.add('hidden');
                    if (icon) icon.classList.remove('rotate-180');
                }
            });
        }

        /* ───────────────────── SPECIAL HANDLERS ───────────────────── */

        function onBuildingTypeChange(value) {
            const areaWrapper = document.getElementById('area-wrapper');
            const fieldAreaWrapper = document.getElementById('field-area-wrapper');

            // LAND type = enum name 'LAND'
            const isLand = value === 'LAND';

            if (isLand) {
                if (areaWrapper) areaWrapper.classList.add('hidden');
                if (fieldAreaWrapper) fieldAreaWrapper.classList.remove('hidden');
            } else {
                if (areaWrapper) areaWrapper.classList.remove('hidden');
                if (fieldAreaWrapper) fieldAreaWrapper.classList.add('hidden');
            }
        }

        function onAddTypeChange(value) {
            const rentContainer = document.getElementById('rent-type-container');
            if (!rentContainer) return;
            if (value === 'rent') {
                rentContainer.classList.remove('hidden');
            } else {
                rentContainer.classList.add('hidden');
            }
        }

        function onCityChange(value) {
            const districtContainer = document.getElementById('district-container');
            const districtList = districtContainer?.querySelector('.custom-select-options');
            const districtText = districtContainer?.querySelector('.custom-select-text');
            const districtInput = document.getElementById('district-input');

            // Reset district
            if (districtList) districtList.innerHTML = '';
            if (districtText) districtText.textContent = 'District';
            if (districtInput) districtInput.value = '';
            if (districtContainer) districtContainer.classList.add('hidden');

            // Reset town
            resetTown();

            if (!DATA.cities || !value) return;

            const cityId = parseInt(value);
            let city = null;
            DATA.cities.forEach(function (c) {
                if (c.id === cityId) city = c;
            });

            if (city && city.districts && city.districts.length > 0) {
                let html = '';
                city.districts.forEach(function (d) {
                    html += '<li data-value="' + d.id + '" class="px-4 py-2 hover:bg-orange-100 cursor-pointer">' + d.name + '</li>';
                });
                if (districtList) districtList.innerHTML = html;
                if (districtContainer) districtContainer.classList.remove('hidden');
            }
        }

        function onDistrictChange(value) {
            resetTown();

            if (!DATA.cities) return;
            const districtId = parseInt(value);
            const cityIdInput = document.getElementById('city-input');
            const cityId = parseInt(cityIdInput ? cityIdInput.value : 0);

            let city = null;
            DATA.cities.forEach(function (c) {
                if (c.id === cityId) city = c;
            });

            if (!city || !city.districts) return;

            let district = null;
            city.districts.forEach(function (d) {
                if (d.id === districtId) district = d;
            });

            if (district && district.towns && district.towns.length > 0) {
                const townContainer = document.getElementById('town-container');
                const townList = townContainer?.querySelector('.custom-select-options');
                let html = '';
                district.towns.forEach(function (t) {
                    html += '<li data-value="' + t.id + '" class="px-4 py-2 hover:bg-orange-100 cursor-pointer">' + t.name + '</li>';
                });
                if (townList) townList.innerHTML = html;
                if (townContainer) townContainer.classList.remove('hidden');
            }
        }

        function resetTown() {
            const townContainer = document.getElementById('town-container');
            const townList = townContainer?.querySelector('.custom-select-options');
            const townText = townContainer?.querySelector('.custom-select-text');
            const townInput = document.getElementById('town-input');
            if (townList) townList.innerHTML = '';
            if (townText) townText.textContent = 'Town';
            if (townInput) townInput.value = '';
            if (townContainer) townContainer.classList.add('hidden');
        }

        /* ─────────────────────────── MAP ─────────────────────────── */

        function initMap() {
            const mapEl = document.getElementById('map');
            if (!mapEl || typeof L === 'undefined') return;

            // Fix Leaflet default icon paths for CDN
            delete L.Icon.Default.prototype._getIconUrl;
            L.Icon.Default.mergeOptions({
                iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
            });

            const map = L.map(mapEl).setView([40.4093, 49.8671], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            let marker = null;

            function updateMapPosition(lat, lng, fetchAddress) {
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                    marker.on('dragend', function (e) {
                        const pos = e.target.getLatLng();
                        updateMapPosition(pos.lat.toFixed(6), pos.lng.toFixed(6), true);
                    });
                }

                if (fetchAddress && addressInput) {
                    fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&zoom=18&addressdetails=1&accept-language=az,ru,en')
                        .then(function (res) { return res.json(); })
                        .then(function (data) {
                            if (data && data.address) {
                                const addr = data.address;
                                let parts = [];
                                if (addr.road) parts.push(addr.road);
                                if (addr.house_number) parts.push('№ ' + addr.house_number);
                                if (addr.suburb && !parts.includes(addr.suburb)) parts.push(addr.suburb);
                                if (addr.neighbourhood && !parts.includes(addr.neighbourhood)) parts.push(addr.neighbourhood);
                                if (addr.city && !parts.includes(addr.city)) parts.push(addr.city);
                                else if (addr.town && !parts.includes(addr.town)) parts.push(addr.town);

                                const resolved = parts.length > 0 ? parts.join(', ') : data.display_name;
                                addressInput.value = resolved;
                                marker.bindPopup('<b>' + resolved + '</b>').openPopup();
                            }
                        })
                        .catch(function (err) {
                            console.error('Reverse geocoding error:', err);
                        });
                }
            }

            map.on('click', function (e) {
                const lat = e.latlng.lat.toFixed(6);
                const lng = e.latlng.lng.toFixed(6);
                updateMapPosition(lat, lng, true);
            });

            const searchBtn = document.getElementById('searchAddress');
            const addressInput = document.getElementById('address');

            function searchAddressOnMap() {
                const addr = addressInput ? addressInput.value.trim() : '';
                if (!addr) return;

                fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(addr) + '&limit=1&accept-language=az,ru,en')
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        if (data && data.length > 0) {
                            const lat = parseFloat(data[0].lat).toFixed(6);
                            const lng = parseFloat(data[0].lon).toFixed(6);
                            map.setView([lat, lng], 16);
                            updateMapPosition(lat, lng, false);
                            if (marker) {
                                marker.bindPopup('<b>' + data[0].display_name + '</b>').openPopup();
                            }
                        } else {
                            alert('Ünvan xəritədə tapılmadı. Zəhmət olmasa xəritədə birbaşa klikləyərək seçin.');
                        }
                    })
                    .catch(function () {
                        alert('Axtarış zamanı xəta baş verdi');
                    });
            }

            if (searchBtn) {
                searchBtn.addEventListener('click', searchAddressOnMap);
            }
            if (addressInput) {
                addressInput.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        searchAddressOnMap();
                    }
                });
            }
        }

        /* ─────────────────────────── IMAGE UPLOAD ─────────────────────────── */

        function initImageUpload() {
            if (!fileInput || !galleryEl || !dropzone) return;

            fileInput.addEventListener('change', renderGallery);

            // Drag and drop
            ['dragenter', 'dragover'].forEach(function (ev) {
                dropzone.addEventListener(ev, function (e) {
                    e.preventDefault();
                    dropzone.classList.add('border-orange-400');
                });
            });
            ['dragleave', 'drop'].forEach(function (ev) {
                dropzone.addEventListener(ev, function (e) {
                    e.preventDefault();
                    dropzone.classList.remove('border-orange-400');
                });
            });
            dropzone.addEventListener('drop', function (e) {
                const droppedFiles = Array.from(e.dataTransfer.files).filter(function (f) {
                    return f.type.startsWith('image/');
                });
                const existingFiles = Array.from(fileInput.files);
                const allFiles = existingFiles.concat(droppedFiles).slice(0, 10);

                const dt = new DataTransfer();
                allFiles.forEach(function (f) { dt.items.add(f); });
                fileInput.files = dt.files;
                renderGallery();
            });
        }

        function renderGallery() {
            if (!fileInput || !galleryEl) return;
            const files = Array.from(fileInput.files);
            galleryEl.innerHTML = '';

            files.forEach(function (file, index) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const div = document.createElement('div');
                    div.className = 'relative group';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'rounded-lg w-full h-28 object-cover';

                    const delBtn = document.createElement('button');
                    delBtn.type = 'button';
                    delBtn.className = 'absolute top-1 right-1 bg-black/50 hover:bg-black/70 text-white p-1 rounded-full transition w-7 h-7 flex items-center justify-center';
                    delBtn.innerHTML = '<i class="bi bi-trash text-sm"></i>';
                    delBtn.onclick = function () {
                        removeFile(index);
                    };

                    div.appendChild(img);
                    div.appendChild(delBtn);
                    galleryEl.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        function removeFile(index) {
            if (!fileInput) return;
            const files = Array.from(fileInput.files);
            files.splice(index, 1);
            const dt = new DataTransfer();
            files.forEach(function (f) { dt.items.add(f); });
            fileInput.files = dt.files;
            renderGallery();
        }

        /* ───────────────────── FEATURES & NEARBY ───────────────────── */

        function renderFeatures() {
            const container = document.getElementById('features');
            if (!container || !DATA.features) return;
            let html = '';
            DATA.features.forEach(function (f) {
                html += '<label class="flex items-center gap-2 cursor-pointer text-sm">' +
                    '<input type="checkbox" name="features[]" value="' + f.id + '" class="form-checkbox text-orange-500 rounded">' +
                    '<span>' + f.name + '</span></label>';
            });
            container.innerHTML = html;
        }

        function renderNearbyObjects() {
            const container = document.getElementById('nearby-objects');
            if (!container || !DATA.nearbyObjects) return;
            let html = '';
            DATA.nearbyObjects.forEach(function (o) {
                html += '<label class="flex items-center gap-2 cursor-pointer text-sm">' +
                    '<input type="checkbox" name="nearby_objects[]" value="' + o.id + '" class="form-checkbox text-orange-500 rounded">' +
                    '<span>' + o.name + '</span></label>';
            });
            container.innerHTML = html;
        }

        function initToggle(btnId, containerId) {
            const btn = document.getElementById(btnId);
            const container = document.getElementById(containerId);
            if (!btn || !container) return;

            const overlay = container.querySelector('.fade-overlay');
            container.classList.add('collapsed');

            btn.addEventListener('click', function () {
                if (container.classList.contains('collapsed')) {
                    container.classList.remove('collapsed');
                    container.classList.add('expanded');
                    if (overlay) overlay.classList.add('hidden');
                    btn.innerHTML = 'Show less <i class="bi bi-chevron-up"></i>';
                } else {
                    container.classList.remove('expanded');
                    container.classList.add('collapsed');
                    if (overlay) overlay.classList.remove('hidden');
                    btn.innerHTML = 'Show more <i class="bi bi-chevron-down"></i>';
                }
            });
        }

        function initSearch(inputId, containerId) {
            const input = document.getElementById(inputId);
            if (!input) return;

            input.addEventListener('keyup', function () {
                let q = this.value.toLowerCase().trim();
                const labels = document.querySelectorAll('#' + containerId + ' label');
                labels.forEach(function (l) {
                    const text = l.textContent.toLowerCase().trim();
                    l.style.display = text.indexOf(q) !== -1 ? '' : 'none';
                });
            });
        }

        /* ───────────────────── TERMS & SUBMIT ───────────────────── */

        function initTermsToggle() {
            if (!termsCheckbox || !submitBtn) return;

            termsCheckbox.addEventListener('change', function () {
                const checked = this.checked;
                submitBtn.disabled = !checked;
                submitBtn.classList.toggle('opacity-50', !checked);
                submitBtn.classList.toggle('cursor-not-allowed', !checked);
            });
        }

        /* ───────────────────── FORM SUBMISSION ───────────────────── */

        // Guard: prevent submit without terms (safety net)
        if (form) {
            form.addEventListener('submit', function (e) {
                if (!termsCheckbox || !termsCheckbox.checked) {
                    e.preventDefault();
                    alert('Please accept the privacy policy.');
                }
            });
        }

        /* ───────────────────── UNSAVED DATA MODAL ───────────────────── */

        function initUnsavedDataModal() {
            const modal = document.getElementById('unsavedDataModal');
            const modalYes = document.getElementById('modalYes');
            const modalNo = document.getElementById('modalNo');
            const storageKey = 'unsavedPropertyData';

            // Show modal after 1s if unsaved data exists
            setTimeout(function () {
                const saved = localStorage.getItem(storageKey);
                if (saved) {
                    try {
                        const parsed = JSON.parse(saved);
                        if (parsed && Object.keys(parsed).length > 0) {
                            showModal(modal);
                        }
                    } catch (_) { }
                }
            }, 1000);

            // Save form data on input change
            form.addEventListener('change', function () {
                saveFormData(storageKey);
            });
            form.addEventListener('input', function () {
                saveFormData(storageKey);
            });

            // Clear on successful submit
            form.addEventListener('submit', function () {
                localStorage.removeItem(storageKey);
            });

            if (modalYes) {
                modalYes.addEventListener('click', function () {
                    restoreFormData(storageKey);
                    hideModal(modal);
                });
            }
            if (modalNo) {
                modalNo.addEventListener('click', function () {
                    localStorage.removeItem(storageKey);
                    hideModal(modal);
                });
            }
        }

        function saveFormData(key) {
            if (!form) return;
            const data = {};
            const inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(function (el) {
                if (el.type === 'checkbox') {
                    data[el.name] = el.checked;
                } else if (el.type === 'radio') {
                    if (el.checked) data[el.name] = el.value;
                } else if (el.name) {
                    data[el.name] = el.value;
                }
            });
            localStorage.setItem(key, JSON.stringify(data));
        }

        function restoreFormData(key) {
            try {
                const saved = JSON.parse(localStorage.getItem(key));
                if (!saved) return;

                Object.keys(saved).forEach(function (name) {
                    const el = form.querySelector('[name="' + name + '"]');
                    if (!el) return;

                    if (el.type === 'checkbox') {
                        el.checked = saved[name] === true;
                    } else if (el.type === 'radio') {
                        const radio = form.querySelector('[name="' + name + '"][value="' + saved[name] + '"]');
                        if (radio) radio.checked = true;
                    } else {
                        el.value = saved[name];
                    }
                });
            } catch (_) { }
        }

        function showModal(modal) {
            if (!modal) return;
            modal.classList.remove('hidden');
        }

        function hideModal(modal) {
            if (!modal) return;
            modal.classList.add('hidden');
        }
    });
})();
