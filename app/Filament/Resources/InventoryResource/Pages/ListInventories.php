<?php

namespace App\Filament\Resources\InventoryResource\Pages;

use App\Filament\Resources\InventoryResource;
use App\Filament\Resources\InventoryResource\Widgets\StatsOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Card;

class ListInventories extends ListRecords
{
    protected static string $resource = InventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Inventory')
                ->icon('heroicon-o-folder-plus')
                ->tooltip('Create a new inventories'),
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
                Section::make('Inventory Item Information')
                    ->icon('heroicon-o-information-circle')
                    ->iconColor('primary')
                    ->schema([
                        TextEntry::make('ItemName')->label('Item Name'),
                        TextEntry::make('ItemCode')->label('Item Code'),
                        TextEntry::make('ItemDescription')->label('Item Description'),
                        TextEntry::make('ItemUnit')->label('Item Unit'),
                        TextEntry::make('ItemQuantity')->label('Item Quantity'),
                        TextEntry::make('ExpirationDate')->label('Expiration Date')->date(),
                        TextEntry::make('ItemStatus')
                            ->label('Item Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'Available' => 'success',
                                'Not Available' => 'danger',
                            }),
                        ImageEntry::make('ItemImage')->label('Item Image')->square(),
                    ])->columns(3),

                Section::make([
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('updated_at')->dateTime(),
                ])->columns(2)->grow(false),
            ]);
    }
}
