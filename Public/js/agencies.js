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

    document.addEventListener("DOMContentLoaded", function () {
        const cardsPerPage = 8;
        const allCards = document.querySelectorAll(".agencies-card");
        const pagination = document.querySelector(".pagination");
        const pageItems = pagination.querySelectorAll(".page-item:not(.disabled)");

        function showPage(pageNumber) {
            const start = (pageNumber - 1) * cardsPerPage;
            const end = start + cardsPerPage;

            allCards.forEach((card, index) => {
                if (index >= start && index < end) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });

            pageItems.forEach(item => item.classList.remove("active"));
            pageItems.forEach(item => {
                const link = item.querySelector(".page-link");
                if (link && link.textContent.trim() == pageNumber) {
                    item.classList.add("active");
                }
            });
        }

        pageItems.forEach(item => {
            const link = item.querySelector(".page-link");
            if (link && link.textContent.trim() !== "...") {
                link.addEventListener("click", function (e) {
                    e.preventDefault();

                    const text = this.textContent.trim();
                    const currentPage = parseInt(document.querySelector(".pagination .active .page-link").textContent);
                    
                    if (this.getAttribute("aria-label") === "Previous") {
                        if (currentPage > 1) showPage(currentPage - 1);
                    } else if (this.getAttribute("aria-label") === "Next") {
                        const lastPage = pageItems[pageItems.length - 2].querySelector(".page-link").textContent;
                        if (currentPage < lastPage) showPage(currentPage + 1);
                    } else {
                        showPage(parseInt(text));
                    }
                });
            }
        });

        showPage(1);
    });
