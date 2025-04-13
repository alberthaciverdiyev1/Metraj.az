<div class="filter-group">
    <label>@lang('Brands')</label>
    <select>
        <option>@lang('All brands')</option>
        @forelse($brands as $key => $item)
            <option {{$item->id}} >{{$item->name}}</option>
        @empty
            <option>@lang('No Data')</option>
        @endforelse
    </select>
</div>
