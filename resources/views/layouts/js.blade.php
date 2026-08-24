<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init();</script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
    // Qlobal AJAX köməkçisi — bütün POST-lar JS fetch ilə gedir
    window.Metraj = {
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

        const { ok, data } = await window.Metraj.post(form.action, new FormData(form));

        if (ok) {
            window.location.href = data.redirect || '/';
        } else {
            window.Metraj.toast(data.message || 'Çıxış zamanı xəta baş verdi', 'error');
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
    window.showMetrajToast = function (opts) {
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
                ? '<i class="bi bi-arrow-left-right text-orange-500 text-base mr-2 font-bold"></i>'
                : '<i class="bi bi-arrow-left-right text-gray-400 text-base mr-2"></i>';
        } else if (isError) {
            iconHtml = '<i class="bi bi-exclamation-triangle-fill text-amber-400 text-base mr-2"></i>';
        }

        let linkHtml = '';
        if (opts.linkUrl && opts.linkText) {
            linkHtml = `<a href="${opts.linkUrl}" class="ml-3 text-orange-400 hover:text-orange-300 font-bold underline text-xs inline-block">${opts.linkText}</a>`;
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
                btn.classList.add('text-orange-600', 'bg-orange-100', 'font-bold');
                btn.classList.remove('text-gray-700');
                if (textSpan) textSpan.textContent = 'Müqayisədə';
                if (icon) {
                    icon.className = 'bi bi-check-circle-fill text-sm sm:text-base text-orange-600';
                }
            } else {
                btn.classList.remove('text-orange-600', 'bg-orange-100', 'font-bold');
                btn.classList.add('text-gray-700');
                if (textSpan) textSpan.textContent = 'Müqayisə';
                if (icon) {
                    icon.className = 'bi bi-arrow-left-right text-sm sm:text-base';
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
        const csrf = window.Metraj.csrfToken();

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

        fetch('/api/favorites/toggle', {
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
                    window.showMetrajToast({
                        type: 'favorite',
                        active: true,
                        message: 'Elan seçilmişlərə əlavə edildi',
                        linkText: 'Seçilmişlərə bax',
                        linkUrl: '/favorites'
                    });
                } else {
                    window.showMetrajToast({
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
        const csrf = window.Metraj.csrfToken();

        let compIds = [];
        try {
            const raw = JSON.parse(localStorage.getItem('compareList')) || [];
            compIds = raw.map(c => typeof c === 'object' && c !== null ? c.id : c).filter(Boolean);
        } catch(e) {}

        const currentlyInCompare = compIds.includes(propertyId);
        const willBeInCompare = !currentlyInCompare;

        if (willBeInCompare && compIds.length >= 4) {
            window.showMetrajToast({
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

        fetch('/api/compares/toggle', {
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
                    window.showMetrajToast({
                        type: 'compare',
                        active: true,
                        message: 'Elan müqayisə siyahısına əlavə edildi',
                        linkText: 'Müqayisə et',
                        linkUrl: '/compares'
                    });
                } else {
                    window.showMetrajToast({
                        type: 'compare',
                        active: false,
                        message: 'Elan müqayisə siyahısından çıxarıldı'
                    });
                }
            } else if (data.limit_reached) {
                window.updateCardCompareButton(propertyId, false);
                window.showMetrajToast({
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

        fetch('/api/favorites/ids', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
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

        fetch('/api/compares/ids', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
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
</script>

@if(session('success'))
<script>
    Toastify({
        text: "{{ session('success') }}",
        duration: 7000,
        close: true,
        gravity: "top",
        position: "right",
        style: {
            background: "#059669",
            borderRadius: "14px",
            fontSize: "14px",
            fontWeight: "600",
            padding: "14px 20px",
            boxShadow: "0 10px 25px -5px rgba(16, 185, 129, 0.45)"
        }
    }).showToast();
</script>
@endif

@if(session('error'))
<script>
    Toastify({
        text: "{{ session('error') }}",
        duration: 7000,
        close: true,
        gravity: "top",
        position: "right",
        style: {
            background: "#e11d48",
            borderRadius: "14px",
            fontSize: "14px",
            fontWeight: "600",
            padding: "14px 20px",
            boxShadow: "0 10px 25px -5px rgba(225, 29, 72, 0.45)"
        }
    }).showToast();
</script>
@endif

@if(isset($js))
    @foreach($js as $file)
        <script src="{{ $file }}"></script>
    @endforeach
@endif

@stack('scripts')
