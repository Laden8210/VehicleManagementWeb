<?php


namespace App\Filament\Resources\ExpensesResource\Widgets;

use App\Models\Expenses;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Fetch the initial Appropriation Budget from the first Expenses record
        $initialPreviousBalance = Expenses::orderBy('id')->first()->AppropriationBudget ?? 0; // Use AppropriationBudget

        // Total amounts for each type of expense
        $totalPurchaseOrder = Expenses::where('PaymentType', 'Purchased Order')->sum('TotalCost');
        $totalReplenishment = Expenses::where('PaymentType', 'Replenishment')->sum('TotalCost');
        $totalReimbursement = Expenses::where('PaymentType', 'Reimbursement')->sum('TotalCost');

        // Calculate total budget used
        $totalBudgetUsed = $totalPurchaseOrder + $totalReplenishment + $totalReimbursement;

        // Calculate the remaining balance
        $remainingBalance = $initialPreviousBalance - $totalBudgetUsed;

        // Calculate the percentage utilized
        $utilizationPercentage = $initialPreviousBalance > 0 ? ($totalBudgetUsed / $initialPreviousBalance) * 100 : 0;

        return [
            Stat::make('INITIAL BALANCE', '₱' . number_format($initialPreviousBalance, 2, '.', ','))
                ->description('Starting Balance')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success')
                ->chartColor('blue')
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #d69e2e; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/expenses'), // Adjust URL as necessary

            Stat::make('TOTAL PURCHASE ORDERS', '₱' . number_format($totalPurchaseOrder, 2, '.', ','))
                ->description('Total Amount for Purchase Orders')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success')
                ->chartColor('red')
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #d69e2e; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/expenses'),

            Stat::make('TOTAL REPLENISHMENTS', '₱' . number_format($totalReplenishment, 2, '.', ','))
                ->description('Total Amount for Replenishments')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success')
                ->chartColor('green')
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #d69e2e; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/expenses'),

            Stat::make('TOTAL REIMBURSEMENTS', '₱' . number_format($totalReimbursement, 2, '.', ','))
                ->description('Total Amount for Reimbursements')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success')
                ->chartColor('orange')
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #d69e2e; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/expenses'),

            Stat::make('REMAINING BALANCE', '₱' . number_format($remainingBalance, 2, '.', ','))
                ->description('Remaining Balance After Expenses')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success')
                ->chartColor('blue')
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #d69e2e; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/expenses'), // Adjust URL as necessary

            // New Stat for Total Utilization Percentage
            Stat::make('UTILIZATION', number_format($utilizationPercentage, 2) . '%')
                ->description('Percentage of Budget Utilized')
                ->descriptionIcon('heroicon-o-chart-pie')
                ->color('success') // You can choose a different color
                ->chartColor('orange')
                ->extraAttributes([
                    'class' => 'text-3xl',
                    'style' => 'color: #d69e2e; border: 1px solid #81e6d9; border-radius: 0.5rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 1.5rem;'
                ])
                ->url('/admin/expenses'), // Adjust URL as necessary
        ];
    }
}
