/* Filament OSM Map Picker — Alpine komponenti */
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
            boundaryLayer: null,
            isUpdatingFromMap: false,
            searchTimer: null,

            layers: {
                voyager: {
                    url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 19
                },
                satellite: {
                    url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
                    attribution: '&copy; Esri World Imagery',
                    maxZoom: 19
                },
                light: {
                    url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}',
                    attribution: '&copy; Esri &copy; OpenStreetMap',
                    maxZoom: 19
                }
            },

            knownCoords: {
                'girne': [35.338244, 33.318627, 13],
                'kyrenia': [35.338244, 33.318627, 13],
                'lefkosa': [35.185566, 33.382276, 13],
                'nicosia': [35.185566, 33.382276, 13],
                'gazimagusa': [35.125000, 33.941667, 13],
                'famagusta': [35.125000, 33.941667, 13],
                'magusa': [35.125000, 33.941667, 13],
                'iskele': [35.286389, 33.890556, 13],
                'trikomo': [35.286389, 33.890556, 13],
                'guzelyurt': [35.198889, 32.993056, 13],
                'morphou': [35.198889, 32.993056, 13],
                'lefke': [35.111111, 32.848889, 13],
                'yenibogazici': [35.195000, 33.896000, 14],
                'catalkoy': [35.318000, 33.398000, 14],
                'alsancak': [35.352000, 33.225000, 14],
                'lapta': [35.342000, 33.167000, 14],
                'esentepe': [35.337000, 33.585000, 14],
                'dikmen': [35.265000, 33.325000, 14],
                'bafra': [35.395000, 34.075000, 14],
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

                const defaultLat = this.latitude ? parseFloat(this.latitude) : 35.338244;
                const defaultLng = this.longitude ? parseFloat(this.longitude) : 33.318627;
                const defaultZoom = (this.latitude && this.longitude) ? 15 : 12;

                this.map = window.L.map(container, {
                    center: [defaultLat, defaultLng],
                    zoom: defaultZoom,
                    zoomControl: false,
                });

                window.L.control.zoom({ position: 'topleft' }).addTo(this.map);

                this.tileLayer = window.L.tileLayer(this.layers.voyager.url, {
                    maxZoom: this.layers.voyager.maxZoom,
                    attribution: this.layers.voyager.attribution,
                }).addTo(this.map);

                const customIcon = window.L.divIcon({
                    className: 'custom-kibriskare-marker',
                    html: `
                        <div class="kibriskare-pin-container">
                            <div class="kibriskare-pin-ripple"></div>
                            <div class="kibriskare-pin-bubble">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="kibriskare-pin-icon">
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

                // Watch Address text input
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

                // Watch City selection changes -> Auto highlight region and fit bounds
                this.$watch('cityId', (newCityId) => {
                    if (!newCityId || !this.locations[newCityId]) return;
                    const city = this.locations[newCityId];
                    const cityName = city.name || city.slug;
                    this.updateMapBoundary(cityName, cityName);
                });

                // Watch District selection changes -> Auto highlight sub-region
                this.$watch('districtId', (newDistrictId) => {
                    if (!newDistrictId) return;
                    const city = this.locations[this.cityId];
                    if (city && city.districts && city.districts[newDistrictId]) {
                        const dist = city.districts[newDistrictId];
                        const distName = dist.name || dist.slug;
                        const cityName = city.name || city.slug;
                        this.updateMapBoundary(distName + ', ' + cityName, distName);
                    }
                });

                // If city is already selected on edit, trigger initial boundary highlight
                if (this.cityId && this.locations[this.cityId] && !this.latitude) {
                    const city = this.locations[this.cityId];
                    const cityName = city.name || city.slug;
                    this.updateMapBoundary(cityName, cityName);
                }

                setTimeout(() => {
                    if (this.map) {
                        this.map.invalidateSize();
                    }
                }, 300);
            },

            updateMapBoundary(query, displayName) {
                if (!this.map || !query) return;

                const url = 'https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query + ', Cyprus') + '&polygon_geojson=1&limit=1&accept-language=tr,en,az';

                fetch(url, { headers: { 'Accept-Language': 'tr, en, az' } })
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

                                const bounds = window.L.latLngBounds([[latMin, lonMin], [latMax, lonMax]]);

                                // Remove previous boundary overlay
                                if (this.boundaryLayer) {
                                    this.map.removeLayer(this.boundaryLayer);
                                    this.boundaryLayer = null;
                                }

                                // Render boundary polygon or rectangle
                                if (item.geojson && (item.geojson.type === 'Polygon' || item.geojson.type === 'MultiPolygon')) {
                                    this.boundaryLayer = window.L.geoJSON(item.geojson, {
                                        style: {
                                            color: '#ea580c',
                                            weight: 2.5,
                                            dashArray: '6, 6',
                                            fillOpacity: 0.08,
                                            fillColor: '#ea580c'
                                        }
                                    }).addTo(this.map);
                                } else {
                                    this.boundaryLayer = window.L.rectangle(bounds, {
                                        color: '#ea580c',
                                        weight: 2.5,
                                        dashArray: '6, 6',
                                        fillOpacity: 0.08,
                                        fillColor: '#ea580c'
                                    }).addTo(this.map);
                                }

                                this.map.fitBounds(bounds, { padding: [25, 25], maxZoom: 15 });

                                const centerLat = parseFloat(item.lat);
                                const centerLng = parseFloat(item.lon);

                                if (this.marker) {
                                    this.marker.setLatLng([centerLat, centerLng]);
                                    if (displayName) {
                                        this.showPopup(displayName);
                                    }
                                }

                                this.updatePosition(centerLat, centerLng, false);
                            }
                        } else {
                            // Fallback to knownCoords
                            const slug = (displayName || query || '').toLowerCase().replace(/[^a-z0-9]/g, '');
                            if (this.knownCoords[slug]) {
                                const [lat, lng, zoom] = this.knownCoords[slug];
                                this.flyToCoordinates(lat, lng, zoom, displayName);
                            }
                        }
                    })
                    .catch(err => {
                        console.error('Boundary geocode error:', err);
                    });
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
                const url = 'https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query + ', Cyprus') + '&limit=1&accept-language=tr,en,az';

                fetch(url, { headers: { 'Accept-Language': 'tr, en, az' } })
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

                const url = 'https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query.trim() + ', Cyprus') + '&limit=1&accept-language=tr,en,az';

                fetch(url, { headers: { 'Accept-Language': 'tr, en, az' } })
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
                const url = 'https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&zoom=18&addressdetails=1&accept-language=tr,en,az';

                fetch(url, { headers: { 'Accept-Language': 'tr, en, az' } })
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
