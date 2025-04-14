<div class="{{$class ?? ''}}" >
    @if($type === 'form')
            <div class="form-group">
                <label for="city-select" class="form-label">@lang('City')</label>
                <select id="city-select" class="w-100">
                    <option value="">@lang('All cities')</option>
                    @forelse($cities as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @empty
                        <option value="">@lang('No Data')</option>
                    @endforelse
                </select>
            </div>
    @else
        <div class="filter-group">
            <label>@lang('Cities')</label>
            <select>
                <option>@lang('All cities')</option>
                @forelse($cities as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @empty
                    <option>@lang('No Data')</option>
                @endforelse
            </select>
        </div>
    @endif
</div>
