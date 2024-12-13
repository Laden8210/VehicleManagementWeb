<?php

namespace App\Filament\Widgets;

use App\Models\Inventory;
use App\Models\RepairRequest;
use App\Models\MaintenanceRecommendation;
use App\Models\Reminder;
use App\Models\Dispatch;
use App\Models\Personnel;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';
    protected static ?int $sort = 2;
    protected static bool $isLazy = true;

    protected function getViewData(): array
    {
        return [
            'stats' => $this->getStats(),
        ];
    }

    protected function getStats(): array
    {
        $user = auth()->user();
        $stats = [];

        // PERSONNEL widget visibility
        if (!$user->hasRole('Storekeeper') && !$user->hasRole('Driver')) {
            $stats[] = Stat::make('PERSONNEL', Personnel::count())
                ->description('Total Personnel')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success')
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #285e61; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/personnels');
        }

        // REPAIR REQUEST widget visibility
        if (!$user->hasRole('Storekeeper')) {
            $currentMonthRepairRequestCount = RepairRequest::whereMonth('created_at', Carbon::now()->month)->count();
            $previousMonthRepairRequestCount = RepairRequest::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
            $repairRequestTrend = $currentMonthRepairRequestCount > $previousMonthRepairRequestCount ? 'increased' : ($currentMonthRepairRequestCount < $previousMonthRepairRequestCount ? 'decreased' : 'unchanged');
            $repairRequestTrendIcon = $repairRequestTrend === 'increased' ? 'heroicon-o-arrow-trending-up' : ($repairRequestTrend === 'decreased' ? 'heroicon-o-arrow-trending-down' : 'heroicon-o-minus');

            $stats[] = Stat::make('REPAIR REQUEST', $currentMonthRepairRequestCount)
                ->description("Total Repair Request ($repairRequestTrend)")
                ->descriptionIcon($repairRequestTrendIcon)
                ->color($repairRequestTrend === 'increased' ? 'success' : ($repairRequestTrend === 'decreased' ? 'danger' : 'gray'))
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #c53030; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/repair-requests');
        }

        // MAINTENANCE RECOMMENDATION widget visibility
        if (!$user->hasRole('Storekeeper')) {
            $currentMonthMaintenanceCount = MaintenanceRecommendation::whereMonth('created_at', Carbon::now()->month)->count();
            $previousMonthMaintenanceCount = MaintenanceRecommendation::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
            $maintenanceTrend = $currentMonthMaintenanceCount > $previousMonthMaintenanceCount ? 'increased' : ($currentMonthMaintenanceCount < $previousMonthMaintenanceCount ? 'decreased' : 'unchanged');
            $maintenanceTrendIcon = $maintenanceTrend === 'increased' ? 'heroicon-o-arrow-trending-up' : ($maintenanceTrend === 'decreased' ? 'heroicon-o-arrow-trending-down' : 'heroicon-o-minus');

            $stats[] = Stat::make('MAINTENANCE RECOMMENDATION', $currentMonthMaintenanceCount)
                ->description("Total Maintenance Recommendation ($maintenanceTrend)")
                ->descriptionIcon($maintenanceTrendIcon)
                ->color($maintenanceTrend === 'increased' ? 'success' : ($maintenanceTrend === 'decreased' ? 'danger' : 'gray'))
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #d69e2e; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/maintenance-recommendations');

            // REMINDERS widget visibility
            $stats[] = Stat::make('REMINDERS', Reminder::count())
                ->description('Total Reminders')
                ->descriptionIcon('heroicon-o-clipboard-document')
                ->color('success')
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #3182ce; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/reminders');

            // DISPATCH widget visibility
            $currentMonthCount = Dispatch::whereMonth('created_at', Carbon::now()->month)->count();
            $previousMonthCount = Dispatch::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
            $trend = $currentMonthCount > $previousMonthCount ? 'increased' : ($currentMonthCount < $previousMonthCount ? 'decreased' : 'unchanged');
            $trendIcon = $trend === 'increased' ? 'heroicon-o-arrow-trending-up' : ($trend === 'decreased' ? 'heroicon-o-arrow-trending-down' : 'heroicon-o-minus');

            $stats[] = Stat::make('DISPATCH', $currentMonthCount)
                ->description("Total Patient Transported ($trend)")
                ->descriptionIcon($trendIcon)
                ->color($trend === 'increased' ? 'success' : ($trend === 'decreased' ? 'danger' : 'gray'))
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #285e61; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/dispatches');
        }

        // INVENTORY widget visibility
        if (!$user->hasRole('Driver')) {
            $currentMonthInventoryCount = Inventory::whereMonth('created_at', Carbon::now()->month)->count();
            $previousMonthInventoryCount = Inventory::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
            $inventoryTrend = $currentMonthInventoryCount > $previousMonthInventoryCount ? 'increased' : ($currentMonthInventoryCount < $previousMonthInventoryCount ? 'decreased' : 'unchanged');
            $inventoryTrendIcon = $inventoryTrend === 'increased' ? 'heroicon-o-arrow-trending-up' : ($inventoryTrend === 'decreased' ? 'heroicon-o-arrow-trending-down' : 'heroicon-o-minus');

            $stats[] = Stat::make('INVENTORY', $currentMonthInventoryCount)
                ->description("Total Inventory items ($inventoryTrend)")
                ->descriptionIcon($inventoryTrendIcon)
                ->color($inventoryTrend === 'increased' ? 'success' : ($inventoryTrend === 'decreased' ? 'danger' : 'gray'))
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #2d3748; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/inventories');
        }

        return $stats;
    }

    protected function getView(): string
    {
        return 'filament.widgets.stats-overview'; // Custom view path
    }
}

