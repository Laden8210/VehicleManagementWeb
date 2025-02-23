<?php

namespace App\Filament\Resources\PatientResource\Pages;

use App\Filament\Resources\PatientResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class EditPatient extends EditRecord
{
    protected static string $resource = PatientResource::class;

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
        ->title('Patient Request Updated')
        ->info()
        ->body('Success! The patient request has been successfully updated on ' . $formattedDateTime . '.')
        ->icon('heroicon-o-pencil-square')
        ->send()
        ->color('info')
        ->duration(5000);
    }
}
