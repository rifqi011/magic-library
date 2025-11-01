<?php

namespace App\Filament\Admin\Resources\BookResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\BookResource;
use App\Filament\Admin\Resources\BookResource\Widgets\BookOverview;

class ListBooks extends ListRecords
{
    protected static string $resource = BookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            BookOverview::class,
        ];
    }
}
