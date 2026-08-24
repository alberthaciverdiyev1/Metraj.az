/* Axtarıram — elan yerləşdirmə (requests/create.blade.php-dən çıxarılıb) */
document.addEventListener('DOMContentLoaded', function () {
    const quillPlaceholder = window.requestCreateConfig?.quillPlaceholder || '';

    // 1) Initialize Quill Editor
    function initQuill() {
        if (typeof Quill === 'undefined') {
            setTimeout(initQuill, 100);
            return;
        }

        const editorElem = document.getElementById('editor_container');
        if (!editorElem || editorElem.__quill) return;

        const quill = new Quill('#editor_container', {
            theme: 'snow',
            placeholder: quillPlaceholder,
            modules: {
                toolbar: [
                    [{ 'header': [2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['clean']
                ]
            }
        });
        editorElem.__quill = quill;

        const form = document.getElementById('propertyRequestForm');
        const descriptionInput = document.getElementById('description_input');

        if (form) {
            form.addEventListener('submit', function (e) {
                if (quill.getText().trim().length === 0) {
                    descriptionInput.value = '';
                } else {
                    descriptionInput.value = quill.root.innerHTML;
                }
            });
        }
    }

    initQuill();

    // 2) Category Sections Switcher
    const radios = document.querySelectorAll('.request-type-radio');
    const buyFields = document.getElementById('buyFields');
    const rentFields = document.getElementById('rentFields');
    const roommateFields = document.getElementById('roommateFields');
    const propertyTypeField = document.getElementById('propertyTypeField');
    const roomsField = document.getElementById('roomsField');
    const imageUploadSection = document.getElementById('imageUploadSection');
    const imageInput = document.getElementById('requestImagesInput');
    const previewGrid = document.getElementById('imagePreviewGrid');

    function updateSections() {
        let selected = 'buy';
        radios.forEach(r => { if (r.checked) selected = r.value; });

        if (selected === 'buy') {
            buyFields.classList.remove('hidden');
            rentFields.classList.add('hidden');
            roommateFields.classList.add('hidden');
            propertyTypeField.classList.remove('hidden');
            roomsField.classList.remove('hidden');
            imageUploadSection.classList.add('hidden');
        } else if (selected === 'rent_monthly' || selected === 'rent_daily') {
            buyFields.classList.add('hidden');
            rentFields.classList.remove('hidden');
            roommateFields.classList.add('hidden');
            propertyTypeField.classList.remove('hidden');
            roomsField.classList.remove('hidden');
            imageUploadSection.classList.add('hidden');
        } else {
            // roommate
            buyFields.classList.add('hidden');
            rentFields.classList.add('hidden');
            roommateFields.classList.remove('hidden');
            propertyTypeField.classList.add('hidden');
            roomsField.classList.add('hidden');
            imageUploadSection.classList.remove('hidden');
        }
    }

    radios.forEach(r => r.addEventListener('change', updateSections));
    updateSections();

    // 3) Image preview
    if (imageInput && previewGrid) {
        imageInput.addEventListener('change', function () {
            previewGrid.innerHTML = '';
            const files = Array.from(this.files);

            files.slice(0, 10).forEach(file => {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = function (e) {
                    const div = document.createElement('div');
                    div.className = 'relative rounded-xl overflow-hidden aspect-square border border-gray-200 bg-gray-100 shadow-2xs group';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                    `;
                    previewGrid.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        });
    }
});
