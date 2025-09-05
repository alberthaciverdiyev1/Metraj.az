import { formatPrice } from "../helpers/price.js";

function getCompareStatus(propertyId) {
  const compareList = JSON.parse(localStorage.getItem("compareList")) || [];
  return compareList.some((compProperty) => compProperty.id === propertyId);
}

function getCookie(name) {
  const nameEQ = name + "=";
  const ca = document.cookie.split(";");
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) === " ") c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
  }
  return null;
}

export function propertyCard(property, showRemoveButton = false) {
  // console.log(property);

  const badges = `
<div class="flex flex-wrap  gap-2">
    ${
      property.add_type === "rent"
          ? `<span class="bg-[color:var(--primary)] text-white text-xs font-semibold px-2 text-center py-1 rounded-full">Kirayə</span>`
          : property.add_type === "sale"
              ? `<span class="bg-[#80807F] text-white text-xs font-semibold px-2 py-1 rounded-full">Satışda</span>`
              : ""
  }

    ${
      property.property_condition === "Repaired"
          ? `<span class="bg-green-600 text-white text-xs font-semibold px-2 text-center  py-1 rounded-full">Təmirli</span>`
          : ``
  // : `<span class="bg-gray-400 text-white text-xs font-semibold px-2 py-1 text-center rounded-full">Təmirsiz</span>`
  }

    ${
      property.in_credit
          ? `<span class="bg-blue-600 text-white text-xs font-semibold px-2 py-1 text-center  rounded-full">İpoteka</span>` : ``
      // : `<span class="bg-gray-400 text-white text-xs font-semibold px-2 py-1 text-center rounded-full">İpotekasız</span>`
  }
    

    ${
      property.document === "kupça var"
          ? `<span class="bg-green-500 text-white text-xs font-semibold px-2 py-1 text-center  rounded-full">Çıxarışlı</span>`
          : ``
  // : `<span class="bg-red-500 text-white text-xs font-semibold px-2 py-1 text-center rounded-full">Çıxarışsız</span>`
  }
</div>
`;

  const rawPrice = property.price && property.price[0]?.price;
  let priceValue = 0;
  if (rawPrice) priceValue = parseFloat(rawPrice.toString().replace(/,/g, ""));
  const price = formatPrice(priceValue);
  const premiumBadge = property.is_premium
    ? `<span class="absolute top-3 right-4 text-[color:var(--primary)] font-semibold text-md bg-white px-2 py-1 rounded-full"><i class="fa-solid fa-crown"></i></span>`    : "";
  const heartIconClass = getFavoriteStatus(property.id) ? "fa-solid" : "fa-regular";
  const propertyData = encodeURIComponent(JSON.stringify(property));

  const favoriteOrRemoveButton = showRemoveButton
    ? `<span onclick="event.stopPropagation(); removeFavorite(${property.id});" class="absolute bottom-3 right-4 text-red-500 font-semibold text-md bg-white px-2 py-1 rounded-full cursor-pointer"><i class="fas fa-times"></i></span>`
    : `<span onclick="event.stopPropagation(); toggleFavorite(this, decodeURIComponent('${propertyData}'));" class="absolute bottom-3 right-4 text-white font-semibold text-md bg-white px-2 py-1 rounded-full cursor-pointer"><i class="${heartIconClass} fa-heart text-red-500"></i></span>`;

  const comparePropertyData = encodeURIComponent(JSON.stringify(property));
  const isCompareActive = getCompareStatus(property.id);
  const compareIconClass = isCompareActive ? "text-[color:var(--primary)]" : "";
  return `
        <div onclick="window.location.href='/property/${property.id}'" 
            data-property-id="${property.id}"  
            class="cursor-pointer border border-[color:var(--border-color)] rounded-2xl overflow-hidden flex flex-col h-full group transition-all duration-300 relative " >

            <!-- Image -->
            <div class="relative overflow-hidden">
                <img src="${property.media.path}" alt="${property.title}" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105" />
                ${premiumBadge}
                ${favoriteOrRemoveButton}
                   <button onclick="event.stopPropagation()" 
            class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-[#494949] bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-70 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
        <img src="/images/icon.svg" />
    </button>

            </div>
             
 
            <!-- Card content -->
            <div class="p-4 flex flex-col flex-1">
                <div class="flex flex-col gap-2" style="min-height:120px;">
                    <h3 class="font-bold text-[color:var(--text-color)] h-[25px] transition hover:text-[color:var(--primary)]">
                        ${property.title}
                    </h3>
                    ${badges}
                    <p class="text-sm sm:text-base md:text-[16px] text-[color:var(--grey-text)] flex items-center   h-[30px]">
                        <img class="mr-2" src="/images/map-pin.svg" /> ${property.address}              
                        </p>
             
                <div class="flex justify-between items-center text-sm sm:text-base md:text-[16px] text-[color:var(--grey-text)] mb-2">
                    <span>${property.buildingType}</span>
                    <span>${property.date}</span>
                </div>

                </div>

                <!-- Price və Compare button -->
                <div class="flex justify-between py-2 mt-auto items-center border-t border-[color:var(--border-color)] pt-4">
                    <span class="text-[color:var(--primary)] font-bold text-base sm:text-lg">${price} AZN</span>
                    <button onclick="event.stopPropagation(); toggleCompare(this, decodeURIComponent('${comparePropertyData}'));" class="flex compare items-center gap-1 text-sm text-[#2C2E33] hover:text-[color:var(--primary)] transition-colors">
                        <img src="/images/compare.svg" ${compareIconClass}" />Müqayisə
                    </button>              
                </div>
            </div>
        </div>
    `;
}
function getFavoriteStatus(propertyId) {
  const favorites = JSON.parse(localStorage.getItem("favorites")) || [];
  return favorites.some((favProperty) => favProperty.id === propertyId);
}

