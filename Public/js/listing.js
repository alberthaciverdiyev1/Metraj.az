import { getPropertiesList } from "./components/property.js";
import { propertyCard } from "./cards/property.js";
import { propertySkeletonCard } from "./cards/propertySkeleton.js"; 

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
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

// PAGINATION
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

    let allPropertiesData = [];
    if (!propertyContainer || !premiumCardContainer || !premiumLoadingOverlay || !allPropertiesLoadingOverlay) {
        console.error('One or more required elements (containers or loading overlays) not found!');
        return;
    }
    
    let currentPage = 1;
    let currentAddType = 'all';
    let currentAddressQuery = '';
    let currentMinArea = '';
    let currentMaxArea = '';
    let currentMinPrice = ''; 
    let currentMaxPrice = ''; 

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

        resultsText.textContent = `Showing ${startIndex + 1}-${endIndex} of ${totalItems} results.`;
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

        let propertiesToFilter = [];
        try {
            allPropertiesData = await getPropertiesList(); 
            propertiesToFilter = [...allPropertiesData];
        } catch (error) {
            console.error("Error fetching properties:", error);
            premiumCardContainer.innerHTML = '<p class="text-red-500 text-center col-span-full">Premium elanlar yüklənərkən xəta baş verdi.</p>';
            propertyContainer.innerHTML = '<p class="text-red-500 text-center col-span-full">Elanlar yüklənərkən xəta baş verdi.</p>';
            premiumLoadingOverlay.style.display = 'none';
            allPropertiesLoadingOverlay.style.display = 'none';
            return; 
        }

        if (currentAddType !== 'all') {
            propertiesToFilter = propertiesToFilter.filter(property =>
                property.add_type === currentAddType
            );
        }

        if (currentAddressQuery) {
            const lowerCaseAddressQuery = currentAddressQuery.toLowerCase();
            propertiesToFilter = propertiesToFilter.filter(property =>
                property.address && property.address.toLowerCase().includes(lowerCaseAddressQuery)
            );
        }

        const minArea = parseFloat(currentMinArea);
        const maxArea = parseFloat(currentMaxArea);

        if (!isNaN(minArea)) {
            propertiesToFilter = propertiesToFilter.filter(property =>
                property.area !== undefined && property.area >= minArea
            );
        }
        if (!isNaN(maxArea)) {
            propertiesToFilter = propertiesToFilter.filter(property =>
                property.area !== undefined && property.area <= maxArea
            );
        }

        const minPrice = parseFloat(currentMinPrice);
        const maxPrice = parseFloat(currentMaxPrice);

        if (!isNaN(minPrice)) {
            propertiesToFilter = propertiesToFilter.filter(property =>
                property.price !== undefined && property.price[0]?.price && parseFloat(property.price[0].price.replace(/,/g, '')) >= minPrice
            );
        }
        if (!isNaN(maxPrice)) {
            propertiesToFilter = propertiesToFilter.filter(property =>
                property.price !== undefined && property.price[0]?.price && parseFloat(property.price[0].price.replace(/,/g, '')) <= maxPrice
            );
        }

        let premiumCardsHtml = '';
        let allCardsHtml = '';

        propertiesToFilter.forEach(property => {
            const cardHtml = propertyCard(property);
            allCardsHtml += cardHtml;

            if (property.is_premium) {
                premiumCardsHtml += cardHtml;
            }
        });

        premiumCardContainer.innerHTML = premiumCardsHtml;
        propertyContainer.innerHTML = allCardsHtml;

        premiumLoadingOverlay.style.display = 'none';
        allPropertiesLoadingOverlay.style.display = 'none';

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