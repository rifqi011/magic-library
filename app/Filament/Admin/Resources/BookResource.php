<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use App\Models\Book;
use Filament\Tables;
use App\Models\Genre;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\BookResource\Pages;
use App\Filament\Admin\Resources\BookResource\RelationManagers;
use Pages\ViewBook;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Book Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(200)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $state, Forms\Set $set) {
                                $set('slug', Str::slug($state));
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->readonly()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->dehydrated(),

                        Forms\Components\TextInput::make('author')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('publisher')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('isbn')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('year')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(now()->year)
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Content')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->required(),

                        Forms\Components\RichEditor::make('synopsis')
                            ->label('Synopsis')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'bulletList',
                                'orderedList',
                                'link',
                                'blockquote',
                                'codeBlock',
                                'undo',
                                'redo',
                            ])
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

                Section::make('Classification')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name', fn($query) => $query->where('status', 'active'))
                            ->searchable()
                            ->preload() // <— tampilkan semua data aktif
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\Textarea::make('description')
                                    ->label('Description'),

                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'active' => 'Active',
                                        'inactive' => 'Inactive',
                                    ])
                                    ->default('active')
                                    ->required(),
                            ])
                            ->createOptionAction(
                                fn(Forms\Components\Actions\Action $action) =>
                                $action->modalHeading('Create New Category')->modalButton('Create')
                            ),

                        Forms\Components\Select::make('genres')
                            ->label('Genres')
                            ->multiple()
                            ->relationship('genres', 'name', fn($query) => $query->where('status', 'active'))
                            ->searchable()
                            ->preload() // <— tampilkan semua data aktif
                            ->required()
                            ->helperText('Select one or more genres for this book.')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\Textarea::make('description')
                                    ->label('Description'),

                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'active' => 'Active',
                                        'inactive' => 'Inactive',
                                    ])
                                    ->default('active')
                                    ->required(),
                            ])
                            ->createOptionAction(
                                fn(Forms\Components\Actions\Action $action) =>
                                $action->modalHeading('Create New Genre')->modalButton('Create')
                            ),
                    ])
                    ->columns(2),

                Section::make('Additional Info')
                    ->schema([
                        Forms\Components\FileUpload::make('cover_image')
                            ->label('Cover Image')
                            ->image()
                            ->directory('book-covers')
                            ->imagePreviewHeight('150px')
                            ->downloadable()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('stock')
                            ->numeric()
                            ->default(1)
                            ->minValue(0)
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->default('active')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->paginated()
            ->defaultPaginationPageOption(10)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')
                    ->label('Cover')
                    ->circular(),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('author')
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category'),

                Tables\Columns\TextColumn::make('genres.name')
                    ->label('Genres')
                    ->limit(30),

                Tables\Columns\TextColumn::make('stock')
                    ->sortable(),

                Tables\Columns\IconColumn::make('status')
                    ->boolean()
                    ->getStateUsing(fn(Book $record): bool => $record->status == 'active')
                    ->sortable()
                    ->label('Active')
            ])
            ->filters([
                TernaryFilter::make('status')
                    ->trueLabel('Active')
                    ->falseLabel('Inactive')
                    ->native(),

                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->placeholder('All Categories'),

                Tables\Filters\SelectFilter::make('genres')
                    ->relationship('genres', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->placeholder('All Genres'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make()
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['genre_ids'])) {
            $data['genres'] = $data['genre_ids'];
            unset($data['genre_ids']);
        }

        return $data;
    }

    public static function afterSave($record, array $data): void
    {
        if (isset($data['genres'])) {
            $record->genres()->sync($data['genres']);
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
            'view' => Pages\ViewBook::route('/{record}'),
        ];
    }
}
