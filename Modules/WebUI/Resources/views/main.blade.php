<!DOCTYPE html>
<html lang="en">

@include('webui::partials.head')

<body class="homepage1-body">

<!--===== PRELOADER STARTS =======-->
<div class="preloader">
    <div class="loading-container">
        <div class="loading"></div>
        <div id="loading-icon"><img src="{{asset('web/img/logo/preloader.png')}}" alt="housebox"></div>
    </div>
</div>
<!--===== PRELOADER ENDS =======-->

<!--===== PROGRESS STARTS=======-->
<div class="paginacontainer">
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"/>
        </svg>
    </div>
</div>
<!--===== PROGRESS ENDS=======-->

<!--===== SEARCHBAR STARTS=======-->
@include('webui::partials.searchbar')
<!--===== SEARCHBAR STARTS=======-->

<!--=====HEADER START=======-->
@include('webui::partials.header')
<!--=====HEADER END =======-->

<!--===== MOBILE HEADER STARTS =======-->
@include('webui::partials.mobile_header')
<!--===== MOBILE HEADER STARTS =======-->

@yield('webui::body')

<!--===== FOOTER AREA STARTS =======-->
@include('webui::partials.footer')
<!--===== FOOTER AREA ENDS =======-->

<!--===== JS SCRIPT LINK =======-->
<script src="{{asset('libs/js/bootstrap.min.js')}}"></script>
<script src="{{asset('libs/js/fontawesome.js')}}"></script>
<script src="{{asset('libs/js/aos.js')}}"></script>
<script src="{{asset('libs/js/counter.js')}}"></script>
<script src="{{asset('libs/js/gsap.min.js')}}"></script>
<script src="{{asset('libs/js/ScrollTrigger.min.js')}}"></script>
<script src="{{asset('libs/js/Splitetext.js')}}"></script>
<script src="{{asset('libs/js/sidebar.js')}}"></script>
<script src="{{asset('libs/js/swiper-slider.js')}}"></script>
<script src="{{asset('libs/js/magnific-popup.js')}}"></script>
<script src="{{asset('libs/js/mobilemenu.js')}}"></script>
<script src="{{asset('libs/js/owlcarousel.min.js')}}"></script>
<script src="{{asset('libs/js/nice-select.js')}}"></script>
<script src="{{asset('libs/js/waypoints.js')}}"></script>
<script src="{{asset('libs/js/slick-slider.js')}}"></script>
<script src="{{asset('libs/js/circle-progress.js')}}"></script>




{{--{{asset('libs/js/jquery-3-7-1.min.js')}}--}}

@vite('Modules/WebUI/Resources/assets/js/main.js')

@if(isset($js))
    @foreach($js as $j)
        @vite('Modules/Web/resources/web/js/'.$j)
    @endforeach
@endif


</body>

</html>
