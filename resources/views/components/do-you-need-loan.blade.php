<div>
    <h2 class="text-3xl sm:text-4xl font-bold text-[var(--text-color)]">{{ __('about.loan_calc_title') }}</h2>
    <p class="text-gray-500 mt-2 text-sm">{{ __('about.loan_calc_subtitle') }}</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
    <div>
        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('about.total_amount') }}</label>
        <input id="totalAmount" type="text" data-type="number" value="100000"
            class="w-full rounded-2xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400" />
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('about.down_payment') }}</label>
        <div class="flex items-center space-x-2">
            <input id="downPayment" type="text" data-type="number" value="20000"
                class="w-full rounded-2xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400" />
            <input id="downPaymentPercent" type="text" data-type="number" value="20"
                class="w-20 rounded-2xl border border-gray-200 bg-gray-50/50 px-3 py-3 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400 text-center font-bold" />
            <span class="text-sm font-bold text-gray-700">%</span>
        </div>
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('about.interest_rate') }} (%)</label>
        <input id="interestRate" type="text" data-type="number" value="5"
            class="w-full rounded-2xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400" />
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('about.amortization_period') }}</label>
        <select id="amortizationPeriod"
            class="w-full rounded-2xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400 cursor-pointer">
            <option value="0">{{ __('about.select_period') }}</option>
            <option value="5">5 {{ __('about.years') }}</option>
            <option value="10">10 {{ __('about.years') }}</option>
            <option value="15">15 {{ __('about.years') }}</option>
            <option value="20">20 {{ __('about.years') }}</option>
            <option value="25">25 {{ __('about.years') }}</option>
            <option value="30">30 {{ __('about.years') }}</option>
        </select>
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('about.property_tax') }}</label>
        <input id="propertyTax" type="text" data-type="number" value="3000"
            class="w-full rounded-2xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400" />
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('about.home_insurance') }}</label>
        <input id="homeInsurance" type="text" data-type="number" value="1200"
            class="w-full rounded-2xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400" />
    </div>
</div>

<p class="text-gray-700 text-sm font-medium mt-6">{{ __('about.estimated_monthly_payment') }}: <span id="paymentDisplay"
        class="text-orange-500 font-bold text-lg sm:text-xl">$0</span></p>

<div class="flex items-center gap-3 mt-4 flex-wrap">
    <button onclick="calculatePayment()"
        class="bg-[var(--primary)] hover:bg-orange-600 text-white font-semibold text-sm px-7 py-3.5 rounded-2xl transition shadow-md">{{ __('about.calculate_now') }}</button>
    <button onclick="resetForm()"
        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm px-6 py-3.5 rounded-2xl transition">
        {{ __('about.start_over') }}
    </button>
</div>
