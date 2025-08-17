const gridBtn = document.getElementById("gridViewBtn");
const listBtn = document.getElementById("listViewBtn");
const cards = document.querySelectorAll(".agencies-card");

gridBtn.addEventListener("click", () => {
    cards.forEach(card => {
        card.classList.remove("list-view");
    });
    gridBtn.classList.add("active");
    listBtn.classList.remove("active");
});

listBtn.addEventListener("click", () => {
    cards.forEach(card => {
        card.classList.add("list-view");
    });
    listBtn.classList.add("active");
    gridBtn.classList.remove("active");
});

document.addEventListener("DOMContentLoaded", () => {
    const gridBtn = document.getElementById("gridViewBtn");
    const listBtn = document.getElementById("listViewBtn");
    const cardsContainer = document.getElementById("agencyCards");
    const cards = document.querySelectorAll(".agencies-card");

    gridBtn.addEventListener("click", () => {
        cardsContainer.classList.remove("list-view");
        gridBtn.classList.add("active");
        listBtn.classList.remove("active");
    });

    listBtn.addEventListener("click", () => {
        cardsContainer.classList.add("list-view");
        listBtn.classList.add("active");
        gridBtn.classList.remove("active");
    });

    function updatePremiumCard(card) {
        card.classList.remove("bg-white");
        card.classList.add("bg-yellow-50", "border-2", "border-yellow-400");

        if (!card.querySelector(".premium-icon")) {
            const span = document.createElement("div");
            span.className = "premium-icon absolute top-2 right-2 bg-white text-white text-xs font-bold px-2 py-1 rounded-full z-10 flex items-center";
            span.innerHTML = '<i class="fa-solid fa-crown text-yellow-500 text-xl hover:text-yellow-400"></i> ';
            card.appendChild(span);
        }

        const btn = card.querySelector(".premium-btn");
        if (btn) btn.style.display = "none";
    }

    // İlk yükləmə zamanı backend və localStorage yoxla
    cards.forEach(card => {
        const isPremiumBackend = card.dataset.isPremium === "true";
        const isPremiumLocal = localStorage.getItem(`agency_${card.dataset.id}_isPremium`) === "true";

        if (isPremiumBackend || isPremiumLocal) {
            updatePremiumCard(card);
        }
    });

    // Storage event: başqa tablarda dəyişəndə dərhal update et
    window.addEventListener("storage", (e) => {
        if (e.key && e.key.startsWith("agency_") && e.key.endsWith("_isPremium") && e.newValue === "true") {
            const id = e.key.split("_")[1];
            const card = document.querySelector(`.agencies-card[data-id="${id}"]`);
            if (card) updatePremiumCard(card);
        }
    });
});




