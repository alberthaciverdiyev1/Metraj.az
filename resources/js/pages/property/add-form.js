/* Yeni elan yerləşdirmə səhifəsi (add.blade.php-dən çıxarılıb) */
document.addEventListener('DOMContentLoaded', function () {
    const CONFIG = window.addFormConfig || {};
    const rates = CONFIG.rates || {};
    const amenityUrl = CONFIG.amenityUrl || '/add-property/amenities';
    const i18n = CONFIG.i18n || {};

    // 0) Initialize Quill Rich Text Editor
    const quill = new Quill('#editor_container', {
        theme: 'snow',
        placeholder: 'Məs: Mənzil yüksək zövqlə təmir olunub, bütün mebel və avadanlıqlar qalır...',
        modules: {
            toolbar: [
                [{ 'header': [2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'clean']
            ]
        }
    });

    const editorWrapper = document.getElementById('editor_wrapper');
    if (editorWrapper) {
        editorWrapper.addEventListener('click', function(e) {
            if (!e.target.closest('.ql-toolbar')) {
                quill.focus();
            }
        });
    }

    const propertyForm = document.getElementById('propertyForm');
    const descriptionHiddenInput = document.getElementById('description_input');

    function clearFormErrors() {
        if (!propertyForm) return;
        propertyForm.querySelectorAll('.field-error-msg').forEach(el => el.remove());
        propertyForm.querySelectorAll('.border-rose-500, .ring-rose-400, .ring-2, .ring-red-400, .border-red-400').forEach(el => {
            el.classList.remove('border-rose-500', 'ring-2', 'ring-rose-400', 'ring-red-400', 'border-red-400');
        });
        const banner = document.getElementById('form_ajax_error_banner');
        if (banner) banner.remove();
    }

    function renderFormErrors(errors, message) {
        clearFormErrors();

        const banner = document.createElement('div');
        banner.id = 'form_ajax_error_banner';
        banner.className = 'bg-rose-50 border border-rose-200 text-rose-800 px-6 py-4 rounded-2xl mb-8 shadow-sm transition-all';

        let errorListHtml = '';
        if (errors && Object.keys(errors).length > 0) {
            errorListHtml = '<ul class="list-disc list-inside text-xs space-y-1 mt-2 text-rose-700">';
            for (const [key, msgs] of Object.entries(errors)) {
                const txt = Array.isArray(msgs) ? msgs[0] : msgs;
                errorListHtml += `<li>${txt}</li>`;
            }
            errorListHtml += '</ul>';
        }

        banner.innerHTML = `
            <div class="flex items-center gap-2 font-semibold text-sm text-rose-800">
                <i class="bi bi-exclamation-triangle-fill text-rose-600 text-base"></i>
                <span>${message || (i18n.fix_errors || 'Zəhmət olmasa formdakı xətaları düzəldin:')}</span>
            </div>
            ${errorListHtml}
        `;

        propertyForm.prepend(banner);

        let firstInvalidElement = null;

        if (errors && Object.keys(errors).length > 0) {
            for (const [key, msgs] of Object.entries(errors)) {
                const errorText = Array.isArray(msgs) ? msgs[0] : msgs;
                const cleanKey = key.split('.')[0];

                let targetEl = propertyForm.querySelector(`[name="${key}"], [name="${key}[]"], #${key}`);
                if (!targetEl && cleanKey !== key) {
                    targetEl = propertyForm.querySelector(`[name="${cleanKey}"], [name="${cleanKey}[]"], #${cleanKey}`);
                }

                if (!targetEl) {
                    if (cleanKey === 'price' || cleanKey === 'price_gbp') {
                        targetEl = document.getElementById('main_price_input') || document.getElementById('price_gbp');
                    } else if (cleanKey === 'photos') {
                        targetEl = document.getElementById('dropzone_box');
                    } else if (cleanKey === 'video') {
                        targetEl = document.getElementById('video_dropzone_box');
                    } else if (cleanKey === 'description') {
                        targetEl = document.getElementById('editor_wrapper') || document.getElementById('editor_container');
                    } else if (cleanKey === 'property_type_id') {
                        targetEl = document.getElementById('property_type_id') || document.querySelector('input[name="property_type_id"]')?.closest('.grid') || document.querySelector('input[name="property_type_id"]')?.closest('div');
                    } else if (cleanKey === 'deal_type_id') {
                        targetEl = document.getElementById('deal_type_id') || document.querySelector('input[name="deal_type_id"]')?.closest('.grid') || document.querySelector('input[name="deal_type_id"]')?.closest('div');
                    } else if (cleanKey === 'advertiser') {
                        targetEl = document.querySelector('input[name="advertiser"]')?.closest('.grid') || document.querySelector('input[name="advertiser"]')?.closest('div');
                    } else if (cleanKey === 'amenities') {
                        targetEl = document.getElementById('amenities_grid');
                    }
                }

                if (targetEl) {
                    if (!firstInvalidElement) {
                        firstInvalidElement = targetEl;
                    }

                    if (targetEl.tagName === 'INPUT' || targetEl.tagName === 'SELECT' || targetEl.tagName === 'TEXTAREA') {
                        targetEl.classList.add('border-rose-500', 'ring-2', 'ring-rose-400');
                    } else {
                        targetEl.classList.add('border-rose-500', 'ring-2', 'ring-rose-400');
                    }

                    const errP = document.createElement('p');
                    errP.className = 'field-error-msg text-xs text-rose-600 font-medium mt-1.5 flex items-center gap-1.5';
                    errP.innerHTML = `<i class="bi bi-exclamation-circle-fill text-rose-500 text-xs"></i> <span>${errorText}</span>`;

                    const parentBox = targetEl.closest('.relative') || targetEl.parentElement;
                    if (parentBox && !parentBox.querySelector('.field-error-msg')) {
                        parentBox.appendChild(errP);
                    } else if (!targetEl.querySelector('.field-error-msg')) {
                        targetEl.insertAdjacentElement('afterend', errP);
                    }
                }
            }
        }

        const scrollTarget = banner || firstInvalidElement;
        if (scrollTarget) {
            scrollTarget.scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (firstInvalidElement && typeof firstInvalidElement.focus === 'function' && firstInvalidElement.tagName !== 'DIV') {
                firstInvalidElement.focus();
            }
        }

        window.KibrisKare.toast(message || 'Zəhmət olmasa xətaları düzəldin', 'error');
    }

    if (propertyForm) {
        const submitBtn = propertyForm.querySelector('button[type="submit"]');

        propertyForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            // Quill məzmununu gizli input-a yaz
            if (descriptionHiddenInput) {
                if (quill.getText().trim().length === 0) {
                    descriptionHiddenInput.value = '';
                } else {
                    descriptionHiddenInput.value = quill.root.innerHTML;
                }
            }

            // Yükləmə halında düyməni deaktiv et
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.6';
                if (!submitBtn.dataset.originalHtml) {
                    submitBtn.dataset.originalHtml = submitBtn.innerHTML;
                }
                submitBtn.innerHTML = '<span class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span> <span>' + (i18n.loading || 'Yüklənir...') + '</span>';
            }

            try {
                const { ok, status, data } = await window.KibrisKare.post(
                    propertyForm.action,
                    new FormData(propertyForm)
                );

                if (ok) {
                    clearFormErrors();
                    window.KibrisKare.toast(data.message || 'Elanınız uğurla qəbul edildi ✅', 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect || '/';
                    }, 1500);
                } else {
                    if (status === 422 && data && data.errors) {
                        renderFormErrors(data.errors, data.message);
                    } else {
                        renderFormErrors({}, data?.message || 'Server xətası baş verdi (' + status + '). Zəhmət olmasa formu yoxlayın.');
                    }
                }
            } catch (err) {
                console.error('Add Property Submit Error:', err);
                renderFormErrors({}, 'Şəbəkə və ya server xətası baş verdi. Zəhmət olmasa yenidən cəhd edin.');
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '1';
                    submitBtn.innerHTML = submitBtn.dataset.originalHtml || (i18n.submit || 'Elanı Yerləşdir');
                }
            }
        });
    }

    // 1) Rates & Multi-Currency Converter
    const currencySymbols = {
        'GBP': '£',
        'AZN': '₼',
        'USD': '$',
        'EUR': '€',
        'TRY': '₺',
        'RUB': '₽',
        'AED': 'د.إ'
    };
    const autoConvertToggle = document.getElementById('auto_convert_toggle');
    const mainCurrencySelect = document.getElementById('main_currency');
    const mainCurrencySymbol = document.getElementById('main_currency_symbol');
    const mainPriceInput = document.getElementById('main_price_input');
    const priceGbpInput = document.getElementById('price_gbp');

    function calculateCurrencies() {
        const cur = mainCurrencySelect ? mainCurrencySelect.value : 'GBP';
        const symbol = currencySymbols[cur] || cur;
        if (mainCurrencySymbol) mainCurrencySymbol.textContent = symbol;

        const val = parseFloat(mainPriceInput ? mainPriceInput.value : 0) || 0;
        if (val <= 0) return;

        // Calculate base GBP from selected currency
        const fromRate = rates[cur] || 1.0;
        const gbp = cur === 'GBP' ? val : (fromRate > 0 ? (val / fromRate) : val);
        if (priceGbpInput) priceGbpInput.value = gbp >= 1000 ? Math.round(gbp) : gbp.toFixed(2);

        if (!autoConvertToggle || !autoConvertToggle.checked) return;

        // Calculate all 7 currencies
        for (const [targetCur, rate] of Object.entries(rates)) {
            const inputId = targetCur === 'GBP' ? 'price_gbp_val' : ('price_' + targetCur.toLowerCase());
            const targetInput = document.getElementById(inputId);
            if (targetInput) {
                if (targetCur === cur) {
                    targetInput.value = val;
                } else {
                    const converted = gbp * rate;
                    targetInput.value = converted >= 1000 ? Math.round(converted) : converted.toFixed(2);
                }
            }
        }
    }

    function toggleCurrencyInputs() {
        const currencyInputs = document.querySelectorAll('.currency-converted-input');
        if (autoConvertToggle && autoConvertToggle.checked) {
            currencyInputs.forEach(input => {
                input.readOnly = true;
                input.classList.add('bg-gray-100/90', 'text-gray-500', 'cursor-not-allowed');
                input.classList.remove('bg-white', 'text-gray-800', 'cursor-text');
            });
            calculateCurrencies();
        } else {
            currencyInputs.forEach(input => {
                input.readOnly = false;
                input.classList.remove('bg-gray-100/90', 'text-gray-500', 'cursor-not-allowed');
                input.classList.add('bg-white', 'text-gray-800', 'cursor-text');
            });
        }
    }

    if (mainPriceInput) mainPriceInput.addEventListener('input', calculateCurrencies);
    if (mainCurrencySelect) mainCurrencySelect.addEventListener('change', calculateCurrencies);
    if (autoConvertToggle) autoConvertToggle.addEventListener('change', toggleCurrencyInputs);
    toggleCurrencyInputs();

    // 2) Torpaq (Land) Dynamic Conditional Visibility
    const wrapperArea = document.getElementById('wrapper_area');
    const wrapperLandArea = document.getElementById('wrapper_land_area');
    const wrapperRooms = document.getElementById('wrapper_rooms');
    const wrapperFloor = document.getElementById('wrapper_floor');
    const wrapperTotalFloors = document.getElementById('wrapper_total_floors');
    const sectionFeatures = document.getElementById('section_features');
    const sectionAmenities = document.getElementById('section_amenities');

    function checkLand() {
        let isLand = false;
        const propTypeSelect = document.getElementById('property_type_id');
        if (propTypeSelect && propTypeSelect.tagName === 'SELECT') {
            const selectedText = propTypeSelect.options[propTypeSelect.selectedIndex]?.text?.toLowerCase() || '';
            isLand = selectedText.includes('torpaq');
        } else {
            const checkedRadio = document.querySelector('input[name="property_type_id"]:checked');
            if (checkedRadio) {
                const labelText = checkedRadio.closest('label')?.innerText?.toLowerCase() || '';
                isLand = labelText.includes('torpaq');
            }
        }

        if (isLand) {
            wrapperArea?.classList.add('hidden');
            wrapperLandArea?.classList.remove('hidden');
            wrapperRooms?.classList.add('hidden');
            wrapperFloor?.classList.add('hidden');
            wrapperTotalFloors?.classList.add('hidden');
            sectionFeatures?.classList.add('hidden');
            sectionAmenities?.classList.add('hidden');
        } else {
            wrapperArea?.classList.remove('hidden');
            wrapperLandArea?.classList.add('hidden');
            wrapperRooms?.classList.remove('hidden');
            wrapperFloor?.classList.remove('hidden');
            wrapperTotalFloors?.classList.remove('hidden');
            sectionFeatures?.classList.remove('hidden');
            sectionAmenities?.classList.remove('hidden');
        }
    }

    const propTypeEl = document.getElementById('property_type_id') || document.querySelectorAll('input[name="property_type_id"]');
    if (propTypeEl instanceof NodeList) {
        propTypeEl.forEach(r => r.addEventListener('change', checkLand));
    } else if (propTypeEl) {
        propTypeEl.addEventListener('change', checkLand);
    }
    checkLand();

    // 2.2) Toggle "Sənəd və Kredit Şərtləri" based on Deal Type (Hide when Rent / Kirayə)
    const sectionDocsCredit = document.getElementById('section_documents_credit');

    function checkDealType() {
        const dealSelect = document.getElementById('deal_type_id');
        let isRent = false;

        if (dealSelect && dealSelect.tagName === 'SELECT') {
            const selectedText = dealSelect.options[dealSelect.selectedIndex]?.text?.toLowerCase() || '';
            isRent = selectedText.includes('kirayə') || selectedText.includes('kira') || selectedText.includes('rent');
        } else {
            const checkedRadio = document.querySelector('input[name="deal_type_id"]:checked');
            if (checkedRadio) {
                const labelText = checkedRadio.closest('label')?.innerText?.toLowerCase() || '';
                isRent = labelText.includes('kirayə') || labelText.includes('kira') || labelText.includes('rent');
            }
        }

        if (isRent) {
            sectionDocsCredit?.classList.add('hidden');
            // Uncheck the checkboxes when hidden so they are not accidentally submitted
            const checkboxes = sectionDocsCredit?.querySelectorAll('input[type="checkbox"]');
            checkboxes?.forEach(cb => cb.checked = false);
        } else {
            sectionDocsCredit?.classList.remove('hidden');
        }
    }

    const dealTypeEl = document.getElementById('deal_type_id') || document.querySelectorAll('input[name="deal_type_id"]');
    if (dealTypeEl instanceof NodeList) {
        dealTypeEl.forEach(r => r.addEventListener('change', checkDealType));
    } else if (dealTypeEl) {
        dealTypeEl.addEventListener('change', checkDealType);
    }
    checkDealType();

    // 3) City & District dynamic filter options with Strict Map Boundary Restriction
    const citySelect = document.getElementById('city_id');
    const districtSelect = document.getElementById('district_id');

    let currentAllowedBounds = null;
    let currentBoundaryLayer = null;
    let currentRegionName = '';
    let lastValidLat = parseFloat(document.getElementById('latitude').value) || 35.3382;
    let lastValidLng = parseFloat(document.getElementById('longitude').value) || 33.3186;
    let noticeTimeout = null;

    function showBoundaryAlert(msg) {
        const notice = document.getElementById('map_boundary_notice');
        const noticeMsg = document.getElementById('map_boundary_msg');
        if (!notice || !noticeMsg) return;
        noticeMsg.textContent = msg;
        notice.classList.remove('hidden');
        clearTimeout(noticeTimeout);
        noticeTimeout = setTimeout(() => {
            notice.classList.add('hidden');
        }, 3500);
    }

    function updateMapBoundary(query, label) {
        currentRegionName = label;
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query + ', Cyprus')}&polygon_geojson=1&limit=1&accept-language=tr,en,az`)
            .then(res => res.json())
            .then(data => {
                if (data && data.length > 0) {
                    const item = data[0];
                    const bbox = item.boundingbox;
                    if (bbox && bbox.length === 4) {
                        const latMin = parseFloat(bbox[0]);
                        const latMax = parseFloat(bbox[1]);
                        const lonMin = parseFloat(bbox[2]);
                        const lonMax = parseFloat(bbox[3]);

                        currentAllowedBounds = L.latLngBounds([[latMin, lonMin], [latMax, lonMax]]);

                        // Remove previous boundary overlay
                        if (currentBoundaryLayer) {
                            map.removeLayer(currentBoundaryLayer);
                            currentBoundaryLayer = null;
                        }

                        // Render boundary polygon/box
                        if (item.geojson && (item.geojson.type === 'Polygon' || item.geojson.type === 'MultiPolygon')) {
                            currentBoundaryLayer = L.geoJSON(item.geojson, {
                                style: {
                                    color: '#ea580c',
                                    weight: 2,
                                    dashArray: '6, 6',
                                    fillOpacity: 0.06,
                                    fillColor: '#ea580c'
                                }
                            }).addTo(map);
                        } else {
                            currentBoundaryLayer = L.rectangle(currentAllowedBounds, {
                                color: '#ea580c',
                                weight: 2,
                                dashArray: '6, 6',
                                fillOpacity: 0.06,
                                fillColor: '#ea580c'
                            }).addTo(map);
                        }

                        // Strictly restrict panning and fit to bounds
                        map.setMaxBounds(currentAllowedBounds.pad(0.12));
                        map.options.maxBoundsViscosity = 1.0;
                        map.fitBounds(currentAllowedBounds, { padding: [25, 25] });

                        // Center marker inside the chosen territory
                        const centerLat = parseFloat(item.lat);
                        const centerLng = parseFloat(item.lon);
                        marker.setLatLng([centerLat, centerLng]);
                        lastValidLat = centerLat;
                        lastValidLng = centerLng;
                        updateCoords(centerLat, centerLng);
                        reverseGeocode(centerLat, centerLng);
                    }
                }
            })
            .catch(() => {});
    }

    if (citySelect) {
        citySelect.addEventListener('change', function() {
            if (districtSelect) {
                districtSelect.innerHTML = '<option value="">Rayon seçin...</option>';
                const selectedOpt = citySelect.options[citySelect.selectedIndex];
                const districtsData = selectedOpt ? selectedOpt.getAttribute('data-districts') : null;

                if (districtsData) {
                    try {
                        const districts = JSON.parse(districtsData);
                        districts.forEach(d => {
                            const opt = document.createElement('option');
                            opt.value = d.id;
                            opt.text = d.name?.tr || d.name?.az || d.slug;
                            districtSelect.appendChild(opt);
                        });
                    } catch(e) {}
                }

                const cityName = selectedOpt ? selectedOpt.text.trim() : '';
                if (cityName && cityName !== 'Şəhər seçin...') {
                    updateMapBoundary(cityName, cityName);
                }
            }
        });
    }

    if (districtSelect) {
        districtSelect.addEventListener('change', function() {
            const districtName = districtSelect.options[districtSelect.selectedIndex]?.text?.trim();
            const cityName = citySelect ? citySelect.options[citySelect.selectedIndex]?.text?.trim() || 'Girne' : 'Girne';
            if (districtName && districtName !== 'Rayon seçin...') {
                updateMapBoundary(districtName + ', ' + cityName, districtName);
            } else if (cityName && cityName !== 'Şəhər seçin...') {
                updateMapBoundary(cityName, cityName);
            }
        });
    }

    // 4) Modern OpenStreetMap with 2-Way Geocoding
    let lat = lastValidLat;
    let lng = lastValidLng;

    const map = L.map('add_property_map', {
        zoomControl: false,
        attributionControl: false
    }).setView([lat, lng], 14);

    L.control.zoom({ position: 'bottomright' }).addTo(map);

    const cartoLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    const satLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19
    });

    let currentLayer = 'carto';
    window.switchMapLayer = function(type) {
        if (type === 'satellite' && currentLayer !== 'satellite') {
            map.removeLayer(cartoLayer);
            satLayer.addTo(map);
            currentLayer = 'satellite';
            document.getElementById('btn_map_sat').className = 'px-2.5 py-1 rounded-lg bg-orange-500 text-white shadow-sm transition';
            document.getElementById('btn_map_carto').className = 'px-2.5 py-1 rounded-lg bg-transparent text-gray-700 hover:bg-gray-100 transition';
        } else if (type === 'carto' && currentLayer !== 'carto') {
            map.removeLayer(satLayer);
            cartoLayer.addTo(map);
            currentLayer = 'carto';
            document.getElementById('btn_map_carto').className = 'px-2.5 py-1 rounded-lg bg-orange-500 text-white shadow-sm transition';
            document.getElementById('btn_map_sat').className = 'px-2.5 py-1 rounded-lg bg-transparent text-gray-700 hover:bg-gray-100 transition';
        }
    };

    const pulseIcon = L.divIcon({
        className: 'custom-pulse-marker',
        html: `
            <div style="position: relative; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                <div style="position: absolute; width: 36px; height: 36px; border-radius: 50%; background: rgba(249, 115, 22, 0.28); animation: leaflet-pulse 2s infinite ease-in-out;"></div>
                <div style="width: 28px; height: 28px; border-radius: 50%; background: #ea580c; border: 3px solid #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; color: white;">
                    <svg style="width: 14px; height: 14px;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                </div>
            </div>
        `,
        iconSize: [40, 40],
        iconAnchor: [20, 36]
    });

    const marker = L.marker([lat, lng], {
        icon: pulseIcon,
        draggable: true
    }).addTo(map);

    function updateCoords(newLat, newLng) {
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        if (latInput) latInput.value = newLat.toFixed(6);
        if (lngInput) lngInput.value = newLng.toFixed(6);
    }

    function reverseGeocode(newLat, newLng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${newLat}&lon=${newLng}&accept-language=az,en`)
            .then(res => res.json())
            .then(data => {
                if (data && data.display_name) {
                    const addressField = document.getElementById('address');
                    if (addressField && (!addressField.value || addressField.value.length < 5)) {
                        addressField.value = data.display_name.split(',').slice(0, 3).join(',');
                    }
                }
            })
            .catch(() => {});
    }

    marker.on('dragend', function(e) {
        const pos = e.target.getLatLng();
        if (currentAllowedBounds && !currentAllowedBounds.contains(pos)) {
            showBoundaryAlert(`Xahiş olunur yalnız seçilmiş ${currentRegionName || 'ərazi'} daxilində yer seçin.`);
            marker.setLatLng([lastValidLat, lastValidLng]);
            return;
        }
        lastValidLat = pos.lat;
        lastValidLng = pos.lng;
        updateCoords(pos.lat, pos.lng);
        reverseGeocode(pos.lat, pos.lng);
    });

    map.on('click', function(e) {
        if (currentAllowedBounds && !currentAllowedBounds.contains(e.latlng)) {
            showBoundaryAlert(`Xahiş olunur yalnız seçilmiş ${currentRegionName || 'ərazi'} daxilində yer seçin.`);
            return;
        }
        marker.setLatLng(e.latlng);
        lastValidLat = e.latlng.lat;
        lastValidLng = e.latlng.lng;
        updateCoords(e.latlng.lat, e.latlng.lng);
        reverseGeocode(e.latlng.lat, e.latlng.lng);
    });

    let searchTimeout = null;
    const addressInput = document.getElementById('address');
    if (addressInput) {
        addressInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            if (query.length < 4) return;

            searchTimeout = setTimeout(() => {
                const cityName = citySelect ? citySelect.options[citySelect.selectedIndex]?.text?.trim() || 'Girne' : 'Girne';
                const fullQuery = (query.includes('Cyprus') || query.includes('Kıbrıs')) ? query : `${query}, ${cityName}, Cyprus`;

                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(fullQuery)}&limit=1&accept-language=tr,en,az`)
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            const newLat = parseFloat(data[0].lat);
                            const newLng = parseFloat(data[0].lon);
                            const newPos = L.latLng(newLat, newLng);

                            if (currentAllowedBounds && !currentAllowedBounds.contains(newPos)) {
                                showBoundaryAlert(`Axtarılan ünvan seçilmiş ${currentRegionName || 'ərazi'} hüdudlarından kənardadır.`);
                                return;
                            }

                            marker.setLatLng(newPos);
                            lastValidLat = newLat;
                            lastValidLng = newLng;
                            map.flyTo(newPos, 16, { duration: 1.2 });
                            updateCoords(newLat, newLng);
                        }
                    })
                    .catch(() => {});
            }, 700);
        });
    }

    // 5) Multi-Photo Upload Preview
    const dropzoneBox = document.getElementById('dropzone_box');
    const photosInput = document.getElementById('photos_input');
    const previewGrid = document.getElementById('photos_preview_grid');

    if (dropzoneBox && photosInput && previewGrid) {
        dropzoneBox.addEventListener('click', () => photosInput.click());

        dropzoneBox.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzoneBox.classList.add('border-orange-500', 'bg-orange-50/40');
        });

        dropzoneBox.addEventListener('dragleave', () => {
            dropzoneBox.classList.remove('border-orange-500', 'bg-orange-50/40');
        });

        dropzoneBox.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzoneBox.classList.remove('border-orange-500', 'bg-orange-50/40');
            if (e.dataTransfer.files.length > 0) {
                photosInput.files = e.dataTransfer.files;
                renderPhotosPreview();
            }
        });

        photosInput.addEventListener('change', renderPhotosPreview);

        function renderPhotosPreview() {
            previewGrid.innerHTML = '';
            const files = Array.from(photosInput.files);
            files.forEach((file, index) => {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = function(e) {
                    const card = document.createElement('div');
                    card.className = 'relative aspect-square rounded-xl overflow-hidden border border-gray-200 shadow-sm bg-gray-100 group';
                    card.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        ${index === 0 ? '<span class="absolute top-1 left-1 px-1.5 py-0.5 rounded bg-orange-500 text-white text-[9px] font-semibold shadow">Əsas</span>' : ''}
                    `;
                    previewGrid.appendChild(card);
                };
                reader.readAsDataURL(file);
            });
        }
    }

    // 6) Video Upload Preview & Handling
    const videoDropzoneBox = document.getElementById('video_dropzone_box');
    const videoInput = document.getElementById('video_input');
    const videoPreviewContainer = document.getElementById('video_preview_container');
    const videoPreviewName = document.getElementById('video_preview_name');
    const videoPreviewSize = document.getElementById('video_preview_size');
    const btnRemoveVideo = document.getElementById('btn_remove_video');

    if (videoDropzoneBox && videoInput) {
        videoDropzoneBox.addEventListener('click', (e) => {
            if (e.target.closest('#btn_remove_video')) return;
            videoInput.click();
        });

        videoDropzoneBox.addEventListener('dragover', (e) => {
            e.preventDefault();
            videoDropzoneBox.classList.add('border-orange-500', 'bg-orange-50/40');
        });

        videoDropzoneBox.addEventListener('dragleave', () => {
            videoDropzoneBox.classList.remove('border-orange-500', 'bg-orange-50/40');
        });

        videoDropzoneBox.addEventListener('drop', (e) => {
            e.preventDefault();
            videoDropzoneBox.classList.remove('border-orange-500', 'bg-orange-50/40');
            if (e.dataTransfer.files.length > 0) {
                const file = e.dataTransfer.files[0];
                if (file.type.startsWith('video/')) {
                    videoInput.files = e.dataTransfer.files;
                    renderVideoPreview(file);
                } else {
                    window.KibrisKare.toast('Zəhmət olmasa düzgün video formatı seçin (MP4, WebM, MOV)', 'error');
                }
            }
        });

        videoInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                if (file.size > 52428800) { // 50MB
                    window.KibrisKare.toast('Video həcmi maksimum 50MB ola bilər', 'error');
                    this.value = '';
                    if (videoPreviewContainer) videoPreviewContainer.classList.add('hidden');
                    return;
                }
                renderVideoPreview(file);
            }
        });

        if (btnRemoveVideo) {
            btnRemoveVideo.addEventListener('click', (e) => {
                e.stopPropagation();
                videoInput.value = '';
                if (videoPreviewContainer) videoPreviewContainer.classList.add('hidden');
            });
        }

        function renderVideoPreview(file) {
            if (!videoPreviewContainer || !videoPreviewName) return;
            videoPreviewName.textContent = file.name;
            const sizeInMb = (file.size / (1024 * 1024)).toFixed(2);
            if (videoPreviewSize) videoPreviewSize.textContent = sizeInMb + ' MB';
            videoPreviewContainer.classList.remove('hidden');
        }
    }

    // Amenities Load More
    const loadMoreAmenitiesBtn = document.getElementById('load_more_amenities_btn');
    const amenitiesGrid = document.getElementById('amenities_grid');
    const loadMoreWrapper = document.getElementById('load_more_amenities_wrapper');

    if (loadMoreAmenitiesBtn && amenitiesGrid) {
        loadMoreAmenitiesBtn.addEventListener('click', async function () {
            const nextPage = parseInt(this.dataset.nextPage, 10) || 2;
            const originalHtml = this.innerHTML;

            this.disabled = true;
            this.innerHTML = '<span class="inline-block w-3.5 h-3.5 border-2 border-orange-500 border-t-transparent rounded-full animate-spin"></span> <span>' + (i18n.loading || 'Yüklənir...') + '</span>';

            try {
                const res = await fetch(amenityUrl + '?page=' + nextPage, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (res.ok) {
                    const data = await res.json();
                    const items = data.data || [];

                    items.forEach(amenity => {
                        if (!amenitiesGrid.querySelector('input[value="' + amenity.id + '"]')) {
                            const label = document.createElement('label');
                            label.className = 'flex items-center gap-2 p-2.5 bg-gray-50/70 border border-gray-100 rounded-xl cursor-pointer hover:border-orange-200 transition';

                            const name = amenity.localized_name
                                || (typeof amenity.name === 'object' && amenity.name !== null
                                    ? (amenity.name[window.currentLocale || 'tr'] || amenity.name.az || Object.values(amenity.name)[0] || '')
                                    : (amenity.name || ''));

                            label.innerHTML = `
                                <input type="checkbox" name="amenities[]" value="${amenity.id}"
                                    class="w-4 h-4 text-orange-500 rounded border-gray-300 focus:ring-orange-500">
                                <span class="text-xs font-medium text-gray-800">${name}</span>
                            `;
                            amenitiesGrid.appendChild(label);
                        }
                    });

                    if (data.has_more) {
                        loadMoreAmenitiesBtn.dataset.nextPage = nextPage + 1;
                        loadMoreAmenitiesBtn.disabled = false;
                        loadMoreAmenitiesBtn.innerHTML = originalHtml;
                    } else {
                        if (loadMoreWrapper) {
                            loadMoreWrapper.remove();
                        }
                    }
                } else {
                    loadMoreAmenitiesBtn.disabled = false;
                    loadMoreAmenitiesBtn.innerHTML = originalHtml;
                }
            } catch (err) {
                console.error(err);
                loadMoreAmenitiesBtn.disabled = false;
                loadMoreAmenitiesBtn.innerHTML = originalHtml;
            }
        });
    }
});
