<?php

namespace App\Filament\Admin\Resources\MemberResource\Widgets;

use App\Models\Member;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Carbon;

class MemberOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $currentMonth = Member::whereMonth('join_date', Carbon::now()->month)
            ->whereYear('join_date', Carbon::now()->year)
            ->count();

        $previousMonth = Member::whereMonth('join_date', Carbon::now()->subMonth()->month)
            ->whereYear('join_date', Carbon::now()->subMonth()->year)
            ->count();

        $totalDifference = $currentMonth - $previousMonth;
        $totalChangeDirection = $totalDifference >= 0 ? 'increase' : 'decrease';

        $activeCount = Member::where('status', 'active')->count();

        $activeThisMonth = Member::where('status', 'active')
            ->whereMonth('join_date', Carbon::now()->month)
            ->whereYear('join_date', Carbon::now()->year)
            ->count();

        $activePreviousMonth = Member::where('status', 'active')
            ->whereMonth('join_date', Carbon::now()->subMonth()->month)
            ->whereYear('join_date', Carbon::now()->subMonth()->year)
            ->count();

        $activeDifference = $activeThisMonth - $activePreviousMonth;
        $activeChangeDirection = $activeDifference >= 0 ? 'increase' : 'decrease';

        $inactiveCount = Member::where('status', 'inactive')->count();

        $inactiveThisMonth = Member::where('status', 'inactive')
            ->whereMonth('updated_at', Carbon::now()->month)
            ->whereYear('updated_at', Carbon::now()->year)
            ->count();

        $inactivePreviousMonth = Member::where('status', 'inactive')
            ->whereMonth('updated_at', Carbon::now()->subMonth()->month)
            ->whereYear('updated_at', Carbon::now()->subMonth()->year)
            ->count();

        $inactiveDifference = $inactiveThisMonth - $inactivePreviousMonth;
        $inactiveChangeDirection = $inactiveDifference >= 0 ? 'increase' : 'decrease';

        $chartData = $this->getChartData();

        return [
            Stat::make('Total Members', Member::count())
                ->description(
                    ($totalDifference >= 0 ? '+' : '') . $totalDifference . ' ' .
                        ($totalChangeDirection === 'increase' ? 'new members' : 'members left') .
                        ' this month'
                )
                ->descriptionIcon($totalChangeDirection === 'increase' ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($totalChangeDirection === 'increase' ? 'success' : 'danger')
                ->chart($chartData)
                ->chartColor($totalChangeDirection === 'increase' ? 'success' : 'danger'),

            Stat::make('Active Members', $activeCount)
                ->description(
                    ($activeDifference >= 0 ? '+' : '') . $activeDifference . ' ' .
                        ($activeChangeDirection === 'increase' ? 'more active' : 'less active') .
                        ' from last month'
                )
                ->descriptionIcon($activeChangeDirection === 'increase' ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($activeChangeDirection === 'increase' ? 'success' : 'danger'),

            Stat::make('Inactive Members', $inactiveCount)
                ->description(
                    ($inactiveDifference >= 0 ? '+' : '') . $inactiveDifference . ' ' .
                        ($inactiveChangeDirection === 'increase' ? 'more inactive' : 'less inactive') .
                        ' from last month'
                )
                ->descriptionIcon($inactiveChangeDirection === 'increase' ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($inactiveChangeDirection === 'increase' ? 'danger' : 'success'), // lebih banyak inactive = buruk
        ];
    }

    private function getChartData(): array
    {
        $days = 7;
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            $data[] = Member::whereDate('join_date', $date->format('Y-m-d'))->count();
        }

        return $data;
    }
}
