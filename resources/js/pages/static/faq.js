/* FAQ səhifəsi — accordion, kateqoriya filtri, canlı axtarış */
(function () {
    'use strict';

    function initFaq() {
        // 1. Accordion Toggle via Event Delegation
        document.addEventListener('click', function (e) {
            const trigger = e.target.closest('.faq-trigger');
            if (!trigger) return;

            const item = trigger.closest('.faq-item');
            if (!item) return;

            const content = item.querySelector('.faq-content');
            const icon = item.querySelector('.faq-icon');
            const isCurrentlyOpen = content && !content.classList.contains('hidden');

            // Close all items
            document.querySelectorAll('.faq-item').forEach(function (otherItem) {
                const otherContent = otherItem.querySelector('.faq-content');
                const otherIcon = otherItem.querySelector('.faq-icon');
                if (otherContent) otherContent.classList.add('hidden');
                if (otherIcon) {
                    otherIcon.style.transform = 'rotate(0deg)';
                    otherIcon.classList.remove('bg-orange-500', 'text-white');
                    otherIcon.classList.add('bg-orange-50', 'text-orange-500');
                }
            });

            // Toggle clicked item
            if (!isCurrentlyOpen && content) {
                content.classList.remove('hidden');
                if (icon) {
                    icon.style.transform = 'rotate(180deg)';
                    icon.classList.remove('bg-orange-50', 'text-orange-500');
                    icon.classList.add('bg-orange-500', 'text-white');
                }
            }
        });

        // 2. Category Filter Buttons
        const filterButtons = document.querySelectorAll('.faq-filter-btn');
        const faqItems = document.querySelectorAll('.faq-item');
        const noResults = document.getElementById('noFaqResults');
        let activeCategory = 'all';

        filterButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                filterButtons.forEach(function (b) {
                    b.className = 'faq-filter-btn px-4 py-2 rounded-2xl text-xs sm:text-sm font-semibold transition duration-200 whitespace-nowrap bg-white text-gray-700 hover:bg-gray-50 border border-gray-200 cursor-pointer';
                });
                this.className = 'faq-filter-btn px-4 py-2 rounded-2xl text-xs sm:text-sm font-semibold transition duration-200 whitespace-nowrap bg-orange-500 text-white shadow-sm cursor-pointer';

                activeCategory = this.dataset.category || 'all';
                applyFilters();
            });
        });

        // 3. Live Search Filter
        const searchInput = document.getElementById('faqSearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                applyFilters();
            });
        }

        function applyFilters() {
            const query = (searchInput ? searchInput.value : '').trim().toLowerCase();
            let visibleCount = 0;

            faqItems.forEach(function (item) {
                const itemCat = item.dataset.category || '';
                const itemText = item.textContent.toLowerCase();

                const matchesCat = (activeCategory === 'all' || itemCat === activeCategory);
                const matchesQuery = query === '' || itemText.includes(query);

                if (matchesCat && matchesQuery) {
                    item.classList.remove('hidden');
                    visibleCount++;
                } else {
                    item.classList.add('hidden');
                }
            });

            if (noResults) {
                if (visibleCount === 0) {
                    noResults.classList.remove('hidden');
                } else {
                    noResults.classList.add('hidden');
                }
            }
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFaq);
    } else {
        initFaq();
    }
})();
