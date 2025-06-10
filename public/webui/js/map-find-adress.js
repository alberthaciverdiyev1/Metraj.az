

document.addEventListener('DOMContentLoaded', function() {
    const map = L.map('map').setView([40.4093, 49.8671], 13); // Default to Baku coordinates
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    let marker = null;
    
    function formatAddress(data) {
        if (!data.address) return data.display_name;
        
        const addr = data.address;
        let formatted = '';
        
        if (addr.house_number) formatted += addr.house_number + ' ';
        
        if (addr.road) formatted += addr.road + ', ';
        
        if (addr.neighbourhood) formatted += addr.neighbourhood + ', ';
        
        if (addr.city) formatted += addr.city;
        else if (addr.town) formatted += addr.town;
        else if (addr.village) formatted += addr.village;
        
        if (addr.postcode) formatted += ', ' + addr.postcode;
        
        if (addr.country) formatted += ', ' + addr.country;
        
        return formatted || data.display_name;
    }
    
    document.getElementById('searchAddress').addEventListener('click', function() {
        const address = document.getElementById('address').value.trim();
        const country = document.getElementById('city_id').value;
        
        if (!address) {
            alert('Please enter an address');
            return;
        }
        
        let queryParams = `format=json&q=${encodeURIComponent(address)}`;
        
        if (country === '1') { // Azerbaijan
            queryParams += '&countrycodes=az';
        }
        
        queryParams += '&addressdetails=1';
        
        fetch(`https://nominatim.openstreetmap.org/search?${queryParams}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    let bestMatch = data[0];
                    
                    if (data.length > 1) {
                        const exactMatches = data.filter(item => 
                            item.class === 'building' || 
                            item.type === 'house' || 
                            item.addresstype === 'house'
                        );
                        
                        if (exactMatches.length > 0) {
                            bestMatch = exactMatches[0];
                        }
                    }
                    
                    const lat = parseFloat(bestMatch.lat);
                    const lon = parseFloat(bestMatch.lon);
                    
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lon;
                    
                    if (bestMatch.address && bestMatch.address.postcode) {
                        document.getElementById('add_no').value = bestMatch.address.postcode;
                    }
                    
                    map.setView([lat, lon], 16);
                    
                    if (marker) {
                        map.removeLayer(marker);
                    }
                    
                    marker = L.marker([lat, lon]).addTo(map)
                        .bindPopup(formatAddress(bestMatch))
                        .openPopup();
                    
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&addressdetails=1`)
                        .then(response => response.json())
                        .then(details => {
                            document.getElementById('address').value = formatAddress(details);
                            if (details.address && details.address.postcode) {
                                document.getElementById('add_no').value = details.address.postcode;
                            }
                        });
                } else {
                    alert('Address not found. Please try a more specific address.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error searching for address');
            });
    });
    
    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        
        if (marker) {
            map.removeLayer(marker);
        }
        
        marker = L.marker([lat, lng]).addTo(map);
        
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`)
            .then(response => response.json())
            .then(data => {
                if (data.display_name) {
                    const formattedAddress = formatAddress(data);
                    document.getElementById('address').value = formattedAddress;
                    
                    if (data.address && data.address.postcode) {
                        document.getElementById('add_no').value = data.address.postcode;
                    }
                    
                    marker.bindPopup(formattedAddress).openPopup();
                }
            });
    });
    
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func.apply(context, args);
            }, wait);
        };
    }
});