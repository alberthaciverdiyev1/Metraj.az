<div>
    @if($type === 'form')
        <div class="col-lg-6 mb-3 mt-3">
            <div class="form-group">
                <label for="currency-select" class="form-label">@lang('Currency')</label>
                <select id="currency-select" class="w-100">
                    <option value="">@lang('All cities')</option>
                    @forelse($currencies as $item)
                        <option value="{{ $item->key }}">{{ $item->label }}</option>
                    @empty
                        <option value="">@lang('No Data')</option>
                    @endforelse
                </select>
            </div>
        </div>
    @else
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
    @endif
</div>
