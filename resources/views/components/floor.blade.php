<div class="bg-white max-w-5xl  mx-auto rounded-xl py-3 px-4 mt-3 shadow-md">
    <h1 class="text-3xl font-bold mb-6">Floors</h1>

    <div class="mb-6 p-5">
        <p class="font-semibold mb-2">Enable Floor Plan:</p>
        <div class="custom-radio-wrapper">
  <label class="custom-radio-label">
    <input type="radio" name="floorPlan" value="enable" class="custom-radio-input" checked />
    <div class="custom-radio-circle">
      <i class="bi bi-check custom-radio-check-icon"></i>
    </div>
    <span class="custom-radio-text">Enable</span>
  </label>

  <label class="custom-radio-label">
    <input type="radio" name="floorPlan" value="disable" class="custom-radio-input" />
    <div class="custom-radio-circle">
      <i class="bi bi-check custom-radio-check-icon"></i>
    </div>
    <span class="custom-radio-text">Disable</span>
  </label>
</div>

    </div>

    <div id="floors-container">
  <div class="bg-gray-100 border border-gray-200 p-4 rounded-2xl relative">
    <h2 class="text-lg font-semibold mb-4">Floor 1:</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block font-medium mb-1">Floor Name:</label>
        <input type="text" class="w-full border border-gray-300 rounded-xl p-2 bg-white" />
      </div>
      <div>
        <label class="block font-medium mb-1">Price Postfix:</label>
        <input type="text" class="w-full border border-gray-300 rounded-xl p-2 bg-white" />
      </div>
      <div>
        <label class="block font-medium mb-1">Floor Price (Only Digits):</label>
        <input type="number" class="w-full border border-gray-300 rounded-xl p-2 bg-white" />
      </div>
      <div>
        <label class="block font-medium mb-1">Size Postfix:</label>
        <input type="text" class="w-full border border-gray-300 rounded-xl p-2 bg-white" />
      </div>
      <div>
        <label class="block font-medium mb-1">Floor Size (Only Digits):</label>
        <input type="number" class="w-full border border-gray-300 rounded-xl p-2 bg-white" />
      </div>
      <div>
        <label class="block font-medium mb-1">Bedrooms:</label>
        <input type="number" class="w-full border border-gray-300 rounded-xl p-2 bg-white" />
      </div>
      <div>
        <label class="block font-medium mb-1">Bathrooms:</label>
        <input type="number" class="w-full border border-gray-300 rounded-xl p-2 bg-white" />
      </div>
      <div>
        <label class="block font-medium mb-1">Description:</label>
        <textarea class="w-full border border-gray-300 rounded-xl p-2 bg-white"></textarea>
      </div>
      <div>
        <label class="block font-medium mb-1">Floor Image:</label>
        <input type="file" class="w-full border border-gray-300 rounded-xl p-2 bg-white" />
      </div>
    </div>
  </div>
</div>


    <div class="text-center mt-6">
        <button onclick="addFloor()" class="bg-orange-400 hover:bg-orange-500 text-white text-xl px-4 py-2 rounded-full">+</button>
    </div>
</div>