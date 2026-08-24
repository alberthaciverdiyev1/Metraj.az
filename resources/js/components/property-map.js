/* Əmlak xəritəsi (Leaflet) — property/map.blade.php-dən çıxarılıb */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-map-config]').forEach(function (container) {
        if (typeof L === 'undefined') return;

        var config;
        try {
            config = JSON.parse(container.getAttribute('data-map-config'));
        } catch (e) {
            return;
        }

        var mapId = config.id;
        if (!mapId || !container) return;

        var lat = parseFloat(config.lat) || 40.409264;
        var lng = parseFloat(config.lng) || 49.867092;
        var title = config.title || '';
        var price = config.price || '';
        var address = config.address || '';

        var map = L.map(mapId, {
            zoomControl: false,
            attributionControl: false
        }).setView([lat, lng], 15);

        L.control.zoom({ position: 'bottomright' }).addTo(map);

        var cartoLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            maxZoom: 19,
            subdomains: 'abcd'
        }).addTo(map);

        var satLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19
        });

        var currentLayer = 'carto';
        window['switchLayer_' + mapId] = function(type) {
            if (type === 'satellite' && currentLayer !== 'satellite') {
                map.removeLayer(cartoLayer);
                satLayer.addTo(map);
                currentLayer = 'satellite';
                var satBtn = document.getElementById('btn_sat_' + mapId);
                var cartoBtn = document.getElementById('btn_carto_' + mapId);
                if (satBtn) satBtn.className = 'px-3 py-1.5 rounded-lg bg-orange-500 text-white shadow-sm transition';
                if (cartoBtn) cartoBtn.className = 'px-3 py-1.5 rounded-lg bg-transparent text-gray-700 hover:bg-gray-100 transition';
            } else if (type === 'carto' && currentLayer !== 'carto') {
                map.removeLayer(satLayer);
                cartoLayer.addTo(map);
                currentLayer = 'carto';
                var cartoBtn2 = document.getElementById('btn_carto_' + mapId);
                var satBtn2 = document.getElementById('btn_sat_' + mapId);
                if (cartoBtn2) cartoBtn2.className = 'px-3 py-1.5 rounded-lg bg-orange-500 text-white shadow-sm transition';
                if (satBtn2) satBtn2.className = 'px-3 py-1.5 rounded-lg bg-transparent text-gray-700 hover:bg-gray-100 transition';
            }
        };

        var pulseIcon = L.divIcon({
            className: 'custom-pulse-marker',
            html: `
                <div style="position: relative; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                    <div style="position: absolute; width: 40px; height: 40px; border-radius: 50%; background: rgba(249, 115, 22, 0.28); animation: leaflet-pulse 2s infinite ease-in-out;"></div>
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #ea580c; border: 3px solid #ffffff; box-shadow: 0 4px 12px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; color: white;">
                        <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    </div>
                </div>
            `,
            iconSize: [44, 44],
            iconAnchor: [22, 40],
            popupAnchor: [0, -36]
        });

        var marker = L.marker([lat, lng], { icon: pulseIcon }).addTo(map);

        var popupContent = `
            <div style="font-family: inherit; font-size: 13px; padding: 4px;">
                <p style="font-weight: bold; color: #111827; margin: 0 0 4px 0;">${title}</p>
                <p style="font-size: 14px; font-weight: 800; color: #ea580c; margin: 0 0 4px 0;">${price}</p>
                <p style="font-size: 11px; color: #6b7280; margin: 0;">${address}</p>
            </div>
        `;
        marker.bindPopup(popupContent).openPopup();
    });
});
