<?php

namespace App\Filament\Resources\VehicleRemarksResource\Pages;

use App\Filament\Resources\VehicleRemarksResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class EditVehicleRemarks extends EditRecord
{
    protected static string $resource = VehicleRemarksResource::class;

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
        ->title('Remark Updated')
        ->info()
        ->body('Success! The remark has been successfully updated on ' . $formattedDateTime . '.')
        ->icon('heroicon-o-truck')
        ->send()
        ->color('info')
        ->duration(5000);
    }
}
