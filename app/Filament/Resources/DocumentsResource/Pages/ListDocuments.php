<?php

namespace App\Filament\Resources\DocumentsResource\Pages;

use App\Filament\Resources\DocumentsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Card;

class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Documents')
                ->icon('heroicon-o-folder-plus')
                ->tooltip('Create a new documents'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Document Details')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->iconColor('primary')
                    ->schema([
                        TextEntry::make('vehicle.VehicleName')->label('Vehicle Name'),
                        TextEntry::make('vehicle.VehicleName')->label('Vehicle MvfileNo'),
                        TextEntry::make('reminder.Remarks')->label('Renewed Documents'),
                        TextEntry::make('DocumentType')->label('Document Type'),
                        TextEntry::make('DocumentNumber')->label('Document Number'),
                        TextEntry::make('IssueDate')->label('Issue Date')->date(),
                        TextEntry::make('ExpirationDate')->label('Expiration Date')->date(),
                    ])->columns(3),

                Section::make([
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('updated_at')->dateTime(),
                ])->columns(2)->grow(false),
            ]);
    }
}
