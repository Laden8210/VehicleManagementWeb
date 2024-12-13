<?php

namespace App\Filament\Resources\RequestResource\Pages;

use App\Filament\Resources\RequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Card;

class ListRequests extends ListRecords
{
    protected static string $resource = RequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Borrower Request')
                ->icon('heroicon-o-folder-plus')
                ->tooltip('Create a new requests'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Borrower Request Details')
                    ->icon('heroicon-o-information-circle')
                    ->iconColor('primary')
                    ->schema([
                        TextEntry::make('GTNumber')->label('Gate Pass Number'),
                        TextEntry::make('borrower.BorrowerName')->label('Borrower Name'),
                        TextEntry::make('inventory.ItemName')->label('Item Name'),
                        TextEntry::make('inventory.ItemQuantity'),
                        TextEntry::make('NumberOfItems')->label('Request Quantity'),
                        TextEntry::make('RequestDate')->label('Request Date')->date(),
                        TextEntry::make('ReturnDate')->label('ReturnDate')->date(),
                        TextEntry::make('Purpose'),
                        TextEntry::make('RequestStatus')->label('Request Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'Approved' => 'success',
                                'Returned' => 'info',
                            }),
                    ])->columns(3),

                Section::make([
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('updated_at')->dateTime(),
                ])->columns(2)->grow(false),
            ]);
    }
}
