import { getPropertiesList } from "./components/property.js";
import { propertyCard } from "./cards/property.js";
import { propertySkeletonCard } from "./cards/propertySkeleton.js";

const gotop = document.getElementById("scrollToTop");
const progress = document.querySelector(".progress-circle .progress");
const propertyContainer = document.getElementById("propertyContainer");
const premiumCardContainer = document.getElementById("premiumCard");
const premiumLoadingOverlay = document.getElementById("premiumLoadingOverlay");
const allPropertiesLoadingOverlay = document.getElementById("allPropertiesLoadingOverlay");
const gridBtn = document.getElementById("gridViewBtn");
const listBtn = document.getElementById("listViewBtn");
const filterPanel = document.querySelector('[data-role="filter-panel"]');
const filterButtons = document.querySelectorAll("button[data-add-type]");
const adNoInput = document.querySelector('[data-role="adno-search-input"]');
const minPriceInput = document.getElementById("minPriceInput");
const maxPriceInput = document.getElementById("maxPriceInput");
const minAreaInput = document.querySelector('[data-role="min-area-input"]');
const maxAreaInput = document.querySelector('[data-type="max-area-input"]');
const searchButton = document.querySelector('[data-role="search-button"]');
const moreFiltersModal = document.querySelector('[data-role="more-filters-modal"]');
const moreFiltersButton = document.querySelector('[data-role="more-filters-button"]');
const closeMoreFiltersButton = document.querySelector('[data-role="close-more-filters"]');
const resetButton = document.querySelector('[data-role="reset-button"]');
const dropdowns = document.querySelectorAll('[data-role="dropdown"]');
const addressInput = document.getElementById("addressInput");
const addressSuggestionsDiv = document.getElementById("addressSuggestions");
const suggestionsList = document.getElementById("suggestionsList");

let currentPage = 1;
const ITEMS_PER_PAGE = 20;
let isLoading = false;
let hasMoreAds = true;
const radius = 18;
const circumference = 2 * Math.PI * radius;

let currentAdType = "all";
let currentMinPrice = "";
let currentMaxPrice = "";
let currentMinArea = "";
let currentMaxArea = "";
let currentAdNoQuery = "";
let selectedBuildingType = "";
let selectedCityName = "";
let selectedRoomCount = "";
let selectedFloorLocated = "";
let selectedNumberOfFloors = "";
let currentAddressQuery = "";

function renderSkeletons(container, count) {
    let skeletonsHtml = "";
    for (let i = 0; i < count; i++) {
        skeletonsHtml += propertySkeletonCard();
    }
    container.innerHTML = skeletonsHtml;
}

function appendSkeletons(container, count) {
    let skeletonsHtml = "";
    for (let i = 0; i < count; i++) {
        skeletonsHtml += propertySkeletonCard();
    }
    container.insertAdjacentHTML("beforeend", skeletonsHtml);
}

function removeSkeletons() {
    const currentSkeletons = propertyContainer.querySelectorAll(".property-skeleton-card");
    currentSkeletons.forEach((skeleton) => skeleton.remove());
    const premiumSkeletons = premiumCardContainer.querySelectorAll(".property-skeleton-card");
    premiumSkeletons.forEach((skeleton) => skeleton.remove());
}

