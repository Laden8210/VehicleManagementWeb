<?php

namespace App\Filament\Resources\VehicleRemarksResource\Pages;


use App\Filament\Resources\VehicleRemarksResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Card;

class ListVehicleRemarks extends ListRecords
{
    protected static string $resource = VehicleRemarksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Vehicle Remarks')
            ->icon('heroicon-o-folder-plus') 
            ->tooltip('Create a new vehicle remarks'),
        ];
    }
    
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
            Section::make('Vehicle Remarks')
            ->icon('heroicon-o-information-circle')
            ->iconColor('primary') 
                ->schema([
                    TextEntry::make('vehicle.VehicleName')
                    ->label('Vehicle Name'),
                    TextEntry::make('vehicle.MvfileNo')
                    ->label('Plate Number'),

                    TextEntry::make('VehicleRemarks')
                    ->label('Vehicle Remarks')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Serviceable' => 'success',
                        'Unserviceable' => 'danger',
                        'Under Maintenance' => 'warning',
                        'For PRS' => 'gray',
                        })
                    ])->columns(3),

            Section::make([
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('updated_at')->dateTime(),
                    ])->columns(2)->grow(false),
            ]);
    }

}
