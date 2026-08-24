/* Favoritlər səhifəsi — seçilmiş elanların idarəsi (favorites.blade.php-dən çıxarılıb) */
document.addEventListener('DOMContentLoaded', function () {
    const R = window.MetrajRoutes || {};
    const favsEmptyState = document.getElementById('favsEmptyState');
    const favoritesContainer = document.getElementById('favoritesContainer');
    const favsActions = document.getElementById('favsActions');
    const favsTotalBadge = document.getElementById('favsTotalBadge');
    const clearAllBtn = document.getElementById('clearAllFavoritesBtn');

    function showEmpty() {
        if (favoritesContainer) {
            favoritesContainer.classList.add('hidden');
            favoritesContainer.innerHTML = '';
        }
        if (favsActions) favsActions.classList.add('hidden');
        if (favsTotalBadge) favsTotalBadge.classList.add('hidden');
        if (favsEmptyState) favsEmptyState.classList.remove('hidden');

        const navBadge = document.getElementById('favorites-count');
        if (navBadge) navBadge.textContent = '0';
        try { localStorage.removeItem('favorites'); } catch(e) {}
    }

    function initFavoritesPage() {
        // Mark all card heart icons as solid on this page
        document.querySelectorAll('#favoritesContainer .fa-heart').forEach(icon => {
            icon.classList.remove('fa-regular');
            icon.classList.add('fa-solid', 'text-red-500');
        });

        // Intercept remove button on this page
        document.querySelectorAll('#favoritesContainer [onclick*="toggleFavorite"]').forEach(btn => {
            btn.onclick = function (e) {
                e.stopPropagation();
                const cardWrapper = btn.closest('.favorite-card-wrapper');
                const card = btn.closest('[data-property-id]');
                const propertyId = card ? parseInt(card.getAttribute('data-property-id')) : null;

                if (propertyId) {
                    const csrf = window.Metraj?.csrfToken() || '';
                    fetch(R.favoritesToggle || '/api/favorites/toggle', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ property_id: propertyId })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            if (cardWrapper) {
                                cardWrapper.style.transform = 'scale(0.95)';
                                cardWrapper.style.opacity = '0';
                                setTimeout(() => {
                                    cardWrapper.remove();
                                    const remainingCards = document.querySelectorAll('#favoritesContainer .favorite-card-wrapper');
                                    if (remainingCards.length === 0) {
                                        showEmpty();
                                    } else {
                                        if (favsTotalBadge) favsTotalBadge.textContent = data.count;
                                        const navBadge = document.getElementById('favorites-count');
                                        if (navBadge) navBadge.textContent = data.count;
                                    }
                                }, 250);
                            }
                        }
                    });
                }
            };
        });
    }

    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function () {
            if (confirm('Bütün seçilmiş elanları silmək istədiyinizdən əminsiniz?')) {
                const csrf = window.Metraj?.csrfToken() || '';
                fetch(R.favoritesClear || '/api/favorites/clear', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showEmpty();
                    }
                });
            }
        });
    }

    initFavoritesPage();
});
