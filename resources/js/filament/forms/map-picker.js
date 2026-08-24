/* Filament OSM Map Picker — Alpine komponenti (filament/forms/components/map-picker.blade.php-dən çıxarılıb) */
(function() {
    function registerOsmMapPicker() {
        if (typeof window.Alpine === 'undefined') return;

        window.Alpine.data('osmMapPicker', (wire, locationsData = {}) => ({
            latitude: wire.entangle('data.latitude'),
            longitude: wire.entangle('data.longitude'),
            address: wire.entangle('data.address'),
            cityId: wire.entangle('data.city_id'),
            districtId: wire.entangle('data.district_id'),
            locations: locationsData,
            activeLayer: 'voyager',
            map: null,
            marker: null,
            tileLayer: null,
            isUpdatingFromMap: false,
            searchTimer: null,

            layers: {
                voyager: {
                    url: 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png',
                    attribution: '&copy; CartoDB &copy; OpenStreetMap',
                    maxZoom: 20
                },
                satellite: {
                    url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
                    attribution: '&copy; Esri World Imagery',
                    maxZoom: 19
                },
                light: {
                    url: 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png',
                    attribution: '&copy; CartoDB &copy; OpenStreetMap',
                    maxZoom: 20
                }
            },

            knownCoords: {
                'baku': [40.409264, 49.867092, 13],
                'sumqayit': [40.589722, 49.668611, 13],
                'ganja': [40.682778, 46.360556, 13],
                'yasamal': [40.380000, 49.810000, 15],
                'nasimi': [40.400000, 49.840000, 15],
                'narimanov': [40.415000, 49.870000, 15],
                'xatai': [40.385000, 49.880000, 15],
            },

            init() {
                this.$nextTick(() => {
                    this.initLeaflet();
                });
            },

            initLeaflet() {
                if (typeof window.L !== 'undefined') {
                    this.setupMap();
                } else {
                    const checkInterval = setInterval(() => {
                        if (typeof window.L !== 'undefined') {
                            clearInterval(checkInterval);
                            this.setupMap();
                        }
                    }, 100);
                }
            },

            setupMap() {
                const container = this.$refs.mapDiv;
                if (!container || !window.L) return;

                if (this.map) {
                    this.map.remove();
                    this.map = null;
                }

                const defaultLat = this.latitude ? parseFloat(this.latitude) : 40.409264;
                const defaultLng = this.longitude ? parseFloat(this.longitude) : 49.867092;
                const defaultZoom = (this.latitude && this.longitude) ? 16 : 13;

                this.map = window.L.map(container, {
                    center: [defaultLat, defaultLng],
                    zoom: defaultZoom,
                    zoomControl: false,
                });

                // Add zoom control at bottom-right for clean uncluttered layout
                window.L.control.zoom({ position: 'topleft' }).addTo(this.map);

                // Set initial tile layer
                this.tileLayer = window.L.tileLayer(this.layers.voyager.url, {
                    maxZoom: this.layers.voyager.maxZoom,
                    attribution: this.layers.voyager.attribution,
                }).addTo(this.map);

                // Create custom modern Metraj Pin
                const customIcon = window.L.divIcon({
                    className: 'custom-metraj-marker',
                    html: `
                        <div class="metraj-pin-container">
                            <div class="metraj-pin-ripple"></div>
                            <div class="metraj-pin-bubble">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="metraj-pin-icon">
                                    <path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z" />
                                    <path d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.432z" />
                                </svg>
                            </div>
                        </div>
                    `,
                    iconSize: [44, 44],
                    iconAnchor: [22, 44],
                    popupAnchor: [0, -42],
                });

                this.marker = window.L.marker([defaultLat, defaultLng], {
                    icon: customIcon,
                    draggable: true,
                }).addTo(this.map);

                if (this.address) {
                    this.showPopup(this.address);
                }

                this.marker.on('dragend', (e) => {
                    const pos = e.target.getLatLng();
                    this.updatePosition(pos.lat, pos.lng, true);
                });

                this.map.on('click', (e) => {
                    this.marker.setLatLng(e.latlng);
                    this.updatePosition(e.latlng.lat, e.latlng.lng, true);
                });

                // Watch typing into 'Dəqiq Ünvan' field directly
                this.$watch('address', (newAddress) => {
                    if (this.isUpdatingFromMap) {
                        this.isUpdatingFromMap = false;
                        return;
                    }

                    if (!newAddress || newAddress.trim().length < 3) return;

                    clearTimeout(this.searchTimer);
                    this.searchTimer = setTimeout(() => {
                        this.searchAddressOnMap(newAddress);
                    }, 500);
                });

                // Watch City selection changes
                this.$watch('cityId', (newCityId) => {
                    if (!newCityId || !this.locations[newCityId]) return;
                    const city = this.locations[newCityId];
                    const slug = (city.slug || '').toLowerCase();

                    if (this.knownCoords[slug]) {
                        const [lat, lng, zoom] = this.knownCoords[slug];
                        this.flyToCoordinates(lat, lng, zoom, city.name);
                    } else {
                        this.goToLocation(city.name + ', Azerbaijan', 13);
                    }
                });

                // Watch District selection changes
                this.$watch('districtId', (newDistrictId) => {
                    if (!newDistrictId) return;
                    const city = this.locations[this.cityId];
                    if (city && city.districts && city.districts[newDistrictId]) {
                        const dist = city.districts[newDistrictId];
                        const slug = (dist.slug || '').toLowerCase();

                        if (this.knownCoords[slug]) {
                            const [lat, lng, zoom] = this.knownCoords[slug];
                            this.flyToCoordinates(lat, lng, zoom, dist.name + ', ' + city.name);
                        } else {
                            this.goToLocation(dist.name + ', ' + city.name + ', Azerbaijan', 15);
                        }
                    }
                });

                setTimeout(() => {
                    if (this.map) {
                        this.map.invalidateSize();
                    }
                }, 300);
            },

            setLayer(layerName) {
                if (!this.layers[layerName] || !this.map) return;
                this.activeLayer = layerName;

                if (this.tileLayer) {
                    this.map.removeLayer(this.tileLayer);
                }

                this.tileLayer = window.L.tileLayer(this.layers[layerName].url, {
                    maxZoom: this.layers[layerName].maxZoom,
                    attribution: this.layers[layerName].attribution,
                }).addTo(this.map);
            },

            locateMe() {
                if (!navigator.geolocation) {
                    alert('Brauzeriniz geolokasiya dəstəkləmir.');
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;
                        this.flyToCoordinates(lat, lng, 16, 'Cari Məkanınız');
                        this.updatePosition(lat, lng, true);
                    },
                    (err) => {
                        alert('Məkanınızı müəyyən etmək mümkün olmadı: ' + err.message);
                    },
                    { enableHighAccuracy: true, timeout: 8000 }
                );
            },

            showPopup(text) {
                if (!this.marker) return;
                this.marker.bindPopup(`
                    <div class="popup-bubble-inner">
                        <div class="popup-bubble-title">Seçilmiş Ünvan</div>
                        <div>${text}</div>
                    </div>
                `).openPopup();
            },

            flyToCoordinates(lat, lng, zoom, label) {
                if (!this.map) return;
                this.map.flyTo([lat, lng], zoom, { duration: 1.2, easeLinearity: 0.25 });
                if (this.marker) {
                    this.marker.setLatLng([lat, lng]);
                    if (label) {
                        this.showPopup(label);
                    }
                }
                this.updatePosition(lat, lng, true);
            },

            goToLocation(query, zoom = 14) {
                if (!query) return;
                const url = 'https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query) + '&limit=1&accept-language=az,ru,en';

                fetch(url, { headers: { 'Accept-Language': 'az, en' } })
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            const lat = parseFloat(data[0].lat);
                            const lng = parseFloat(data[0].lon);
                            this.flyToCoordinates(lat, lng, zoom, data[0].display_name);
                        }
                    })
                    .catch(err => console.error('Location change geocode error:', err));
            },

            searchAddressOnMap(query) {
                if (!query || !query.trim()) return;

                const url = 'https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query.trim()) + '&limit=1&accept-language=az,ru,en';

                fetch(url, { headers: { 'Accept-Language': 'az, en' } })
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            const lat = parseFloat(data[0].lat);
                            const lng = parseFloat(data[0].lon);

                            if (this.map) {
                                this.map.flyTo([lat, lng], 16, { duration: 1.2 });
                            }
                            if (this.marker) {
                                this.marker.setLatLng([lat, lng]);
                                this.showPopup(data[0].display_name);
                            }

                            this.latitude = lat.toFixed(6);
                            this.longitude = lng.toFixed(6);
                        }
                    })
                    .catch(err => console.error('Address search error:', err));
            },

            updatePosition(lat, lng, fetchAddress = true) {
                const formattedLat = parseFloat(lat).toFixed(6);
                const formattedLng = parseFloat(lng).toFixed(6);

                this.latitude = formattedLat;
                this.longitude = formattedLng;

                if (fetchAddress) {
                    this.reverseGeocode(formattedLat, formattedLng);
                }
            },

            reverseGeocode(lat, lng) {
                const url = 'https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&zoom=18&addressdetails=1&accept-language=az,ru,en';

                fetch(url, { headers: { 'Accept-Language': 'az, en' } })
                    .then(res => res.json())
                    .then(data => {
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

                            this.isUpdatingFromMap = true;
                            this.address = resolved;

                            this.showPopup(resolved);
                        }
                    })
                    .catch(err => console.error('Reverse geocode error:', err));
            }
        }));
    }

    if (window.Alpine) {
        registerOsmMapPicker();
    } else {
        document.addEventListener('alpine:init', registerOsmMapPicker);
    }
})();
