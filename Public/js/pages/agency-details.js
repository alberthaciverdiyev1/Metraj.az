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
    const tabButtons = document.querySelectorAll(".tab-btn");
    const relatedPropertiesContainer = document.getElementById("related-properties");

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

    function getRelatedProperties(filter = "all") { 
        const agencyId = document.getElementById('side').dataset.agencyId;
        console.log(agencyId);

        axios.get(`/related-properties/${agencyId}?status=${filter}`)
            .then(res => {
                let htmlContent = "";
                if (Array.isArray(res.data)) {
                    res.data.forEach(property => {
                        htmlContent += propertyCard(property);
                    });
                } else {
                    Object.entries(res.data).forEach(([key, value]) => {
                        if (value.length === 0) return; 
                        htmlContent += ``;
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

    getRelatedProperties();
});
