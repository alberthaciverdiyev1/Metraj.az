<?php

namespace App\Filament\Admin\Resources;

use App\Modules\Blog\Models\Blog;
use App\Filament\Admin\Resources\BlogResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    public static function getNavigationGroup(): ?string {
        return __('admin.content_and_search'); }

    public static function getNavigationLabel(): string {
        return __('admin.blog_and_news'); }

    protected static ?string $modelLabel = 'Bloq';

    protected static ?string $pluralModelLabel = 'Bloqlar';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('admin.blog_information'))
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('admin.title'))
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', \Illuminate\Support\Str::slug($state)))
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('slug')
                            ->label('URL (Slug)')
                            ->nullable()
                            ->unique(table: 'blogs', column: 'slug', ignoreRecord: true)
                            ->helperText(__('admin.slug_auto_hint')),

                        Forms\Components\Select::make('category')
                            ->label('Kategoriya')
                            ->options([
                                'Məsləhət' => __('admin.advice'),
                                'Bazar' => 'Bazar',
                                'Xəbər' => __('admin.news'),
                                'İnvestisiya' => __('admin.investment'),
                                'Hüquqi' => __('admin.legal'),
                                'Həyat tərzi' => __('admin.lifestyle'),
                                'Texniki' => 'Texniki',
                            ])
                            ->searchable()
                            ->placeholder(__('admin.select_category')),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->label(__('admin.publish_date'))
                            ->default(now())
                            ->required(),

                        Forms\Components\FileUpload::make('cover_image')
                            ->label(__('admin.cover_image'))
                            ->image()
                            ->imageEditor()
                            ->directory('blogs')
                            ->visibility('public')
                            ->helperText(__('admin.blog_card_and_top_hint'))
                            ->columnSpanFull(),
                    ])->columns(3),

                Forms\Components\Section::make(__('admin.text'))
                    ->schema([
                        Forms\Components\Textarea::make('excerpt')
                            ->label(__('admin.excerpt_label'))
                            ->placeholder(__('admin.card_excerpt_hint'))
                            ->rows(2)
                            ->maxLength(500)
                            ->helperText(__('admin.blog_card_hint')),

                        Forms\Components\RichEditor::make('content')
                            ->label(__('admin.content'))
                            ->required()
                            ->placeholder(__('admin.article_main_text_placeholder'))
                            ->columnSpanFull(),
                    ])->columns(1),

                Forms\Components\Section::make(__('admin.seo_settings'))
                    ->description(__('admin.seo_title_desc_hint'))
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label(__('admin.meta_title_seo'))
                            ->placeholder(__('admin.meta_title_fallback_hint'))
                            ->maxLength(255),
                        Forms\Components\Textarea::make('meta_description')
                            ->label(__('admin.meta_description_seo'))
                            ->placeholder(__('admin.meta_description_fallback_hint'))
                            ->rows(2)
                            ->maxLength(500),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')
                    ->label(__('admin.image'))
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode(substr($record->title ?? 'B', 0, 1)) . '&background=F97316&color=fff&size=64'),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('admin.title'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(45),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategoriya')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Məsləhət' => 'info',
                        'Bazar' => 'success',
                        'Xəbər' => 'warning',
                        'İnvestisiya' => 'primary',
                        'Hüquqi' => 'danger',
                        'Həyat tərzi' => 'gray',
                        'Texniki' => 'secondary',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('excerpt')
                    ->label(__('admin.excerpt'))
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('views_count')
                    ->label(__('admin.view'))
                    ->icon('heroicon-m-eye')
                    ->numeric()
                    ->default(0)
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('published_at')
                    ->label(__('admin.publish_date'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategoriya')
                    ->options([
                        'Məsləhət' => __('admin.advice'),
                        'Bazar' => 'Bazar',
                        'Xəbər' => __('admin.news'),
                        'İnvestisiya' => __('admin.investment'),
                        'Hüquqi' => __('admin.legal'),
                        'Həyat tərzi' => __('admin.lifestyle'),
                        'Texniki' => 'Texniki',
                    ]),
                Tables\Filters\Filter::make('published')
                    ->label(__('admin.published_only'))
                    ->query(fn ($query) => $query->whereNotNull('published_at')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'view' => Pages\ViewBlog::route('/{record}'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
