/* Müqayisə səhifəsi — elementlərin silinməsi və siyahının təmizlənməsi (compare.blade.php-dən çıxarılıb) */
(function () {
'use strict';

const R = window.MetrajRoutes || {};
window.removeCompareItem = function (propertyId) {
    const csrf = window.Metraj?.csrfToken() || '';
    fetch(R.comparesToggle || '/api/compares/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ property_id: propertyId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    });
};

document.getElementById('clearAllCompareBtn')?.addEventListener('click', function () {
    if (confirm('Bütün müqayisə siyahısını təmizləmək istədiyinizdən əminsiniz?')) {
        const csrf = window.Metraj?.csrfToken() || '';
        fetch(R.comparesClear || '/api/compares/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        });
    }
});
})();
