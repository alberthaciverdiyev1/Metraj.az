function getCompareList() {
    return JSON.parse(localStorage.getItem('compareList')) || [];
}

function saveCompareList(compareList) {
    localStorage.setItem('compareList', JSON.stringify(compareList));
}


function formatPrice(priceValue) {
    priceValue = Number(priceValue) || 0;
    return priceValue.toLocaleString('az-AZ', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });
}

// Compare table render
function renderCompareTable() {
    const compareList = getCompareList();
    const compareContainer = document.getElementById('compareContainer');
    if (!compareContainer) return;

    if (compareList.length === 0) {
        compareContainer.innerHTML = `
            <div class="text-center py-12">
                <div class="mx-auto w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-exchange-alt text-[var(--primary)] text-2xl"></i>
                </div>
                <h4 class="text-xl font-medium text-gray-700 mb-2">Müqayisə üçün siyahı əlavə edilməyib.</h4>
                <p class="text-base text-gray-500">
                    Müqayisə etmək üçün məhsul əlavə edin.
                </p>
            </div>
        `;
        return;
    }

    let cardsHtml = `
        <div class="flex flex-col gap-6">
            <div class="flex justify-end">
                <button id="clearAllCompareBtn" class="flex items-center bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-5 rounded-lg transition-all duration-200">
                    <i class="fas fa-broom mr-2"></i> Bütününü Təmizlə
                </button>
            </div>
            ${compareList.map(product => `
                <div class="bg-white p-4 rounded-xl shadow-lg flex flex-col md:flex-row items-center gap-4 border border-gray-200">
                    <div class="w-full md:w-48 h-32 flex items-center justify-center border border-gray-200 rounded-lg overflow-hidden">
                        ${product.media && product.media.path ? `<img src="${product.media.path}" alt="${product.title}" class="object-contain w-full h-full"/>` : `<i class="fas fa-image text-gray-400 text-2xl"></i>`}
                    </div>
                    <div class="flex-1">
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">${product.title}</h4>
                        <p class="text-base text-gray-600">Qiymət: <span class="font-bold text-[var(--primary)]">${formatPrice(product.price?.[0]?.price)} AZN</span></p>
                        <p class="text-base text-gray-600">Ünvan: ${product.address || 'N/A'}</p>
                        <p class="text-base text-gray-600">Elan Nömrəsi: ${product.adNo || 'N/A'}</p>
                        <p class="text-base text-gray-600">Yataq Otağı: ${product.beds || 'N/A'}</p>
                        <p class="text-base text-gray-600">Hamam: ${product.baths || 'N/A'}</p>
                        <p class="text-base text-gray-600">Sahə: ${product.area || 'N/A'} Kvm</p>
                        <button onclick="removeCompareItem(${product.id})" class="mt-3 text-red-500 hover:text-red-700">Sil</button>
                    </div>
                </div>
            `).join('')}
        </div>
    `;

    compareContainer.innerHTML = cardsHtml;

    const clearButton = document.getElementById('clearAllCompareBtn');
    if (clearButton) clearButton.addEventListener('click', clearAllCompareItems);
}


// Sil funksiyası
window.removeCompareItem = function(id) {
    let compareList = getCompareList();
    compareList = compareList.filter(p => p.id != id);
    saveCompareList(compareList);
    renderCompareTable();
}

// Bütün məhsulları təmizlə
function clearAllCompareItems() {
    localStorage.removeItem('compareList');
    renderCompareTable();
}
// QLOBAL EDİRİK
window.addToCompare = function(product) {
    let compareList = JSON.parse(localStorage.getItem('compareList')) || [];

    // id tiplərini eyniləşdirək ki, string/number fərqi problem yaratmasın
    if (!compareList.some(p => String(p.id) === String(product.id))) {
        compareList.push(product);
        localStorage.setItem('compareList', JSON.stringify(compareList));
        console.log('Compare List updated and saved to localStorage:', compareList);
    } else {
        console.log('Məhsul artıq müqayisə siyahısındadır:', product.id);
    }

    // varsa, ikonları və cədvəli yenilə
    if (typeof updateCompareIconsOnOtherPages === 'function') updateCompareIconsOnOtherPages();
    if (typeof renderCompareTable === 'function') renderCompareTable();
};




// DOM yüklənəndə render et
document.addEventListener('DOMContentLoaded', () => {
    renderCompareTable();
});

function updateCompareIconsOnOtherPages() {
    const compareList = getCompareList();
    document.querySelectorAll('.compare i').forEach(icon => {
        const button = icon.closest('button.compare');
        if (button) {
            const propertyData = button.getAttribute('onclick').match(/decodeURIComponent\('(.*?)'\)/);
            if (propertyData && propertyData[1]) {
                try {
                    const property = JSON.parse(decodeURIComponent(propertyData[1]));
                    if (compareList.some(compProperty => compProperty.id === property.id)) {
                        icon.classList.add('text-[color:var(--primary)]');
                    } else {
                        icon.classList.remove('text-[color:var(--primary)]');
                    }
                } catch (e) {
                }
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    renderCompareTable();
});