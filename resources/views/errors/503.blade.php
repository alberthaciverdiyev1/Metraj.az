@include('errors.layout', [
    'code' => '503',
    'badge' => __('errors.503.badge'),
    'badgeClass' => 'bg-amber-50 text-amber-800 border border-amber-200/80',
    'codeColor' => 'text-amber-950',
    'icon' => 'fa-solid fa-screwdriver-wrench',
    'title' => __('errors.503.title'),
    'description' => __('errors.503.description'),
    'showRefresh' => true,
])
