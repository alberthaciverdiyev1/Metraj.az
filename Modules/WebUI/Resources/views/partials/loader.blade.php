@if(config('app.env') !== 'local')
    <div class="preloader">
        <div class="loading-container">
            <div class="loading"></div>
            <div id="loading-icon"><img src="{{asset('web/img/logo/preloader.png')}}" alt="housebox"></div>
        </div>
    </div>
@endif
