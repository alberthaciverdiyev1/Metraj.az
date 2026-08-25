@props(['whatsapp' => null, 'phone' => null, 'message' => '', 'whatsappLabel' => 'WhatsApp'])

<div class="space-y-3 pt-2">
    @if($whatsapp)
        @php
            $wa = preg_replace('/[^0-9]/', '', $whatsapp);
        @endphp
        <a href="https://wa.me/{{ $wa }}?text={{ urlencode($message) }}"
           target="_blank" rel="noopener noreferrer"
           class="w-full inline-flex items-center justify-center gap-2.5 px-6 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold text-sm rounded-2xl shadow-xs transition hover:shadow-md">
            <i class="bi bi-whatsapp text-lg"></i>
            <span>{{ $whatsappLabel }}</span>
        </a>
    @endif

    @if($phone)
        <a href="tel:{{ $phone }}"
           class="w-full inline-flex items-center justify-center gap-2.5 px-6 py-3.5 bg-gray-900 hover:bg-orange-500 text-white font-semibold text-sm rounded-2xl shadow-xs transition hover:shadow-md">
            <i class="bi bi-telephone-fill text-base"></i>
            <span>{{ $phone }}</span>
        </a>
    @endif
</div>
