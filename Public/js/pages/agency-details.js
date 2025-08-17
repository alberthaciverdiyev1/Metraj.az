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
    const tabButtons = document.querySelectorAll(".tab-btn");
    const relatedPropertiesContainer = document.getElementById("related-properties");

    let allProperties = [];
    let visibleCount = 0;
    const step = 2;

    const loader = document.createElement("div");
    loader.id = "infinity-loader";
    loader.className = "py-6 text-center text-gray-500";
    loader.innerText = "Yüklənir...";
    relatedPropertiesContainer.after(loader);

    function getRelatedProperties(filter = "all") {
        axios.get(`/related-properties/${agencyId}?status=${filter}`)
            .then(res => {
                if (Array.isArray(res.data)) {
                    allProperties = res.data;
                } else {
                    allProperties = [];
                    Object.values(res.data).forEach(arr => {
                        allProperties.push(...arr);
                    });
                }

                visibleCount = 0;
                relatedPropertiesContainer.innerHTML = "";

                if (allProperties.length === 0) {
                    loader.innerText = "Heç bir məhsul tapılmadı ❌";
                    return;
                }

                renderMore();
            })
            .catch(error => {
                console.error("Error fetching related properties:", error);
                relatedPropertiesContainer.innerHTML = "<p>Əmlakları yükləmək mümkün olmadı.</p>";
            });
    }

    function renderMore() {
        if (allProperties.length === 0) {
            loader.innerText = "Heç bir məhsul tapılmadı ❌";
            observer.disconnect();
            return;
        }

        const nextItems = allProperties.slice(visibleCount, visibleCount + step);

        if (nextItems.length === 0) {
            loader.innerText = "Bütün məhsullar göstərildi 🗸";
            observer.disconnect();
            return;
        }

        // Loader mesajı göstər
        loader.innerText = "Yüklənir...";

        setTimeout(() => {
            nextItems.forEach(property => {
                relatedPropertiesContainer.insertAdjacentHTML("beforeend", propertyCard(property));
            });

            visibleCount += step;

            if (visibleCount >= allProperties.length) {
                loader.innerText = "Bütün məhsullar göstərildi 🗸";
                observer.disconnect();
            }
        }, 2000); 
    }



    const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
            renderMore();
        }
    });
    observer.observe(loader);

    tabButtons.forEach(button => {
        button.addEventListener("click", () => {
            tabButtons.forEach(btn => btn.classList.remove("active"));
            button.classList.add("active");

            relatedPropertiesContainer.innerHTML = `
                <div class="absolute inset-0 z-50 flex justify-center items-center bg-white/50">
                    <span class="loader"></span>
                </div>
            `;

            getRelatedProperties(button.getAttribute("data-filter"));
        });
    });

    getRelatedProperties();
});

