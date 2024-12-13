<?php

namespace App\Filament\Resources\VehicleResource\Widgets;

use App\Models\Vehicle;
use App\Models\VehicleRemarks;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $stats = [];
        $stats[] = $this->getServiceableVehiclesStat();
        $stats[] = $this->getUnserviceableVehiclesStat();
        $stats[] = $this->getUnderMaintenanceVehiclesStat();
        $stats[] = $this->getBodyTypeStat('RESCUE VEHICLES', 'Rescue Vehicle', 'blue');
        $stats[] = $this->getBodyTypeStat('AMBULANCES', 'Ambulance', 'purple');
        $stats[] = $this->getBodyTypeStat('PATIENT TRANSPORT VEHICLES', 'PTV', 'teal');

        return $stats;
    }

    protected function getServiceableVehiclesStat(): Stat
    {
        $currentMonthCount = $this->getDistinctVehicleCount('Serviceable', Carbon::now()->month);
        $previousMonthCount = $this->getDistinctVehicleCount('Serviceable', Carbon::now()->subMonth()->month);
        $trendInfo = $this->getTrendInfo($currentMonthCount, $previousMonthCount);

        return Stat::make('SERVICEABLE', $currentMonthCount)
            ->description("Total Serviceable vehicles ({$trendInfo['trend']})")
            ->descriptionIcon($trendInfo['icon'])
            ->color($trendInfo['color'])
            ->extraAttributes($this->getExtraAttributes())
            ->url('/admin/vehicles');
    }

    protected function getUnserviceableVehiclesStat(): Stat
    {
        $currentMonthCount = $this->getDistinctVehicleCount('Unserviceable', Carbon::now()->month);
        $previousMonthCount = $this->getDistinctVehicleCount('Unserviceable', Carbon::now()->subMonth()->month);
        $trendInfo = $this->getTrendInfo($currentMonthCount, $previousMonthCount);

        return Stat::make('UNSERVICEABLE', $currentMonthCount)
            ->description("Total Unserviceable vehicles ({$trendInfo['trend']})")
            ->descriptionIcon($trendInfo['icon'])
            ->color($trendInfo['color'])
            ->extraAttributes($this->getExtraAttributes())
            ->url('/admin/vehicles');
    }

    protected function getUnderMaintenanceVehiclesStat(): Stat
    {
        $currentMonthCount = $this->getDistinctVehicleCount('Under REPAIR', Carbon::now()->month);
        $previousMonthCount = $this->getDistinctVehicleCount('Under REPAIR', Carbon::now()->subMonth()->month);
        $trendInfo = $this->getTrendInfo($currentMonthCount, $previousMonthCount);

        return Stat::make('UNDER REPAIR', $currentMonthCount)
            ->description("Total under repair vehicles ({$trendInfo['trend']})")
            ->descriptionIcon($trendInfo['icon'])
            ->color($trendInfo['color'])
            ->extraAttributes($this->getExtraAttributes())
            ->url('/admin/vehicles');
    }

    protected function getBodyTypeStat(string $title, string $bodyType, string $chartColor): Stat
    {
        return Stat::make($title, Vehicle::where('BodyType', $bodyType)->count())
            ->description("Total {$title}")
            ->descriptionIcon('heroicon-o-truck')
            ->color('success')
            ->chartColor($chartColor)
            ->extraAttributes($this->getExtraAttributes())
            ->url("/admin/vehicles?filter[BodyType]={$bodyType}");
    }

    protected function getDistinctVehicleCount(string $vehicleStatus, int $month): int
    {
        return VehicleRemarks::where('VehicleRemarks', $vehicleStatus)
            ->whereMonth('created_at', $month)
            ->distinct('vehicles_id')
            ->count('vehicles_id');
    }

    protected function getTrendInfo(int $currentCount, int $previousCount): array
    {
        $trend = $currentCount > $previousCount ? 'increased' :
            ($currentCount < $previousCount ? 'decreased' : 'unchanged');

        $icon = $trend === 'increased' ? 'heroicon-o-arrow-trending-up' :
            ($trend === 'decreased' ? 'heroicon-o-arrow-trending-down' : 'heroicon-o-minus');

        $color = $trend === 'increased' ? 'success' :
            ($trend === 'decreased' ? 'danger' : 'gray');

        return ['trend' => $trend, 'icon' => $icon, 'color' => $color];
    }

    protected function getExtraAttributes(): array
    {
        return [
            'class' => 'text-3xl',
            'style' => 'color: #3182ce; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;',
        ];
    }
}
