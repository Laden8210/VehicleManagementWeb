<?php

namespace App\Filament\Resources\TripTicketResource\Pages;

use App\Filament\Resources\TripTicketResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Actions;

class ViewTripTicket extends ViewRecord
{
    protected static string $resource = TripTicketResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Trip Ticket Information')
                    ->icon('heroicon-o-information-circle')
                    ->iconColor('primary')
                    ->schema([
                        TextEntry::make('TripTicketNumber')->label('Trip Ticket Number'),
                        TextEntry::make('ArrivalDate')
                            ->label('Arrival Date')
                            ->date(),
                        TextEntry::make('ReturnDate')
                            ->label('Return Date')
                            ->date(),
                        TextEntry::make('vehicle.VehicleName')->label('Vehicle Name'),
                        TextEntry::make('user.name')->label('Driver Name'),
                        TextEntry::make('Responder Names')
                            ->label('Responder Names')
                            ->getStateUsing(fn($record) => $record->responder_names),
                        TextEntry::make('Destination'),
                        TextEntry::make('Purpose'),
                        TextEntry::make('KmBeforeTravel')->label('Km Before Travel'),
                        TextEntry::make('KmAfterTravel')->label('Km After Travel'),
                        TextEntry::make('DistanceTravelled')->label('Distance Travelled'),
                        TextEntry::make('TimeDeparture_A')->label('Time of Departure A')->Time(),
                        TextEntry::make('TimeArrival_A')->label('Time of Arrival A')->Time(),
                        TextEntry::make('TimeDeparture_B')->label('Time of Departure B')->Time(),
                        TextEntry::make('TimeArrival_B')->label('Time of Departure B')->Time(),
                    ])->columns(3),

                Section::make('Fuel Management')
                    ->icon('heroicon-o-percent-badge')
                    ->iconColor('primary')
                    ->schema([
                        TextEntry::make('BalanceStart')->label('Balance Start'),
                        TextEntry::make('IssuedFromOffice')->label('Issued From Office'),
                        TextEntry::make('AddedDuringTrip')->label('Added During The Trip'),
                        TextEntry::make('TotalFuelTank')->label('Total Fuel In Tank'),
                        TextEntry::make('FuelConsumption')->label('Fuel Consumption'),
                        TextEntry::make('BalanceEnd')->label('Balance End'),
                    ])->columns(3),

                Section::make([
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('updated_at')->dateTime(),
                ])->columns(2)->grow(false),
            ]);
    }


}
