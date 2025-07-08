import {propertyCard} from "../cards/property.js";

document.addEventListener("DOMContentLoaded", () => {
    const tabButtons = document.querySelectorAll(".tab-btn");
    const propertyCards = document.querySelectorAll(".property-card");

    tabButtons.forEach(button => {
        button.addEventListener("click", () => {
            tabButtons.forEach(btn => btn.classList.remove("active"));
            button.classList.add("active");

            const filter = button.getAttribute("data-filter");

            propertyCards.forEach(card => {
                const status = (card.getAttribute("data-status") || "").toLowerCase();

                if (filter === "all" || status === filter) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });
        });
    });

    function getRelatedProperties() {
        const agencyId = document.getElementById('side').dataset.agencyId;
        console.log(agencyId);
        axios.get(`/related-properties/${agencyId}`).then(res => {
            let h = "";
            Object.entries(res.data).forEach(([key, value]) => {
                if (value.length === 0) return;
                h+=`<h3 class="text-2xl font-bold mb-4">${key}</h3> <br>`;
                value.forEach(property => {
                    h += propertyCard(property);
                });
            });

            // res.data.forEach(property => {
            // console.log("data"+property);
            // })
            document.getElementById("related-properties").innerHTML = h;
        })
    }

    getRelatedProperties();
});
