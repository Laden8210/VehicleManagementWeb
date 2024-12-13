<?php

namespace App\Filament\Resources\ServiceRecordsResource\Pages;

use App\Filament\Resources\ServiceRecordsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Card;

class ListServiceRecords extends ListRecords
{
    protected static string $resource = ServiceRecordsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Service Records')
            ->icon('heroicon-o-folder-plus') 
            ->tooltip('Create a new service records'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
            Section::make('Vehicle Service Record')
            ->icon('heroicon-o-document-duplicate')
            ->iconColor('primary') 
                ->schema([
                    TextEntry::make('suppliers.SupplierName')->label('Supplier Name'),
                    TextEntry::make('MaintenanceDate')->label('Maintenance Date'),
                    TextEntry::make('MaintenanceType')->label('Maintenance Type'),
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
