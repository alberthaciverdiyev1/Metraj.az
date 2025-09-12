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
            <div class="py-12">
                <div class="mx-auto w-20 h-20 bg-blue-50 rounded-full flex text-center items-center justify-center mb-4 shadow-md">
                    <i class="fas fa-exchange-alt text-[var(--primary)] text-2xl"></i>
                </div>
                <h4 class="text-xl text-center font-semibold text-gray-800 mb-2">Müqayisə üçün siyahı əlavə edilməyib.</h4>
                <p class="text-base text-center text-gray-500">Müqayisə etmək üçün məhsul əlavə edin.</p>
            </div>
        `;
        return;
    }

    // Məhsul məlumat başlıqları
    const attributes = [
        { key: "title", label: "Ad" },
        { key: "media", label: "Şəkil" },
        { key: "price", label: "Qiymət" },
        { key: "address", label: "Ünvan" },
        { key: "beds", label: "Yataq Otağı" },
        { key: "baths", label: "Hamam" },
        { key: "area", label: "Sahə" },
        { key: "buildingType", label: "Bina Növü" },
        { key: "add_type", label: "Elanın Növü" }
    ];

    let tableHtml = `
        <div class="flex justify-end mb-4">
            <button id="clearAllCompareBtn" 
                class="flex gap-2 text-center items-center bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-5 rounded-lg shadow transition-all duration-200">
                <i class="fas fa-broom"></i> Bütününü Təmizlə
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                <tbody>
                    ${attributes.map((attr, index) => `
                        <tr class="${index % 2 === 0 ? 'bg-white' : 'bg-gray-50'}">
                            <!-- Sol sütun -->
                            <td class="p-3 border font-semibold text-gray-700 w-48">${attr.label}</td>
                            
                            <!-- Məhsullar -->
                            ${compareList.map(product => `
                                <td class="p-3 border text-center">
                                    ${attr.key === "media"
            ? (product.media && product.media.path
                ? `<div class="w-28 h-20 mx-auto flex justify-center  rounded-md overflow-hidden bg-gray-50">
                       <img src="${product.media.path}" alt="${product.title}" class="object-cover w-full h-full"/>
                   </div>`
                : `<i class="fas fa-image text-gray-400  text-lg"></i>`)
            : attr.key === "price"
                ? `<span class="text-[var(--primary)] font-medium">${formatPrice(product.price?.[0]?.price)} AZN</span>`
                : attr.key === "status"
                    ? (product.status ? product.status : '<span class="text-gray-400">Gözləmədə</span>')
                    : product[attr.key] || 'N/A'
        }
                                </td>
                            `).join('')}
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `;

    compareContainer.innerHTML = tableHtml;

    const clearButton = document.getElementById('clearAllCompareBtn');
    if (clearButton) clearButton.addEventListener('click', clearAllCompareItems);
}




// Sil funksiyası
window.removeCompareItem = function (id) {
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
window.addToCompare = function (product) {
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