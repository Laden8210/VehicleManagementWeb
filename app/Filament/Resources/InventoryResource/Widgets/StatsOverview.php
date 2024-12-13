<?php

namespace App\Filament\Resources\InventoryResource\Widgets;

use App\Models\Borrower;
use App\Models\Inventory;
use App\Models\Request;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('TOTAL ITEMS', number_format(Inventory::count()))
                ->description('Total items added')
                ->descriptionIcon('heroicon-o-cube')
                ->color('success')
                ->chartColor('green')
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #d69e2e; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/inventories'),

            Stat::make('TOTAL BORROWERS', number_format(Borrower::count()))
                ->description('Total registered borrowers')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success')
                ->chartColor('orange')
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #d69e2e; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/borrowers'),

            Stat::make('TOTAL REQUESTS', number_format(Request::count()))
                ->description('Total item requests')
                ->descriptionIcon('heroicon-o-clipboard-document-list')
                ->color('success')
                ->chartColor('orange')
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #d69e2e; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/requests'),


        ];
    }
}
