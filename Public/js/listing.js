import { getPropertiesList } from "./components/property.js"; 
import { propertyCard } from "./cards/property.js";
import { propertySkeletonCard } from "./cards/propertySkeleton.js";

const gotop = document.getElementById('scrollToTop');
const progress = document.querySelector('.progress-circle .progress');
const radius = 18;
const circumference = 2 * Math.PI * radius;

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

let itemsPerPage = 9;
let currentPage = 1;
let currentAddType = 'all';
let currentAddressQuery = '';
let currentMinArea = '';
let currentMaxArea = '';
let currentMinPrice = '';
let currentMaxPrice = '';
let selectedCategory = 'All Categories';
let selectedCity = 'All Cities';


let allPropertiesData = []; 
let filteredPropertiesForMainDisplay = [];
let filteredPremiumProperties = [];

function getTotalPagesForMainDisplay() {
    return Math.ceil(filteredPropertiesForMainDisplay.length / itemsPerPage);
}

function showPage(page) {
    currentPage = page;
    const totalItemsToDisplay = filteredPropertiesForMainDisplay.length;
    const totalPages = getTotalPagesForMainDisplay();
    const startIndex = (page - 1) * itemsPerPage;
    const endIndex = Math.min(startIndex + itemsPerPage, totalItemsToDisplay);

    let currentCardsHtml = '';

    if (totalItemsToDisplay > 0) {
        for (let i = startIndex; i < endIndex; i++) {
            if (filteredPropertiesForMainDisplay[i]) {
                currentCardsHtml += propertyCard(filteredPropertiesForMainDisplay[i]);
            }
        }
    } else {
        currentCardsHtml = '<p class="col-span-full text-center text-gray-500">Elan tapılmadı.</p>';
    }

    if (propertyContainer) {
        propertyContainer.innerHTML = currentCardsHtml;
    } else {
        console.error("Error: 'propertyContainer' elementi tapılmadı.");
    }
    
    resultsText.textContent = `Göstərilir ${Math.min(startIndex + 1, totalItemsToDisplay)}-${endIndex} cəmi ${totalItemsToDisplay} elandan.`;
    if (totalItemsToDisplay === 0) {
        resultsText.textContent = 'Heç bir elan tapılmadı.';
    }

    updatePaginationUI();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updatePaginationUI() {
    const totalPages = getTotalPagesForMainDisplay();
    paginationContainer.innerHTML = '';

    if (totalPages <= 1 && filteredPropertiesForMainDisplay.length > 0) {
        paginationContainer.style.display = 'none';
        return;
    } else if (filteredPropertiesForMainDisplay.length === 0) {
        paginationContainer.style.display = 'none';
        return;
    }
    paginationContainer.style.display = 'flex';

    const prevDisabled = currentPage === 1 ? 'disabled' : '';
    paginationContainer.innerHTML += `
        <li class="page-item ${prevDisabled}">
            <a class="page-link" href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
    `;

    for (let i = 1; i <= totalPages; i++) {
        const activeClass = i === currentPage ? 'active' : '';
        paginationContainer.innerHTML += `
            <li class="page-item ${activeClass}">
                <a class="page-link" href="#">${i}</a>
            </li>
        `;
    }

    const nextDisabled = currentPage === totalPages ? 'disabled' : '';
    paginationContainer.innerHTML += `
        <li class="page-item ${nextDisabled}">
            <a class="page-link" href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    `;
}

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
        console.log("started");
    renderSkeletons(premiumCardContainer, 4);
    renderSkeletons(propertyContainer, itemsPerPage);


    const searchParams = {
        adType: currentAddType === 'all' ? '' : currentAddType,
        address: currentAddressQuery,
        min_area: currentMinArea,
        max_area: currentMaxArea,
        min_price: currentMinPrice,
        max_price: currentMaxPrice,
        category: selectedCategory === 'All Categories' ? '' : selectedCategory,
        city: selectedCity === 'All Cities' ? '' : selectedCity,
       
    };

    try {
        const fetchedPropertiesArray = await getPropertiesList(searchParams); 
        console.log('API-dən gələn filterlənmiş data (listing.js):', fetchedPropertiesArray);

        if (!Array.isArray(fetchedPropertiesArray)) {
            console.warn("API-dən gözlənilməyən data formatı gəldi (filterAndRenderProperties): massiv gözlənilir. Aldığımız:", fetchedPropertiesArray);
            allPropertiesData = []; 
        } else {
            allPropertiesData = fetchedPropertiesArray; 
        }

    } catch (error) {
        console.error("Əmlaklar çəkilərkən xəta:", error);
        allPropertiesData = [];
        premiumCardContainer.innerHTML = '<p class="text-red-500 text-center col-span-full">Premium elanlar yüklənərkən xəta baş verdi.</p>';
        propertyContainer.innerHTML = '<p class="text-red-500 text-center col-span-full">Elanlar yüklənərkən xəta baş verdi.</p>';
        premiumLoadingOverlay.style.display = 'none';
        allPropertiesLoadingOverlay.style.display = 'none';
        return;
    }


    filteredPropertiesForMainDisplay = [...allPropertiesData]; 

   
    filteredPremiumProperties = filteredPropertiesForMainDisplay.filter(property => property.is_premium);

    let premiumCardsHtml = '';
    // const displayedPremiumProperties = filteredPremiumProperties.slice(0, 4);
    const displayedPremiumProperties = filteredPremiumProperties;

    if (displayedPremiumProperties.length > 0) {
        displayedPremiumProperties.forEach(property => {
            premiumCardsHtml += propertyCard(property);
        });
    } else {
        premiumCardsHtml = '<p class="col-span-full text-center text-gray-500">Axtarışınıza uyğun premium elan tapılmadı.</p>';
    }

    if (premiumCardContainer) {
        premiumCardContainer.innerHTML = premiumCardsHtml;
    } else {
        console.error("Error: 'premiumCardContainer' elementi tapılmadı.");
    }

    console.log('Əsas konteynerdə göstəriləcək elanların sayı (backenddən gələn):', filteredPropertiesForMainDisplay.length);
    console.log('Filterlənmiş premium elanların sayı (backenddən gələn datadan filterlənən):', filteredPremiumProperties.length);

    currentPage = 1;
    showPage(currentPage);

    premiumLoadingOverlay.style.display = 'none';
    allPropertiesLoadingOverlay.style.display = 'none';
}

