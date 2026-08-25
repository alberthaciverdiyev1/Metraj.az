/* Əlaqə səhifəsi — xəritə & forma AJAX (contact.blade.php-dən çıxarılıb) */
document.addEventListener('DOMContentLoaded', function () {
    const i18n = window.contactConfig?.i18n || {};

    // 1. Initialize Leaflet Map
    const mapEl = document.getElementById('contactMap');
    if (mapEl && typeof L !== 'undefined') {
        // Baku / KKTC location
        const defaultLat = 40.4093;
        const defaultLng = 49.8671;

        const map = L.map('contactMap', {
            center: [defaultLat, defaultLng],
            zoom: 14,
            scrollWheelZoom: false
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Custom Marker
        const customIcon = L.divIcon({
            className: 'custom-map-pin',
            html: '<div style="background: #f97316; width: 36px; height: 36px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(234,88,12,0.4); border: 2px solid white;"><i class="bi bi-geo-alt-fill" style="transform: rotate(45deg); color: white; font-size: 16px;"></i></div>',
            iconSize: [36, 36],
            iconAnchor: [18, 36]
        });

        const popupTitle = i18n.mapPopupTitle || 'Metraj.az';
        const popupAddr = i18n.mapPopupAddress || '';
        L.marker([defaultLat, defaultLng], { icon: customIcon })
            .addTo(map)
            .bindPopup('<b>' + popupTitle + '</b>' + (popupAddr ? '<br>' + popupAddr : ''))
            .openPopup();
    }

    // 2. Contact Form AJAX Submission
    const contactForm = document.getElementById('contactForm');
    const submitBtn = document.getElementById('contactSubmitBtn');

    if (contactForm) {
        contactForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> ' + (i18n.sending || 'Göndərilir...');
            }

            try {
                const formData = new FormData(contactForm);
                const response = await fetch(contactForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    if (window.Metraj && window.Metraj.toast) {
                        window.Metraj.toast(data.message || i18n.sent || 'Mesajınız uğurla göndərildi!');
                    } else {
                        alert(data.message || i18n.sent || 'Mesajınız uğurla göndərildi!');
                    }
                    contactForm.reset();
                } else {
                    let errMsg = data.message || i18n.error || 'Xəta baş verdi, zəhmət olmasa yenidən cəhd edin.';
                    if (data.errors) {
                        const first = Object.values(data.errors)[0];
                        if (first && first[0]) errMsg = first[0];
                    }
                    if (window.Metraj && window.Metraj.toast) {
                        window.Metraj.toast(errMsg, 'error');
                    } else {
                        alert(errMsg);
                    }
                }
            } catch (err) {
                console.error('Contact submit error:', err);
                alert(i18n.network || 'Şəbəkə xətası baş verdi.');
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<span>' + (i18n.send || 'Mesajı Göndər') + '</span> <i class="bi bi-send-fill text-xs"></i>';
                }
            }
        });
    }
});
