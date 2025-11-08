<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Genre;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\GenreResource\Pages;
use App\Filament\Admin\Resources\GenreResource\Pages\EditGenre;
use App\Filament\Admin\Resources\GenreResource\Pages\ListGenres;
use App\Filament\Admin\Resources\GenreResource\RelationManagers;
use App\Filament\Admin\Resources\GenreResource\Pages\CreateGenre;

class GenreResource extends Resource
{
    protected static ?string $model = Genre::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Library Catalog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $state, Forms\Set $set) {
                        $set('slug', Str::slug($state));
                    }),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->readOnly()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->dehydrated(),

                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->required()
                    ->default('active')
                    ->native(false)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->limit(30),

                IconColumn::make('status')
                    ->boolean()
                    ->getStateUsing(fn(Genre $record): bool => $record->status == 'active')
                    ->sortable()
                    ->label('Active')
            ])
            ->filters([
                TernaryFilter::make('status')
                    ->trueLabel('Active')
                    ->falseLabel('Inactive')
                    ->queries(
                        true: fn(Builder $query) => $query->where('status', 'active'),
                        false: fn(Builder $query) => $query->where('status', 'inactive'),
                        blank: fn(Builder $query) => $query
                    )
                    ->native(false)
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(function ($record) {
                        if ($record->books()->exists()) {
                            return false;
                        }

                        return true;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(function (Collection $records) {
                        // Filter genres that are allowed to be deleted (no related books in pivot table)
                        $allowed = $records->filter(
                            fn($record) => !$record->books()->exists()
                        );

                        $blocked = $records->diff($allowed);

                        // If none can be deleted
                        if ($allowed->isEmpty()) {
                            Notification::make()
                                ->title('Action Cancelled')
                                ->body('No genres can be deleted. Genres with related books cannot be deleted.')
                                ->danger()
                                ->send();

                            return;
                        }

                        // Delete only allowed records
                        $deleted = 0;
                        foreach ($allowed as $record) {
                            $record->delete();
                            $deleted++;
                        }

                        // Show notification for blocked records
                        if ($blocked->isNotEmpty()) {
                            Notification::make()
                                ->title('Partial Success')
                                ->body("Successfully deleted {$deleted} genre(s). Some genres could not be deleted: " . $blocked->pluck('name')->join(', '))
                                ->warning()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Success')
                                ->body("Successfully deleted {$deleted} genre(s).")
                                ->success()
                                ->send();
                        }
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGenres::route('/'),
            'create' => Pages\CreateGenre::route('/create'),
            'edit' => Pages\EditGenre::route('/{record}/edit'),
        ];
    }
}
