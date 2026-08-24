<div>
    <h2 class="text-4xl font-semibold text-[var(--text-color)]">{{ __('Do you need a home loan?') }}<br />{{ __('Get pre-approved') }}</h2>
    <p class="text-gray-500 mt-3 text-[14px]">{{ __('Find a lender who can offer competitive mortgage rates and help you with pre-approval.') }}</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-900 font-semibold">{{ __('Total Amount') }}</label>
        <input id="totalAmount" type="text" data-type="number" value="100000"
            class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-400" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 font-semibold">{{ __('Down Payment') }}</label>
        <div class="flex items-center space-x-2">
            <input id="downPayment" type="text" data-type="number" value="20000"
                class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-400" />
            <input id="downPaymentPercent" type="text" data-type="number" value="20"
                class="mt-1 w-20 rounded-xl border border-gray-200 px-3 py-3 focus:outline-none focus:ring-2 focus:ring-orange-400" />
            <span class="text-sm font-medium text-gray-700">%</span>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 font-semibold">{{ __('Interest Rate') }}</label>
        <input id="interestRate" type="text" data-type="number" value="5"
            class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-400" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 font-semibold">{{ __('Amortization Period (years)') }}</label>
        <select id="amortizationPeriod"
            class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-400">
            <option value="0">{{ __('Select amortization period') }}</option>
            <option value="5">5 {{ __('years') }}</option>
            <option value="10">10 {{ __('years') }}</option>
            <option value="15">15 {{ __('years') }}</option>
            <option value="20">20 {{ __('years') }}</option>
            <option value="25">25 {{ __('years') }}</option>
            <option value="30">30 {{ __('years') }}</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 font-semibold">{{ __('Property Tax') }} ($/{{ __('year') }})</label>
        <input id="propertyTax" type="text" data-type="number" value="3000"
            class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-400" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 font-semibold">{{ __('Home Insurance') }} ($/{{ __('year') }})</label>
        <input id="homeInsurance" type="text" data-type="number" value="1200"
            class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-400" />
    </div>
</div>

<p class="text-gray-700 mt-4">{{ __('Your estimated monthly payment') }}: <span id="paymentDisplay"
        class="text-orange-500 font-semibold">$0</span></p>

<div class="flex space-x-4 mt-4">
    <button onclick="calculatePayment()"
        class="bg-[var(--primary)] text-white px-6 py-3 rounded-2xl all-btn button-hover">{{ __('Calculate now') }}</button>
    <button onclick="resetForm()"
        class="relative inline-block px-8 py-3 rounded-2xl border border-[color:var(--primary)] text-md text-[color:var(--primary)] overflow-hidden transition-all duration-300 hover-effect-button">
        <span class="absolute inset-0 w-0 h-full bg-[color:var(--primary)] transition-all duration-300 ease-in-out z-0 hover-effect-button-fill"></span>
        <span class="relative z-10">{{ __('Start over') }}</span>
    </button>
</div>
