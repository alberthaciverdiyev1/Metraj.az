@include('errors.layout', [
    'code' => '500',
    'badge' => __('errors.500.badge'),
    'badgeClass' => 'bg-rose-50 text-rose-700 border border-rose-200/80',
    'codeColor' => 'text-rose-900',
    'icon' => 'fa-solid fa-triangle-exclamation',
    'title' => __('errors.500.title'),
    'description' => __('errors.500.description'),
    'showRefresh' => true,
])
