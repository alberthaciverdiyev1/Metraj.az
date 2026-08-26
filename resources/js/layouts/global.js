/* KibrisKare global helpers (layouts/js.blade.php-dən çıxarılıb) — window.KibrisKare, favorite/compare toggle, toast, logout, card image switcher */
(function () {
'use strict';

const R = window.KibrisKareRoutes || {};

    // Qlobal AJAX köməkçisi — bütün POST-lar JS fetch ilə gedir
    window.KibrisKare = {
        csrfToken() {
            const meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.content : '';
        },
        async post(url, body, opts = {}) {
            const isFormData = body instanceof FormData;
            const headers = {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': this.csrfToken(),
            };
            if (!isFormData) {
                headers['Content-Type'] = 'application/json';
            }
            const res = await fetch(url, {
                method: 'POST',
                headers,
                body: isFormData ? body : JSON.stringify(body),
                ...opts,
            });
            const data = await res.json().catch(() => ({}));
            return { ok: res.ok, status: res.status, data };
        },
        toast(message, type = 'success') {
            if (typeof Toastify === 'undefined') return;
            Toastify({
                text: message,
                duration: 5000,
                close: true,
                gravity: 'top',
                position: 'right',
                style: {
                    background: type === 'success' ? '#059669' : '#e11d48',
                    borderRadius: '14px',
                    fontSize: '14px',
                    fontWeight: '600',
                    padding: '14px 20px',
                },
            }).showToast();
        },
    };

    // Logout — bütün .js-logout formaları fetch ilə çıxış edir
    document.addEventListener('submit', async function (e) {
        const form = e.target.closest('form.js-logout');
        if (!form) return;
        e.preventDefault();

        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.style.opacity = '0.6';
        }

        const { ok, data } = await window.KibrisKare.post(form.action, new FormData(form));

        if (ok) {
            window.location.href = data.redirect || '/';
        } else {
            window.KibrisKare.toast(data.message || 'Çıxış zamanı xəta baş verdi', 'error');
            if (btn) {
                btn.disabled = false;
                btn.style.opacity = '1';
            }
        }
    });

    // Qlobal Property Card Şəkil Dəyişdiricisi (Yalnız ox düymələrinə kliklədikdə)
    window.showPropertyCardImage = function (container, images, index) {
        if (!container || !images || !images.length) return;
        const img = container.querySelector('.card-image');
        if (img) img.src = images[index];
        container.setAttribute('data-current', index);

        const dots = container.querySelectorAll('.absolute.bottom-2 > span');
        dots.forEach(function (dot, i) {
            dot.classList.toggle('bg-white', i === index);
            dot.classList.toggle('bg-white/50', i !== index);
        });
    };

    window.nextImage = function (btn) {
        const container = btn.closest('[data-images]');
        if (!container) return;
        try {
            const images = JSON.parse(container.getAttribute('data-images') || '[]');
            if (!images.length) return;
            const current = parseInt(container.getAttribute('data-current'), 10) || 0;
            const next = (current + 1) % images.length;
            window.showPropertyCardImage(container, images, next);
        } catch (e) {
            console.error(e);
        }
    };

    window.prevImage = function (btn) {
        const container = btn.closest('[data-images]');
        if (!container) return;
        try {
            const images = JSON.parse(container.getAttribute('data-images') || '[]');
            if (!images.length) return;
            const current = parseInt(container.getAttribute('data-current'), 10) || 0;
            const prev = (current - 1 + images.length) % images.length;
            window.showPropertyCardImage(container, images, prev);
        } catch (e) {
            console.error(e);
        }
    };

    /* ===== QLOBAL TOP-RIGHT TOAST BİLDİRİŞ SİSTEMİ ===== */
    window.showKibrisKareToast = function (opts) {
        if (typeof Toastify === 'undefined') return;
        const isFav = opts.type === 'favorite';
        const isComp = opts.type === 'compare';
        const isError = opts.type === 'error';

        let iconHtml = '';
        if (isFav) {
            iconHtml = opts.active
                ? '<i class="fa-solid fa-heart text-rose-500 text-base mr-2"></i>'
                : '<i class="fa-regular fa-heart text-gray-400 text-base mr-2"></i>';
        } else if (isComp) {
            iconHtml = opts.active
                ? '<i class="bi bi-arrow-left-right text-orange-500 text-base mr-2 font-semibold"></i>'
                : '<i class="bi bi-arrow-left-right text-gray-400 text-base mr-2"></i>';
        } else if (isError) {
            iconHtml = '<i class="bi bi-exclamation-triangle-fill text-amber-400 text-base mr-2"></i>';
        }

        let linkHtml = '';
        if (opts.linkUrl && opts.linkText) {
            linkHtml = `<a href="${opts.linkUrl}" class="ml-3 text-orange-400 hover:text-orange-300 font-semibold underline text-xs inline-block">${opts.linkText}</a>`;
        }

        const div = document.createElement('div');
        div.className = 'flex items-center text-sm font-medium text-white';
        div.innerHTML = `${iconHtml}<span class="flex-1">${opts.message}</span>${linkHtml}`;

        Toastify({
            node: div,
            duration: 3500,
            close: true,
            gravity: "top",
            position: "right",
            style: {
                background: "#1e293b",
                borderRadius: "14px",
                fontSize: "14px",
                fontWeight: "500",
                padding: "12px 18px",
                boxShadow: "0 10px 25px -5px rgba(0, 0, 0, 0.35)",
                border: "1px solid #334155",
                marginTop: "10px",
                zIndex: "999999",
                display: "inline-flex",
                alignItems: "center",
                justifyContent: "space-between",
                gap: "12px",
            }
        }).showToast();
    };

    /* ===== KARTLARIN STATUSLARINI SİNXRONLAŞDIRMA (FAVORİT & MÜQAYİSƏ) ===== */
    window.updateCardCompareButton = function (propertyId, isCompared) {
        document.querySelectorAll(`[data-compare-btn="${propertyId}"]`).forEach(btn => {
            const textSpan = btn.querySelector('.compare-btn-text');
            const icon = btn.querySelector('i');
            if (isCompared) {
                btn.classList.add('text-orange-600', 'bg-orange-100', 'font-semibold');
                btn.classList.remove('text-gray-700');
                if (textSpan) textSpan.textContent = 'Müqayisədə';
                if (icon) {
                    icon.className = 'bi bi-check-circle-fill text-sm sm:text-base text-orange-600';
                }
            } else {
                btn.classList.remove('text-orange-600', 'bg-orange-100', 'font-semibold');
                btn.classList.add('text-gray-700');
                if (textSpan) textSpan.textContent = 'Müqayisə';
                if (icon) {
                    icon.className = 'bi bi-arrow-left-right text-sm sm:text-base text-orange-500';
                }
            }
        });
    };

    window.updateCardFavoriteButton = function (propertyId, isFavorite) {
        document.querySelectorAll(`[data-fav-btn="${propertyId}"]`).forEach(btn => {
            const icon = btn.querySelector('i') || (btn.tagName === 'I' ? btn : null);
            if (!icon) return;
            if (isFavorite) {
                icon.classList.remove('fa-regular');
                icon.classList.add('fa-solid', 'text-red-500');
            } else {
                icon.classList.remove('fa-solid', 'text-red-500');
                icon.classList.add('fa-regular');
            }
        });
    };

    window.syncCardStates = function () {
        let favIds = [];
        let compIds = [];
        try {
            const rawFavs = JSON.parse(localStorage.getItem('favorites')) || [];
            favIds = rawFavs.map(f => typeof f === 'object' && f !== null ? f.id : f).filter(Boolean);
            const rawComps = JSON.parse(localStorage.getItem('compareList')) || [];
            compIds = rawComps.map(c => typeof c === 'object' && c !== null ? c.id : c).filter(Boolean);
        } catch (e) {}

        // Favorit düymələri
        document.querySelectorAll('[data-fav-btn]').forEach(btn => {
            const id = parseInt(btn.getAttribute('data-fav-btn'), 10);
            window.updateCardFavoriteButton(id, favIds.includes(id));
        });

        // Müqayisə düymələri
        document.querySelectorAll('[data-compare-btn]').forEach(btn => {
            const id = parseInt(btn.getAttribute('data-compare-btn'), 10);
            window.updateCardCompareButton(id, compIds.includes(id));
        });
    };

    /* ===== QLOBAL FAVORITE & COMPARE (ANLIQ OPTIMISTIC UPDATE & BACKEND SİNXRONİZASİYASI) ===== */
    window.toggleFavorite = function (element, propertyId) {
        propertyId = parseInt(propertyId, 10);
        const csrf = window.KibrisKare.csrfToken();

        let favIds = [];
        try {
            const raw = JSON.parse(localStorage.getItem('favorites')) || [];
            favIds = raw.map(f => typeof f === 'object' && f !== null ? f.id : f).filter(Boolean);
        } catch(e) {}

        const currentlyInFav = favIds.includes(propertyId);
        const willBeInFav = !currentlyInFav;

        // Dərhal vizual olaraq dəyiş (0ms gecikmə)
        window.updateCardFavoriteButton(propertyId, willBeInFav);

        // Yerli yaddaşı müvəqqəti yenilə
        let newFavs = willBeInFav ? [...favIds, propertyId] : favIds.filter(id => id !== propertyId);
        try {
            localStorage.setItem('favorites', JSON.stringify(newFavs.map(id => ({ id: id }))));
        } catch(e) {}

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
                const navBadge = document.getElementById('favorites-count');
                if (navBadge) navBadge.textContent = data.count;
                const totalBadge = document.getElementById('favsTotalBadge');
                if (totalBadge) totalBadge.textContent = data.count;

                try {
                    localStorage.setItem('favorites', JSON.stringify((data.ids || []).map(id => ({ id: id }))));
                } catch (e) {}

                window.updateCardFavoriteButton(propertyId, data.is_favorite);

                if (data.is_favorite) {
                    window.showKibrisKareToast({
                        type: 'favorite',
                        active: true,
                        message: 'Elan seçilmişlərə əlavə edildi',
                        linkText: 'Seçilmişlərə bax',
                        linkUrl: '/favorites'
                    });
                } else {
                    window.showKibrisKareToast({
                        type: 'favorite',
                        active: false,
                        message: 'Elan seçilmişlərdən çıxarıldı'
                    });
                }
            }
        })
        .catch(e => {
            console.error('Favorite error:', e);
            window.updateCardFavoriteButton(propertyId, currentlyInFav);
        });
    };

    window.toggleCompare = function (element, propertyId) {
        propertyId = parseInt(propertyId, 10);
        const csrf = window.KibrisKare.csrfToken();

        let compIds = [];
        try {
            const raw = JSON.parse(localStorage.getItem('compareList')) || [];
            compIds = raw.map(c => typeof c === 'object' && c !== null ? c.id : c).filter(Boolean);
        } catch(e) {}

        const currentlyInCompare = compIds.includes(propertyId);
        const willBeInCompare = !currentlyInCompare;

        if (willBeInCompare && compIds.length >= 4) {
            window.showKibrisKareToast({
                type: 'error',
                message: 'Ən çox 4 elan müqayisə edilə bilər.'
            });
            return;
        }

        // Dərhal vizual olaraq dəyiş (0ms gecikmə)
        window.updateCardCompareButton(propertyId, willBeInCompare);

        // Yerli yaddaşı müvəqqəti yenilə
        let newComps = willBeInCompare ? [...compIds, propertyId] : compIds.filter(id => id !== propertyId);
        try {
            localStorage.setItem('compareList', JSON.stringify(newComps.map(id => ({ id: id }))));
        } catch(e) {}

        fetch(R.comparesToggle || '/api/compares/toggle', {
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
                const navBadge = document.getElementById('compares-count');
                if (navBadge) navBadge.textContent = data.count;

                try {
                    localStorage.setItem('compareList', JSON.stringify((data.ids || []).map(id => ({ id: id }))));
                } catch (e) {}

                window.updateCardCompareButton(propertyId, data.is_compared);

                if (data.is_compared) {
                    window.showKibrisKareToast({
                        type: 'compare',
                        active: true,
                        message: 'Elan müqayisə siyahısına əlavə edildi',
                        linkText: 'Müqayisə et',
                        linkUrl: '/compares'
                    });
                } else {
                    window.showKibrisKareToast({
                        type: 'compare',
                        active: false,
                        message: 'Elan müqayisə siyahısından çıxarıldı'
                    });
                }
            } else if (data.limit_reached) {
                window.updateCardCompareButton(propertyId, false);
                window.showKibrisKareToast({
                    type: 'error',
                    message: data.message || 'Ən çox 4 elan müqayisə edilə bilər.'
                });
            }
        })
        .catch(e => {
            console.error('Compare error:', e);
            window.updateCardCompareButton(propertyId, currentlyInCompare);
        });
    };

    // Səhifə açıldıqda backend-dən sayları al və navbarda yenilə
    document.addEventListener('DOMContentLoaded', function () {
        window.syncCardStates();

        fetch(R.favoritesIds || '/api/favorites/ids', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    const navBadge = document.getElementById('favorites-count');
                    if (navBadge) navBadge.textContent = d.count;
                    try {
                        localStorage.setItem('favorites', JSON.stringify((d.ids || []).map(id => ({ id: id }))));
                    } catch(e) {}
                    window.syncCardStates();
                }
            }).catch(() => {});

        fetch(R.comparesIds || '/api/compares/ids', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    const navBadge = document.getElementById('compares-count');
                    if (navBadge) navBadge.textContent = d.count;
                    try {
                        localStorage.setItem('compareList', JSON.stringify((d.ids || []).map(id => ({ id: id }))));
                    } catch(e) {}
                    window.syncCardStates();
                }
            }).catch(() => {});
    });

    /* ===== QLOBAL ESCAPE TUŞU İLƏ BÜTÜN MODALLARIN VƏ DRAWER-LƏRİN BAĞLANMASI ===== */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' || e.key === 'Esc' || e.keyCode === 27) {
            // 1. Şəhər filtri modalı
            const cityModal = document.getElementById('cityFilterModal');
            const closeCityBtn = document.getElementById('closeCityFilterModal');
            if (cityModal && (!cityModal.classList.contains('hidden') && cityModal.style.display !== 'none')) {
                if (closeCityBtn) {
                    closeCityBtn.click();
                } else {
                    cityModal.classList.add('hidden');
                }
            }

            // 2. Ətraflı filtrlər modalı
            const moreFiltersModal = document.getElementById('moreFiltersModal');
            const closeMoreBtn = document.getElementById('closeMoreFilters') || document.getElementById('closeMoreFiltersBtn');
            if (moreFiltersModal && (!moreFiltersModal.classList.contains('hidden') && moreFiltersModal.style.display !== 'none')) {
                if (closeMoreBtn) {
                    closeMoreBtn.click();
                } else {
                    moreFiltersModal.classList.add('hidden');
                }
            }

            // 3. Mobil menyu drawer-i
            const mobileDrawer = document.getElementById('mobileMoreDrawer');
            const closeDrawerBtn = document.getElementById('closeMobileMoreDrawer');
            if (mobileDrawer && (!mobileDrawer.classList.contains('hidden') && mobileDrawer.style.display !== 'none')) {
                if (closeDrawerBtn) {
                    closeDrawerBtn.click();
                } else {
                    mobileDrawer.classList.add('hidden');
                }
            }

            // 4. Detal səhifəsindəki Qalereya / Lightbox modalı
            const galleryModal = document.getElementById('modal');
            const closeGalleryBtn = document.getElementById('closeModal') || document.getElementById('close-modal');
            if (galleryModal && (!galleryModal.classList.contains('hidden') && galleryModal.style.display !== 'none')) {
                if (closeGalleryBtn) {
                    closeGalleryBtn.click();
                } else if (typeof window.closeModal === 'function') {
                    window.closeModal();
                } else {
                    galleryModal.classList.add('hidden');
                    galleryModal.style.display = 'none';
                }
            }

            // 5. Nömrə / Əlaqə modalı
            const phoneModal = document.getElementById('multiplePhoneModal') || document.getElementById('phoneModal');
            if (phoneModal && (!phoneModal.classList.contains('hidden') && phoneModal.style.display !== 'none')) {
                const closePhoneBtn = phoneModal.querySelector('.close-modal') || phoneModal.querySelector('.close-phone-modal') || phoneModal.querySelector('[data-close-modal]');
                if (closePhoneBtn) {
                    closePhoneBtn.click();
                } else {
                    phoneModal.classList.add('hidden');
                    phoneModal.style.display = 'none';
                }
            }

            // 6. İrəli çək / Premium modalları
            ['moveForwardModal', 'premiumModal'].forEach(mId => {
                const el = document.getElementById(mId);
                if (el && el.style.display !== 'none' && !el.classList.contains('hidden')) {
                    const cBtn = el.querySelector('[id^="close"]') || el.querySelector('.close-modal') || el.querySelector('button');
                    if (cBtn) {
                        cBtn.click();
                    } else {
                        el.style.display = 'none';
                        el.classList.add('hidden');
                    }
                }
            });

            // 7. Açıq olan bütün xüsusi filter menyuları və dropdown-lar
            document.querySelectorAll('.filter-custom-menu:not(.hidden)').forEach(menu => {
                menu.classList.add('hidden');
            });
            document.querySelectorAll('.filter-custom-chevron.rotate-180').forEach(chev => {
                chev.classList.remove('rotate-180');
            });

            // 8. Qlobal digər açıq modallar
            document.querySelectorAll('.modal:not(.hidden), [data-modal]:not(.hidden), [role="dialog"]:not(.hidden)').forEach(m => {
                if (m.style.display !== 'none') {
                    const closeBtn = m.querySelector('[data-close-modal], .close-modal, .btn-close, .modal-close');
                    if (closeBtn) {
                        closeBtn.click();
                    } else {
                        m.classList.add('hidden');
                    }
                }
            });

            // 9. Navbar dropdown-ları
            ['navCurrencyDropdown', 'navLangDropdown', 'navUserDropdown'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.classList.add('hidden');
            });
            ['navCurrencyChevron', 'navLangChevron', 'navUserChevron'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.classList.remove('rotate-180');
            });
        }
    });
})();
