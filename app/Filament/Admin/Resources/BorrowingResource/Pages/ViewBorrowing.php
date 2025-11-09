<?php

namespace App\Filament\Admin\Resources\BorrowingResource\Pages;

use App\Filament\Admin\Resources\BorrowingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;

class ViewBorrowing extends ViewRecord
{
    protected static string $resource = BorrowingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('return')
                ->label('Return Books')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('success')
                ->visible(fn() => $this->record->status !== 'returned')
                ->requiresConfirmation()
                ->modalHeading('Return Books')
                ->modalDescription('Are you sure you want to mark this borrowing as returned? This will update the book stock.')
                ->modalSubmitActionLabel('Yes, Return Books')
                ->action(fn() => $this->record->returnBooks())
                ->after(fn() => $this->refreshFormData(['status', 'return_date', 'fine'])),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Borrowing Information')
                    ->schema([
                        Components\TextEntry::make('id')
                            ->label('Borrowing ID'),

                        Components\TextEntry::make('member.name')
                            ->label('Member'),

                        Components\TextEntry::make('member.email')
                            ->label('Member Email')
                            ->icon('heroicon-m-envelope'),

                        Components\TextEntry::make('borrow_date')
                            ->label('Borrow Date')
                            ->date('d M Y'),

                        Components\TextEntry::make('due_date')
                            ->label('Due Date')
                            ->date('d M Y')
                            ->badge()
                            ->color(fn($record) => $record->due_date->isPast() && $record->status !== 'returned' ? 'danger' : 'success'),

                        Components\TextEntry::make('return_date')
                            ->label('Return Date')
                            ->date('d M Y')
                            ->placeholder('Not returned yet'),

                        Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'borrowed' => 'warning',
                                'returned' => 'success',
                                'late' => 'danger',
                            }),

                        Components\TextEntry::make('fine')
                            ->label('Fine Amount')
                            ->money('IDR')
                            ->visible(fn($record) => $record->fine > 0),

                        Components\TextEntry::make('createdBy.name')
                            ->label('Created By'),

                        Components\TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime('d M Y, H:i'),
                    ])
                    ->columns(2),

                Components\Section::make('Borrowed Books')
                    ->schema([
                        Components\RepeatableEntry::make('borrowingDetails')
                            ->label('')
                            ->schema([
                                Components\TextEntry::make('book.title')
                                    ->label('Book Title'),

                                Components\TextEntry::make('book.author')
                                    ->label('Author'),

                                Components\TextEntry::make('book.isbn')
                                    ->label('ISBN'),

                                Components\TextEntry::make('quantity')
                                    ->label('Quantity')
                                    ->badge(),
                            ])
                            ->columns(4),
                    ]),
            ]);
    }
}
