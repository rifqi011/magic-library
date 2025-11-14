<?php

namespace App\Filament\Admin\Resources\BorrowingResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\BorrowingResource;
use App\Filament\Admin\Resources\BorrowingResource\Widgets\BorrowingOverview;

class ListBorrowings extends ListRecords
{
    protected static string $resource = BorrowingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            BorrowingOverview::class
        ];
    }
}
