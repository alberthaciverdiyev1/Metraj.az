/* Otaq yoldaşı — elan yerləşdirmə şəkil önizləməsi (roommates/create.blade.php-dən çıxarılıb) */
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('imageUploadInput');
    const container = document.getElementById('imagePreviewContainer');

    if (input && container) {
        input.addEventListener('change', function () {
            container.innerHTML = '';
            if (this.files && this.files.length > 0) {
                container.classList.remove('hidden');
                Array.from(this.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const div = document.createElement('div');
                        div.className = 'relative aspect-[4/3] rounded-xl overflow-hidden bg-gray-100 border border-gray-200 shadow-2xs';
                        div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover" />
                                         <span class="absolute top-1 left-1 bg-black/60 text-white text-[10px] px-1.5 py-0.5 rounded-md font-semibold">${index + 1}</span>`;
                        container.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            } else {
                container.classList.add('hidden');
            }
        });
    }
});
