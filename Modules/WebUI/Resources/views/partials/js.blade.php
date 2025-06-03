<!-- webui::partials/js.blade.php -->

<!-- Statik skriptlər -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

<!-- Dinamik skriptlər -->
@forelse($js as $j)
    <script src="{{ asset('webui/js/'.$j) }}"></script>
@empty
@endforelse
