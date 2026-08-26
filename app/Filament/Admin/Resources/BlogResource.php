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

    protected static ?string $navigationGroup = 'Məzmun';

    protected static ?string $navigationLabel = 'Bloqlar';

    protected static ?string $modelLabel = 'Bloq';

    protected static ?string $pluralModelLabel = 'Bloqlar';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Bloq Məlumatları')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Başlıq')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', \Illuminate\Support\Str::slug($state)))
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('slug')
                            ->label('URL (Slug)')
                            ->required()
                            ->unique(table: 'blogs', column: 'slug', ignoreRecord: true)
                            ->helperText('Boş buraxsa, başlıqdan avtomatik yaranır.'),

                        Forms\Components\Select::make('category')
                            ->label('Kategoriya')
                            ->options([
                                'Məsləhət' => 'Məsləhət',
                                'Bazar' => 'Bazar',
                                'Xəbər' => 'Xəbər',
                                'İnvestisiya' => 'İnvestisiya',
                                'Hüquqi' => 'Hüquqi',
                                'Həyat tərzi' => 'Həyat tərzi',
                                'Texniki' => 'Texniki',
                            ])
                            ->searchable()
                            ->placeholder('Kategoriya seçin'),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Dərc Tarixi')
                            ->default(now())
                            ->required(),

                        Forms\Components\FileUpload::make('cover_image')
                            ->label('Üzlük Şəkli')
                            ->image()
                            ->imageEditor()
                            ->directory('blogs')
                            ->visibility('public')
                            ->helperText('Bloq kartında və məqalənin başında görünür.')
                            ->columnSpanFull(),
                    ])->columns(3),

                Forms\Components\Section::make('Mətn')
                    ->schema([
                        Forms\Components\Textarea::make('excerpt')
                            ->label('Qısa Mətn (Excerpt)')
                            ->placeholder('Kartda görünən qısa təsvir — 1-2 cümlə')
                            ->rows(2)
                            ->maxLength(500)
                            ->helperText('Bloq kartında göstərilir. Qısa və maraqlı yazın.'),

                        Forms\Components\RichEditor::make('content')
                            ->label('Məzmun')
                            ->required()
                            ->placeholder('Məqalənin əsas mətni...')
                            ->columnSpanFull(),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')
                    ->label('Şəkil')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode(substr($record->title ?? 'B', 0, 1)) . '&background=F97316&color=fff&size=64'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Başlıq')
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
                    ->label('Qısa Mətn')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('views_count')
                    ->label('Baxış')
                    ->icon('heroicon-m-eye')
                    ->numeric()
                    ->default(0)
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Dərc Tarixi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategoriya')
                    ->options([
                        'Məsləhət' => 'Məsləhət',
                        'Bazar' => 'Bazar',
                        'Xəbər' => 'Xəbər',
                        'İnvestisiya' => 'İnvestisiya',
                        'Hüquqi' => 'Hüquqi',
                        'Həyat tərzi' => 'Həyat tərzi',
                        'Texniki' => 'Texniki',
                    ]),
                Tables\Filters\Filter::make('published')
                    ->label('Yalnız dərc olunanlar')
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
