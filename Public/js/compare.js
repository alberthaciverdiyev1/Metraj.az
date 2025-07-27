function getCompareList() {
    return JSON.parse(localStorage.getItem('compareList')) || [];
}

function saveCompareList(compareList) {
    localStorage.setItem('compareList', JSON.stringify(compareList));
}

function formatPrice(priceValue) {
    if (typeof priceValue !== 'number') {
        priceValue = parseFloat(priceValue);
    }
    return priceValue.toLocaleString('az-AZ', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });
}

function renderCompareTable() {
    const compareList = getCompareList();
    const compareContainer = document.querySelector('.bg-white.p-6.rounded-xl.shadow-lg');
    
    if (!compareContainer) {
        return;
    }

    if (compareList.length === 0) {
        compareContainer.innerHTML = `
            <div class="text-center py-12">
                <div class="mx-auto w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-exchange-alt text-[var(--primary)] text-2xl"></i>
                </div>
                <h4 class="text-xl font-medium text-gray-700 mb-2">Müqayisə üçün məhsul seçilməyib</h4>
                <p class="text-base text-gray-500">
                    Müqayisə etmək üçün məhsul əlavə edin.
                </p>
            </div>
        `;
        return;
    }

    const allFeatureKeys = new Set();
    compareList.forEach(product => {
        if (product.features) {
            Object.keys(product.features).forEach(key => {
                allFeatureKeys.add(key);
            });
        }
    });

    const featureKeysArray = Array.from(allFeatureKeys);

    let tableHtml = `
        <div class="flex justify-end mb-6">
            <button id="clearAllCompareBtn"
                class="flex items-center bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-5 rounded-lg transition-all duration-200"
                type="button"
            >
                <i class="fas fa-broom mr-2"></i> Təmizlə
            </button>
        </div>

        <div class="overflow-x-auto w-full rounded-2xl border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Ad</th>
                        ${compareList.map(product => `
                            <th scope="col" class="px-4 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider">
                                <p class="text-base font-semibold text-gray-800">${product.title}</p>
                                <button onclick="removeCompareItem(${product.id})" class="text-red-500 hover:text-red-700 text-xs mt-1">Sil</button>
                            </th>
                        `).join('')}
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-base font-medium text-gray-700">
                            <div class="flex items-center">
                                <i class="fas fa-money-bill-wave text-[var(--primary)] mr-2"></i>
                                <span>Qiymət</span>
                            </div>
                        </td>
                        ${compareList.map(product => {
                            const rawPrice = product.price && product.price[0]?.price;
                            let priceValue = 0;
                            if (rawPrice) {
                                priceValue = parseFloat(rawPrice.toString().replace(/,/g, ''));
                            }
                            const price = formatPrice(priceValue);
                            return `
                                <td class="px-4 py-3 text-center text-xl font-bold text-[var(--primary)]">
                                    ${price} AZN
                                </td>
                            `;
                        }).join('')}
                    </tr>

                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-base font-medium text-gray-700">
                            <div class="flex items-center">
                                <i class="fas fa-image text-[var(--primary)] mr-2"></i>
                                <span>Şəkil</span>
                            </div>
                        </td>
                        ${compareList.map(product => `
                            <td class="px-4 py-3 text-center">
                                ${product.media && product.media.path ? `
                                <div class="w-48 h-32 mx-auto rounded-lg overflow-hidden flex items-center justify-center border border-gray-200">
                                    <img src="${product.media.path}" alt="${product.title}" class="object-contain w-full h-full" />
                                </div>
                                ` : `
                                <div class="w-32 h-32 mx-auto rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 border border-gray-200">
                                    <i class="fas fa-image text-2xl"></i>
                                </div>
                                `}
                            </td>
                        `).join('')}
                    </tr>

                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-base font-medium text-gray-700">
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-[var(--primary)] mr-2"></i>
                                <span>Ünvan</span>
                            </div>
                        </td>
                        ${compareList.map(product => `
                            <td class="px-4 py-3 text-center text-base text-gray-600">
                                ${product.address || 'N/A'}
                            </td>
                        `).join('')}
                    </tr>

                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-base font-medium text-gray-700">
                            <div class="flex items-center">
                                <i class="fas fa-hashtag text-[var(--primary)] mr-2"></i>
                                <span>Elan Nömrəsi</span>
                            </div>
                        </td>
                        ${compareList.map(product => `
                            <td class="px-4 py-3 text-center text-base text-gray-600">
                                ${product.adNo || 'N/A'}
                            </td>
                        `).join('')}
                    </tr>

                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-base font-medium text-gray-700">
                            <div class="flex items-center">
                                <i class="fas fa-bed text-[var(--primary)] mr-2"></i>
                                <span>Yataq Otağı</span>
                            </div>
                        </td>
                        ${compareList.map(product => `
                            <td class="px-4 py-3 text-center text-base text-gray-600">
                                ${product.beds || 'N/A'}
                            </td>
                        `).join('')}
                    </tr>

                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-base font-medium text-gray-700">
                            <div class="flex items-center">
                                <i class="fas fa-bath text-[var(--primary)] mr-2"></i>
                                <span>Hamam</span>
                            </div>
                        </td>
                        ${compareList.map(product => `
                            <td class="px-4 py-3 text-center text-base text-gray-600">
                                ${product.baths || 'N/A'}
                            </td>
                        `).join('')}
                    </tr>

                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-base font-medium text-gray-700">
                            <div class="flex items-center">
                                <i class="fas fa-ruler-combined text-[var(--primary)] mr-2"></i>
                                <span>Sahə (Kvm)</span>
                            </div>
                        </td>
                        ${compareList.map(product => `
                            <td class="px-4 py-3 text-center text-base text-gray-600">
                                ${product.area || 'N/A'}
                            </td>
                        `).join('')}
                    </tr>

                    ${featureKeysArray.map(featureKey => `
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap text-base font-medium text-gray-700">
                                <div class="flex items-center">
                                    <i class="fas fa-circle text-[var(--primary)] mr-2 text-xs"></i>
                                    <span>${featureKey}</span>
                                </div>
                            </td>
                            ${compareList.map(product => `
                                <td class="px-4 py-3 text-center text-base text-gray-600">
                                    ${product.features && product.features[featureKey] !== undefined ? product.features[featureKey] : '—'}
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
    if (clearButton) {
        clearButton.addEventListener('click', clearAllCompareItems);
    }
}

window.removeCompareItem = function(propertyId) {
    let compareList = getCompareList();
    compareList = compareList.filter(property => property.id !== propertyId);
    saveCompareList(compareList);
    renderCompareTable();
    updateCompareIconsOnOtherPages();
};

function clearAllCompareItems() {
    if (confirm('Bütün müqayisə edilən məhsulları silmək istədiyinizə əminsiniz?')) {
        localStorage.removeItem('compareList');
        renderCompareTable();
        updateCompareIconsOnOtherPages();
    }
}

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