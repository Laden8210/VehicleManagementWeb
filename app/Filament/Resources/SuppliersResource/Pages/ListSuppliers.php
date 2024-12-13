<?php

namespace App\Filament\Resources\SuppliersResource\Pages;

use App\Filament\Resources\SuppliersResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Card;

class ListSuppliers extends ListRecords
{
    protected static string $resource = SuppliersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-folder-plus')
                ->tooltip('Create a new suppliers'),
        ];
    }


    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Supplier Information')
                    ->icon('heroicon-o-information-circle')
                    ->iconColor('primary')
                    ->schema([
                        TextEntry::make('SupplierName')->label('Supplier Name'),
                        TextEntry::make('ContactPerson')->label('Contact Person'),
                        TextEntry::make('Designation'),
                        TextEntry::make('MobileNumber')->label('Mobile Number'),
                        TextEntry::make('CompleteAddress')->label('Complete Address'),
                        TextEntry::make('EmailAddress')->label('Email Address'),
                        TextEntry::make('YearEstablished')->label('Year Established'),
                        TextEntry::make('PhilgepsMembership')->label('PhilGEPS Membership')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'Red' => 'danger',
                                'Platinum' => 'gray',
                            }),
                    ])->columns(3),

                Section::make([
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('updated_at')->dateTime(),
                ])->columns(2)->grow(false),
            ]);
    }
}
