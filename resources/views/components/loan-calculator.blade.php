<div class="calculator max-w-3xl mx-auto p-4">
    <h3 class="font-semibold text-xl mb-4">{{ __('Loan Calculator') }}</h3>
    <div class="flex flex-wrap -mx-2">
        <div class="w-full md:w-1/2 px-2 mb-4 md:mb-0">
            <div class="form-group mb-3">
                <label>{{ __('Total Amount') }}</label>
                <input type="text" data-type="number" id="amount" value="1000" class="w-full border rounded px-3 py-2">
            </div>
            <div class="form-group mb-3">
                <label>{{ __('Interest Rate') }}</label>
                <input type="text" data-type="number" id="rate" value="0" class="w-full border rounded px-3 py-2">
            </div>
            <div class="form-group mb-3">
                <label>{{ __('Property Tax') }}</label>
                <input type="text" data-type="number" id="tax" value="$3000" class="w-full border rounded px-3 py-2">
            </div>
        </div>
        <div class="w-full md:w-1/2 px-2">
            <div class="form-group mb-3">
                <label>{{ __('Down Payment') }}</label>
                <input type="text" data-type="number" id="down" value="2000" class="w-full border rounded px-3 py-2">
            </div>
            <div class="form-group mb-3">
                <label>{{ __('Amortization Period (months)') }}</label>
                <select id="months" class="w-full border rounded px-3 py-2">
                    <option value="">{{ __('Select amortization period') }}</option>
                    <option value="12">12 {{ __('months') }}</option>
                    <option value="24">24 {{ __('months') }}</option>
                    <option value="36">36 {{ __('months') }}</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label>{{ __('Home Insurance') }}</label>
                <input type="text" data-type="number" id="insurance" value="$3000" class="w-full border rounded px-3 py-2">
            </div>
        </div>
    </div>
    <button class="calc-button all-btn button-hover mt-4 w-full md:w-auto" onclick="calculateLoan()">
        {{ __('Calculate now') }} <i class="fa fa-chevron-right"></i>
    </button>
</div>
