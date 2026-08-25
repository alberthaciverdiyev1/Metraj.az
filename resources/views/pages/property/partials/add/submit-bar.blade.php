<!-- Submit Action Bar -->
<div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-5 bg-gray-50 border border-gray-200/90 rounded-2xl">
    <p class="text-xs text-gray-500">
        <i class="bi bi-shield-check text-orange-500 mr-1 text-sm"></i>
        <span>{{ __('add_property.submit_notice') }}</span>
    </p>
    <button type="submit" id="submit_property_btn"
        class="w-full sm:w-auto px-8 py-3.5 bg-orange-500 hover:bg-orange-600 text-white font-semibold text-sm rounded-xl shadow transition duration-200 transform active:scale-98 flex items-center justify-center gap-2 shrink-0">
        <i class="bi bi-check2-circle text-base"></i>
        <span>{{ __('add_property.submit_btn') }}</span>
    </button>
</div>
