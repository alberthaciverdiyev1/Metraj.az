/* Navbar — badge-lər, dropdown-lar, mobil drawer (navbar.blade.php-dən çıxarılıb) */
document.addEventListener('DOMContentLoaded', function () {
    // Nav count badges
    function updateNavBadges() {
      const favBadge = document.getElementById('favorites-count');
      const mobileFavBadge = document.getElementById('mobile-bottom-fav-count');
      const compBadge = document.getElementById('compares-count');

      try {
        const favs = JSON.parse(localStorage.getItem('favorites')) || [];
        if (favBadge) favBadge.textContent = favs.length;
        if (mobileFavBadge) {
          mobileFavBadge.textContent = favs.length;
          mobileFavBadge.classList.toggle('hidden', favs.length === 0);
        }
      } catch(e) {}

      if (compBadge) {
        try {
          const comps = JSON.parse(localStorage.getItem('compareList')) || [];
          compBadge.textContent = comps.length;
        } catch(e) {}
      }
    }
    updateNavBadges();
    window.addEventListener('storage', updateNavBadges);

    // Generic Dropdown Helper
    function setupDropdown(btnId, menuId, chevronId) {
      const btn = document.getElementById(btnId);
      const menu = document.getElementById(menuId);
      const chevron = chevronId ? document.getElementById(chevronId) : null;
      if (!btn || !menu) return;

      btn.addEventListener('click', function (e) {
        e.stopPropagation();
        ['navCurrencyDropdown', 'navLangDropdown', 'navUserDropdown'].forEach(id => {
          if (id !== menuId) {
            const el = document.getElementById(id);
            if (el) el.classList.add('hidden');
          }
        });
        ['navCurrencyChevron', 'navLangChevron', 'navUserChevron'].forEach(id => {
          if (id !== chevronId) {
            const el = document.getElementById(id);
            if (el) el.classList.remove('rotate-180');
          }
        });

        const isHidden = menu.classList.contains('hidden');
        menu.classList.toggle('hidden', !isHidden);
        if (chevron) chevron.classList.toggle('rotate-180', isHidden);
      });
    }

    setupDropdown('navCurrencyBtn', 'navCurrencyDropdown', 'navCurrencyChevron');
    setupDropdown('navLangBtn', 'navLangDropdown', 'navLangChevron');
    setupDropdown('navUserMenuBtn', 'navUserDropdown', 'navUserChevron');

    document.addEventListener('click', function () {
      ['navCurrencyDropdown', 'navLangDropdown', 'navUserDropdown'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.add('hidden');
      });
      ['navCurrencyChevron', 'navLangChevron', 'navUserChevron'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.remove('rotate-180');
      });
    });

    // Mobile "Daha Çox" Drawer toggle
    const mobileMoreBtn = document.getElementById('mobileMoreDrawerBtn');
    const mobileDrawer = document.getElementById('mobileMoreDrawer');
    const closeDrawerBtn = document.getElementById('closeMobileMoreDrawer');
    const backdrop = document.getElementById('mobileMoreDrawerBackdrop');

    function openDrawer() {
      if (mobileDrawer) mobileDrawer.classList.remove('hidden');
    }
    function closeDrawer() {
      if (mobileDrawer) mobileDrawer.classList.add('hidden');
    }

    if (mobileMoreBtn) mobileMoreBtn.addEventListener('click', openDrawer);
    if (closeDrawerBtn) closeDrawerBtn.addEventListener('click', closeDrawer);
    if (backdrop) backdrop.addEventListener('click', closeDrawer);
});