async function fetchAndAppendProperties(isNewSearch = false) {
    if (isLoading || (!hasMoreAds && !isNewSearch)) {
        return;
    }

    isLoading = true;
    if (isNewSearch) {
        propertyContainer.innerHTML = "";
        premiumCardContainer.innerHTML = "";
        currentPage = 1;
        hasMoreAds = true;
        renderSkeletons(premiumCardContainer, 4);
        renderSkeletons(propertyContainer, ITEMS_PER_PAGE);
    } else {
        appendSkeletons(propertyContainer, ITEMS_PER_PAGE);
    }

    premiumLoadingOverlay.style.display = "flex";
    allPropertiesLoadingOverlay.style.display = "flex";

    const searchParams = {
        page: currentPage,
        limit: ITEMS_PER_PAGE,
        adType: currentAdType === "all" ? "" : currentAdType,
        priceMin: currentMinPrice,
        priceMax: currentMaxPrice,
        areaMin: currentMinArea,
        areaMax: currentMaxArea,
        adNo: currentAdNoQuery,
        buildingType: selectedBuildingType,
        address: currentAddressQuery,
        roomCount: selectedRoomCount,
        floorLocated: selectedFloorLocated,
        numberOfFloors: selectedNumberOfFloors,
    };
    
    const cleanParams = {};
    for (const key in searchParams) {
        if (searchParams[key] !== "" && searchParams[key] !== undefined && searchParams[key] !== null) {
            cleanParams[key] = searchParams[key];
        }
    }
    
    console.log("Göndərilən parametrlər:", cleanParams);

    try {
        const fetchedProperties = await getPropertiesList(cleanParams);
        
        if (isNewSearch) {
            const premiumProperties = fetchedProperties.filter((property) => property.is_premium);
            let premiumCardsHtml = "";
            if (premiumProperties.length > 0) {
                premiumProperties.forEach((property) => {
                    premiumCardsHtml += propertyCard(property);
                });
            } else {
                premiumCardsHtml = '<p class="col-span-full text-center text-gray-500">Axtarışınıza uyğun premium elan tapılmadı.</p>';
            }
            premiumCardContainer.innerHTML = premiumCardsHtml;
        }

        removeSkeletons();

        let cardsHtml = "";
        if (fetchedProperties && fetchedProperties.length > 0) {
            fetchedProperties.forEach((property) => {
                cardsHtml += propertyCard(property);
            });
            propertyContainer.insertAdjacentHTML("beforeend", cardsHtml);
            currentPage++;
            hasMoreAds = fetchedProperties.length === ITEMS_PER_PAGE;
        } else {
            hasMoreAds = false;
            if (isNewSearch) {
                propertyContainer.innerHTML = '<p class="col-span-full text-center text-gray-500">Elan tapılmadı.</p>';
            } else {
                console.log("Bütün elanlar yükləndi.");
            }
        }
    } catch (error) {
        console.error("Əmlaklar çəkilərkən xəta:", error);
        hasMoreAds = false;
        removeSkeletons();
        if (isNewSearch) {
            propertyContainer.innerHTML = '<p class="text-red-500 text-center col-span-full">Elanlar yüklənərkən xəta baş verdi.</p>';
            premiumCardContainer.innerHTML = '<p class="text-red-500 text-center col-span-full">Premium elanlar yüklənərkən xəta baş verdi.</p>';
        }
    } finally {
        isLoading = false;
        premiumLoadingOverlay.style.display = "none";
        allPropertiesLoadingOverlay.style.display = "none";
    }
}

window.addEventListener("scroll", () => {
    const scrollTop = window.scrollY;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    const scrollPercent = docHeight > 0 ? scrollTop / docHeight : 0;
    if (progress) {
        const offset = circumference - scrollPercent * circumference;
        progress.style.strokeDashoffset = offset;
    }
    if (gotop) {
        if (scrollTop > window.innerHeight / 2) {
            gotop.style.display = "flex";
        } else {
            gotop.style.display = "none";
        }
    }
    
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 300) {
        fetchAndAppendProperties(false);
    }
});

if (gotop) {
    gotop.addEventListener("click", () => {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });
}

function updateAddressSuggestions(query) {
    suggestionsList.innerHTML = "";
    if (query.length < 2) {
        addressSuggestionsDiv.classList.add("hidden");
        return;
    }
    
    const uniqueAddresses = new Set();
    const lowerCaseQuery = query.toLowerCase();

    async function fetchAllAddressesForSuggestions() {
        try {
            const allPropsForSuggestions = await getPropertiesList({
                page: 1,
                limit: 999999,
            });
            allPropsForSuggestions.forEach((property) => {
                if (property.address && property.address.toLowerCase().includes(lowerCaseQuery)) {
                    uniqueAddresses.add(property.address);
                }
            });
        } catch (error) {
            console.error("Ünvan təkliflərini çəkərkən xəta:", error);
        }
    }

    fetchAllAddressesForSuggestions().then(() => {
        if (uniqueAddresses.size > 0) {
            addressSuggestionsDiv.classList.remove("hidden");
            uniqueAddresses.forEach((address) => {
                const li = document.createElement("li");
                li.classList.add("p-2", "hover:bg-gray-100", "cursor-pointer");
                li.textContent = address;
                li.addEventListener("click", () => {
                    if(addressInput) addressInput.value = address;
                    currentAddressQuery = address;
                    if(addressSuggestionsDiv) addressSuggestionsDiv.classList.add("hidden");
                    fetchAndAppendProperties(true);
                });
                suggestionsList.appendChild(li);
            });
        } else {
            addressSuggestionsDiv.classList.add("hidden");
        }
    });
}

