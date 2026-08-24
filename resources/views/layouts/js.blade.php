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
