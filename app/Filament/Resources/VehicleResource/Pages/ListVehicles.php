<?php

namespace App\Filament\Resources\VehicleResource\Pages;

use App\Filament\Resources\VehicleResource;
use App\Filament\Resources\VehicleResource\Widgets\StatsOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Card;

class ListVehicles extends ListRecords
{
    protected static string $resource = VehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Vehicle')
                ->icon('heroicon-o-folder-plus')
                ->tooltip('Create a new vehicle')

        ];
    }

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

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Vehicle Information')
                    ->icon('heroicon-o-information-circle')
                    ->iconColor('primary')
                    ->schema([
                        TextEntry::make('VehicleName')->label('Vehicle Name'),
                        TextEntry::make('MvfileNo')->label('MV File No'),
                        TextEntry::make('PlateNumber')->label('Plate Number'),
                        TextEntry::make('EngineNumber')->label('Engine Number'),
                        TextEntry::make('ChassisNumber')->label('Chassis Number'),
                        TextEntry::make('Fuel'),
                        TextEntry::make('Make'),
                        TextEntry::make('Series'),
                        TextEntry::make('BodyType')->label('Body Type'),
                        TextEntry::make('YearModel')->label('Year Model'),
                        TextEntry::make('Color'),
                    ])->columns(3),

                Section::make('Vehicle Registration')
                    ->icon('heroicon-o-calendar-days')
                    ->iconColor('primary')
                    ->schema([
                        TextEntry::make('PurchasedDate')->label('Purchased Date')->date(),
                        TextEntry::make('RegistrationDate')->label('Registration Date')->date(),
                        TextEntry::make('OrcrNo')->label('OR CR No'),
                        TextEntry::make('PurchasedCost')->label('Purchased Cost'),
                        TextEntry::make('PropertyNumber')->label('Property Number'),
                    ])->columns(3),
                Section::make([
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('updated_at')->dateTime(),
                ])->columns(2)->grow(false),
            ]);
    }

}
