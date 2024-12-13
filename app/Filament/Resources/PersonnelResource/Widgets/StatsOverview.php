<?php

namespace App\Filament\Resources\PersonnelResource\Widgets;

use App\Models\Personnel;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('PERMANENT', Personnel::where('Status', 'Permanent')->count())
                ->description('Total Permanent employees')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success')
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #3182ce; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/personnels'),

            Stat::make('CASUAL', Personnel::where('Status', 'Casual')->count())
                ->description('Total Casual employees')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success')
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #3182ce; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/personnels'),

            Stat::make('JOB ORDER', Personnel::where('Status', 'Job Order')->count())
                ->description('Total Job Order workers')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success')
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #3182ce; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/personnels'),
        ];
    }
}
