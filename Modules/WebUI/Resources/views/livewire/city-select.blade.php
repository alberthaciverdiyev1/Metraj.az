<fieldset style="border: none; padding: 0; margin: 0;">
    <legend style="font-weight: 500; font-size: 14px; margin-bottom: 8px;">@lang('Cities')</legend>
    <select>
        <option>@lang('All cities')</option>
        @forelse($cities as $key => $item)
            <option {{$item->id}} >{{$item->name}}</option>
        @empty
            <option>@lang('No Data')</option>
        @endforelse
    </select>
</fieldset>
