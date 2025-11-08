<?php

namespace App\Filament\Admin\Resources\GenreResource\Pages;

use App\Filament\Admin\Resources\GenreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGenre extends EditRecord
{
    protected static string $resource = GenreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(function ($record) {
                    if ($record->books()->exists()) {
                        return false;
                    }

                    return true;
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
