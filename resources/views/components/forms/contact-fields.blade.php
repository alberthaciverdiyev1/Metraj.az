<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <!-- Name -->
    <div>
        <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('Adınız') }} <span class="text-rose-500">*</span></label>
        <input type="text" name="contact_name" value="{{ old('contact_name', auth()->user()?->name) }}" required
               placeholder="Əli Məmmədov"
               class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-4 py-3 focus:bg-white focus:outline-none focus:border-orange-500 transition">
    </div>

    <!-- Phone -->
    <div>
        <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('Telefon Nömrəsi') }} <span class="text-rose-500">*</span></label>
        <input type="tel" name="contact_phone" value="{{ old('contact_phone', auth()->user()?->phone) }}" required
               placeholder="+994 50 123 45 67"
               class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-4 py-3 focus:bg-white focus:outline-none focus:border-orange-500 transition">
    </div>

    <!-- WhatsApp -->
    <div>
        <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('WhatsApp Nömrəsi') }}</label>
        <input type="tel" name="contact_whatsapp" value="{{ old('contact_whatsapp', auth()->user()?->phone) }}"
               placeholder="+994 50 123 45 67"
               class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs sm:text-sm rounded-xl px-4 py-3 focus:bg-white focus:outline-none focus:border-orange-500 transition">
    </div>
</div>
