@php
    $record = (isset($getRecord) && is_callable($getRecord)) ? $getRecord() : ($record ?? null);
    if (! $record) {
        return;
    }

    $status = $record->status;
    $statusBg = match ($status?->value ?? '') {
        'draft' => 'background: #475569; color: #ffffff; box-shadow: 0 2px 6px rgba(71, 85, 105, 0.45);',
        'pending_approval' => 'background: #d97706; color: #ffffff; box-shadow: 0 2px 6px rgba(217, 119, 6, 0.45);',
        'published' => 'background: #16a34a; color: #ffffff; box-shadow: 0 2px 6px rgba(22, 163, 74, 0.45);',
        'rejected' => 'background: #dc2626; color: #ffffff; box-shadow: 0 2px 6px rgba(220, 38, 38, 0.45);',
        'sold', 'rented' => 'background: #2563eb; color: #ffffff; box-shadow: 0 2px 6px rgba(37, 99, 235, 0.45);',
        default => 'background: #475569; color: #ffffff; box-shadow: 0 2px 6px rgba(71, 85, 105, 0.45);',
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

<div style="width: 100%; height: 100%; display: flex; flex-direction: column; background: transparent; overflow: hidden;">
    <!-- Cover Image Container -->
    <div style="position: relative; width: 100%; height: 185px; overflow: hidden; background-color: #f1f5f9;">
        <img
            src="{{ $record->first_image_url }}"
            alt="{{ $record->title }}"
            style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;"
            loading="lazy"
        />

        <!-- Top Badges Overlay -->
        <div style="position: absolute; top: 12px; left: 12px; right: 12px; display: flex; align-items: center; justify-content: space-between; pointer-events: none; z-index: 10;">
            <span style="display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 9999px; font-size: 11.5px; font-weight: 600; {{ $statusBg }}">
                {{ $status?->label() ?? 'Qaralama' }}
            </span>

            <div style="display: flex; align-items: center; gap: 4px;">
                @if($record->is_vip)
                    <span style="padding: 2px 7px; border-radius: 6px; font-size: 11px; font-weight: 700; background: #f59e0b; color: #ffffff;">VIP</span>
                @endif
                @if($record->is_featured)
                    <span style="padding: 2px 7px; border-radius: 6px; font-size: 11px; font-weight: 700; background: #4f46e5; color: #ffffff;">TOP</span>
                @endif
                <span style="padding: 3px 9px; border-radius: 9999px; font-size: 11.5px; font-weight: 700; font-family: monospace; background: rgba(15, 23, 42, 0.85); color: #ffffff; backdrop-filter: blur(4px);">
                    #{{ $record->code ?? $record->id }}
                </span>
            </div>
        </div>

        <!-- Bottom Price Badge Overlay -->
        <div style="position: absolute; bottom: 12px; left: 12px; pointer-events: none; z-index: 10;">
            <div style="background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(8px); color: #ffffff; font-weight: 800; font-size: 15px; padding: 5px 12px; border-radius: 10px; display: flex; align-items: center; gap: 5px; border: 1px solid rgba(255,255,255,0.15);">
                <span style="color: #4ade80; font-weight: 600;">{{ $currencySymbol }}</span>
                <span>{{ number_format($record->price, 0, '.', ' ') }}</span>
            </div>
        </div>
    </div>

    <!-- Body Content -->
    <div style="padding: 14px 16px; flex: 1; display: flex; flex-direction: column; justify-content: space-between; gap: 10px;">
        <div>
            <!-- Title -->
            <a href="{{ $viewUrl }}" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-weight: 700; font-size: 14.5px; line-height: 1.35; color: #0f172a; text-decoration: none;">
                {{ $record->title }}
            </a>

            <!-- Location -->
            <div style="display: flex; align-items: center; gap: 4px; font-size: 12px; color: #64748b; margin-top: 6px;">
                <svg style="width: 14px; height: 14px; color: #94a3b8; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $locationName }} @if($record->landmark) • {{ $record->landmark }} @endif</span>
            </div>
        </div>

        <!-- Meta Stats Row -->
        <div style="padding-top: 8px; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; font-size: 12px;">
            <!-- Left: Views & Inquiries -->
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 4px; color: #64748b; font-weight: 600;" title="Baxış sayı">
                    <svg style="width: 15px; height: 15px; color: #94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span>{{ number_format($record->views_count ?? 0) }}</span>
                </div>

                <div style="display: flex; align-items: center; gap: 4px; color: #0284c7; font-weight: 600;" title="Müraciət sayı">
                    <svg style="width: 15px; height: 15px; color: #0284c7;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span>{{ $record->inquiries()->count() }}</span>
                </div>
            </div>

            <!-- Right: Seller Badge -->
            <span style="padding: 2px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; background: #f1f5f9; color: #475569;">
                {{ $record->seller_type?->label() ?? 'Mülkiyyətçi' }}
            </span>
        </div>

        <!-- Footer Actions Bar -->
        <div style="padding-top: 10px; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
            <span style="font-size: 11.5px; color: #94a3b8; font-family: monospace;">
                {{ $record->created_at?->format('d.m.Y') }}
            </span>

            <div style="display: flex; align-items: center; gap: 6px;">
                <a
                    href="{{ $viewUrl }}"
                    style="display: flex; align-items: center; gap: 4px; padding: 5px 10px; border-radius: 8px; font-size: 12px; font-weight: 600; color: #475569; background: #f8fafc; border: 1px solid #e2e8f0; text-decoration: none; transition: all 0.2s ease;"
                    onmouseover="this.style.background='#e2e8f0';"
                    onmouseout="this.style.background='#f8fafc';"
                >
                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span>Bax</span>
                </a>

                <a
                    href="{{ $editUrl }}"
                    style="display: flex; align-items: center; gap: 4px; padding: 5px 10px; border-radius: 8px; font-size: 12px; font-weight: 600; color: #ea580c; background: #fff7ed; border: 1px solid #ffedd5; text-decoration: none; transition: all 0.2s ease;"
                    onmouseover="this.style.background='#ffedd5';"
                    onmouseout="this.style.background='#fff7ed';"
                    title="Düzəliş et"
                >
                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>Düzəliş et</span>
                </a>

                <button
                    type="button"
                    wire:click="mountTableAction('delete', '{{ $record->getKey() }}')"
                    style="display: flex; align-items: center; gap: 4px; padding: 5px 10px; border-radius: 8px; font-size: 12px; font-weight: 600; color: #dc2626; background: #fef2f2; border: 1px solid #fee2e2; cursor: pointer; transition: all 0.2s ease;"
                    onmouseover="this.style.background='#fee2e2';"
                    onmouseout="this.style.background='#fef2f2';"
                    title="Sil"
                >
                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span>Sil</span>
                </button>
            </div>
        </div>
    </div>
</div>