document.addEventListener("DOMContentLoaded", async () => {
    await fetchAndAppendProperties(true);

    if (filterButtons) {
        filterButtons.forEach((button) => {
            button.addEventListener("click", () => {
                currentAdType = button.getAttribute("data-add-type");
                filterButtons.forEach((btn) => {
                    btn.classList.remove("active", "bg-[color:var(--primary)]", "text-white");
                });
                button.classList.add("active", "bg-[color:var(--primary)]", "text-white");
                fetchAndAppendProperties(true);
            });
        });
    }

    if (minPriceInput) minPriceInput.addEventListener("input", (e) => currentMinPrice = e.target.value);
    if (maxPriceInput) maxPriceInput.addEventListener("input", (e) => currentMaxPrice = e.target.value);
    if (minAreaInput) minAreaInput.addEventListener("input", (e) => currentMinArea = e.target.value);
    if (maxAreaInput) maxAreaInput.addEventListener("input", (e) => currentMaxArea = e.target.value);
    
    if (adNoInput) {
        adNoInput.addEventListener('input', (e) => {
            currentAdNoQuery = e.target.value;
            fetchAndAppendProperties(true);
        });
        adNoInput.addEventListener('change', () => {
            if (adNoInput.value === '') fetchAndAppendProperties(true);
        });
    }

    if (searchButton) {
        searchButton.addEventListener("click", () => {
            fetchAndAppendProperties(true);
        });
    }

    if (addressInput) {
        addressInput.addEventListener("input", (event) => {
            currentAddressQuery = event.target.value;
            updateAddressSuggestions(currentAddressQuery);
        });
        document.addEventListener("click", (event) => {
            if (addressSuggestionsDiv && !addressInput.contains(event.target) && !addressSuggestionsDiv.contains(event.target)) {
                addressSuggestionsDiv.classList.add("hidden");
            }
        });
    }

    if (dropdowns) {
        dropdowns.forEach(dropdown => {
            const header = dropdown.querySelector('.flex.items-center.justify-between');
            const list = dropdown.querySelector('div.absolute');
    
            if (header) {
                header.addEventListener('click', () => {
                    dropdowns.forEach(otherDropdown => {
                        if (otherDropdown !== dropdown) {
                            const otherList = otherDropdown.querySelector('div.absolute');
                            const otherIcon = otherDropdown.querySelector('.bi-chevron-down');
                            if (otherList) otherList.classList.add('hidden');
                            if (otherIcon) otherIcon.classList.remove('rotate-180');
                        }
                    });
                    if (list) list.classList.toggle('hidden');
                    const icon = header.querySelector('.bi-chevron-down');
                    if (icon) icon.classList.toggle('rotate-180');
                });
            }
        
            dropdown.querySelectorAll("ul li").forEach((item) => {
                item.addEventListener("click", (e) => {
                    const filterType = dropdown.getAttribute('data-filter');
                    const value = e.target.getAttribute('data-value');
                    const textContent = e.target.textContent.trim();
                    const displayElement = dropdown.querySelector(`[data-role="display-value"][data-filter="${filterType}"]`);
    
                    switch (filterType) {
                        case "buildingType":
                            selectedBuildingType = value === "All Categories" ? "" : value;
                            break;
                        case "city":
                            selectedCityName = textContent === "All Cities" ? "" : textContent;
                            break;
                        case "roomCount":
                            selectedRoomCount = value;
                            break;
                        case "floorLocated":
                            selectedFloorLocated = value;
                            break;
                        case "numberOfFloors":
                            selectedNumberOfFloors = value;
                            break;
                    }
                    if(displayElement) displayElement.textContent = textContent;
                    if(list) list.classList.add('hidden');
                    const icon = header.querySelector('.bi-chevron-down');
                    if (icon) icon.classList.remove('rotate-180');
                    fetchAndAppendProperties(true);
                });
            });
        });
    }

    if (moreFiltersButton) {
        moreFiltersButton.addEventListener("click", () => {
            moreFiltersModal.classList.remove('hidden');
            if (filterPanel) filterPanel.classList.add('blur-effect');
        });
    }

    if (closeMoreFiltersButton) {
        closeMoreFiltersButton.addEventListener("click", () => {
            moreFiltersModal.classList.add('hidden');
            if (filterPanel) filterPanel.classList.remove('blur-effect');
        });
    }

    if (resetButton) {
        resetButton.addEventListener("click", () => {
            currentAdType = "all";
            currentMinPrice = "";
            currentMaxPrice = "";
            currentMinArea = "";
            currentMaxArea = "";
            currentAdNoQuery = "";
            currentAddressQuery = "";
            selectedBuildingType = "";
            selectedCityName = "";
            selectedRoomCount = "";
            selectedFloorLocated = "";
            selectedNumberOfFloors = "";

            filterButtons.forEach(btn => btn.classList.remove('active', 'bg-[color:var(--primary)]', 'text-white'));
            const allButton = document.querySelector("button[data-add-type='all']");
            if (allButton) allButton.classList.add('active', 'bg-[color:var(--primary)]', 'text-white');
            
            if (minPriceInput) minPriceInput.value = "";
            if (maxPriceInput) maxPriceInput.value = "";
            if (minAreaInput) minAreaInput.value = "";
            if (maxAreaInput) maxAreaInput.value = "";
            if (adNoInput) adNoInput.value = "";
            if (addressInput) addressInput.value = "";
            
            const buildingTypeDisplay = document.querySelector('[data-role="display-value"][data-filter="buildingType"]');
            if (buildingTypeDisplay) buildingTypeDisplay.textContent = "All Categories";
            
            const cityDisplay = document.querySelector('[data-role="display-value"][data-filter="city"]');
            if (cityDisplay) cityDisplay.textContent = "All Cities";
            
            const roomCountDisplay = document.querySelector('[data-role="display-value"][data-filter="roomCount"]');
            if (roomCountDisplay) roomCountDisplay.textContent = "Otaq sayı";
            
            const floorLocatedDisplay = document.querySelector('[data-role="display-value"][data-filter="floorLocated"]');
            if (floorLocatedDisplay) floorLocatedDisplay.textContent = "Yerləşən mərtəbə";

            const numberOfFloorsDisplay = document.querySelector('[data-role="display-value"][data-filter="numberOfFloors"]');
            if (numberOfFloorsDisplay) numberOfFloorsDisplay.textContent = "Binanın mərtəbə sayı";

            if(moreFiltersModal) moreFiltersModal.classList.add('hidden');
            if(filterPanel) filterPanel.classList.remove('blur-effect');

            fetchAndAppendProperties(true);
        });
    }

    if (gridBtn) {
        gridBtn.addEventListener("click", () => {
            propertyContainer.classList.remove("list-view");
            propertyContainer.classList.add("grid", "grid-cols-1", "sm:grid-cols-1", "md:grid-cols-2", "lg:grid-cols-2", "xl:grid-cols-4");
            premiumCardContainer.classList.remove("list-view");
            premiumCardContainer.classList.add("grid", "grid-cols-1", "sm:grid-cols-1", "md:grid-cols-2", "lg:grid-cols-2", "xl:grid-cols-4");
        });
    }

    if (listBtn) {
        listBtn.addEventListener("click", () => {
            propertyContainer.classList.add("list-view");
            propertyContainer.classList.remove("grid", "grid-cols-1", "sm:grid-cols-1", "md:grid-cols-2", "lg:grid-cols-2", "xl:grid-cols-4");
            premiumCardContainer.classList.add("list-view");
            premiumCardContainer.classList.remove("grid", "grid-cols-1", "sm:grid-cols-1", "md:grid-cols-2", "lg:grid-cols-2", "xl:grid-cols-4");
        });
    }
});