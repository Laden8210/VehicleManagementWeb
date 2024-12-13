<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use App\Models\Expenses;
use Carbon\Carbon;

class ExpensesChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Expenses';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = $this->getMonthlyExpenses();

        return [
            'datasets' => [
                [
                    'label' => 'Vehicle repairs and maintenance expenses per month',
                    'data' => array_values($data),  // Monthly totals for each month
                ],
            ],
            'labels' => array_keys($data),  // Month names for labels
        ];
    }

    // Fetch monthly expense totals from the database
    public function getMonthlyExpenses()
    {
        // Initialize all months with zero expenses
        $monthlyExpenses = collect(range(1, 12))->mapWithKeys(function ($month) {
            return [Carbon::create()->month($month)->format('F') => 0];
        });

        // Fetch expense data and replace zeroes with actual totals
        $expenses = Expenses::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(TotalCost) as total')
        )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::create()->month($item->month)->format('F') => $item->total];
            });

        // Merge expenses into the initialized months, keeping the order from January to December
        return $monthlyExpenses->merge($expenses)->toArray();
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public static function canView(): bool
    {
        return auth()->user()->hasRole('Admin');
    }
}
