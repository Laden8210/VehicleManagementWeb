<?php

namespace App\Filament\Resources\BorrowerResource\Pages;

use App\Filament\Resources\BorrowerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Card;

class ListBorrowers extends ListRecords
{
    protected static string $resource = BorrowerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Borrower')
            ->icon('heroicon-o-folder-plus') 
            ->tooltip('Create a new borrowers'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
            Section::make('Borrower Information')
            ->icon('heroicon-o-information-circle')
            ->iconColor('primary') 
                ->schema([
                    TextEntry::make('BorrowerName')->label('Borrower Name'),
                    TextEntry::make('BorrowerAddress')->label('Borrower Address'),
                    TextEntry::make('BorrowerNumber')->label('Borrower Number'),
                    TextEntry::make('BorrowerEmail')->label('Borrower Email'),
                    ImageEntry::make('BorrowerIDPresented')->label('Borrower ID Presented')->square(),
                    ])->columns(3),

            Section::make([
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('updated_at')->dateTime(),
                    ])->columns(2)->grow(false),
            ]);
    }
}
