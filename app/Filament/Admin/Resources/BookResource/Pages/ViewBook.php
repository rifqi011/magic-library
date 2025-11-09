<?php

namespace App\Filament\Admin\Resources\BookResource\Pages;

use App\Filament\Admin\Resources\BookResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;

class ViewBook extends ViewRecord
{
    protected static string $resource = BookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Book Information')
                    ->schema([
                        Components\ImageEntry::make('cover_image')
                            ->label('Cover Image')
                            ->height(200)
                            ->columnSpanFull(),

                        Components\TextEntry::make('title')
                            ->label('Title'),

                        Components\TextEntry::make('author')
                            ->label('Author'),

                        Components\TextEntry::make('publisher')
                            ->label('Publisher'),

                        Components\TextEntry::make('year')
                            ->label('Year'),

                        Components\TextEntry::make('isbn')
                            ->label('ISBN'),

                        Components\TextEntry::make('category.name')
                            ->label('Category')
                            ->badge(),

                        Components\TextEntry::make('genres.name')
                            ->label('Genre')
                            ->badge()
                            ->separator(','),

                        Components\TextEntry::make('stock')
                            ->label('Stock')
                            ->badge()
                            ->color(fn(int $state): string => match (true) {
                                $state === 0 => 'danger',
                                $state <= 5 => 'warning',
                                default => 'success',
                            }),

                        Components\TextEntry::make('description')
                            ->label('Description')
                            ->columnSpanFull()
                            ->markdown(),

                        Components\TextEntry::make('synopsis')
                            ->label('Synopsis')
                            ->columnSpanFull()
                            ->markdown(),

                        Components\TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime('d M Y, H:i'),
                    ])
                    ->columns(2),

                Components\Section::make('Borrowing History')
                    ->schema([
                        Components\ViewEntry::make('borrowing_history')
                            ->label('')
                            ->view('filament.resources.book-resource.pages.borrowing-history-table')
                            ->viewData([
                                'record' => $this->record,
                            ]),
                    ])
                    ->collapsed(),
            ]);
    }
}
