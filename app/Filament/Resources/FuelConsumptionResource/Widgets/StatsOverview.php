<?php

namespace App\Filament\Resources\FuelConsumptionResource\Widgets;

use App\Models\FuelConsumption;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $initialPreviousBalance = FuelConsumption::orderBy('id')->first()->PreviousBalance ?? 0;


        $totalDiesel = FuelConsumption::whereHas('tripTicket.vehicle', function ($query) {
            $query->where('Fuel', 'Diesel');
        })->sum('Amount');

        $totalGasoline = FuelConsumption::whereHas('tripTicket.vehicle', function ($query) {
            $query->where('Fuel', 'Gasoline');
        })->sum('Amount');

        $dieselPercentage = $initialPreviousBalance > 0 ? ($totalDiesel / $initialPreviousBalance) * 100 : 0;
        $gasolinePercentage = $initialPreviousBalance > 0 ? ($totalGasoline / $initialPreviousBalance) * 100 : 0;

        $totalUtilization = $dieselPercentage + $gasolinePercentage;

        $currentMonthConsumption = FuelConsumption::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->sum('Amount');

        $previousMonthConsumption = FuelConsumption::whereMonth('created_at', date('m', strtotime('first day of last month')))
            ->whereYear('created_at', date('Y', strtotime('first day of last month')))
            ->sum('Amount');

        $trendInfo = $this->getTrendInfo($currentMonthConsumption, $previousMonthConsumption);

        return [
            Stat::make('INITIAL BALANCE', '₱' . number_format($initialPreviousBalance, 2, '.', ','))
                ->description('Starting Balance')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success')
                ->chartColor('blue')
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #3182ce; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/fuel-consumptions'),

            Stat::make('DIESEL', '₱' . number_format($totalDiesel, 2, '.', ',') . ' (' . number_format($dieselPercentage, 2) . '%)')
                ->description('Total Diesel Fuel Cost')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success')
                ->chartColor('red')
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #3182ce; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/fuel-consumptions'),

            Stat::make('GASOLINE', '₱' . number_format($totalGasoline, 2, '.', ',') . ' (' . number_format($gasolinePercentage, 2) . '%)')
                ->description('Total Gasoline Fuel Cost')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success')
                ->chartColor('green')
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #3182ce; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/fuel-consumptions'),

            Stat::make('TOTAL UTILIZATION', number_format($totalUtilization, 2) . '%')
                ->description('Current Fuel Utilization')
                ->descriptionIcon('heroicon-o-chart-pie')
                ->color('success')
                ->chartColor('orange')
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #3182ce; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/fuel-consumptions'),

            Stat::make('REAL-TIME FUEL MONTHLY CONSUMPTION', '₱' . number_format($currentMonthConsumption, 2, '.', ',') . ' (' . $trendInfo['trend'] . ')')
                ->description('Fuel Consumption(This Month vs Last Month)')
                ->descriptionIcon($trendInfo['icon'])
                ->color($trendInfo['color'])
                ->chartColor('purple')
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #3182ce; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/fuel-consumptions'),
        ];
    }

    protected function getTrendInfo(float $currentConsumption, float $previousConsumption): array
    {
        $trend = $currentConsumption > $previousConsumption ? 'increased' :
            ($currentConsumption < $previousConsumption ? 'decreased' : 'unchanged');

        $icon = $trend === 'increased' ? 'heroicon-o-arrow-trending-up' :
            ($trend === 'decreased' ? 'heroicon-o-arrow-trending-down' : 'heroicon-o-minus');

        $color = $trend === 'increased' ? 'success' :
            ($trend === 'decreased' ? 'danger' : 'gray');

        return ['trend' => $trend, 'icon' => $icon, 'color' => $color];
    }
}