window.toggleFavorite = async function (element, propertyJsonString) {
  const icon = element.querySelector("i");
  let favorites = JSON.parse(localStorage.getItem("favorites")) || [];
  const property = JSON.parse(propertyJsonString);
  const token = getCookie("session");

  console.log("--- Toggle Favorite Attempt ---");
  console.log("Token exists:", !!token);

  const isAlreadyFavorite = favorites.some(
    (favProperty) => favProperty.id === property.id
  );

  if (token) {
    const url = "/api/favorite";
    const method = isAlreadyFavorite ? "DELETE" : "POST";
    const body = JSON.stringify({ property_id: property.id });

    try {
      const response = await fetch(url, {
        method: method,
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: body,
      });

      if (response.ok) {
        const data = await response.json();
        console.log("Backend response:", data);

        if (method === "POST") {
          icon.classList.remove("fa-regular");
          icon.classList.add("fa-solid");
          favorites.push(property);
          alert(`${property.title} seçilmişlərə əlavə edildi.`);
        } else {
          icon.classList.remove("fa-solid");
          icon.classList.add("fa-regular");
          favorites = favorites.filter(
            (favProperty) => favProperty.id !== property.id
          );
          alert(`${property.title} seçilmişlərdən çıxarıldı.`);
        }
        localStorage.setItem("favorites", JSON.stringify(favorites));
        console.log("Favorites updated in localStorage:", favorites);

        if (
          window.location.pathname === "/favorites" ||
          window.location.pathname === "/favorites.html"
        ) {
          if (typeof renderFavorites === "function") {
            renderFavorites();
          }
        }
      } else {
        console.error(
          "Failed to update favorite status on backend:",
          response.status,
          response.statusText
        );
        const errorData = await response.json();
        console.error("Backend error details:", errorData);
        alert(
          "Seçilmişləri yeniləmək zamanı xəta baş verdi. Zəhmət olmasa, yenidən cəhd edin."
        );
      }
    } catch (error) {
      console.error("Error during API call:", error);
      alert(
        "Şəbəkə xətası baş verdi. Zəhmət olmasa, internet bağlantınızı yoxlayın."
      );
    }
  } else {
    console.log("User not logged in. Updating favorites in localStorage only.");
    if (!isAlreadyFavorite) {
      icon.classList.remove("fa-regular");
      icon.classList.add("fa-solid");
      favorites.push(property);
      alert(`${property.title} seçilmişlərə əlavə edildi (yalnız yerli).`);
    } else {
      icon.classList.remove("fa-solid");
      icon.classList.add("fa-regular");
      favorites = favorites.filter(
        (favProperty) => favProperty.id !== property.id
      );
      alert(`${property.title} seçilmişlərdən çıxarıldı (yalnız yerli).`);
    }
    localStorage.setItem("favorites", JSON.stringify(favorites));
    console.log("Favorites updated in localStorage:", favorites);

    if (
      window.location.pathname === "/favorites" ||
      window.location.pathname === "/favorites.html"
    ) {
      if (typeof renderFavorites === "function") {
        renderFavorites();
      }
    }
  }
  console.log("--- Toggle Favorite End ---");
};

