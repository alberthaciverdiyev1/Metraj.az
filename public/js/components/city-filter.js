/* Şəhər filtri modalı — şəhər seçimi (city-filter.blade.php-dən çıxarılıb) */
document.addEventListener('DOMContentLoaded', function () {
    var citySelect = document.getElementById('citySelect');
    var cityButtons = document.querySelectorAll('.city-btn');
    var rightPanelPlaceholder = document.getElementById('rightPanelPlaceholder');

    function selectCity(cityId) {
        if (!citySelect) return;
        citySelect.value = cityId;
        citySelect.dispatchEvent(new Event('change'));

        // Toggle active state in city buttons
        cityButtons.forEach(function (btn) {
            var isTarget = btn.getAttribute('data-city-id') == cityId;
            btn.classList.toggle('border-orange-500', isTarget);
            btn.classList.toggle('bg-orange-50/50', isTarget);
            btn.classList.toggle('text-orange-500', isTarget);
            btn.classList.toggle('border-gray-200/60', !isTarget);
            btn.classList.toggle('bg-white', !isTarget);
            btn.classList.toggle('text-gray-700', !isTarget);
        });

        // Hide placeholder to show the region/landmark choices
        if (rightPanelPlaceholder) {
            rightPanelPlaceholder.classList.add('hidden');
        }
    }

    cityButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var cityId = this.getAttribute('data-city-id');
            selectCity(cityId);
        });
    });

    // Initialize state on load if city is already selected
    if (citySelect && citySelect.value) {
        selectCity(citySelect.value);
    }
});
