<?php

namespace App\Filament\Resources\TripTicketResource\Widgets;

use App\Models\TripTicket;
use Filament\Widgets\ChartWidget;

class FuelChart extends ChartWidget
{
    protected static ?string $heading = 'Vehicle Fuel Balance Chart';

    protected function getData(): array
    {
        $vehicles = TripTicket::with('vehicle')
            ->get()
            ->groupBy('vehicles_id')
            ->map(function ($trips) {
                $latestTrip = $trips->sortByDesc('created_at')->first();
                return [
                    'vehicle' => optional($latestTrip->vehicle)->VehicleName,
                    'balance_end' => optional($latestTrip)->BalanceEnd,
                ];
            })
            ->filter(fn($vehicle) => !is_null($vehicle['vehicle']));

        $labels = $vehicles->pluck('vehicle')->toArray();
        $data = $vehicles->pluck('balance_end')->toArray();

        if (empty($labels) || empty($data)) {
            return [
                'datasets' => [
                    [
                        'label' => 'Fuel Balance',
                        'data' => [0],
                        'backgroundColor' => ['#e0e0e0'],
                    ],
                ],
                'labels' => ['No Data Available'],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Fuel Balance',
                    'data' => $data,
                    'backgroundColor' => [
                        '#ff6384',
                        '#36a2eb',
                        '#cc65fe',
                        '#ffce56',
                        '#4bc0c0',
                        '#9966ff',
                        '#ff9f40',
                        '#ffcd56',
                        '#c9cbcf',
                        '#8b0000',
                        '#ff4500',
                        '#ffd700',
                        '#9acd32',
                        '#32cd32',
                        '#00ced1',
                        '#4682b4',
                        '#6a5acd',
                        '#8a2be2',
                        '#d2691e',
                        '#ff1493'
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getChartOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'cutout' => '10%',
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
                'datalabels' => [
                    'display' => true,
                    'color' => '#fff', // Color of the label
                    'font' => [
                        'size' => 14, // Font size of the label
                        'weight' => 'bold',
                    ],
                    'formatter' => function ($value) {
                        return round($value); // Display rounded value
                    },
                ],
            ],
        ];
    }
}
