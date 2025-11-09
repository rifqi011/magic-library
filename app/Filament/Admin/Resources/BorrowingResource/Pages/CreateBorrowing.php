<?php

namespace App\Filament\Admin\Resources\BorrowingResource\Pages;

use App\Filament\Admin\Resources\BorrowingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBorrowing extends CreateRecord
{
    protected static string $resource = BorrowingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Borrowing created successfully';
    }

    protected function afterCreate(): void
    {
        //
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure created_by is set
        $data['created_by'] = auth()->id();

        return $data;
    }
}
