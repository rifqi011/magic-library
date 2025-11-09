<?php

namespace App\Filament\Admin\Resources\BorrowingResource\Pages;

use App\Filament\Admin\Resources\BorrowingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBorrowing extends EditRecord
{
    protected static string $resource = BorrowingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
