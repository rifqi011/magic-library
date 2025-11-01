<?php

namespace App\Filament\Admin\Resources\BookResource\Widgets;

use App\Models\Book;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class BookOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $activeCount = Book::where('status', 'active')->count();

        $inactiveCount = Book::where('status', 'inactive')->count();

        return [
            Stat::make('Total Books', Book::count()),

            Stat::make('Active Books', $activeCount),

            Stat::make('Inactive Books', $inactiveCount)
        ];
    }
}
