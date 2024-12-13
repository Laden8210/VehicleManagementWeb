<?php

namespace App\Filament\Resources\PatientResource\Pages;

use App\Filament\Resources\PatientResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Card;


class ListPatients extends ListRecords
{
    protected static string $resource = PatientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Patient Request')
                ->icon('heroicon-o-folder-plus')
                ->tooltip('Create a new patients'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Patient Information')
                    ->icon('heroicon-o-information-circle')
                    ->iconColor('primary')
                    ->schema([
                        TextEntry::make('dispatch.RequestorName')->label('Requestor Name'),
                        TextEntry::make('PatientName')->label('Patient Name'),
                        TextEntry::make('Gender'),
                        TextEntry::make('Age'),
                        TextEntry::make('PatientNumber')->label('Patient Number'),
                        TextEntry::make('PatientAddress')->label('Patient Address'),
                        TextEntry::make('PatientDiagnosis')->label('Patient Diagnosis'),
                    ])->columns(3),

                Section::make('Assigned Personnel')
                    ->icon('heroicon-o-user-group')
                    ->iconColor('primary')
                    ->schema([
                        TextEntry::make('tripticket.TripTicketNumber')->label('Trip Ticket Number'),
                    ])->columns(2),

                Section::make([
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('updated_at')->dateTime(),
                ])->columns(2)->grow(false),
            ]);
    }
}
