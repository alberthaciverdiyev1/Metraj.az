@include('errors.layout', [
    'code' => $exception?->getStatusCode() ?? '400',
    'badge' => __('errors.generic_badge'),
    'badgeClass' => 'bg-gray-100 text-gray-800 border border-gray-200',
    'codeColor' => 'text-gray-900',
    'icon' => 'fa-solid fa-circle-exclamation',
    'title' => $exception?->getMessage() ?: __('errors.404.title'),
    'description' => __('errors.generic_description'),
    'showRefresh' => true,
])