window.addEventListener('scroll', () => {
    const scrollTop = window.scrollY;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    const scrollPercent = scrollTop / docHeight;

    const offset = circumference - scrollPercent * circumference;
    if (progress) progress.style.strokeDashoffset = offset;

    if (gotop) {
        if (scrollTop > window.innerHeight / 2) {
            gotop.style.display = 'flex';
        } else {
            gotop.style.display = 'none';
        }
    }
});

if (gotop) {
    gotop.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

document.addEventListener('DOMContentLoaded', async () => {
    if (!propertyContainer || !premiumCardContainer || !premiumLoadingOverlay || !allPropertiesLoadingOverlay || !paginationContainer || !resultsText) {
        console.error('Lazımi elementlərdən biri tapılmadı (konteynerlər, yükləmə qatmanları, səhifələmə elementləri)!');
        return;
    }

    if (paginationContainer) {
        paginationContainer.addEventListener('click', function (e) {
            e.preventDefault();
            if (e.target.closest('.page-link')) {
                const target = e.target.closest('.page-link');
                const action = target.getAttribute('aria-label');
                const totalPages = getTotalPagesForMainDisplay();

                if (action === 'Previous' && currentPage > 1) {
                    showPage(currentPage - 1);
                } else if (action === 'Next' && currentPage < totalPages) {
                    showPage(currentPage + 1);
                } else if (!action) { 
                    const pageNumber = parseInt(target.textContent);
                    if (!isNaN(pageNumber)) {
                        showPage(pageNumber);
                    }
                }
            }
        });
    }

    const toggleButtons = [gridBtn, listBtn];
    toggleButtons.forEach(btn => {
        if (btn) {
            btn.addEventListener("click", () => {
                toggleButtons.forEach(b => {
                    if (b) b.classList.remove("active-filter");
                });
                btn.classList.add("active-filter");
            });
        }
    });

    if (gridBtn) {
        gridBtn.addEventListener('click', () => {
            propertyContainer.classList.remove('list-view');
            propertyContainer.classList.add(
                'grid', 'grid-cols-1', 'sm:grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-2', 'xl:grid-cols-4'
            );
            premiumCardContainer.classList.remove('list-view');
            premiumCardContainer.classList.add(
                'grid', 'grid-cols-1', 'sm:grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-2', 'xl:grid-cols-4'
            );
            showPage(currentPage);
        });
    }

    if (listBtn) {
        listBtn.addEventListener('click', () => {
            propertyContainer.classList.add('list-view');
            propertyContainer.classList.remove(
                'grid', 'grid-cols-1', 'sm:grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-2', 'xl:grid-cols-4'
            );
            premiumCardContainer.classList.add('list-view');
            premiumCardContainer.classList.remove(
                'grid', 'grid-cols-1', 'sm:grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-2', 'xl:grid-cols-4'
            );
            showPage(currentPage);
        });
    }

    const filterButtons = document.querySelectorAll('button[data-add-type]');
    filterButtons.forEach(button => {
        button.addEventListener('click', async () => {
            const selectedAddType = button.getAttribute('data-add-type');
            currentAddType = selectedAddType;

            if (selectedAddType === 'all') {
                currentAddressQuery = '';
                if (addressInput) addressInput.value = '';
                if (addressSuggestionsDiv) addressSuggestionsDiv.classList.add('hidden');

                currentMinArea = '';
                if (minAreaInput) minAreaInput.value = '';
                currentMaxArea = '';
                if (maxAreaInput) maxAreaInput.value = '';

                currentMinPrice = '';
                if (minPriceInput) minPriceInput.value = '';
                currentMaxPrice = '';
                if (maxPriceInput) maxPriceInput.value = '';
                selectedCategory = 'All Categories';
                document.querySelector('[x-text="selectedCategory"]').textContent = 'All Categories'; 
                selectedCity = 'All Cities';
                document.querySelector('[x-text="selectedCity"]').textContent = 'All Cities';
            }

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

    if (addressInput) {
        addressInput.addEventListener('input', (event) => {
            currentAddressQuery = event.target.value;
           
            updateAddressSuggestions(currentAddressQuery); 
        });
        document.addEventListener('click', (event) => {
            if (addressSuggestionsDiv && !addressInput.contains(event.target) && !addressSuggestionsDiv.contains(event.target)) {
                addressSuggestionsDiv.classList.add('hidden');
            }
        });
    }

    if (minAreaInput) {
        minAreaInput.addEventListener('input', (event) => {
            currentMinArea = event.target.value;
        });
    }
    if (maxAreaInput) {
        maxAreaInput.addEventListener('input', (event) => {
            currentMaxArea = event.target.value;
        });
    }

    if (minPriceInput) {
        minPriceInput.addEventListener('input', (event) => {
            currentMinPrice = event.target.value;
        });
    }
    if (maxPriceInput) {
        maxPriceInput.addEventListener('input', (event) => {
            currentMaxPrice = event.target.value;
        });
    }

    if (searchButton) {
        searchButton.addEventListener('click', () => {
            filterAndRenderProperties();
        });
    }

    document.querySelectorAll('.relative.p-3.bg-gray-50.rounded-lg.border.border-gray-200.cursor-pointer').forEach(dropdown => {
        if (dropdown.querySelector('[x-text="selectedCategory"]')) {
            dropdown.querySelectorAll('ul li').forEach(item => {
                item.addEventListener('click', (e) => {
                    const category = e.target.textContent.trim();
                    selectedCategory = category;
                    filterAndRenderProperties();
                });
            });
        } else if (dropdown.querySelector('[x-text="selectedCity"]')) {
            dropdown.querySelectorAll('ul li').forEach(item => {
                item.addEventListener('click', (e) => {
                    const city = e.target.textContent.trim();
                    selectedCity = city;
                    filterAndRenderProperties();
                });
            });
        }
    });

    const allButton = document.querySelector('button[data-add-type="all"]');
    if (allButton) {
        allButton.classList.add('bg-[color:var(--primary)]', 'text-white');
        allButton.classList.remove('bg-white', 'text-gray-700', 'hover:bg-gray-100');
        await filterAndRenderProperties();
    } else {
        await filterAndRenderProperties();
    }
});