<div id="modal-advance"
     class="fixed inset-0 flex items-center justify-center z-[1000] bg-black/50" style="display: none;">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full mx-4 min-h-[50vh] max-h-[80vh] relative p-4 flex flex-col space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold gap-2 m-0 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 500 500" fill="#28a745" class="ml-1" aria-hidden="true">
                    <path clip-rule="evenodd" fill-rule="evenodd" d="M433.704,237.465c4.456,6.086,7.092,13.539,7.092,21.622 c0,20.079-16.266,36.341-36.344,36.341h-36.341c-9.991,0-18.173,8.18-18.173,18.172v109.025c0,20.079-16.262,36.341-36.341,36.341 H186.4c-20.079,0-36.34-16.262-36.34-36.341V313.6c0-9.992-8.181-18.172-18.172-18.172H95.547 c-20.079,0-36.342-16.262-36.342-36.341c0-8.083,2.635-15.536,7.08-21.622L217.747,54.388c17.807-17.808,46.695-17.808,64.505,0 L433.704,237.465z"/>
                </svg>
                {{ __('property.move_forward') }}
            </h3>
            <button class="text-gray-500 hover:text-gray-800 text-xl font-semibold" data-close="modal-advance">&times;</button>
        </div>
        <p class="text-sm">{{ __('property.promotion_desc') }}</p>
        <h4 class="font-medium mt-4">{{ __('property.select_duration') }}</h4>
        <div class="flex flex-col space-y-2.5">
            <label class="flex items-center justify-between p-2 border rounded hover:bg-gray-50 cursor-pointer text-sm">
                <span>1 {{ __('property.times') }} / 3,00 AZN</span>
                <input type="radio" name="advanceOption" class="h-4 w-4 text-blue-600">
            </label>
            <label class="flex items-center justify-between p-3 border rounded hover:bg-gray-50 cursor-pointer text-sm">
                <span>3 {{ __('property.times') }} ({{ __('property.every_24_hours') }}) / 6,00 AZN</span>
                <input type="radio" name="advanceOption" class="h-4 w-4 text-blue-600">
            </label>
            <label class="flex items-center justify-between p-3 border rounded hover:bg-gray-50 cursor-pointer text-sm">
                <span>5 {{ __('property.times') }} ({{ __('property.every_24_hours') }}) / 9,00 AZN</span>
                <input type="radio" name="advanceOption" class="h-4 w-4 text-blue-600">
            </label>
            <label class="flex items-center justify-between p-3 border rounded hover:bg-gray-50 cursor-pointer text-sm">
                <span>10 {{ __('property.times') }} ({{ __('property.every_24_hours') }}) / 15,00 AZN</span>
                <input type="radio" name="advanceOption" class="h-4 w-4 text-blue-600">
            </label>
        </div>
        <div class="border-t pt-3 flex flex-col space-y-2.5">
            <p class="text-xs text-gray-600">{{ __('property.payment_terms_notice') }}</p>
            <button class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-3 rounded">{{ __('property.pay') }}</button>
        </div>
    </div>
</div>
