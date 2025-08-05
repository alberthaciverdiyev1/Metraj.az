import { getPropertiesList } from "../../components/property.js";
import { propertyCard } from "../../cards/property.js";
import { propertySkeletonCard } from "../../cards/propertySkeleton.js";

const gotop = document.getElementById("scrollToTop");
const progress = document.querySelector(".progress-circle .progress");
const radius = 18;
const circumference = 2 * Math.PI * radius;

const gridBtn = document.getElementById("gridViewBtn");
const listBtn = document.getElementById("listViewBtn");
const propertyContainer = document.getElementById("propertyContainer");
const premiumCardContainer = document.getElementById("premiumCard");
const premiumLoadingOverlay = document.getElementById("premiumLoadingOverlay");
const allPropertiesLoadingOverlay = document.getElementById("allPropertiesLoadingOverlay");

const addressInput = document.getElementById("addressInput");
const addressSuggestionsDiv = document.getElementById("addressSuggestions");
const suggestionsList = document.getElementById("suggestionsList");


const minAreaInput = document.querySelector('[data-role="min-area-input"]');
const maxAreaInput = document.querySelector('[data-type="max-area-input"]');
const minPriceInput = document.getElementById("minPriceInput");
const maxPriceInput = document.getElementById("maxPriceInput");
const adNoInput = document.querySelector('[data-role="adno-search-input"]');
let currentPage = 1;
const ITEMS_PER_PAGE = 20;
let isLoading = false;
let hasMoreAds = true;

let currentAddType = "all";
let currentAddressQuery = "";
let currentMinArea = "";
let currentMaxArea = "";
let currentMinPrice = "";
let currentMaxPrice = "";
let currentAdNoQuery = "";
let selectedBuildingType = "All Categories";
let selectedCity = "All Cities";

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
        addType: currentAddType === "all" ? "" : currentAddType,
        address: currentAddressQuery,
        min_area: currentMinArea,
        max_area: currentMaxArea,
        min_price: currentMinPrice,
        max_price: currentMaxPrice,
        propertyType: selectedBuildingType === "All Categories" ? "" : selectedBuildingType,
        cityId: selectedCity === "All Cities" ? "" : selectedCity,
        adNo: currentAdNoQuery,
    };
    console.log(searchParams);
    try {
        const fetchedProperties = await getPropertiesList(searchParams);

        if (isNewSearch) {
            const premiumProperties = fetchedProperties.filter(
                (property) => property.is_premium
            );
            let premiumCardsHtml = "";
            if (premiumProperties.length > 0) {
                premiumProperties.forEach((property) => {
                    premiumCardsHtml += propertyCard(property);
                });
            } else {
                premiumCardsHtml =
                    '<p class="col-span-full text-center text-gray-500">Axtarışınıza uyğun premium elan tapılmadı.</p>';
            }
            premiumCardContainer.innerHTML = premiumCardsHtml;
        }

        removeSkeletons();

        if (fetchedProperties && fetchedProperties.length > 0) {
            let cardsHtml = "";
            fetchedProperties.forEach((property) => {
                cardsHtml += propertyCard(property);
            });
            propertyContainer.insertAdjacentHTML("beforeend", cardsHtml);
            currentPage++;
            hasMoreAds = fetchedProperties.length === ITEMS_PER_PAGE;
        } else {
            hasMoreAds = false;
            if (isNewSearch) {
                propertyContainer.innerHTML =
                    '<p class="col-span-full text-center text-gray-500">Elan tapılmadı.</p>';
            } else {
                console.log("Bütün elanlar yükləndi.");
            }
        }
    } catch (error) {
        console.error("Əmlaklar çəkilərkən xəta:", error);
        hasMoreAds = false;
        removeSkeletons();
        if (isNewSearch) {
            propertyContainer.innerHTML =
                '<p class="text-red-500 text-center col-span-full">Elanlar yüklənərkən xəta baş verdi.</p>';
            premiumCardContainer.innerHTML =
                '<p class="text-red-500 text-center col-span-full">Premium elanlar yüklənərkən xəta baş verdi.</p>';
        } else {
            console.error("Sonsuz sürüşmə: Daha çox əmlak çəkilərkən xəta.");
        }
    } finally {
        isLoading = false;
        premiumLoadingOverlay.style.display = "none";
        allPropertiesLoadingOverlay.style.display = "none";
    }
}

