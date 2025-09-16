import { getPropertiesList } from "../../components/property.js";
import { propertyCard } from "../../cards/property.js";
import { propertySkeletonCard } from "../../cards/propertySkeleton.js";

const dom = {
    propertyContainer: document.getElementById("propertyContainer"),
    premiumCardContainer: document.getElementById("premiumCard"),
    premiumLoadingOverlay: document.getElementById("premiumLoadingOverlay"),
    allPropertiesLoadingOverlay: document.getElementById("allPropertiesLoadingOverlay"),
    gridBtn: document.getElementById("gridViewBtn"),
    listBtn: document.getElementById("listViewBtn"),
    scrollToTop: document.getElementById("scrollToTop"),
    progressCircle: document.querySelector(".progress-circle .progress"),
    addressInput: document.getElementById("addressInput"),
    suggestionsWrapper: document.getElementById("addressSuggestions"),
    suggestionsList: document.getElementById("suggestionsList"),
    minAreaInput: document.querySelector('[data-role="min-area-input"]'),
    maxAreaInput: document.querySelector('[data-role="max-area-input"]'),
    minPriceInput: document.getElementById("minPriceInput"),
    maxPriceInput: document.getElementById("maxPriceInput"),
    adNoInput: document.querySelector('[data-role="adno-search-input"]'),
    searchBtn: document.querySelector('[data-role="search-button"]'),
    filterButtons: document.querySelectorAll("button[data-add-type]"),
    dropdowns: document.querySelectorAll(".dropdown-select"),
    moreFiltersModal: document.querySelector('[data-role="more-filters-modal"]'),
    moreFiltersButton: document.querySelector('[data-role="more-filters-button"]'),
    closeMoreFiltersButton: document.querySelector('[data-role="close-more-filters"]'),
    resetButton: document.querySelector('[data-role="reset-button"]'),
    filterPanel: document.querySelector('[data-role="filter-panel"]'),
};

let state = {
    page: 1,
    isLoading: false,
    hasMore: true,
    filters: {
        addType: "all",
        address: "",
        minArea: "",
        maxArea: "",
        minPrice: "",
        maxPrice: "",
        adNo: "",
        buildingType: "",
        cityId: "",
        roomCount: "",
        floorLocated: "",
        numberOfFloors: "",
    },
};

const ITEMS_PER_PAGE = 20;

function createSkeletons(count) {
    return new Array(count)
        .fill(0)
        .map(() => propertySkeletonCard())
        .join("");
}

function clearSkeletons() {
    dom.propertyContainer
        .querySelectorAll(".property-skeleton-card")
        .forEach((el) => el.remove());
    dom.premiumCardContainer
        .querySelectorAll(".property-skeleton-card")
        .forEach((el) => el.remove());
}

function updateProgressCircle() {
    const radius = 18;
    const circumference = 2 * Math.PI * radius;
    const scrollTop = window.scrollY;
    const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
    const progress = scrollHeight > 0 ? scrollTop / scrollHeight : 0;
    if (dom.progressCircle) {
        dom.progressCircle.style.strokeDashoffset = circumference - progress * circumference;
    }
}

