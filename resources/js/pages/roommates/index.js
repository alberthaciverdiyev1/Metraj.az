/* Otaq yoldaşı — lista filtrləri (roommates/index.blade.php-dən çıxarılıb) */
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('roommateFilterForm');
    const listingTypeInput = document.getElementById('listingTypeInput');
    const genderInput = document.getElementById('genderInput');
    const typeBtns = document.querySelectorAll('.filter-type-btn');
    const genderBtns = document.querySelectorAll('.filter-gender-btn');

    typeBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const val = btn.getAttribute('data-filter-type');
            listingTypeInput.value = val;
            form.submit();
        });
    });

    genderBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const val = btn.getAttribute('data-filter-gender');
            genderInput.value = val;
            form.submit();
        });
    });
});
