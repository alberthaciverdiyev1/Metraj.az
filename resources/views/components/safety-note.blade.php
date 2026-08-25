@props([
    'icon' => 'bi-shield-check',
    'title' => null,
    'text' => null,
])

<div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 text-[11px] text-gray-500 leading-relaxed space-y-1">
    <div class="font-semibold text-gray-700 flex items-center gap-1.5">
        <i class="bi {{ $icon }} text-emerald-600"></i>
        <span>{{ $title ?? __('property.safety_note_title') }}</span>
    </div>
    <p>{{ $text ?? __('property.safety_note_desc') }}</p>
</div>
