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
  maxAreaInput: document.querySelector('[data-type="max-area-input"]'),
  minPriceInput: document.getElementById("minPriceInput"),
  maxPriceInput: document.getElementById("maxPriceInput"),
  adNoInput: document.querySelector('[data-role="adno-search-input"]'),
  searchBtn: document.querySelector('[data-role="search-button"]'),
  filterButtons: document.querySelectorAll("button[data-add-type]"),
  dropdowns: document.querySelectorAll(".dropdown-select"),
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
    buildingType: "All Categories",
    city: "All Cities",
  }
};

const ITEMS_PER_PAGE = 20;

// 🔧 YARDIMCI FONKSİYONLAR
function createSkeletons(count) {
  return new Array(count).fill(0).map(() => propertySkeletonCard()).join("");
}

function clearSkeletons() {
  dom.propertyContainer.querySelectorAll(".property-skeleton-card").forEach(el => el.remove());
  dom.premiumCardContainer.querySelectorAll(".property-skeleton-card").forEach(el => el.remove());
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

  try {
    const params = {
      page: state.page,
      limit: ITEMS_PER_PAGE,
      ...getFilterParams(),
    };

    console.log({ params });
    const data = await getPropertiesList(params);
    clearSkeletons();

    if (isNewSearch) {
      renderPremiumProperties(data);
    }

    if (data.length > 0) {
      const cards = data.map(propertyCard).join("");
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
  return {
    addType: f.addType === "all" ? "" : f.addType,
    address: f.address,
    minArea: f.minArea,
    maxArea: f.maxArea,
    minPrice: f.minPrice,
    maxPrice: f.maxPrice,
    adNo: f.adNo,
    propertyType: f.buildingType === "All Categories" ? "" : f.buildingType,
    cityId: f.city === "All Cities" ? "" : f.city,
  };
}

function renderPremiumProperties(data) {
  const premium = data.filter(p => p.is_premium);
  dom.premiumCardContainer.innerHTML = premium.length
      ? premium.map(propertyCard).join("")
      : `<p class="col-span-full text-center text-gray-500">Axtarışınıza uyğun premium elan tapılmadı.</p>`;
}

// 🧠 EVENTS
function setupScrollEvents() {
  window.addEventListener("scroll", () => {
    updateProgressCircle();

    const scrollY = window.scrollY;
    const scrollThreshold = document.body.offsetHeight - 300;

    dom.scrollToTop.style.display = scrollY > window.innerHeight / 2 ? "flex" : "none";

    if (window.innerHeight + scrollY >= scrollThreshold) {
      loadProperties(false);
    }
  });

  dom.scrollToTop.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
}

function setupFilterEvents() {
  dom.filterButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      state.filters.addType = btn.getAttribute("data-add-type");
      dom.filterButtons.forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
      resetFiltersIfAll();
      loadProperties(true);
    });
  });
}

function setupInputFilters() {
  const updateFilter = (key, value) => {
    state.filters[key] = value;
  };

  dom.minAreaInput?.addEventListener("input", e => updateFilter("minArea", e.target.value));
  dom.maxAreaInput?.addEventListener("input", e => updateFilter("maxArea", e.target.value));
  dom.minPriceInput?.addEventListener("input", e => updateFilter("minPrice", e.target.value));
  dom.maxPriceInput?.addEventListener("input", e => updateFilter("maxPrice", e.target.value));
  dom.adNoInput?.addEventListener("input", e => {
    updateFilter("adNo", e.target.value);
    loadProperties(true);
  });

  dom.searchBtn?.addEventListener("click", () => loadProperties(true));
}

function setupAddressSuggestions() {
  dom.addressInput?.addEventListener("input", async (e) => {
    const query = e.target.value.trim().toLowerCase();
    state.filters.address = query;

    if (query.length < 2) return dom.suggestionsWrapper.classList.add("hidden");

    const allProps = await getPropertiesList({ page: 1, limit: 999999 });
    const addresses = [...new Set(allProps.map(p => p.address).filter(a => a?.toLowerCase().includes(query)))];

    dom.suggestionsList.innerHTML = addresses.map(addr => `
      <li class="p-2 hover:bg-gray-100 cursor-pointer">${addr}</li>
    `).join("");

    dom.suggestionsWrapper.classList.toggle("hidden", addresses.length === 0);

    dom.suggestionsList.querySelectorAll("li").forEach(li => {
      li.addEventListener("click", () => {
        dom.addressInput.value = li.textContent;
        state.filters.address = li.textContent;
        dom.suggestionsWrapper.classList.add("hidden");
        loadProperties(true);
      });
    });
  });

  document.addEventListener("click", (e) => {
    if (!dom.addressInput.contains(e.target) && !dom.suggestionsWrapper.contains(e.target)) {
      dom.suggestionsWrapper.classList.add("hidden");
    }
  });
}

function setupViewToggle() {
  if (!dom.gridBtn || !dom.listBtn) return;

  dom.gridBtn.addEventListener("click", () => toggleView("grid"));
  dom.listBtn.addEventListener("click", () => toggleView("list"));
}

function toggleView(viewType) {
  const isGrid = viewType === "grid";
  const classes = isGrid
      ? ["grid", "grid-cols-1", "md:grid-cols-2", "xl:grid-cols-4"]
      : ["list-view"];

  ["propertyContainer", "premiumCardContainer"].forEach(id => {
    const el = dom[id];
    el.className = isGrid ? classes.join(" ") : "list-view";
  });

  dom.gridBtn.classList.toggle("active-filter", isGrid);
  dom.listBtn.classList.toggle("active-filter", !isGrid);
}

function setupDropdownFilters() {
  dom.dropdowns.forEach(drop => {
    const span = drop.querySelector("[x-text]");
    const type = span?.getAttribute("x-text");

    drop.querySelectorAll("ul li").forEach((li) => {
      li.addEventListener("click", () => {
        const val = li.textContent.trim();
        span.textContent = val;
        if (type === "selectedBuildingType") state.filters.buildingType = val;
        if (type === "selectedCity") state.filters.city = val;
        loadProperties(true);
      });
    });
  });
}

function resetFiltersIfAll() {
  if (state.filters.addType !== "all") return;
  Object.assign(state.filters, {
    address: "",
    minArea: "",
    maxArea: "",
    minPrice: "",
    maxPrice: "",
    adNo: "",
    buildingType: "All Categories",
    city: "All Cities",
  });

  if (dom.addressInput) dom.addressInput.value = "";
  if (dom.minAreaInput) dom.minAreaInput.value = "";
  if (dom.maxAreaInput) dom.maxAreaInput.value = "";
  if (dom.minPriceInput) dom.minPriceInput.value = "";
  if (dom.maxPriceInput) dom.maxPriceInput.value = "";
}

document.addEventListener("DOMContentLoaded", () => {
  loadProperties(true);
  setupScrollEvents();
  setupFilterEvents();
  setupInputFilters();
  setupAddressSuggestions();
  setupViewToggle();
  setupDropdownFilters();
});
