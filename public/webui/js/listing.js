const gotop = document.getElementById('scrollToTop');
const progress = document.querySelector('.progress-circle .progress');
const radius = 18;
const circumference = 2 * Math.PI * radius;

window.addEventListener('scroll', () => {
    const scrollTop = window.scrollY;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    const scrollPercent = scrollTop / docHeight;

    const offset = circumference - scrollPercent * circumference;
    progress.style.strokeDashoffset = offset;

    if (scrollTop > window.innerHeight / 2) {
        gotop.style.display = 'flex';
    } else {
        gotop.style.display = 'none';
    }
});

gotop.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});



//PAGINATION

document.addEventListener('DOMContentLoaded', () => {
    const itemsPerPage = 9;
    const paginationContainer = document.querySelector('.pagination');
    const resultsText = document.querySelector('.result .text');
    const gridBtn = document.getElementById("gridViewBtn");
    const listBtn = document.getElementById("listViewBtn");
    const container = document.getElementById('propertyContainer');

    if (!container) {
        console.error('Element with id="propertyContainer" not found!');
        return;
    }

    let currentPage = 1;

    function getPropertyCards() {
        return container.querySelectorAll(':scope > div');
    }

    function getTotalPages() {
        return Math.ceil(getPropertyCards().length / itemsPerPage);
    }

    function showPage(page) {
        currentPage = page;
        const propertyCards = getPropertyCards();
        const totalItems = propertyCards.length;
        const totalPages = getTotalPages();
        const startIndex = (page - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, totalItems);

        propertyCards.forEach(card => {
            card.style.display = 'none';
        });

        for (let i = startIndex; i < endIndex; i++) {
            if (propertyCards[i]) {
                propertyCards[i].style.display = 'block';
            }
        }

        resultsText.textContent = `Showing ${startIndex + 1}-${endIndex} of ${totalItems} results.`;
        updatePaginationUI();
    }

    function updatePaginationUI() {
        const totalPages = getTotalPages();
        const pageItems = document.querySelectorAll('.pagination .page-item');

        pageItems.forEach(item => {
            item.classList.remove('active');
        });

        const currentPageItem = document.querySelector(`.pagination .page-item:nth-child(${currentPage + 1})`);
        if (currentPageItem) {
            currentPageItem.classList.add('active');
        }

        const prevButton = document.querySelector('.pagination .page-item:first-child');
        const nextButton = document.querySelector('.pagination .page-item:last-child');

        if (prevButton) {
            prevButton.classList.toggle('disabled', currentPage === 1);
        }

        if (nextButton) {
            nextButton.classList.toggle('disabled', currentPage === totalPages);
        }
    }

    function initPagination() {
        showPage(currentPage);
    }

    paginationContainer.addEventListener('click', function (e) {
        e.preventDefault();

        if (e.target.closest('.page-link')) {
            const target = e.target.closest('.page-link');
            const action = target.getAttribute('aria-label');

            const totalPages = getTotalPages();

            if (action === 'Previous' && currentPage > 1) {
                showPage(currentPage - 1);
            } else if (action === 'Next' && currentPage < totalPages) {
                showPage(currentPage + 1);
            } else if (!action) {
                const pageNumber = parseInt(target.textContent);
                if (!isNaN(pageNumber)) {
                    showPage(pageNumber);
                }
            }
        }
    });

    const toggleButtons = [gridBtn, listBtn];

 toggleButtons.forEach(btn => {
    btn.addEventListener("click", () => {
        toggleButtons.forEach(b => {
            b.classList.remove("active", "bg-primary");
        });
        btn.classList.add("active", "bg-primary");
    });
});


    gridBtn.addEventListener('click', () => {
        container.classList.remove('list-view');
        container.classList.add(
            'grid',
            'grid-cols-1',
            'sm:grid-cols-1',
            'md:grid-cols-2',
            'lg:grid-cols-2',
            'xl:grid-cols-3'
        );
        showPage(currentPage); 
    });

    listBtn.addEventListener('click', () => {
        container.classList.add('list-view');
        container.classList.remove(
            'grid',
            'grid-cols-1',
            'sm:grid-cols-1',
            'md:grid-cols-2',
            'lg:grid-cols-2',
            'xl:grid-cols-3'
        );
        showPage(currentPage);
    });

    initPagination();
});
document.addEventListener('DOMContentLoaded', () => {
    const gridBtn = document.getElementById('gridViewBtn');
    const listBtn = document.getElementById('listViewBtn');
    const container = document.getElementById('propertyContainer');

    if (!container) {
        console.error('Element with id="propertyContainer" not found!');
        return;
    }

    gridBtn.addEventListener('click', () => {
        container.classList.remove('list-view');
        container.classList.add('grid', 'grid-cols-1', 'sm:grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-2', 'xl:grid-cols-3');
    });

    listBtn.addEventListener('click', () => {
        container.classList.add('list-view');
        container.classList.remove('grid', 'grid-cols-1', 'sm:grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-2', 'xl:grid-cols-3');
    });
});
