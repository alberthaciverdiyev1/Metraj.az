
<div class="box-select">
    <h5>@lang('Gearbox')</h5>
    <div class="nice-select" tabindex="0">
        <span class="current">@lang('Select gearbox')</span>
        <ul class="list">
            @forelse($gearbox as $key => $item)
                <li data-value="{{$item->key}}" class="option">{{$item->label}}</li>
            @empty
                <li class="option"> @lang('No Data')</li>
            @endforelse

        </ul>
    </div>
</div>