function removeSkeletons() {
    const currentSkeletons = propertyContainer.querySelectorAll(
        ".property-skeleton-card"
    );
    currentSkeletons.forEach((skeleton) => skeleton.remove());

    const premiumSkeletons = premiumCardContainer.querySelectorAll(
        ".property-skeleton-card"
    );
    premiumSkeletons.forEach((skeleton) => skeleton.remove());
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

document.addEventListener("DOMContentLoaded", async () => {
    if (
        !propertyContainer ||
        !premiumCardContainer ||
        !premiumLoadingOverlay ||
        !allPropertiesLoadingOverlay
    ) {
        console.error(
            "Lazımi elementlərdən biri tapılmadı (konteynerlər, yükləmə qatmanları)!"
        );
        return;
    }

    await fetchAndAppendProperties(true);

    const toggleButtons = [gridBtn, listBtn];
    toggleButtons.forEach((btn) => {
        if (btn) {
            btn.addEventListener("click", () => {
                toggleButtons.forEach((b) => {
                    if (b) b.classList.remove("active-filter");
                });
                btn.classList.add("active-filter");
            });
        }
    });

    if (gridBtn) {
        gridBtn.addEventListener("click", () => {
            propertyContainer.classList.remove("list-view");
            propertyContainer.classList.add(
                "grid",
                "grid-cols-1",
                "sm:grid-cols-1",
                "md:grid-cols-2",
                "lg:grid-cols-2",
                "xl:grid-cols-4"
            );
            premiumCardContainer.classList.remove("list-view");
            premiumCardContainer.classList.add(
                "grid",
                "grid-cols-1",
                "sm:grid-cols-1",
                "md:grid-cols-2",
                "lg:grid-cols-2",
                "xl:grid-cols-4"
            );
        });
    }

    if (listBtn) {
        listBtn.addEventListener("click", () => {
            propertyContainer.classList.add("list-view");
            propertyContainer.classList.remove(
                "grid",
                "grid-cols-1",
                "sm:grid-cols-1",
                "md:grid-cols-2",
                "lg:grid-cols-2",
                "xl:grid-cols-4"
            );
            premiumCardContainer.classList.add("list-view");
            premiumCardContainer.classList.remove(
                "grid",
                "grid-cols-1",
                "sm:grid-cols-1",
                "md:grid-cols-2",
                "lg:grid-cols-2",
                "xl:grid-cols-4"
            );
        });
    }

    const filterButtons = document.querySelectorAll("button[data-add-type]");
    filterButtons.forEach((button) => {
        button.addEventListener("click", async () => {
            const selectedAddType = button.getAttribute("data-add-type");
            currentAddType = selectedAddType;

            if (selectedAddType === "all") {
                currentAddressQuery = "";
                if (addressInput) addressInput.value = "";
                if (addressSuggestionsDiv)
                    addressSuggestionsDiv.classList.add("hidden");

                currentMinArea = "";
                if (minAreaInput) minAreaInput.value = "";
                currentMaxArea = "";
                if (maxAreaInput) maxAreaInput.value = "";

                currentMinPrice = "";
                if (minPriceInput) minPriceInput.value = "";
                currentMaxPrice = "";
                if (maxPriceInput) maxPriceInput.value = "";

                selectedBuildingType = "All Categories";
                document.querySelector('[x-text="selectedBuildingType"]').textContent =
                    "All Categories";
                selectedCity = "All Cities";
                document.querySelector('[x-text="selectedCity"]').textContent =
                    "All Cities";
            }

            filterButtons.forEach((btn) => {
                btn.classList.remove("bg-[color:var(--primary)]", "text-white");
                btn.classList.add("bg-white", "text-gray-700", "hover:bg-gray-100");
            });

            button.classList.add("bg-[color:var(--primary)]", "text-white");
            button.classList.remove("bg-white", "text-gray-700", "hover:bg-gray-100");

            await fetchAndAppendProperties(true);
        });
    });

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
                    if (
                        property.address &&
                        property.address.toLowerCase().includes(lowerCaseQuery)
                    ) {
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
                        addressInput.value = address;
                        currentAddressQuery = address;
                        addressSuggestionsDiv.classList.add("hidden");
                        fetchAndAppendProperties(true);
                    });
                    suggestionsList.appendChild(li);
                });
            } else {
                addressSuggestionsDiv.classList.add("hidden");
            }
        });
    }

    if (addressInput) {
        addressInput.addEventListener("input", (event) => {
            currentAddressQuery = event.target.value;
            updateAddressSuggestions(currentAddressQuery);
        });
        document.addEventListener("click", (event) => {
            if (
                addressSuggestionsDiv &&
                !addressInput.contains(event.target) &&
                !addressSuggestionsDiv.contains(event.target)
            ) {
                addressSuggestionsDiv.classList.add("hidden");
            }
        });
    }

    if (minAreaInput) {
        minAreaInput.addEventListener("input", (event) => {
            currentMinArea = event.target.value;
        });
    }
    if (maxAreaInput) {
        maxAreaInput.addEventListener("input", (event) => {
            currentMaxArea = event.target.value;
        });
    }

    if (minPriceInput) {
        minPriceInput.addEventListener("input", (event) => {
            currentMinPrice = event.target.value;
        });
    }
    if (maxPriceInput) {
        maxPriceInput.addEventListener("input", (event) => {
            currentMaxPrice = event.target.value;
        });
    }

    document.querySelector('[data-role="search-button"]').addEventListener("click", () => {
        fetchAndAppendProperties(true);
    });


    document.querySelectorAll(
        ".relative.p-3.bg-gray-50.rounded-lg.border.border-gray-200.cursor-pointer"
    )
        .forEach((dropdown) => {
            const categoryDisplaySpan = dropdown.querySelector(
                '[x-text="selectedBuildingType"]'
            );
            const cityDisplaySpan = dropdown.querySelector('[x-text="selectedCity"]');

            if (categoryDisplaySpan) {
                dropdown.querySelectorAll("ul li").forEach((item) => {
                    item.addEventListener("click", (e) => {
                        const category = e.target.textContent.trim();
                        selectedBuildingType = category;
                        categoryDisplaySpan.textContent = category;
                        fetchAndAppendProperties(true);
                    });
                });
            } else if (cityDisplaySpan) {
                dropdown.querySelectorAll("ul li").forEach((item) => {
                    item.addEventListener("click", (e) => {
                        const city = e.target.textContent.trim();
                        selectedCity = city;
                        cityDisplaySpan.textContent = city;
                        fetchAndAppendProperties(true);
                    });
                });
            }
        });


    if (adNoInput) {
        adNoInput.addEventListener('input', (event) => {
            currentAdNoQuery = event.target.value;
            fetchAndAppendProperties(true);
        });

        adNoInput.addEventListener('change', () => {
            if (adNoInput.value === '') {
                currentAdNoQuery = '';
                fetchAndAppendProperties(true);
            }
        });
    }
});
