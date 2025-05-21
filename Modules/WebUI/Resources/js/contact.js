    const map = L.map('map').setView([40.4093, 49.8671], 13); // Bakı

    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
      attribution: '&copy; OpenStreetMap & Carto',
      subdomains: 'abcd',
      maxZoom: 19
    }).addTo(map);

    const locations = [
      [40.4093, 49.8671],  // Bakı
      [40.3986, 49.8822],  // Sahil
      [40.3953, 49.8785]   // İçərişəhər
    ];

    const customIcon = L.divIcon({
      className: '',
      html: '<div class="pulse-marker"></div>',
      iconSize: [20, 20],
      iconAnchor: [10, 10]
    });

    locations.forEach(coords => {
      L.marker(coords, { icon: customIcon }).addTo(map);
    });