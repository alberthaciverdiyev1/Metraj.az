/* Axtarıram — detal səhifəsi şəkil qaleriyası (requests/show.blade.php-dən çıxarılıb) */
window.imagesData = window.requestShowConfig?.images || [];
window.currentMainImageIndex = 0;

window.selectPageImage = function (index) {
    window.currentMainImageIndex = index;
    const mainImg = document.getElementById('mainRequestDisplayImg');
    if (mainImg && window.imagesData[index]) {
        mainImg.src = window.imagesData[index];
    }

    const thumbs = document.querySelectorAll('.page-thumb');
    thumbs.forEach((thumb, i) => {
        if (i === index) {
            thumb.classList.add('active', 'border-orange-500');
            thumb.classList.remove('border-transparent');
        } else {
            thumb.classList.remove('active', 'border-orange-500');
            thumb.classList.add('border-transparent');
        }
    });
};
