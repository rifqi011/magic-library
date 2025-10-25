<?php

namespace App\Filament\Admin\Resources\MemberResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\MemberResource;
use App\Filament\Admin\Resources\MemberResource\Widgets\MemberOverview;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            MemberOverview::class,
        ];
    }
}
