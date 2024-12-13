<?php

namespace App\Filament\Resources\FuelConsumptionResource\Pages;

use App\Filament\Resources\FuelConsumptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class EditFuelConsumption extends EditRecord
{
    protected static string $resource = FuelConsumptionResource::class;

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
        ->title('Fuel Consumption Updated')
        ->info()
        ->body('Success! The fuel consumption has been successfully updated on ' . $formattedDateTime . '.')
        ->icon('heroicon-o-funnel')
        ->send()
        ->color('info')
        ->duration(5000);
    }
}