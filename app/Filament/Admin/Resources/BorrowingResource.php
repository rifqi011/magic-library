<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use App\Models\Book;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Borrowing;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\BorrowingResource\Pages;
use App\Filament\Admin\Resources\BorrowingResource\RelationManagers;

class BorrowingResource extends Resource
{
    protected static ?string $model = Borrowing::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Borrowing Information')
                    ->schema([
                        Forms\Components\Select::make('member_id')
                            ->label('Member')
                            ->relationship(
                                'member',
                                'name',
                                fn(Builder $query) => $query->where('status', 'active')
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\DatePicker::make('borrow_date')
                            ->label('Borrow Date')
                            ->default(now())
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->columnSpan(1),

                        Forms\Components\DatePicker::make('due_date')
                            ->label('Due Date')
                            ->default(now()->addDays(7))
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->after('borrow_date')
                            ->columnSpan(1),

                        Forms\Components\DatePicker::make('return_date')
                            ->label('Return Date')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->columnSpan(1)
                            ->visible(fn($record) => $record !== null),

                        Forms\Components\Select::make('status')
                            ->options([
                                'borrowed' => 'Borrowed',
                                'returned' => 'Returned',
                                'late' => 'Late',
                            ])
                            ->default('borrowed')
                            ->required()
                            ->columnSpan(1)
                            ->disabled(fn($record) => $record === null),

                        Forms\Components\TextInput::make('fine')
                            ->label('Fine Amount')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->disabled()
                            ->dehydrated()
                            ->columnSpan(1)
                            ->visible(fn($record) => $record !== null),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Books')
                    ->schema([
                        Forms\Components\Repeater::make('borrowingDetails')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('book_id')
                                    ->label('Book')
                                    ->options(function () {
                                        return \App\Models\Book::where('stock', '>', 0)
                                            ->pluck('title', 'id');
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->disableOptionWhen(function ($value, $state, Forms\Get $get) {
                                        // Disable if book is already selected in other repeater items
                                        return collect($get('../*.book_id'))
                                            ->reject(fn($id) => $id == $state)
                                            ->contains($value);
                                    })
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        if ($state) {
                                            $book = \App\Models\Book::find($state);
                                            if ($book && $book->stock > 0) {
                                                $set('quantity', 1);
                                            }
                                        }
                                    })
                                    ->columnSpan(2),

                                Forms\Components\TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                        $bookId = $get('book_id');
                                        if ($bookId) {
                                            $book = Book::find($bookId);
                                            if ($book && $state > $book->stock) {
                                                $set('quantity', $book->stock);
                                                Notification::make()
                                                    ->warning()
                                                    ->title('Stock Limit')
                                                    ->body("Only {$book->stock} book(s) available in stock.")
                                                    ->send();
                                            }
                                        }
                                    })
                                    ->columnSpan(1),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->addActionLabel('Add Book')
                            ->collapsible()
                            ->disabled(fn($record) => $record?->status === 'returned'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('member.name')
                    ->label('Member')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('borrow_date')
                    ->label('Borrow Date')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('Due Date')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('return_date')
                    ->label('Return Date')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'borrowed',
                        'success' => 'returned',
                        'danger' => 'late',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('fine')
                    ->label('Fine')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('borrowing_details_count')
                    ->label('Books Count')
                    ->counts('borrowingDetails')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Created By')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'borrowed' => 'Borrowed',
                        'returned' => 'Returned',
                        'late' => 'Late',
                    ]),

                Tables\Filters\Filter::make('borrow_date')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('borrow_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('borrow_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('return')
                    ->label('Return Books')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('success')
                    ->visible(fn(Borrowing $record) => $record->status !== 'returned')
                    ->requiresConfirmation()
                    ->action(fn(Borrowing $record) => $record->returnBooks()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListBorrowings::route('/'),
            'create' => Pages\CreateBorrowing::route('/create'),
            'edit' => Pages\EditBorrowing::route('/{record}/edit'),
            'view' => Pages\ViewBorrowing::route('/{record}'),
        ];
    }
}
