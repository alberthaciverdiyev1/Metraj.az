<div>
    @if($type === 'form')
        <div class="col-lg-6 mb-3 mt-3">
            <div class="form-group">
                <label for="brand-select" class="form-label">@lang('Body type')</label>
                <select id="gear-select" class="w-100">
                    <option value="">@lang('Select body type')</option>
                    @forelse($body_types as $item)
                        <option value="{{ $item->key }}">{{ $item->label }}</option>
                    @empty
                        <option value="">@lang('No Data')</option>
                    @endforelse
                </select>
            </div>
        </div>
    @else
        <div class="box-select">
            <h5>@lang('Body type')</h5>
            <div class="nice-select" tabindex="0">
                <span class="current">@lang('Select body type')</span>
                <ul class="list">
                    @forelse($body_types as $key => $item)
                        <li data-value="{{$item->key}}" class="option">{{$item->label}}</li>
                    @empty
                        <li class="option"> @lang('No Data')</li>
                    @endforelse

                </ul>
            </div>
        </div>
    @endif
</div>
