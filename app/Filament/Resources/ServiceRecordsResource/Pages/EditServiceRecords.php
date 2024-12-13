<?php

namespace App\Filament\Resources\ServiceRecordsResource\Pages;

use App\Filament\Resources\ServiceRecordsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class EditServiceRecords extends EditRecord
{
    protected static string $resource = ServiceRecordsResource::class;

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
        ->title('Service Record Updated')
        ->info()
        ->body('Success! The service record has been successfully updated on ' . $formattedDateTime . '.')
        ->icon('heroicon-o-pencil-square')
        ->send()
        ->color('info')
        ->duration(5000);
    }
}
