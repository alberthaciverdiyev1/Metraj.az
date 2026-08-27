<x-filament-panels::page>
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="max-w-3xl">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Yeni Sitemap Yaradın</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Bu alət elanları, kateqoriyaları və bloqları sitemap.xml faylına əlavə edir. Hər bir sitemap faylında maksimum 10,000 keçid ola bilər. Keçid sayı limiti aşarsa, sistem avtomatik olaraq növbəti sitemap fayllarını (sitemap_1.xml, sitemap_2.xml və s.) və sitemap indeks faylını yaradacaq.
                </p>
            </div>
            <x-filament::button wire:click="generateSitemap" size="lg" color="warning" icon="heroicon-o-cpu-chip" class="flex-shrink-0">
                Sitemap XML-ləri Yarat
            </x-filament::button>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-md font-bold text-gray-900 dark:text-white">Mövcud Sitemap Faylları</h3>
            </div>
            
            @if(empty($sitemaps))
                <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                    <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span>Hazırda heç bir sitemap.xml faylı yoxdur. Zəhmət olmasa yuxarıdakı düymə ilə yaradın.</span>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase border-b border-gray-100 dark:border-gray-700">
                                <th class="px-6 py-3">Fayl Adı</th>
                                <th class="px-6 py-3">Növü</th>
                                <th class="px-6 py-3">Keçid Sayı</th>
                                <th class="px-6 py-3">Həcmi</th>
                                <th class="px-6 py-3">Son Yenilənmə</th>
                                <th class="px-6 py-3">Link</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($sitemaps as $sitemap)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 text-sm text-gray-700 dark:text-gray-300">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $sitemap['name'] }}</td>
                                    <td class="px-6 py-4">
                                        @if($sitemap['is_index'])
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                                İndeks Faylı
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                                Keçidlər
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-semibold">{{ number_format($sitemap['count']) }}</td>
                                    <td class="px-6 py-4">{{ $sitemap['size'] }}</td>
                                    <td class="px-6 py-4">{{ $sitemap['modified_at'] }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ $sitemap['url'] }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold text-orange-600 hover:text-orange-500 dark:text-orange-400">
                                            Aç <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
