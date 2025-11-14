<?php

namespace App\Filament\Admin\Resources\BorrowingResource\Widgets;

use App\Models\Borrowing;
use Illuminate\Support\Carbon;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class BorrowingOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Today Borrowings Statistics
        $todayCount = Borrowing::whereDate('borrow_date', Carbon::today())->count();

        $yesterdayCount = Borrowing::whereDate('borrow_date', Carbon::yesterday())->count();

        $todayDifference = $todayCount - $yesterdayCount;
        $todayChangeDirection = $todayDifference >= 0 ? 'increase' : 'decrease';

        // Borrowed Statistics
        $borrowedCount = Borrowing::where('status', 'borrowed')->count();

        $borrowedThisMonth = Borrowing::where('status', 'borrowed')
            ->whereMonth('borrow_date', Carbon::now()->month)
            ->whereYear('borrow_date', Carbon::now()->year)
            ->count();

        $borrowedPreviousMonth = Borrowing::where('status', 'borrowed')
            ->whereMonth('borrow_date', Carbon::now()->subMonth()->month)
            ->whereYear('borrow_date', Carbon::now()->subMonth()->year)
            ->count();

        $borrowedDifference = $borrowedThisMonth - $borrowedPreviousMonth;
        $borrowedChangeDirection = $borrowedDifference >= 0 ? 'increase' : 'decrease';

        // Late Statistics
        $lateCount = Borrowing::where('status', 'late')->count();

        $lateThisMonth = Borrowing::where('status', 'late')
            ->whereMonth('borrow_date', Carbon::now()->month)
            ->whereYear('borrow_date', Carbon::now()->year)
            ->count();

        $latePreviousMonth = Borrowing::where('status', 'late')
            ->whereMonth('borrow_date', Carbon::now()->subMonth()->month)
            ->whereYear('borrow_date', Carbon::now()->subMonth()->year)
            ->count();

        $lateDifference = $lateThisMonth - $latePreviousMonth;
        $lateChangeDirection = $lateDifference >= 0 ? 'increase' : 'decrease';

        // Chart Data
        $todayChartData = $this->getTodayChartData();
        $borrowedChartData = $this->getBorrowedChartData();
        $lateChartData = $this->getLateChartData();

        return [
            Stat::make('Today Borrowings', $todayCount)
                ->description(
                    ($todayDifference >= 0 ? '+' : '') . abs($todayDifference) . ' ' .
                        ($todayChangeDirection === 'increase' ? 'more than' : 'less than') .
                        ' yesterday'
                )
                ->descriptionIcon($todayChangeDirection === 'increase' ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($todayChangeDirection === 'increase' ? 'success' : 'danger')
                ->chart($todayChartData)
                ->chartColor($todayChangeDirection === 'increase' ? 'success' : 'danger'),

            Stat::make('Borrowed', $borrowedCount)
                ->description(
                    ($borrowedDifference >= 0 ? '+' : '') . abs($borrowedDifference) . ' ' .
                        ($borrowedChangeDirection === 'increase' ? 'more borrowed' : 'less borrowed') .
                        ' from last month'
                )
                ->descriptionIcon($borrowedChangeDirection === 'increase' ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($borrowedChangeDirection === 'increase' ? 'warning' : 'success')
                ->chart($borrowedChartData)
                ->chartColor($borrowedChangeDirection === 'increase' ? 'warning' : 'success'),

            Stat::make('Late', $lateCount)
                ->description(
                    ($lateDifference >= 0 ? '+' : '') . abs($lateDifference) . ' ' .
                        ($lateChangeDirection === 'increase' ? 'more late' : 'less late') .
                        ' from last month'
                )
                ->descriptionIcon($lateChangeDirection === 'increase' ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($lateChangeDirection === 'increase' ? 'danger' : 'success')
                ->chart($lateChartData)
                ->chartColor($lateChangeDirection === 'increase' ? 'danger' : 'success'),
        ];
    }

    private function getTodayChartData(): array
    {
        $days = 7;
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            $data[] = Borrowing::whereDate('borrow_date', $date->format('Y-m-d'))->count();
        }

        return $data;
    }

    private function getBorrowedChartData(): array
    {
        $days = 7;
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            $data[] = Borrowing::where('status', 'borrowed')
                ->whereDate('borrow_date', $date->format('Y-m-d'))
                ->count();
        }

        return $data;
    }

    private function getLateChartData(): array
    {
        $days = 7;
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            $data[] = Borrowing::where('status', 'late')
                ->whereDate('borrow_date', $date->format('Y-m-d'))
                ->count();
        }

        return $data;
    }
}
