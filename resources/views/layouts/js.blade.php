<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
    // Front JS faylları üçün Laravel route() ilə yaradılmış API URL-ləri
    window.KibrisKareRoutes = {
        favoritesToggle: "{{ route('favorites.toggle') }}",
        favoritesClear: "{{ route('favorites.clear') }}",
        favoritesIds: "{{ route('favorites.ids') }}",
        comparesToggle: "{{ route('compares.toggle') }}",
        comparesClear: "{{ route('compares.clear') }}",
        comparesIds: "{{ route('compares.ids') }}",
        cities: "{{ url('/api/cities') }}",
    };
</script>

<script src="{{ asset('js/layouts/global.js') }}"></script>

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
