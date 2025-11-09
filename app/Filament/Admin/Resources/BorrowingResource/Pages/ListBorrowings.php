<?php

namespace App\Filament\Admin\Resources\BorrowingResource\Pages;

use App\Filament\Admin\Resources\BorrowingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBorrowings extends ListRecords
{
    protected static string $resource = BorrowingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