async function loadProperties(isNewSearch = false) {
    if (state.isLoading || (!state.hasMore && !isNewSearch)) return;

    state.isLoading = true;
    if (isNewSearch) {
        dom.propertyContainer.innerHTML = createSkeletons(ITEMS_PER_PAGE);
        dom.premiumCardContainer.innerHTML = createSkeletons(4);
        dom.scrollToTop.style.display = "none";
        state.page = 1;
        state.hasMore = true;
    } else {
        dom.propertyContainer.insertAdjacentHTML("beforeend", createSkeletons(ITEMS_PER_PAGE));
    }

    dom.premiumLoadingOverlay.style.display = "flex";
    dom.allPropertiesLoadingOverlay.style.display = "flex";

    const params = {
        page: state.page,
        limit: ITEMS_PER_PAGE,
        ...getFilterParams(),
    };

    console.log("Göndərilən parametrlər:", params);

    try {
        const data = await getPropertiesList(params);
        clearSkeletons();

        if (isNewSearch) {
            renderPremiumProperties(data);
        }

        if (data.length > 0) {
            const cards = data.map((p) => propertyCard(p, false)).join("");
            dom.propertyContainer.insertAdjacentHTML("beforeend", cards);
            state.page++;
            state.hasMore = data.length === ITEMS_PER_PAGE;
        } else {
            if (isNewSearch) {
                dom.propertyContainer.innerHTML = `<p class="text-center col-span-full text-gray-500">Elan tapılmadı.</p>`;
            }
            state.hasMore = false;
        }
    } catch (err) {
        console.error("Veri çekme hatası:", err);
        clearSkeletons();
        if (isNewSearch) {
            dom.propertyContainer.innerHTML = `<p class="text-red-500 text-center col-span-full">Elanlar yüklənərkən xəta baş verdi.</p>`;
            dom.premiumCardContainer.innerHTML = `<p class="text-red-500 text-center col-span-full">Premium elanlar yüklənərkən xəta baş verdi.</p>`;
        }
        state.hasMore = false;
    } finally {
        dom.premiumLoadingOverlay.style.display = "none";
        dom.allPropertiesLoadingOverlay.style.display = "none";
        state.isLoading = false;
    }
}

function getFilterParams() {
    const f = state.filters;
    const cleanParams = {};

    if (f.addType && f.addType !== "all") {
        cleanParams.adType = f.addType;
    }
    if (f.address) cleanParams.address = f.address;
    if (f.minArea) cleanParams.minArea = f.minArea;
    if (f.maxArea) cleanParams.maxArea = f.maxArea;
    if (f.minPrice) cleanParams.minPrice = f.minPrice;
    if (f.maxPrice) cleanParams.maxPrice = f.maxPrice;
    if (f.adNo) cleanParams.adNo = f.adNo;
    if (f.buildingType) cleanParams.buildingType = f.buildingType;
    if (f.cityId) cleanParams.cityId = f.cityId;
    if (f.roomCount) cleanParams.roomCount = f.roomCount;
    if (f.floorLocated) cleanParams.floorLocated = f.floorLocated;
    if (f.numberOfFloors) cleanParams.numberOfFloors = f.numberOfFloors;

    return cleanParams;
}

function renderPremiumProperties(data) {
    const premium = data.filter((p) => p.is_premium);
    dom.premiumCardContainer.innerHTML = premium.length
        ? premium.map((p) => propertyCard(p)).join("")
        : `<p class="col-span-full text-center text-gray-500">Axtarışınıza uyğun premium elan tapılmadı.</p>`;
}

