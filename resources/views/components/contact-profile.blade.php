@props(['name', 'role'])

<div class="flex items-center gap-3">
    <div class="w-12 h-12 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center font-semibold text-lg">
        {{ mb_strtoupper(mb_substr($name, 0, 1)) }}
    </div>
    <div>
        <div class="font-semibold text-sm sm:text-base text-gray-900">{{ $name }}</div>
        <div class="text-xs text-gray-400">{{ $role }}</div>
    </div>
</div>
