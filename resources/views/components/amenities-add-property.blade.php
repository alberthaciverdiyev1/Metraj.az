<div
    x-data="featuresComponent()"
    x-init="fetchFeatures()"
    class="p-8 max-w-5xl mx-auto rounded-lg shadow-md mt-3 font-sans bg-white text-gray-800"
>
    <h2 class="text-xl font-semibold mb-4">
        Xüsusiyyətlər <span style="color: red">*</span>
    </h2>

    <template x-if="loading">
        <p>Yüklənir...</p>
    </template>

    <template x-if="!loading && features.length === 0">
        <p>Xüsusiyyət tapılmadı.</p>
    </template>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4" x-show="!loading">
        <template x-for="feature in features" :key="feature.id">
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="amenities[]" :value="feature.id" class="form-checkbox text-blue-600">
                <span x-text="feature.name"></span>
            </label>
        </template>
    </div>
</div>

