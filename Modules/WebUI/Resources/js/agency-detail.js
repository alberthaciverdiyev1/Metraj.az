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
});





document.addEventListener("DOMContentLoaded", function () {
    const cards = document.querySelectorAll(".property-card");
    const pagination = document.querySelector(".pagination");
    if (!pagination) return; 

    const pageLinks = pagination.querySelectorAll(".page-link:not([aria-label])");
    const prevBtn = pagination.querySelector('[aria-label="Previous"]');
    const nextBtn = pagination.querySelector('[aria-label="Next"]');
    let currentPage = 1;
    const totalPages = parseInt(document.getElementById("totalPages").value);

    function showPage(page) {
        currentPage = page;
        cards.forEach(card => {
            const pageNum = parseInt(card.dataset.page);
            card.style.display = (pageNum === page) ? "block" : "none";
        });

        pageLinks.forEach(link => {
            link.parentElement.classList.remove("active");
            if (parseInt(link.textContent) === page) {
                link.parentElement.classList.add("active");
            }
        });

        prevBtn.parentElement.classList.toggle("disabled", currentPage === 1);
        nextBtn.parentElement.classList.toggle("disabled", currentPage === totalPages);
    }

    pageLinks.forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const selectedPage = parseInt(this.textContent);
            if (!isNaN(selectedPage)) {
                showPage(selectedPage);
            }
        });
    });

    prevBtn.addEventListener("click", function (e) {
        e.preventDefault();
        if (currentPage > 1) {
            showPage(currentPage - 1);
        }
    });

    nextBtn.addEventListener("click", function (e) {
        e.preventDefault();
        if (currentPage < totalPages) {
            showPage(currentPage + 1);
        }
    });

    showPage(currentPage);
});
