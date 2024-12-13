<?php

namespace App\Filament\Resources\ReminderResource\Pages;

use App\Filament\Resources\ReminderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use App\Models\Personnel;
use App\Models\PersonnelRolesResource;
use App\Notifications\ReminderNotification;
use Carbon\Carbon;

class EditReminder extends EditRecord
{
    protected static string $resource = ReminderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }

    protected function getRedirectUrl(): String
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
    $formattedDateTime = Carbon::now('Asia/Manila')->format('F j, Y, g:i a');

    return Notification::make()
        ->title('Reminder Updated')
        ->info()
        ->body('Success! The reminder has been successfully updated on ' . $formattedDateTime . '.')
        ->icon('heroicon-o-tag')
        ->send()
        ->color('info')
        ->duration(5000);
    }

    protected function afterSave(): void
    {
        $record = $this->record;

        // Assuming you have a way to identify the drivers
        $drivers = Personnel::whereHas('roles', function ($query) {
            $query->where('RoleName', 'Driver');
        })->get();

        // Send notification to each driver
        foreach ($drivers as $driver) {
            $driver->notify(new ReminderNotification($record)); // Use notify method
        }
    }
}
