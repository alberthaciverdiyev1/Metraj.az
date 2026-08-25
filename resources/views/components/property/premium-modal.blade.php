<div id="modal-premium"
     class="fixed inset-0 flex items-center justify-center z-[1000] bg-black/50" style="display: none;">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full mx-4 min-h-[50vh] max-h-[80vh] relative p-4 flex flex-col space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold gap-3 m-0 flex items-center">
                <i class="fa-solid fa-crown text-yellow-500 text-3xl hover:text-yellow-400"></i>
                {{ __('property.make_premium') }}
            </h3>
            <button class="text-gray-500 hover:text-gray-800 text-xl font-semibold" data-close="modal-premium">&times;</button>
        </div>
        <p class="text-sm">{{ __('property.promotion_desc') }}</p>
        <h4 class="font-medium mt-4">{{ __('property.select_duration') }}</h4>
        <div class="flex flex-col space-y-2.5">
            <label class="flex items-center justify-between p-3 border rounded hover:bg-gray-50 cursor-pointer text-sm">
                <span>1 {{ __('property.times') }} / 7,00 AZN</span>
                <input type="radio" name="premiumOption" class="h-4 w-4 text-blue-600">
            </label>
            <label class="flex items-center justify-between p-3 border rounded hover:bg-gray-50 cursor-pointer text-sm">
                <span>3 {{ __('property.times') }} ({{ __('property.every_24_hours') }}) / 14,00 AZN</span>
                <input type="radio" name="premiumOption" class="h-4 w-4 text-blue-600">
            </label>
            <label class="flex items-center justify-between p-3 border rounded hover:bg-gray-50 cursor-pointer text-sm">
                <span>5 {{ __('property.times') }} ({{ __('property.every_24_hours') }}) / 21,00 AZN</span>
                <input type="radio" name="premiumOption" class="h-4 w-4 text-blue-600">
            </label>
            <label class="flex items-center justify-between p-3 border rounded hover:bg-gray-50 cursor-pointer text-sm">
                <span>10 {{ __('property.times') }} ({{ __('property.every_24_hours') }}) / 28,00 AZN</span>
                <input type="radio" name="premiumOption" class="h-4 w-4 text-blue-600">
            </label>
        </div>
        <div class="border-t pt-3 flex flex-col space-y-2.5">
            <p class="text-xs text-gray-600">{{ __('property.payment_terms_notice') }}</p>
            <button class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-3 rounded">{{ __('property.pay') }}</button>
        </div>
    </div>
</div>
