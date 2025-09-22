    // document.addEventListener("DOMContentLoaded", () => {
    //     const btn = document.getElementById('dropdownButton');
    //     const menu = document.getElementById('dropdownMenu');

    //     btn.addEventListener('click', () => {
    //         menu.classList.toggle('hidden');
    //     });

    //     window.addEventListener('click', (e) => {
    //         if (!btn.contains(e.target) && !menu.contains(e.target)) {
    //             menu.classList.add('hidden');
    //         }
    //     });

    //     const dropdownLinks = menu.querySelectorAll('a');

    //     dropdownLinks.forEach(link => {
    //         link.addEventListener('click', () => {
    //             dropdownLinks.forEach(l => {
    //                 l.classList.remove('bg-orange-400', 'text-white');
    //                 l.classList.add('text-gray-700');
    //             });
    //             link.classList.add('bg-orange-400', 'text-white');
    //             link.classList.remove('text-gray-700');
    //         });
    //     });

    //     burgerBtn=document.getElementById('burgerBtn');


    // });


   document.addEventListener("DOMContentLoaded", () => {
    // 1️⃣ Dropdown menu
    const btn = document.getElementById('dropdownButton');
    const menu = document.getElementById('dropdownMenu');

    btn?.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });

    window.addEventListener('click', (e) => {
        if (!btn?.contains(e.target) && !menu?.contains(e.target)) {
            menu?.classList.add('hidden');
        }
    });

    // Dropdown link aktivlik
    const dropdownLinks = menu?.querySelectorAll('a') || [];
    dropdownLinks.forEach(link => {
        link.addEventListener('click', () => {
            dropdownLinks.forEach(l => {
                l.classList.remove('bg-orange-400', 'text-white');
                l.classList.add('text-gray-700');
            });
            link.classList.add('bg-orange-400', 'text-white');
            link.classList.remove('text-gray-700');
        });
    });

    // 2️⃣ Burger button (mobile menu)
    const burgerBtn = document.getElementById('burgerBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    burgerBtn?.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // 3️⃣ Navbar aktiv link rəngləmə
    const links = document.querySelectorAll("nav a");
    const linkAddProperty = document.querySelector(".add-property-link");
    const currentPath = window.location.pathname;

    links.forEach(link => {
        if (link.getAttribute("href") === currentPath) {
            link.classList.add("text-orange-400", "font-bold");
        } else {
            link.classList.add("text-gray-700");
        }
    });

    if (linkAddProperty?.getAttribute("href") === currentPath) {
        linkAddProperty.classList.add("text-orange-400");
    }

   // 4️⃣ Favorites & Compares sayıları
function updateCounts() {
    const favorites = JSON.parse(localStorage.getItem("favorites")) || [];
    const compareList = JSON.parse(localStorage.getItem("compareList")) || [];

    const favoritesCount = document.getElementById("favorites-count");
    const compareCount = document.getElementById("compares-count");

    if (favoritesCount) favoritesCount.textContent = favorites.length;
    if (compareCount) compareCount.textContent = compareList.length;
}

// ✅ Toastify funksiyası
function showToast(message) {
    Toastify({
        text: message,
        duration: 3000,
        gravity: "bottom",
        position: "right",
        close: true,
        style: {
            background: "linear-gradient(to right, #FFA500, #FF4500)",
            borderRadius: "8px",
            padding: "12px 24px",
            minWidth: "250px",      
            maxWidth: "40%",   
        }
    }).showToast();
}


// ✅ Favorite toggle
window.toggleFavorite = function(element, propertyJsonString) {
    const icon = element.querySelector("i");
    let favorites = JSON.parse(localStorage.getItem("favorites")) || [];
    const property = JSON.parse(propertyJsonString);

    const isAlreadyFavorite = favorites.some(fav => fav.id === property.id);

    if (!isAlreadyFavorite) {
        icon.classList.remove("fa-regular");
        icon.classList.add("fa-solid");
        favorites.push(property);
        showToast(`${property.title} seçilmişlərə əlavə edildi.`);
    } else {
        icon.classList.remove("fa-solid");
        icon.classList.add("fa-regular");
        favorites = favorites.filter(fav => fav.id !== property.id);
        showToast(`${property.title} seçilmişlərdən çıxarıldı.`);
    }

    localStorage.setItem("favorites", JSON.stringify(favorites));
    updateCounts();
}

// ✅ Compare toggle
window.toggleCompare = function(element, propertyJsonString) {
    let compareList = JSON.parse(localStorage.getItem("compareList")) || [];
    const property = JSON.parse(propertyJsonString);
    const compareIcon = element.querySelector("i");
    const maxCompareItems = 3;

    const isAlreadyInCompare = compareList.some(c => c.id === property.id);

    if (!isAlreadyInCompare) {
        if (compareList.length < maxCompareItems) {
            compareList.push(property);
            compareIcon?.classList.add("text-[color:var(--primary)]");
            showToast(`${property.title} müqayisə siyahısına əlavə edildi.`);
        } else {
            showToast(`Ən çox ${maxCompareItems} mülk müqayisə edə bilərsiniz.`);
        }
    } else {
        compareList = compareList.filter(c => c.id !== property.id);
        compareIcon?.classList.remove("text-[color:var(--primary)]");
        showToast(`${property.title} müqayisə siyahısından çıxarıldı.`);
    }

    localStorage.setItem("compareList", JSON.stringify(compareList));
    updateCounts();
}


    // İlk yüklenmə zamanı sayları göstər
    updateCounts();
});

