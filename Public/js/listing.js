import {getPropertiesList} from "./components/property.js";
import {propertyCard} from "./cards/property.js";
import {propertySkeletonCard} from "./cards/propertySkeleton.js";

const gotop = document.getElementById('scrollToTop');
const progress = document.querySelector('.progress-circle .progress');
const radius = 18;
const circumference = 2 * Math.PI * radius;

window.addEventListener('scroll', () => {
    const scrollTop = window.scrollY;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    const scrollPercent = scrollTop / docHeight;

    const offset = circumference - scrollPercent * circumference;
    progress.style.strokeDashoffset = offset;

    if (scrollTop > window.innerHeight / 2) {
        gotop.style.display = 'flex';
    } else {
        gotop.style.display = 'none';
    }
});

gotop.addEventListener('click', () => {
    window.scrollTo({top: 0, behavior: 'smooth'});
});

document.addEventListener('DOMContentLoaded', async () => {
    const itemsPerPage = 9;
    const paginationContainer = document.querySelector('.pagination');
    const resultsText = document.querySelector('.result .text');
    const gridBtn = document.getElementById("gridViewBtn");
    const listBtn = document.getElementById("listViewBtn");
    const propertyContainer = document.getElementById('propertyContainer');
    const premiumCardContainer = document.getElementById('premiumCard');

    const premiumLoadingOverlay = document.getElementById('premiumLoadingOverlay');
    const allPropertiesLoadingOverlay = document.getElementById('allPropertiesLoadingOverlay');

    const addressInput = document.getElementById('addressInput');
    const addressSuggestionsDiv = document.getElementById('addressSuggestions');
    const suggestionsList = document.getElementById('suggestionsList');
    const searchButton = document.querySelector('button.bg-black');

    const minAreaInput = document.querySelector('input[placeholder="Min ölçü"]');
    const maxAreaInput = document.querySelector('input[placeholder="Max ölçü"]');

    const minPriceInput = document.getElementById('minPriceInput');
    const maxPriceInput = document.getElementById('maxPriceInput');

    // Elementlərin mövcudluğunu yoxla
    if (!propertyContainer || !premiumCardContainer || !premiumLoadingOverlay || !allPropertiesLoadingOverlay) {
        console.error('Lazımi elementlərdən biri tapılmadı (konteynerlər və ya yükləmə qatmanları)!');
        return;
    }

    let currentPage = 1;
    let currentAddType = ''; // "all", "sale", "rent"
    let currentAddressQuery = '';
    let currentMinArea = '';
    let currentMaxArea = '';
    let currentMinPrice = '';
    let currentMaxPrice = '';

    let allPropertiesData = [];

    // Helper funksiyalar
    function getPropertyCards(containerElement) {
        return containerElement.querySelectorAll(':scope > div');
    }

    function getTotalPages(containerElement) {
        return Math.ceil(getPropertyCards(containerElement).length / itemsPerPage);
    }

    function showPage(page, containerElement) {
        currentPage = page;
        const propertyCards = getPropertyCards(containerElement);
        const totalItems = propertyCards.length;
        const totalPages = getTotalPages(containerElement);
        const startIndex = (page - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, totalItems);

        propertyCards.forEach(card => {
            card.style.display = 'none';
        });

        for (let i = startIndex; i < endIndex; i++) {
            if (propertyCards[i]) {
                propertyCards[i].style.display = 'block';
            }
        }

        resultsText.textContent = `Göstərilir ${startIndex + 1}-${endIndex} cəmi ${totalItems} elandan.`;
        updatePaginationUI(containerElement);
    }

    function updatePaginationUI(containerElement) {
        const totalPages = getTotalPages(containerElement);
        const pageItems = document.querySelectorAll('.pagination .page-item');
        pageItems.forEach(item => {
            item.classList.remove('active');
        });

        const currentPageItem = document.querySelector(`.pagination .page-item:nth-child(${currentPage + 1})`);
        if (currentPageItem) {
            currentPageItem.classList.add('active');
        }

        const prevButton = document.querySelector('.pagination .page-item:first-child');
        const nextButton = document.querySelector('.pagination .page-item:last-child');

        if (prevButton) {
            prevButton.classList.toggle('disabled', currentPage === 1);
        }

        if (nextButton) {
            nextButton.classList.toggle('disabled', currentPage === totalPages);
        }
    }

    function initPagination(containerElement) {
        showPage(currentPage, containerElement);
    }

    paginationContainer.addEventListener('click', function (e) {
        e.preventDefault();

        if (e.target.closest('.page-link')) {
            const target = e.target.closest('.page-link');
            const action = target.getAttribute('aria-label');

            const totalPages = getTotalPages(propertyContainer);

            if (action === 'Previous' && currentPage > 1) {
                showPage(currentPage - 1, propertyContainer);
            } else if (action === 'Next' && currentPage < totalPages) {
                showPage(currentPage + 1, propertyContainer);
            } else if (!action) {
                const pageNumber = parseInt(target.textContent);
                if (!isNaN(pageNumber)) {
                    showPage(pageNumber, propertyContainer);
                }
            }
        }
    });

    const toggleButtons = [gridBtn, listBtn];

    toggleButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            toggleButtons.forEach(b => {
                b.classList.remove("active-filter");
            });
            btn.classList.add("active-filter");
        });
    });

    gridBtn.addEventListener('click', () => {
        propertyContainer.classList.remove('list-view');
        propertyContainer.classList.add(
            'grid',
            'grid-cols-1',
            'sm:grid-cols-1',
            'md:grid-cols-2',
            'lg:grid-cols-2',
            'xl:grid-cols-4'
        );
        premiumCardContainer.classList.remove('list-view');
        premiumCardContainer.classList.add(
            'grid',
            'grid-cols-1',
            'sm:grid-cols-1',
            'md:grid-cols-2',
            'lg:grid-cols-2',
            'xl:grid-cols-4'
        );
        showPage(currentPage, propertyContainer);
    });

    listBtn.addEventListener('click', () => {
        propertyContainer.classList.add('list-view');
        propertyContainer.classList.remove(
            'grid',
            'grid-cols-1',
            'sm:grid-cols-1',
            'md:grid-cols-2',
            'lg:grid-cols-2',
            'xl:grid-cols-4'
        );
        premiumCardContainer.classList.add('list-view');
        premiumCardContainer.classList.remove(
            'grid',
            'grid-cols-1',
            'sm:grid-cols-1',
            'md:grid-cols-2',
            'lg:grid-cols-2',
            'xl:grid-cols-4'
        );
        showPage(currentPage, propertyContainer);
    });

    function renderSkeletons(container, count) {
        let skeletonsHtml = '';
        for (let i = 0; i < count; i++) {
            skeletonsHtml += propertySkeletonCard();
        }
        container.innerHTML = skeletonsHtml;
    }


    async function filterAndRenderProperties() {
        premiumLoadingOverlay.style.display = 'flex';
        allPropertiesLoadingOverlay.style.display = 'flex';

        renderSkeletons(premiumCardContainer, 4);
        renderSkeletons(propertyContainer, itemsPerPage);

        const searchParams = {
            adType: currentAddType,
            address: currentAddressQuery,
            minArea: currentMinArea,
            maxArea: currentMaxArea,
            minPrice: currentMinPrice,
            maxPrice: currentMaxPrice
        };

        let fetchedApiResponse;
        let processedProperties = [];

        try {
            // API-dən bütün əmlakları çəkin
            // Qeyd: Əgər API pagination-ı dəstəkləyirsə, burada sadəcə lazımi səhifəni çəkmək daha effektiv olar.
            // Lakin verilmiş koda əsasən, hamısı çəkilib frontenddə filtr olunur.
            fetchedApiResponse = await getPropertiesList(searchParams);

            const rawPropertiesData = fetchedApiResponse.data;

            if (typeof rawPropertiesData === 'object' && rawPropertiesData !== null && !Array.isArray(rawPropertiesData)) {
                for (const category in rawPropertiesData) {
                    if (rawPropertiesData.hasOwnProperty(category) && Array.isArray(rawPropertiesData[category])) {
                        processedProperties = processedProperties.concat(rawPropertiesData[category]);
                    }
                }
            } else if (Array.isArray(rawPropertiesData)) {
                processedProperties = rawPropertiesData;
            } else {
                console.warn("API-dən gözlənilməyən data formatı gəldi:", rawPropertiesData);
            }

            allPropertiesData = processedProperties; // Bütün əmlakları saxlayın

        } catch (error) {
            console.error("Əmlaklar çəkilərkən xəta:", error);
            premiumCardContainer.innerHTML = '<p class="text-red-500 text-center col-span-full">Premium elanlar yüklənərkən xəta baş verdi.</p>';
            propertyContainer.innerHTML = '<p class="text-red-500 text-center col-span-full">Elanlar yüklənərkən xəta baş verdi.</p>';
            premiumLoadingOverlay.style.display = 'none';
            allPropertiesLoadingOverlay.style.display = 'none';
            return;
        }

        let premiumCardsHtml = '';
        let allCardsHtml = '';

        const premiumProperties = allPropertiesData.filter(property => property.is_premium);
        const nonPremiumProperties = allPropertiesData.filter(property => !property.is_premium);

        // Premium elanlardan yalnız ilk 4-ü göstərin
        //const displayedPremiumProperties = premiumProperties.slice(0, 4);
        const displayedPremiumProperties = premiumProperties;

        if (displayedPremiumProperties.length > 0) {
            displayedPremiumProperties.forEach(property => {
                premiumCardsHtml += propertyCard(property);
            });
        } else {
            premiumCardsHtml = '<p class="col-span-full text-center text-gray-500">Premium elan tapılmadı.</p>';
        }
        premiumCardContainer.innerHTML = premiumCardsHtml;

        if (nonPremiumProperties.length > 0) {
            nonPremiumProperties.forEach(property => {
                allCardsHtml += propertyCard(property);
            });
        } else {
            allCardsHtml = '<p class="col-span-full text-center text-gray-500">Elan tapılmadı.</p>';
        }
        propertyContainer.innerHTML = allCardsHtml;

        premiumLoadingOverlay.style.display = 'none';
        allPropertiesLoadingOverlay.style.display = 'none';

        currentPage = 1;
        initPagination(propertyContainer);
    }


    const filterButtons = document.querySelectorAll('button[data-add-type]');
    filterButtons.forEach(button => {
        button.addEventListener('click', async () => {
            const selectedAddType = button.getAttribute('data-add-type');
            currentAddType = selectedAddType;

            filterButtons.forEach(btn => {
                btn.classList.remove('bg-[color:var(--primary)]', 'text-white');
                btn.classList.add('bg-white', 'text-gray-700', 'hover:bg-gray-100');
            });

            button.classList.add('bg-[color:var(--primary)]', 'text-white');
            button.classList.remove('bg-white', 'text-gray-700', 'hover:bg-gray-100');

            await filterAndRenderProperties();
        });
    });

    function updateAddressSuggestions(query) {
        suggestionsList.innerHTML = '';
        if (query.length < 2) {
            addressSuggestionsDiv.classList.add('hidden');
            return;
        }

        const uniqueAddresses = new Set();
        const lowerCaseQuery = query.toLowerCase();

        allPropertiesData.forEach(property => {
            if (property.address && property.address.toLowerCase().includes(lowerCaseQuery)) {
                uniqueAddresses.add(property.address);
            }
        });

        if (uniqueAddresses.size > 0) {
            addressSuggestionsDiv.classList.remove('hidden');
            uniqueAddresses.forEach(address => {
                const li = document.createElement('li');
                li.classList.add('p-2', 'hover:bg-gray-100', 'cursor-pointer');
                li.textContent = address;
                li.addEventListener('click', () => {
                    addressInput.value = address;
                    currentAddressQuery = address;
                    addressSuggestionsDiv.classList.add('hidden');
                    filterAndRenderProperties();
                });
                suggestionsList.appendChild(li);
            });
        } else {
            addressSuggestionsDiv.classList.add('hidden');
        }
    }

    addressInput.addEventListener('input', (event) => {
        currentAddressQuery = event.target.value;
        updateAddressSuggestions(currentAddressQuery);
    });

    minAreaInput.addEventListener('input', (event) => {
        currentMinArea = event.target.value;
    });

    maxAreaInput.addEventListener('input', (event) => {
        currentMaxArea = event.target.value;
    });

    minPriceInput.addEventListener('input', (event) => {
        currentMinPrice = event.target.value;
    });

    maxPriceInput.addEventListener('input', (event) => {
        currentMaxPrice = event.target.value;
    });

    searchButton.addEventListener('click', () => {
        filterAndRenderProperties();
    });

    document.addEventListener('click', (event) => {
        if (!addressInput.contains(event.target) && !addressSuggestionsDiv.contains(event.target)) {
            addressSuggestionsDiv.classList.add('hidden');
        }
    });

    const allButton = document.querySelector('button[data-add-type="all"]');
    if (allButton) {
        allButton.classList.add('bg-[color:var(--primary)]', 'text-white');
        allButton.classList.remove('bg-white', 'text-gray-700', 'hover:bg-gray-100');
        filterAndRenderProperties();
    }
});