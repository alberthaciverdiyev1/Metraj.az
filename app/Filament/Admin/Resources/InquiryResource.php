<?php

namespace App\Filament\Admin\Resources;

use App\Modules\Inquiry\Models\Inquiry;
use App\Filament\Admin\Resources\InquiryResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function getNavigationGroup(): ?string {
        return __('admin.properties_and_inquiries'); }

    public static function getNavigationLabel(): string {
        return __('admin.customer_inquiries'); }

    public static function getModelLabel(): string {
        return __('admin.inquiry'); }

    public static function getPluralModelLabel(): string {
        return __('admin.customer_inquiries'); }

    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::whereIn('status', ['new', 'yeni'])->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('admin.inquiry_information'))
                    ->schema([
                        Forms\Components\Select::make('property_id')
                            ->label(__('admin.related_property'))
                            ->relationship('property', 'title')
                            ->getOptionLabelFromRecordUsing(fn ($record) => ($record->code ? "[{$record->code}] " : '') . (is_array($record->title) ? ($record->title['az'] ?? ($record->title['tr'] ?? reset($record->title))) : $record->title))
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->placeholder(__('admin.general_inquiry_no_property')),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'new' => 'Yeni',
                                'contacted' => __('admin.contacted'),
                                'in_progress' => __('admin.viewing_scheduled'),
                                'closed' => __('admin.closed_successful'),
                                'cancelled' => __('admin.cancelled'),
                            ])
                            ->default('new')
                            ->required(),

                        Forms\Components\TextInput::make('name')
                            ->label(__('admin.customer_name'))
                            ->required(),

                        Forms\Components\TextInput::make('phone')
                            ->label(__('admin.phone_number'))
                            ->tel(),

                        Forms\Components\TextInput::make('email')
                            ->label(__('admin.email'))
                            ->email(),

                        Forms\Components\Textarea::make('message')
                            ->label(__('admin.customer_message'))
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('admin.customer'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('property.code')
                    ->label('Elan Kodu')
                    ->badge()
                    ->color('primary')
                    ->placeholder('-')
                    ->searchable(),

                Tables\Columns\TextColumn::make('property.title')
                    ->label(__('admin.property'))
                    ->formatStateUsing(fn ($state, $record) => $record->property ? (is_array($record->property->title) ? ($record->property->title['az'] ?? ($record->property->title['tr'] ?? reset($record->property->title))) : $record->property->title) : 'Ümumi Müraciət')
                    ->limit(25)
                    ->placeholder(__('admin.general_inquiry'))
                    ->searchable(),

                Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'new' => 'Yeni',
                        'contacted' => __('admin.contacted'),
                        'in_progress' => __('admin.viewing_scheduled'),
                        'closed' => __('admin.closed_successful'),
                        'cancelled' => __('admin.cancelled'),
                    ])
                    ->selectablePlaceholder(false)
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarix')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'new' => 'Yeni',
                        'contacted' => __('admin.contacted'),
                        'in_progress' => __('admin.viewing_scheduled'),
                        'closed' => __('admin.closed'),
                        'cancelled' => __('admin.cancelled'),
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInquiries::route('/'),
            'create' => Pages\CreateInquiry::route('/create'),
            'edit' => Pages\EditInquiry::route('/{record}/edit'),
        ];
    }
}
