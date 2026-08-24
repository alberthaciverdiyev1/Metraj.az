@php
    $record = (isset($getRecord) && is_callable($getRecord)) ? $getRecord() : ($record ?? null);
    if (! $record) {
        return;
    }

    $status = $record->status;
    $statusClass = match ($status?->value ?? '') {
        'pending_approval' => 'pgc-status-pending',
        'published' => 'pgc-status-published',
        'rejected' => 'pgc-status-rejected',
        'sold', 'rented' => 'pgc-status-sold',
        default => 'pgc-status-draft',
    };

    $locationName = $record->district?->name['az'] ?? ($record->city?->name['az'] ?? 'Bakı');
    $currencySymbol = ($record->currency === 'GBP' || empty($record->currency)) ? '£' : $record->currency;
    
    // Panel detection
    $panelId = \Filament\Facades\Filament::getCurrentPanel()?->getId() ?? 'admin';
    if ($panelId === 'agency') {
        $viewUrl = \App\Filament\Agency\Resources\PropertyResource::getUrl('view', ['record' => $record]);
        $editUrl = \App\Filament\Agency\Resources\PropertyResource::getUrl('edit', ['record' => $record]);
    } else {
        $viewUrl = \App\Filament\Admin\Resources\PropertyResource::getUrl('view', ['record' => $record]);
        $editUrl = \App\Filament\Admin\Resources\PropertyResource::getUrl('edit', ['record' => $record]);
    }
@endphp

<div class="pgc-card">
    <!-- Cover Image Container -->
    <div class="pgc-cover">
        <img
            src="{{ $record->first_image_url }}"
            alt="{{ $record->title }}"
            loading="lazy"
        />

        <!-- Top Badges Overlay -->
        <div class="pgc-badges-top">
            <span class="pgc-status-badge {{ $statusClass }}">
                {{ $status?->label() ?? 'Qaralama' }}
            </span>

            <div class="pgc-badges-right">
                @if($record->is_vip)
                    <span class="pgc-badge-vip">VIP</span>
                @endif
                @if($record->is_featured)
                    <span class="pgc-badge-top">TOP</span>
                @endif
                <span class="pgc-badge-code">
                    #{{ $record->code ?? $record->id }}
                </span>
            </div>
        </div>

        <!-- Bottom Price Badge Overlay -->
        <div class="pgc-price-pos">
            <div class="pgc-price-box">
                <span class="pgc-price-currency">{{ $currencySymbol }}</span>
                <span>{{ number_format($record->price, 0, '.', ' ') }}</span>
            </div>
        </div>
    </div>

    <!-- Body Content -->
    <div class="pgc-body">
        <div>
            <!-- Title -->
            <a href="{{ $viewUrl }}" class="pgc-title">
                {{ $record->title }}
            </a>

            <!-- Location -->
            <div class="pgc-location">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>{{ $locationName }} @if($record->landmark) • {{ $record->landmark }} @endif</span>
            </div>
        </div>

        <!-- Meta Stats Row -->
        <div class="pgc-meta">
            <!-- Left: Views & Inquiries -->
            <div class="pgc-meta-left">
                <div class="pgc-meta-item" title="Baxış sayı">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span>{{ number_format($record->views_count ?? 0) }}</span>
                </div>

                <div class="pgc-meta-item-inquiry" title="Müraciət sayı">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span>{{ $record->inquiries()->count() }}</span>
                </div>
            </div>

            <!-- Right: Seller Badge -->
            <span class="pgc-seller-badge">
                {{ $record->seller_type?->label() ?? 'Mülkiyyətçi' }}
            </span>
        </div>

        <!-- Footer Actions Bar -->
        <div class="pgc-actions">
            <span class="pgc-date">
                {{ $record->created_at?->format('d.m.Y') }}
            </span>

            <div class="pgc-actions-group">
                <a
                    href="{{ $viewUrl }}"
                    class="pgc-btn pgc-btn-view"
                >
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span>Bax</span>
                </a>

                <a
                    href="{{ $editUrl }}"
                    class="pgc-btn pgc-btn-edit"
                    title="Düzəliş et"
                >
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>Düzəliş et</span>
                </a>

                <button
                    type="button"
                    wire:click="mountTableAction('delete', '{{ $record->getKey() }}')"
                    class="pgc-btn pgc-btn-delete"
                    title="Sil"
                >
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span>Sil</span>
                </button>
            </div>
        </div>
    </div>
</div>
