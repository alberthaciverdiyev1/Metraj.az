<style>
    .checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
    }

    .checkbox-item {
        margin-top: 6px; /* Eskisi mt-12 idi, azalttık */
    }

    .checkbox-item .text-4 {
        font-size: 15px;
    }

    .checkbox-item .btn-checkbox {
        width: 18px;
        height: 18px;
        border-radius: 4px;
        margin-right: 4px;
    }
</style>

<div class="group-amenities">
    @forelse($features as $feature)
        <fieldset class="checkbox-item style-1">
            <label class="d-flex align-items-center">
                <input value="{{ $feature->id }}" type="checkbox">
                <span class="btn-checkbox"></span>
                <span class="text-4">{{ $feature->name }}</span>
            </label>
        </fieldset>
    @empty
        <p class="text-muted">@lang('No features available.')</p>
    @endforelse
</div>
