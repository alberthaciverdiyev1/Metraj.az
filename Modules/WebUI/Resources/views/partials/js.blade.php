@forelse($js as $j)
    <script src="{{ asset('webui/js/'.$j) }}"></script>
@empty
@endforelse