window.removeFavorite = function (propertyId) {
  let favorites = JSON.parse(localStorage.getItem("favorites")) || [];
  favorites = favorites.filter((favProperty) => favProperty.id !== propertyId);
  localStorage.setItem("favorites", JSON.stringify(favorites));
  console.log("Removed favorite:", propertyId, "New list:", favorites);

  const cardToRemove = document.querySelector(
    `[data-property-id="${propertyId}"]`
  );
  if (cardToRemove) {
    cardToRemove.remove();
  }

  if (favorites.length === 0) {
    const favoritesContainer = document.getElementById("favoritesContainer");
    if (favoritesContainer) {
      favoritesContainer.innerHTML =
        '<p class="text-center text-gray-500 col-span-full">Seçilmiş elan yoxdur.</p>';
      const clearAllFavoritesBtn = document.getElementById(
        "clearAllFavoritesBtn"
      );
      if (clearAllFavoritesBtn) {
        clearAllFavoritesBtn.style.display = "none";
      }
    }
  }
};

function showCompareToast(message) {
  let toastContainer = document.getElementById("toastContainer");
  if (!toastContainer) {
    toastContainer = document.createElement("div");
    toastContainer.id = "toastContainer";
    toastContainer.style.position = "fixed";
    toastContainer.style.bottom = "20px";
    toastContainer.style.right = "20px";
    toastContainer.style.zIndex = "9999";
    document.body.appendChild(toastContainer);
  }

  const toast = document.createElement("div");
  toast.style.background = "#2C2E33";
  toast.style.color = "#fff";
  toast.style.padding = "12px 16px";
  toast.style.marginTop = "8px";
  toast.style.borderRadius = "8px";
  toast.style.fontSize = "14px";
  toast.style.boxShadow = "0 2px 6px rgba(0,0,0,0.2)";
  toast.innerHTML = `
        ${message} 
        <a href="/compares" style="color: #4f9ef7; margin-left: 10px; text-decoration: underline;">
            Müqayisə səhifəsinə bax
        </a>
    `;

  toastContainer.appendChild(toast);

  setTimeout(() => toast.remove(), 4000);
}

window.toggleCompare = function (element, propertyJsonString) {
  let compareList = JSON.parse(localStorage.getItem("compareList")) || [];
  const property = JSON.parse(propertyJsonString);
  const maxCompareItems = 3;

  const isAlreadyInCompare = compareList.some(
    (compProperty) => compProperty.id === property.id
  );
  const compareIcon = element.querySelector("i");

  if (!isAlreadyInCompare) {
    if (compareList.length < maxCompareItems) {
      compareList.push(property);
      compareIcon.classList.add("text-[color:var(--primary)]");
      showCompareToast(`${property.title} müqayisə siyahısına əlavə edildi.`);
    } else {
      showCompareToast(
        `Siz ən çox ${maxCompareItems} mülkü müqayisə edə bilərsiniz. Əvvəlcə birini çıxarın.`
      );
    }
  } else {
    compareList = compareList.filter(
      (compProperty) => compProperty.id !== property.id
    );
    compareIcon.classList.remove("text-[color:var(--primary)]");
    showCompareToast(`${property.title} müqayisə siyahısından çıxarıldı.`);
  }

  localStorage.setItem("compareList", JSON.stringify(compareList));
  console.log("Compare List updated and saved to localStorage:", compareList);
};
