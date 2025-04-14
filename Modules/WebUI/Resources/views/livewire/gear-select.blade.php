<div>
    @if($type === 'form')
        <div class="col-lg-6 mb-3 mt-3">
            <div class="form-group">
                <label for="brand-select" class="form-label">@lang('Gear')</label>
                <select id="gear-select" class="w-100">
                    <option value="">@lang('Select gear')</option>
                    @forelse($gears as $item)
                        <option value="{{ $item->key }}">{{ $item->label }}</option>
                    @empty
                        <option value="">@lang('No Data')</option>
                    @endforelse
                </select>
            </div>
        </div>
    @else
        <div class="box-select">
            <h5>@lang('Gear')</h5>
            <div class="nice-select" tabindex="0">
                <span class="current">@lang('Select gear')</span>
                <ul class="list">
                    @forelse($gears as $key => $item)
                        <li data-value="{{$item->key}}" class="option">{{$item->label}}</li>
                    @empty
                        <li class="option"> @lang('No Data')</li>
                    @endforelse

                </ul>
            </div>
        </div>
    @endif
</div>
