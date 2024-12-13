<?php

namespace App\Filament\Resources\ReminderResource\Pages;

use App\Filament\Resources\ReminderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Card;

class ListReminders extends ListRecords
{
    protected static string $resource = ReminderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Reminder')
                ->icon('heroicon-o-folder-plus')
                ->tooltip('Create a new reminders'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Reminder Information')
                    ->icon('heroicon-o-bell-alert')
                    ->iconColor('primary')
                    ->schema([
                        TextEntry::make('vehicle.VehicleName')->label('Vehicle Name'),
                        TextEntry::make('vehicle.PlateNumber')->label('Plate Number'),
                        TextEntry::make('ReminderDate')->label('Reminder Date')->date(),
                        TextEntry::make('Remarks')->label('Expiring Documents'),
                        TextEntry::make('ReminderStatus')->label('Reminder Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'Pending' => 'warning',
                                'Sent' => 'warning',
                                'Acknowledged' => 'warning',
                                'Action Taken' => 'info',
                                'Done' => 'success',
                            })
                            ->icon(fn(string $state): string => match ($state) {
                                'Pending' => 'heroicon-o-clock', // Example icon for Pending
                                'Sent' => 'heroicon-o-check-circle', // Example icon for Sent
                                'Acknowledged' => 'heroicon-o-check-circle', // Example icon for Acknowledged
                                'Action Taken' => 'heroicon-o-pencil', // Example icon for Action Taken
                                'Done' => 'heroicon-o-check-badge', // Example icon for Overdue
                                default => 'heroicon-o-x-circle', // Default icon if none match
                            }),
                    ])->columns(3),

                Section::make([
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('updated_at')->dateTime(),
                ])->columns(2)->grow(false),
            ]);
    }
}
