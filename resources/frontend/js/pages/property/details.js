/* Əmlak detal səhifəsi — şəkil məlumatları & müraciət formu (details.blade.php-dən çıxarılıb) */

// image-gallery.js tərəfindən istifadə olunan qlobal şəkil siyahısı
window.imagesData = window.propertyDetailConfig?.images || [];

// Müraciət (inquiry) formu — JS fetch ilə göndərilir
document.addEventListener('DOMContentLoaded', function () {
    const inquiryForm = document.getElementById('inquiry-form');
    if (!inquiryForm) return;

    inquiryForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const btn = inquiryForm.querySelector('button[type="submit"]');
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>';

        const { ok, data } = await window.Metraj.post(
            inquiryForm.action,
            new FormData(inquiryForm)
        );

        btn.disabled = false;
        btn.innerHTML = originalHtml;

        if (ok) {
            window.Metraj.toast(data.message || 'Müraciətiniz qəbul edildi ✅');
            inquiryForm.reset();
        } else {
            window.Metraj.toast(data.message || 'Xəta baş verdi, yenidən cəhd edin', 'error');
        }
    });
});