function setupScrollEvents() {
    window.addEventListener("scroll", () => {
        updateProgressCircle();

        const scrollY = window.scrollY;
        const scrollThreshold = document.body.offsetHeight - window.innerHeight - 300;

        if (dom.scrollToTop) {
            dom.scrollToTop.style.display = scrollY > window.innerHeight / 2 ? "flex" : "none";
        }

        if (scrollY > scrollThreshold) {
            loadProperties(false);
        }
    });

    if (dom.scrollToTop) {
        dom.scrollToTop.addEventListener("click", () => {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    }
}

function setupFilterEvents() {
    if (dom.filterButtons) {
        dom.filterButtons.forEach((btn) => {
            btn.addEventListener("click", () => {
                state.filters.addType = btn.getAttribute("data-add-type");
                dom.filterButtons.forEach((b) =>
                    b.classList.remove(
                        "active",
                        "bg-[color:var(--primary)]",
                        "text-white"
                    )
                );
                btn.classList.add("active", "bg-[color:var(--primary)]", "text-white");
                loadProperties(true);
            });
        });
    }
}

function setupInputFilters() {
    const updateFilter = (key, value) => {
        state.filters[key] = value;
    };

    if (dom.minAreaInput)
        dom.minAreaInput.addEventListener("input", (e) => updateFilter("minArea", e.target.value));
    if (dom.maxAreaInput)
        dom.maxAreaInput.addEventListener("input", (e) => updateFilter("maxArea", e.target.value));
    if (dom.minPriceInput)
        dom.minPriceInput.addEventListener("input", (e) => updateFilter("minPrice", e.target.value));
    if (dom.maxPriceInput)
        dom.maxPriceInput.addEventListener("input", (e) => updateFilter("maxPrice", e.target.value));

    if (dom.adNoInput) {
        dom.adNoInput.addEventListener("input", (e) => {
            updateFilter("adNo", e.target.value);
            loadProperties(true);
        });
        dom.adNoInput.addEventListener("change", () => {
            if (dom.adNoInput.value === "") loadProperties(true);
        });
    }

    if (dom.searchBtn)
        dom.searchBtn.addEventListener("click", () => loadProperties(true));
}

function setupAddressSuggestions() {
    if (dom.addressInput) {
        dom.addressInput.addEventListener("input", async (e) => {
            const query = e.target.value.trim().toLowerCase();
            state.filters.address = query;

            if (query.length < 2) return dom.suggestionsWrapper.classList.add("hidden");

            try {
                const allProps = await getPropertiesList({
                    page: 1,
                    limit: 999999
                });
                const addresses = [
                    ...new Set(
                        allProps
                            .map((p) => p.address)
                            .filter((a) => a?.toLowerCase().includes(query))
                    ),
                ];

                dom.suggestionsList.innerHTML = addresses
                    .map(
                        (addr) => `
                        <li class="p-2 hover:bg-gray-100 cursor-pointer">${addr}</li>
                        `
                    )
                    .join("");

                dom.suggestionsWrapper.classList.toggle("hidden", addresses.length === 0);

                dom.suggestionsList.querySelectorAll("li").forEach((li) => {
                    li.addEventListener("click", () => {
                        dom.addressInput.value = li.textContent;
                        state.filters.address = li.textContent;
                        dom.suggestionsWrapper.classList.add("hidden");
                        loadProperties(true);
                    });
                });
            } catch (error) {
                console.error("Ünvan təkliflərini çəkərkən xəta:", error);
            }
        });

        document.addEventListener("click", (e) => {
            if (
                dom.suggestionsWrapper &&
                !dom.addressInput.contains(e.target) &&
                !dom.suggestionsWrapper.contains(e.target)
            ) {
                dom.suggestionsWrapper.classList.add("hidden");
            }
        });
    }
}

function setupViewToggle() {
    if (!dom.gridBtn || !dom.listBtn) return;

    dom.gridBtn.addEventListener("click", () => toggleView("grid"));
    dom.listBtn.addEventListener("click", () => toggleView("list"));
}

function toggleView(viewType) {
    const isGrid = viewType === "grid";
    const gridClasses = [
        "grid",
        "grid-cols-1",
        "sm:grid-cols-1",
        "md:grid-cols-2",
        "lg:grid-cols-2",
        "xl:grid-cols-4",
        "gap-6",
    ];
    const listClasses = ["list-view"];

    if (dom.propertyContainer) {
        if (isGrid) {
            dom.propertyContainer.classList.remove(...listClasses);
            dom.propertyContainer.classList.add(...gridClasses);
        } else {
            dom.propertyContainer.classList.remove(...gridClasses);
            dom.propertyContainer.classList.add(...listClasses);
        }
    }
    if (dom.premiumCardContainer) {
        if (isGrid) {
            dom.premiumCardContainer.classList.remove(...listClasses);
            dom.premiumCardContainer.classList.add(...gridClasses);
        } else {
            dom.premiumCardContainer.classList.remove(...gridClasses);
            dom.premiumCardContainer.classList.add(...listClasses);
        }
    }

    if (dom.gridBtn) dom.gridBtn.classList.toggle("active", isGrid);
    if (dom.listBtn) dom.listBtn.classList.toggle("active", !isGrid);
}

function setupDropdownFilters() {
    if (dom.dropdowns) {
        dom.dropdowns.forEach((dropdown) => {
            const header = dropdown.querySelector(".flex.items-center.justify-between");
            const list = dropdown.querySelector("div.absolute");
            const icon = dropdown.querySelector(".bi-chevron-down");

            if (header) {
                header.addEventListener("click", () => {
                    dom.dropdowns.forEach((otherDropdown) => {
                        if (otherDropdown !== dropdown) {
                            const otherList = otherDropdown.querySelector("div.absolute");
                            const otherIcon = otherDropdown.querySelector(".bi-chevron-down");
                            if (otherList) otherList.classList.add("hidden");
                            if (otherIcon) otherIcon.classList.remove("rotate-180");
                        }
                    });
                    if (list) list.classList.toggle("hidden");
                    if (icon) icon.classList.toggle("rotate-180");
                });
            }

            dropdown.querySelectorAll("ul li").forEach((item) => {
                item.addEventListener("click", (e) => {
                    const filterType = dropdown.getAttribute("data-filter");
                    const value = e.target.getAttribute("data-value");
                    const textContent = e.target.textContent.trim();
                    const displayElement = dropdown.querySelector(
                        `[data-role="display-value"][data-filter="${filterType}"]`
                    );

                    switch (filterType) {
                        case "buildingType":
                            state.filters.buildingType = value;
                            break;
                        case "city":
                            state.filters.cityId = value;
                            break;
                        case "roomCount":
                            state.filters.roomCount = value;
                            break;
                        case "floorLocated":
                            state.filters.floorLocated = value;
                            break;
                        case "numberOfFloors":
                            state.filters.numberOfFloors = value;
                            break;
                    }

                    if (displayElement) displayElement.textContent = textContent;
                    if (list) list.classList.add("hidden");
                    if (icon) icon.classList.remove("rotate-180");
                    loadProperties(true);
                });
            });
        });
    }
}

function setupMoreFiltersModal() {
    if (dom.moreFiltersButton) {
        dom.moreFiltersButton.addEventListener("click", () => {
            if (dom.moreFiltersModal) dom.moreFiltersModal.classList.remove("hidden");
            if (dom.filterPanel) dom.filterPanel.classList.add("blur-effect");
        });
    }

    if (dom.closeMoreFiltersButton) {
        dom.closeMoreFiltersButton.addEventListener("click", () => {
            if (dom.moreFiltersModal) dom.moreFiltersModal.classList.add("hidden");
            if (dom.filterPanel) dom.filterPanel.classList.remove("blur-effect");
        });
    }
}

function setupResetButton() {
    const resetButton = document.querySelector('[class*="bi-arrow-clockwise"]');

    if (resetButton) {
        resetButton.addEventListener("click", () => {
            Object.assign(state.filters, {
                addType: "all",
                address: "",
                minArea: "",
                maxArea: "",
                minPrice: "",
                maxPrice: "",
                adNo: "",
                buildingType: "",
                cityId: "",
                roomCount: "",
                floorLocated: "",
                numberOfFloors: "",
            });

            dom.filterButtons.forEach((btn) => {
                btn.classList.remove("bg-[color:var(--primary)]", "text-white");
                btn.classList.add("bg-white", "text-gray-700");
            });
            const allButton = document.querySelector("button[data-add-type='all']");
            if (allButton) {
                allButton.classList.remove("bg-white", "text-gray-700");
                allButton.classList.add("bg-[color:var(--primary)]", "text-white");
            }

            if (dom.minPriceInput) dom.minPriceInput.value = "";
            if (dom.maxPriceInput) dom.maxPriceInput.value = "";
            if (dom.minAreaInput) dom.minAreaInput.value = "";
            if (dom.maxAreaInput) dom.maxAreaInput.value = "";
            if (dom.adNoInput) dom.adNoInput.value = "";
            if (dom.addressInput) dom.addressInput.value = "";

            const dropdownsToReset = [
                { type: "buildingType", text: "Bütün Kateqoriyalar" },
                { type: "city", text: "Bütün Şəhərlər" },
                { type: "roomCount", text: "Otaq sayı" },
                { type: "floorLocated", text: "Yerləşən mərtəbə" },
                { type: "numberOfFloors", text: "Binanın mərtəbə sayı" },
            ];

            dropdownsToReset.forEach((item) => {
                const display = document.querySelector(`[data-role="display-value"][data-filter="${item.type}"]`);
                if (display) display.textContent = item.text;

                const listItems = document.querySelectorAll(`[data-filter="${item.type}"] li`);
                listItems.forEach(li => {
                    if (li.textContent.trim() === item.text) {
                        li.classList.add('selected');
                    } else {
                        li.classList.remove('selected');
                    }
                });
            });

            if (dom.moreFiltersModal) dom.moreFiltersModal.classList.add("hidden");
            if (dom.filterPanel) dom.filterPanel.classList.remove("blur-effect");

            loadProperties(true);
        });
    }
}
function initDropdowns() {
    const dropdowns = document.querySelectorAll(".dropdown-select");

    dropdowns.forEach(dropdown => {
        const display = dropdown.querySelector('[data-role="display-value"]');
        const menu = dropdown.querySelector(".dropdown-menu"); // menu-ya ayrıca class ver

        if (!menu) return; // əgər menu yoxdursa, davam etmə

        // Aç/bağla
        dropdown.addEventListener("click", (e) => {
            e.stopPropagation();
            dropdowns.forEach(d => {
                if (d !== dropdown) d.querySelector(".dropdown-menu")?.classList.add("hidden");
            });
            menu.classList.toggle("hidden");
        });

        // Seçim
        menu.querySelectorAll("li").forEach(item => {
            item.addEventListener("click", () => {
                display.textContent = item.textContent;
                menu.classList.add("hidden");
            });
        });
    });

    // Çöldə klik edəndə bağlansın
    document.addEventListener("click", () => {
        document.querySelectorAll(".dropdown-menu").forEach(menu => menu.classList.add("hidden"));
    });
}
// İstədiyin yerdə çağırırsan
initDropdowns();

//seher selecti ucun js 
const modal = document.getElementById("filterModal");
const resetBtn = document.getElementById("resetBtn");




// Sıfırla
resetBtn.addEventListener("click", () => {
    document.querySelectorAll("input[type=checkbox]").forEach(ch => ch.checked = false);
});




// === Konfiqurasiya (lazımdırsa dəyiş) ===
const API_BASE = ""; // eyni domen-disə boş burax; əks halda məsələn "/api"
const endpoints = {
    cities: "/cities",
    subways: "/subways",
    nearby: "/nearby-objects"
};

// === Util: API GET + sadə front cache ===
const cache = new Map();
async function apiGet(path) {
    const url = API_BASE + path;
    if (cache.has(url)) return cache.get(url);
    const res = await fetch(url);
    if (!res.ok) throw new Error(`${url} -> ${res.status}`);
    const data = await res.json();
    cache.set(url, data);
    return data;
}

// === Elementlər ===
// const modal = document.getElementById("filterModal");
const openModal = document.getElementById("openModal");
const closeModal = document.getElementById("closeModal");
const applyBtn = document.getElementById("applyBtn");
const applyCount = document.getElementById("applyCount");

const citySelect = document.getElementById("citySelect");

const metroTab = document.getElementById("metroTab");
const nishangahTab = document.getElementById("nishangahTab");
const rayonTab = document.getElementById("rayonTab");

const metroList = document.getElementById("metroList");
const metroEmpty = document.getElementById("metroEmpty");
const metroSearch = document.getElementById("metroSearch");

const nishangahList = document.getElementById("nishangahList");
const nishangahEmpty = document.getElementById("nishangahEmpty");
const nishangahSearch = document.getElementById("nishangahSearch");

const rayonList = document.getElementById("rayonList");
const rayonEmpty = document.getElementById("rayonEmpty");
const rayonSearch = document.getElementById("rayonSearch");

[metroTab, nishangahTab, rayonTab].forEach(el => el.classList.add("hidden"));

// === Modal aç/bağla ===
openModal.addEventListener("click", () => {
    modal.classList.remove("hidden");
    if (!citySelect.dataset.loaded) initLoad();
});
closeModal.addEventListener("click", () => modal.classList.add("hidden"));
modal.addEventListener("click", (e) => { if (e.target === modal) modal.classList.add("hidden"); });

// === Tab-lar ===
const tabBtns = document.querySelectorAll(".tabBtn");
const tabContents = document.querySelectorAll(".tabContent");

function showTab(tabEl, btnEl) {
    tabContents.forEach(tc => tc.classList.add("hidden"));
    tabBtns.forEach(b => b.classList.remove("border-blue-600", "text-blue-600"));
    tabEl.classList.remove("hidden");
    btnEl.classList.add("border-blue-600", "text-blue-600");
}

tabBtns.forEach(btn => {
    btn.addEventListener("click", async () => {
        const tabEl = document.getElementById(btn.dataset.tab);
        showTab(tabEl, btn);
        if (btn.dataset.tab === "rayonTab") await reloadLists();
    });
});

// === İlk yükləmə: şəhərlər, sonra rayon/metro/nişangah ===
async function initLoad() {
    try {
        const cities = await apiGet(endpoints.cities);
        fillSelect(citySelect, cities, "id", "name");
        citySelect.dataset.loaded = "1";
        await reloadLists();
    } catch (e) {
        console.error("Init load error:", e);
    }
}

// === Şəhər dəyişəndə siyahıları yenilə və Rayon tabını aktiv et ===
citySelect.addEventListener("change", async () => {
    const cityId = citySelect.value;

    // Siyahıları yenilə
    await reloadLists();

    // Rayon tabını avtomatik aç
    showTab(rayonTab, document.querySelector('[data-tab="rayonTab"]'));

    // Metro tabı yalnız Bakı üçün görünür (id: "baki")
    const metroTabBtn = document.querySelector('[data-tab="metroTab"]');
    if(cityId === "baki") {
        metroTabBtn.classList.remove("hidden");
    } else {
        metroTabBtn.classList.add("hidden");
        metroTab.classList.add("hidden"); // eyni zamanda content-i də gizlət
    }
});


// === Siyahıları yeniləyən funksiyalar ===
async function reloadLists() {
    const cityId = citySelect.value || "";

    // Metro
    const subways = await getByCity(endpoints.subways, cityId);
    renderCheckboxList(metroList, subways, "id", "name", "metro");
    toggleEmpty(metroList, metroEmpty);

    // Nişangah
    const nearby = await getByCity(endpoints.nearby, cityId);
    renderCheckboxList(nishangahList, nearby, "id", "name", "nearby");
    toggleEmpty(nishangahList, nishangahEmpty);

    // Rayon
    let districts = [];
    if (cityId) {
        const cities = await apiGet(endpoints.cities);
        const selected = cities.find(c => c.id == cityId);
        districts = selected?.districts || [];
    }
    renderCheckboxList(rayonList, districts, "id", "name", "district");
    toggleEmpty(rayonList, rayonEmpty);


    updateApplyCount();
}

// === Axtarış funksiyaları ===
function wireSearch(inputEl, listContainer, emptyEl) {
    inputEl.addEventListener("input", () => {
        const q = inputEl.value.trim().toLowerCase();
        let visible = 0;
        listContainer.querySelectorAll("label[data-text]").forEach(l => {
            const show = l.dataset.text.includes(q);
            l.classList.toggle("hidden", !show);
            if (show) visible++;
        });
        emptyEl.classList.toggle("hidden", visible !== 0);
    });
}

wireSearch(rayonSearch, rayonList, rayonEmpty);
wireSearch(metroSearch, metroList, metroEmpty);
wireSearch(nishangahSearch, nishangahList, nishangahEmpty);

// === API helper ===
async function getByCity(basePath, cityId) {
    if (!cityId) return await apiGet(basePath);
    try {
        const withParam = await apiGet(`${basePath}?city_id=${encodeURIComponent(cityId)}`);
        if (Array.isArray(withParam)) return withParam;
    } catch (_) {}
    const all = await apiGet(basePath);
    return (all || []).filter(x =>
        String(x.city_id ?? x.cityId ?? x.city)?.toLowerCase() === String(cityId).toLowerCase()
    );
}

// === Sıfırla ===
resetBtn.addEventListener("click", () => {
    modal.querySelectorAll("input[type=checkbox]").forEach(ch => ch.checked = false);
    metroSearch.value = "";
    nishangahSearch.value = "";
    rayonSearch.value = "";
    metroList.querySelectorAll("label").forEach(l => l.classList.remove("hidden"));
    nishangahList.querySelectorAll("label").forEach(l => l.classList.remove("hidden"));
    rayonList.querySelectorAll("label").forEach(l => l.classList.remove("hidden"));
    toggleEmpty(metroList, metroEmpty);
    toggleEmpty(nishangahList, nishangahEmpty);
    toggleEmpty(rayonList, rayonEmpty);
    updateApplyCount();
});

// === Apply ===
applyBtn.addEventListener("click", () => {
    const selected = getSelected();
    console.log("Seçilən filtrlər:", selected);
    modal.classList.add("hidden");
});

function getSelected() {
    const pick = (name) => [...modal.querySelectorAll(`input[name="${name}"]:checked`)].map(x => x.value);
    return {
        city_id: citySelect.value || "",
        subways: pick("metro"),
        nearby: pick("nearby"),
        districts: pick("district")
    };
}

function updateApplyCount() {
    const s = getSelected();
    const totalSelected = (s.subways?.length || 0) + (s.nearby?.length || 0) + (s.districts?.length || 0);
    applyCount.textContent = totalSelected;
}

// === Köməkçi funksiyalar ===
function fillSelect(selectEl, items, valueKey, labelKey) {
    selectEl.innerHTML = '<option value="">Şəhər seçimi</option>';
    (items || []).forEach(i => {
        const opt = document.createElement("option");
        opt.value = i[valueKey];
        opt.textContent = i[labelKey];
        selectEl.appendChild(opt);
    });
}

function renderCheckboxList(container, items, valueKey, labelKey, name) {
    container.innerHTML = "";
    const frag = document.createDocumentFragment();
    (items || []).forEach(i => {
        const lbl = document.createElement("label");
        lbl.className = "flex items-center gap-2";
        lbl.dataset.text = String(i[labelKey] ?? "").toLowerCase();

        const input = document.createElement("input");
        input.type = "checkbox";
        input.name = name;
        input.value = i[valueKey];

        lbl.appendChild(input);
        lbl.append(document.createTextNode(" " + i[labelKey]));
        frag.appendChild(lbl);
    });
    container.appendChild(frag);
}

function toggleEmpty(listEl, emptyEl) {
    const hasItems = listEl.querySelector("label");
    emptyEl.classList.toggle("hidden", !!hasItems);
}

// === DOMContentLoaded ===
document.addEventListener("DOMContentLoaded", () => {
    loadProperties(true);
    setupScrollEvents();
    setupFilterEvents();
    setupInputFilters();
    setupAddressSuggestions();
    setupViewToggle();
    setupDropdownFilters();
    setupMoreFiltersModal();
    setupResetButton();
    initDropdowns();
});
