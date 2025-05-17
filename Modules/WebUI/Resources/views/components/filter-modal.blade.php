
<div x-data="{ open: false }">
    <button @click="open = true"
        class="flex items-center gap-1 border border-[var(--border-color)] rounded-lg px-3 py-2 hover:text-[color:var(--primary)] transition">
        Filter
        <i class="fas fa-sliders-h text-[color:var(--primary)]"></i>
    </button>

<div x-show="open" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-[rgba(0,0,0,0.1)] backdrop-blur-sm">

        <div class="bg-white rounded-xl p-6 w-full max-w-xl relative">
            <button @click="open = false" class="absolute top-4 right-4 text-2xl text-[var(--grey-text)]">&times;</button>
            <h2 class="text-xl font-bold text-[var(--text-color)] mb-4">Advanced Search</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[var(--grey-text)] mb-1">Price from <span class="text-[var(--primary)]">$100</span> to <span class="text-[var(--primary)]">$500,000</span></label>
                    <input type="range" min="100" max="500000" class="w-full accent-[var(--primary)]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--grey-text)] mb-1">Size range from <span class="text-[var(--primary)]">0</span> to <span class="text-[var(--primary)]">1,000</span></label>
                    <input type="range" min="0" max="1000" class="w-full accent-[var(--primary)]">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <select class="border border-[var(--border-color)] rounded-lg px-3 py-2 w-full">
                    <option>Province / States</option>
                </select>
                <select class="border border-[var(--border-color)] rounded-lg px-3 py-2 w-full">
                    <option>1</option>
                </select>
                <select class="border border-[var(--border-color)] rounded-lg px-3 py-2 w-full">
                    <option>Bath: Any</option>
                </select>
                <select class="border border-[var(--border-color)] rounded-lg px-3 py-2 w-full">
                    <option>Beds: Any</option>
                </select>
            </div>

            <div class="mt-6">
                <p class="font-medium text-[var(--text-color)] mb-2">Amenities:</p>
                <div class="grid grid-cols-3 gap-2 text-sm text-[var(--grey-text)]">
                    <label><input type="checkbox" class="mr-1">Bed linens</label>
                    <label><input type="checkbox" class="mr-1">Carbon alarm</label>
                    <label><input type="checkbox" class="mr-1">Check-in lockbox</label>
                    <label><input type="checkbox" class="mr-1">Coffee maker</label>
                    <label><input type="checkbox" class="mr-1">Fireplace</label>
                    <label><input type="checkbox" class="mr-1">Extra pillows</label>
                    <label><input type="checkbox" class="mr-1">First aid kit</label>
                    <label><input type="checkbox" class="mr-1">Hangers</label>
                    <label><input type="checkbox" class="mr-1">Iron</label>
                    <label><input type="checkbox" class="mr-1">Microwave</label>
                    <label><input type="checkbox" class="mr-1">Refrigerator</label>
                    <label><input type="checkbox" class="mr-1">Security cameras</label>
                    <label><input type="checkbox" class="mr-1">Smoke alarm</label>
                    <label><input type="checkbox" class="mr-1">Fireplace</label>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
