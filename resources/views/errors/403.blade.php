@include('errors.layout', [
    'code' => '403',
    'badge' => __('errors.403.badge'),
    'badgeClass' => 'bg-red-50 text-red-700 border border-red-200/80',
    'codeColor' => 'text-slate-900',
    'icon' => 'fa-solid fa-lock',
    'title' => __('errors.403.title'),
    'description' => __('errors.403.description'),
    'showRefresh' => false,
])
