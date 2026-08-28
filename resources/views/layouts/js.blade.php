<script>
    // Polyfill AOS in case external/cached scripts attempt to invoke it
    if (typeof window.AOS === 'undefined') {
        window.AOS = { init: function() {}, refresh: function() {} };
    }

    // Qlobal AJAX köməkçisi — bütün POST-lar JS fetch ilə gedir
    window.KibrisKare = window.KibrisKare || {
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
            if (typeof Toastify !== 'undefined') {
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
                        boxShadow: '0 10px 25px -5px rgba(0,0,0,0.3)',
                        zIndex: '999999'
                    },
                }).showToast();
                return;
            }

            let container = document.getElementById('kibriskare-fallback-toasts');
            if (!container) {
                container = document.createElement('div');
                container.id = 'kibriskare-fallback-toasts';
                container.style.cssText = 'position:fixed;top:20px;right:20px;z-index:999999;display:flex;flex-direction:column;gap:10px;max-width:380px;';
                document.body.appendChild(container);
            }
            const el = document.createElement('div');
            el.style.cssText = `background:${type === 'success' ? '#059669' : '#e11d48'};color:#fff;border-radius:14px;font-size:14px;font-weight:600;padding:14px 20px;box-shadow:0 10px 25px -5px rgba(0,0,0,0.3);transition:all 0.3s ease;`;
            el.textContent = message;
            container.appendChild(el);
            setTimeout(() => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(-10px)';
                setTimeout(() => el.remove(), 300);
            }, 5000);
        },
    };

    // Front JS faylları üçün Laravel route() ilə yaradılmış API URL-ləri
    window.KibrisKareRoutes = {
        locale: "{{ app()->getLocale() }}",
        favoritesToggle: "{{ route('favorites.toggle') }}",
        favoritesClear: "{{ route('favorites.clear') }}",
        favoritesIds: "{{ route('favorites.ids') }}",
        comparesToggle: "{{ route('compares.toggle') }}",
        comparesClear: "{{ route('compares.clear') }}",
        comparesIds: "{{ route('compares.ids') }}",
        cities: "{{ route('api.cities') }}",
    };
</script>

<script src="{{ asset('js/layouts/global.js') }}?v={{ file_exists(public_path('js/layouts/global.js')) ? filemtime(public_path('js/layouts/global.js')) : time() }}"></script>

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
