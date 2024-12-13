<?php

namespace App\Filament\Resources\ServiceDetailsResource\Pages;

use App\Filament\Resources\ServiceDetailsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Card;

class ListServiceDetails extends ListRecords
{
    protected static string $resource = ServiceDetailsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Service Details')
            ->icon('heroicon-o-folder-plus') 
            ->tooltip('Create a new service details'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
            Section::make('Vehicle Service Details')
            ->icon('heroicon-o-wrench')
            ->iconColor('primary') 
                ->schema([
                    TextEntry::make('suppliers.SupplierName')->label('Supplier Name'),
                    TextEntry::make('RepairDate')->label('Repair Date'),
                    TextEntry::make('RepairType')->label('Repair Type'),
                    TextEntry::make('ServiceDescription')->label('Service Description'),
                    TextEntry::make('ChangedParts')->label('Changed Parts'),
                    TextEntry::make('ServiceCosts')->label('Service Costs'),
                    ])->columns(3),

            Section::make([
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('updated_at')->dateTime(),
                    ])->columns(2)->grow(false),
            ]);
    }
}
