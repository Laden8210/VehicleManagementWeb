<?php

namespace App\Filament\Resources\DispatchResource\Widgets;

use App\Models\Dispatch;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{

    protected function getStats(): array
    {
        return [
            Stat::make('PATIENT TRANSPORT SERVICES', number_format(Dispatch::where('RequestStatus', 'Approved')->count()))
                ->description('Total transported patients')
                ->descriptionIcon('heroicon-o-chart-bar-square')
                ->color('success')
                ->chartColor('green')
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #d69e2e; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/dispatches'),
        ];
    }
}
