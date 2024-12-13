<?php

namespace App\Filament\Resources\DispatchResource\Pages;

use App\Filament\Resources\DispatchResource;
use App\Filament\Resources\DispatchResource\Widgets\StatsOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Card;

class ListDispatches extends ListRecords
{
    protected static string $resource = DispatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Patient Request')
                ->icon('heroicon-o-folder-plus')
                ->tooltip('Create a new dispatches'),
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
                Section::make('Dispatch Details')
                    ->icon('heroicon-o-information-circle')
                    ->iconColor('primary')
                    ->schema([
                        TextEntry::make('RequestDate')->label('Request Date')->date(),
                        TextEntry::make('RequestorName')->label('Requestor Name'),
                        TextEntry::make('TravelDate')->label('Travel Date')->date(),
                        TextEntry::make('PickupTime')->label('Pickup Time'),
                        TextEntry::make('Destination'),
                        TextEntry::make('RequestStatus')->label('Request Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'Pending' => 'warning',
                                'Approved' => 'success',
                                'Disapproved' => 'danger',
                                'Cancelled' => 'gray',
                            }),
                        TextEntry::make('Remarks'),
                    ])->columns(3),

                Section::make([
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('updated_at')->dateTime(),
                ])->columns(2)->grow(false),
            ]);
    }
}
