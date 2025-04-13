<div class="box-select ">
    <h5>@lang('Currency')</h5>
    <div class="nice-select" tabindex="0">
        <span class="current">@lang('Currency')</span>
        <ul class="list">
            @forelse($currencies as $key => $item)
                <li data-value="{{$item->key}}" class="option">{{$item->label}}</li>
            @empty
                <li class="option"> @lang('No Data')</li>
            @endforelse

        </ul>
    </div>
</div>
