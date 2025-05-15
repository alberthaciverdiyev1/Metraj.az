<!doctype html>

<html lang="en">
    @include('webui::partials.head')
    <body>
        @include('webui::partials.navbar')

        @yield('content')
        
        @include('webui::partials.footer')

        @include('webui::partials.js')
    </body>
</html>
