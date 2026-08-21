<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init();</script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

@if(isset($js))
    @foreach($js as $file)
        <script src="{{ $file }}"></script>
    @endforeach
@endif

@stack('scripts')
