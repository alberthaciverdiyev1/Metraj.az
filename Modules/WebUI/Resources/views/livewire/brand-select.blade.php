<div>
    @if($type === 'form')
        <div class="col-lg-6 mb-3">
            <div class="form-group">
                <label for="brand-select" class="form-label">@lang('Brands')</label>
                <select id="brand-select" class=" w-100">
                    <option value="">@lang('All brands')</option>
                    @forelse($brands as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @empty
                        <option value="">@lang('No Data')</option>
                    @endforelse
                </select>
            </div>
        </div>
    @else
        <div class="filter-group">
            <label>@lang('Brands')</label>
            <select>
                <option>@lang('All brands')</option>
                @forelse($brands as $key => $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @empty
                    <option>@lang('No Data')</option>
                @endforelse
            </select>
        </div>
    @endif
</div>
