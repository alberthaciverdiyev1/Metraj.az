<section class="w-full bg-white ">
  <div class="container mx-auto px-3">
    <div class="max-w-7xl mx-auto px-4 md:flex md:items-center md:justify-between gap-8">
      <div class="md:w-1/2 space-y-6">
        <div>
          <h2 class="text-4xl font-bold text-[var(--text-color)]">Do you need a home loan?<br />
            Get pre-approved
          </h2>
          <p class="text-gray-500 mt-3 text-[14px]">Find a lender who can offer competitive mortgage rates and help you with pre-approval.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-900 font-bold">Total Amount</label>
            <input id="totalAmount" type="number" value="100000" class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-400" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 font-bold">Down Payment</label>
            <div class="flex items-center space-x-2">
              <input id="downPayment" type="number" value="20000" class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-400" />
              <input id="downPaymentPercent" type="number" value="20" class="mt-1 w-20 rounded-xl border border-gray-200 px-3 py-3 focus:outline-none focus:ring-2 focus:ring-orange-400" />
              <span class="text-sm font-medium text-gray-700">%</span>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 font-bold">Interest Rate</label>
            <input id="interestRate" type="number" value="5" class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-400" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 font-bold">Amortization Period (years)</label>
            <select id="amortizationPeriod" class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-400">
              <option value="0">Select amortization period</option>
              <option value="5">5 years</option>
              <option value="10">10 years</option>
              <option value="15">15 years</option>
              <option value="20">20 years</option>
              <option value="25">25 years</option>
              <option value="30">30 years</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 font-bold">Property Tax ($/year)</label>
            <input id="propertyTax" type="number" value="3000" class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-400" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 font-bold">Home Insurance ($/year)</label>
            <input id="homeInsurance" type="number" value="1200" class="mt-1 w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-400" />
          </div>
        </div>


        <p class="text-gray-700 mt-4">Your estimated monthly payment: <span id="paymentDisplay" class="text-orange-500 font-bold">$0</span></p>

        <div class="flex space-x-4 mt-4">
          <button onclick="calculatePayment()" class="bg-[var(--primary)] text-white px-6 py-3 rounded-2xl all-btn button-hover ">Calculate now</button>
          <button onclick="resetForm()" class="relative inline-block px-8 py-3 rounded-2xl border border-[color:var(--primary)] text-md text-[color:var(--primary)] overflow-hidden transition-all duration-300 hover-effect-button">
            <span class="absolute inset-0 w-0 h-full bg-[color:var(--primary)] transition-all duration-300 ease-in-out z-0 hover-effect-button-fill"></span>
            <span class="relative z-10">Start over</span>
          </button>

        </div>
      </div>
      <div
        class="bg-fixed  md:w-1/2 mt-10 h-[700px] md:mt-0 rounded-xl "
        style="background-image: url('webui/images/section-pre-approved.jpg');"></div>

    </div>

    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 shadow-xl max-w-sm w-full">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Monthly Payment</h2>
        <p class="text-lg text-gray-700">Your estimated monthly payment is <span id="modalPayment" class="font-bold text-orange-500"></span></p>
        <button onclick="closeModal()" class="mt-4 w-full bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600 transition-colors">Close</button>
      </div>
    </div>

  </div>


</section>

  