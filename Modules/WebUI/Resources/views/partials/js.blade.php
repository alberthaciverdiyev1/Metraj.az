@forelse($js as $j)
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="{{ asset('webui/js/'.$j) }}"></script>
       <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
@empty
@endforelse
