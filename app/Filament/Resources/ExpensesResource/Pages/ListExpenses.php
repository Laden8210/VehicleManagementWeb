<?php

namespace App\Filament\Resources\ExpensesResource\Pages;

use App\Filament\Resources\ExpensesResource;
use App\Filament\Resources\ExpensesResource\Widgets\StatsOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Card;

class ListExpenses extends ListRecords
{
    protected static string $resource = ExpensesResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class
        ];
    }
    protected function getFooterWidgets(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Expenses')
                ->icon('heroicon-o-folder-plus')
                ->tooltip('Create a new expenses'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Vehicle Information')
                    ->icon('heroicon-o-information-circle')
                    ->iconColor('primary')
                    ->schema([
                        TextEntry::make('repairrequests.RRNumber')->label('R-R Number'),
                        TextEntry::make('maintenancerecommendations.MRNumber')->label('M-R Number'),
                        TextEntry::make('RepairMaintenanceDate')->label('Repair / Maintenance Date'),
                        TextEntry::make('Description'),
                        TextEntry::make('AppropriationBudget')->label('Appropriation Budget')->formatStateUsing(fn($state) => '₱' . number_format($state, 2, '.', ',')),
                        TextEntry::make('TotalCost')->label('Total Cost')->formatStateUsing(fn($state) => '₱' . number_format($state, 2, '.', ',')),
                        TextEntry::make('AppropriationBalance')->label('Appropriation Balance')->formatStateUsing(fn($state) => '₱' . number_format($state, 2, '.', ',')),
                        TextEntry::make('PaymentType')->label('Payment Type'),
                        TextEntry::make('PaymentStatus')->label('Payment Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'Pending' => 'warning',
                                'In Progress' => 'info',
                                'Paid' => 'success'
                            }),
                        TextEntry::make('DvNumber')->label('Disbursement Voucher Number'),
                    ])->columns(3),

                Section::make([
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('updated_at')->dateTime(),
                ])->columns(2)->grow(false),
            ]);
    }
}
