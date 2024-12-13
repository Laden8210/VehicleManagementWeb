<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use App\Models\Dispatch;

class ProductsChart extends ChartWidget
{
    protected static ?string $heading = 'Patient Transport Services';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = $this->getPatientsPerMonth();

        return [

            'datasets' => [

                [
                    'label' => 'Patient transported per Month',
                    'data' => $data['patientsPerMonth'],

                ],
            ],
            'labels' => $data['months'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getPatientsPerMonth(): array
    {
        $now = Carbon::now();

        $patientsPerMonth = [];
        $months = [];


        foreach (range(1, 12) as $month) {

            $formattedMonth = $now->copy()->month($month)->format('Y-m');

            $count = Dispatch::whereMonth('created_at', $month)->whereYear('created_at', $now->year)->count();

            $patientsPerMonth[] = $count;
            $months[] = $now->copy()->month($month)->format('M');
        }

        return [
            'patientsPerMonth' => $patientsPerMonth,
            'months' => $months,
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->hasRole('Admin');
    }

}
