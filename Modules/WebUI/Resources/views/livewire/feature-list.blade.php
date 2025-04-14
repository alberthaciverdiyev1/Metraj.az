<style>
    .group-amenities.form-layout {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }

    .group-amenities.form-layout .checkbox-item {
        flex: 0 0 calc(33.333% - 10px);
        margin-top: 0;
    }

    .checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
    }

    .checkbox-item {
        margin-top: 6px;
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

    @media (max-width: 768px) {
        .group-amenities.form-layout .checkbox-item {
            flex: 0 0 100%;
        }
    }
</style>

<div class="group-amenities {{ $type === 'form' ? 'form-layout my-4' : '' }}">
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

