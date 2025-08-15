// import {propertyCard} from "../cards/property.js";

// document.addEventListener("DOMContentLoaded", () => {
//     const tabButtons = document.querySelectorAll(".tab-btn");
//     const propertyCards = document.querySelectorAll(".property-card");

//     tabButtons.forEach(button => {
//         button.addEventListener("click", () => {
//             tabButtons.forEach(btn => btn.classList.remove("active"));
//             button.classList.add("active");

//             const filter = button.getAttribute("data-filter");

//             propertyCards.forEach(card => {
//                 const status = (card.getAttribute("data-status") || "").toLowerCase();

//                 if (filter === "all" || status === filter) {
//                     card.style.display = "block";
//                 } else {
//                     card.style.display = "none";
//                 }
//             });
//         });
//     });

//     function getRelatedProperties() {
//         const agencyId = document.getElementById('side').dataset.agencyId;
//         console.log(agencyId);
//         axios.get(`/related-properties/${agencyId}`).then(res => {
//             let h = "";
//             if (Array.isArray(res.data)) {
//                 res.data.forEach(property => {
//                     h += propertyCard(property);
//                 });
//             } else {
//                 Object.entries(res.data).forEach(([key, value]) => {
//                     if (value.length === 0) return;
//                     h += `<h3 class="text-2xl font-bold mb-4"></h3> <br>`;
//                     value.forEach(property => {
//                         h += propertyCard(property);
//                     });
//                 });
//             }
//             document.getElementById("related-properties").innerHTML = h;
//         })
//         .catch(error => {
//             console.error("Error fetching related properties:", error);
//             document.getElementById("related-properties").innerHTML = "<p>Failed to load properties. Please try again later.</p>";
//         });
//     }

//     getRelatedProperties();
// });


import { propertyCard } from "../cards/property.js";

document.addEventListener("DOMContentLoaded", () => {
    const agencySection = document.getElementById("side");
    if (!agencySection) return;

    const agencyId = agencySection.dataset.agencyId;
    const makePremiumBtn = document.getElementById("makePremiumBtn");
    const tabButtons = document.querySelectorAll(".tab-btn");
    const relatedPropertiesContainer = document.getElementById("related-properties");

    function applyPremiumView() {
        if (makePremiumBtn) makePremiumBtn.style.display = "none";

        const h2 = agencySection.querySelector("h2");
        if (h2 && !h2.querySelector(".premium-label")) {
            const span = document.createElement("span");
            span.className = "premium-label flex items-center bg-yellow-400 text-white text-xs font-bold px-2 py-1 rounded-full ml-2";
            span.innerHTML = '<i class="bi bi-star-fill mr-1"></i> Premium';
            h2.appendChild(span);
        }

        const infoDiv = agencySection.querySelector(".agencies-info");
        if (infoDiv) {
            infoDiv.classList.remove("bg-white");
            infoDiv.classList.add("bg-yellow-50", "border-2", "border-yellow-400", "p-4", "rounded-md");
        }
    }

    // localStorage yoxla (premium statusu yadda saxla)
    if (localStorage.getItem(`agency_${agencyId}_isPremium`) === "true") {
        applyPremiumView();
    }

    if (makePremiumBtn) {
        makePremiumBtn.addEventListener("click", async (e) => {
            e.preventDefault();
            try {
                const res = await fetch(`/agency/make-premium/${agencyId}`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({})
                });
                if (!res.ok) throw new Error("Xəta baş verdi");
                await res.json();

                // Görünüşü yenilə
                applyPremiumView();

                // localStorage-a qeyd et
                localStorage.setItem(`agency_${agencyId}_isPremium`, "true");

            } catch (err) {
                console.error(err);
                alert("Premium etmək mümkün olmadı!");
            }
        });
    }

    function getRelatedProperties(filter = "all") {
        axios.get(`/related-properties/${agencyId}?status=${filter}`)
            .then(res => {
                let htmlContent = "";
                if (Array.isArray(res.data)) {
                    res.data.forEach(property => {
                        htmlContent += propertyCard(property);
                    });
                } else {
                    Object.entries(res.data).forEach(([_, value]) => {
                        if (value.length === 0) return;
                        value.forEach(property => {
                            htmlContent += propertyCard(property);
                        });
                    });
                }
                relatedPropertiesContainer.innerHTML = htmlContent;
            })
            .catch(error => {
                console.error("Error fetching related properties:", error);
                relatedPropertiesContainer.innerHTML = "<p>Əmlakları yükləmək mümkün olmadı. Zəhmət olmasa, daha sonra yenidən cəhd edin.</p>";
            });
    }

    tabButtons.forEach(button => {
        button.addEventListener("click", () => {
            tabButtons.forEach(btn => btn.classList.remove("active"));
            button.classList.add("active");

            const filter = button.getAttribute("data-filter");

            relatedPropertiesContainer.innerHTML = `
                <div class="absolute inset-0 z-50 flex justify-center items-center bg-white/50">
                    <span class="loader"></span>
                </div>
            `;

            getRelatedProperties(filter);
        });
    });

    // İlk yükləmə zamanı bütün əmlakları gətir
    getRelatedProperties();
});
