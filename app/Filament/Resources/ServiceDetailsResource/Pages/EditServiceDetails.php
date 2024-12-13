<?php

namespace App\Filament\Resources\ServiceDetailsResource\Pages;

use App\Filament\Resources\ServiceDetailsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class EditServiceDetails extends EditRecord
{
    protected static string $resource = ServiceDetailsResource::class;

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
        ->title('Service Details Updated')
        ->info()
        ->body('Success! The service details has been successfully updated on ' . $formattedDateTime . '.')
        ->icon('heroicon-o-wrench')
        ->send()
        ->color('info')
        ->duration(5000);
    }
}
