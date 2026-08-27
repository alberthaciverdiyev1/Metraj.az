@include('errors.layout', [
    'code' => '429',
    'badge' => __('errors.429.badge'),
    'badgeClass' => 'bg-purple-50 text-purple-700 border border-purple-200/80',
    'codeColor' => 'text-purple-950',
    'icon' => 'fa-solid fa-gauge-high',
    'title' => __('errors.429.title'),
    'description' => __('errors.429.description'),
    'showRefresh' => true,
])
