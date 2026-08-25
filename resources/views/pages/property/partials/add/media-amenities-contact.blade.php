<!-- BÖLMƏ 3: Şəkillər, Təchizatlar və Əlaqə -->
<div class="bg-white rounded-2xl border border-gray-200/80 p-6 sm:p-8 shadow-sm space-y-7">
    <div class="border-b border-gray-100 pb-4">
        <h2 class="text-lg font-semibold text-gray-900">{{ __('add_property.section_3_title') }}</h2>
        <p class="text-xs text-gray-500 mt-0.5">{{ __('add_property.section_3_desc') }}</p>
    </div>

    <!-- Şəkillər -->
    <div>
        <label class="block text-xs font-semibold text-gray-700 mb-2">{{ __('add_property.photos') }}</label>
        <div id="dropzone_box" class="border-2 border-dashed border-gray-300 hover:border-orange-500 rounded-2xl p-6 text-center cursor-pointer bg-gray-50/50 hover:bg-orange-50/20 transition-all">
            <input type="file" name="photos[]" id="photos_input" multiple accept="image/*" class="hidden">
            <div class="flex flex-col items-center justify-center gap-2 pointer-events-none">
                <i class="bi bi-cloud-arrow-up text-orange-500 text-3xl"></i>
                <p class="text-sm font-semibold text-gray-800">{{ __('add_property.photos_dropzone') }}</p>
                <p class="text-[11px] text-gray-400">{{ __('add_property.photos_hint') }}</p>
            </div>
        </div>
        <!-- Preview Gallery Grid -->
        <div id="photos_preview_grid" class="grid grid-cols-3 sm:grid-cols-6 gap-2.5 pt-3"></div>
    </div>

    <!-- Video Çarx (İstəyə görə - 1 ədəd) -->
    <div class="pt-4 border-t border-gray-100">
        <div class="flex items-center justify-between mb-2">
            <label class="block text-xs font-semibold text-gray-700">{{ __('add_property.video') }} <span class="text-gray-400 font-normal">({{ __('add_property.video_optional') }})</span></label>
            <span class="text-[11px] text-gray-400">{{ __('add_property.video_hint') }}</span>
        </div>
        
        <div id="video_dropzone_box" class="border-2 border-dashed border-gray-300 hover:border-orange-500 rounded-2xl p-5 text-center cursor-pointer bg-gray-50/50 hover:bg-orange-50/20 transition-all">
            <input type="file" name="video" id="video_input" accept="video/mp4,video/webm,video/quicktime,video/x-msvideo,video/ogg" class="hidden">
            <div id="video_empty_state" class="flex flex-col items-center justify-center gap-1.5 pointer-events-none">
                <i class="bi bi-camera-video text-orange-500 text-2xl"></i>
                <p class="text-xs font-semibold text-gray-800">{{ __('add_property.video_dropzone') }}</p>
                <p class="text-[10px] text-gray-400">{{ __('add_property.video_desc') }}</p>
            </div>
        </div>

        <!-- Video Preview Container -->
        <div id="video_preview_container" class="hidden pt-3">
            <div class="relative rounded-2xl overflow-hidden border border-gray-200 bg-gray-900 p-3 flex items-center justify-between gap-4 shadow-sm">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-11 h-11 rounded-xl bg-orange-500/20 text-orange-400 flex items-center justify-center text-xl shrink-0">
                        <i class="bi bi-file-earmark-play-fill"></i>
                    </div>
                    <div class="min-w-0">
                        <p id="video_preview_name" class="text-xs font-bold text-white truncate"></p>
                        <p id="video_preview_size" class="text-[10px] text-gray-400 font-medium"></p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" id="btn_remove_video" class="px-3 py-1.5 rounded-xl bg-rose-500/20 hover:bg-rose-600 text-rose-300 hover:text-white text-xs font-semibold transition flex items-center gap-1 cursor-pointer">
                        <i class="bi bi-trash3 text-xs"></i>
                        <span>{{ __('add_property.delete') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Təchizatlar (Amenities) -->
    <div id="section_amenities" class="pt-4 border-t border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <label class="block text-xs font-semibold text-gray-700">{{ __('add_property.amenities') }}</label>
        </div>
        <div id="amenities_grid" class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
            @foreach($amenities as $amenity)
                @php
                    $aName = is_array($amenity->name) ? ($amenity->name[app()->getLocale()] ?? $amenity->name['az'] ?? reset($amenity->name)) : ($amenity->localized_name ?? $amenity->name);
                @endphp
                <label class="flex items-center gap-2 p-2.5 bg-gray-50/70 border border-gray-100 rounded-xl cursor-pointer hover:border-orange-200 transition">
                    <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" {{ in_array($amenity->id, old('amenities', [])) ? 'checked' : '' }}
                        class="w-4 h-4 text-orange-500 rounded border-gray-300 focus:ring-orange-500">
                    <span class="text-xs font-medium text-gray-800">{{ $aName }}</span>
                </label>
            @endforeach
        </div>

        @if($amenities instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $amenities->hasMorePages())
        <div id="load_more_amenities_wrapper" class="mt-4 flex justify-center">
            <button type="button" id="load_more_amenities_btn" data-next-page="2"
                    class="px-5 py-2.5 bg-white border border-gray-200 hover:border-orange-500 hover:text-orange-600 text-gray-700 text-xs font-semibold rounded-xl shadow-sm transition flex items-center gap-2">
                <i class="bi bi-arrow-clockwise"></i>
                <span>{{ __('add_property.load_more') }}</span>
            </button>
        </div>
        @endif
    </div>

    <!-- Əlaqə Məlumatları -->
    <div class="pt-4 border-t border-gray-100">
        <label class="block text-xs font-semibold text-gray-700 mb-3">{{ __('add_property.contact_info') }}</label>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-[11px] font-semibold text-gray-600 mb-1">{{ __('add_property.seller_type') }} <span class="text-rose-500">*</span></label>
                <div class="grid grid-cols-2 gap-1.5">
                    <label class="relative flex items-center justify-center p-2 text-center rounded-xl border cursor-pointer select-none transition-all
                        has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/70 has-[:checked]:text-orange-950 has-[:checked]:font-semibold
                        border-gray-200 bg-gray-50 text-gray-700 text-xs">
                        <input type="radio" name="advertiser" value="owner" {{ old('advertiser', 'owner') == 'owner' ? 'checked' : '' }} required class="sr-only">
                        <span>{{ __('add_property.owner') }}</span>
                    </label>
                    <label class="relative flex items-center justify-center p-2 text-center rounded-xl border cursor-pointer select-none transition-all
                        has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/70 has-[:checked]:text-orange-950 has-[:checked]:font-semibold
                        border-gray-200 bg-gray-50 text-gray-700 text-xs">
                        <input type="radio" name="advertiser" value="agent" {{ old('advertiser') == 'agent' ? 'checked' : '' }} required class="sr-only">
                        <span>{{ __('add_property.agent') }}</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-gray-600 mb-1">{{ __('add_property.name_or_company') }} <span class="text-rose-500">*</span></label>
                <input type="text" name="advertiser_name" id="advertiser_name" value="{{ old('advertiser_name', auth()->user()?->name) }}" required placeholder="Məs: Əli Əliyev"
                    class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-3 py-2 text-xs sm:text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-gray-600 mb-1">{{ __('add_property.phone') }} <span class="text-rose-500">*</span></label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', auth()->user()?->agent?->phone ?? auth()->user()?->phone) }}" required placeholder="Məs: +994 50 123 45 67"
                    class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-3 py-2 text-xs sm:text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-gray-600 mb-1">{{ __('add_property.whatsapp') }}</label>
                <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', auth()->user()?->agent?->whatsapp) }}" placeholder="Məs: +994 50 123 45 67"
                    class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-3 py-2 text-xs sm:text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mt-4">
            <div class="sm:col-span-2">
                <label class="block text-[11px] font-semibold text-gray-600 mb-1">{{ __('add_property.email') }} <span class="text-rose-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email', auth()->user()?->email) }}" required placeholder="elan@metraj.az"
                    class="w-full bg-gray-50/70 border border-gray-200 rounded-xl px-3 py-2 text-xs sm:text-sm font-medium text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                <p class="text-[11px] text-gray-500 mt-1.5 flex items-center gap-1.5">
                    <i class="bi bi-info-circle text-orange-500 shrink-0 text-xs"></i>
                    <span>{{ __('add_property.email_hint') }}</span>
                </p>
            </div>
        </div>
    </div>
</div>
