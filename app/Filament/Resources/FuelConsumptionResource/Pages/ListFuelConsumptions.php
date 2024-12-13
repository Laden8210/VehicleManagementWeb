<?php

namespace App\Filament\Resources\FuelConsumptionResource\Pages;

use App\Filament\Resources\FuelConsumptionResource;
use App\Filament\Resources\FuelConsumptionResource\Widgets\StatsOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;

class ListFuelConsumptions extends ListRecords
{
    protected static string $resource = FuelConsumptionResource::class;

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
                ->label('New Fuel Request')
                ->icon('heroicon-o-folder-plus')
                ->tooltip('Create a new consumptions'),
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
                        TextEntry::make('WithdrawalSlipNo')->label('Withdrawal Slip No'),
                        TextEntry::make('PONum')->label('Purchased Order Number'),
                        TextEntry::make('RequestDate')->label('Request Date')->date(),
                        TextEntry::make('ReferenceNumber')->label('Reference Number'),
                        TextEntry::make('tripticket.TripTicketNumber')->label('Trip Ticket Number'),
                        TextEntry::make('Quantity'),
                        TextEntry::make('Price'),
                        TextEntry::make('Amount'),
                        TextEntry::make('PreviousBalance')->label('Previous Balance'),
                        TextEntry::make('RemainingBalance')->label('Remaining Balance'),
                        TextEntry::make('ReferenceNumber')->label('Reference Number'),
                    ])->columns(3),

                Section::make([
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('updated_at')->dateTime(),
                ])->columns(2)->grow(false),
            ]);
    }
}
