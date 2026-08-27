@include('errors.layout', [
    'code' => '404',
    'badge' => __('errors.404.badge'),
    'badgeClass' => 'bg-amber-50 text-amber-700 border border-amber-200/80',
    'codeColor' => 'text-slate-800',
    'icon' => 'fa-solid fa-map-location-dot',
    'title' => __('errors.404.title'),
    'description' => __('errors.404.description'),
    'showRefresh' => false,
])
