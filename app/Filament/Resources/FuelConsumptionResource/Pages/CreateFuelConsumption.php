<?php

namespace App\Filament\Resources\FuelConsumptionResource\Pages;

use App\Filament\Resources\FuelConsumptionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class CreateFuelConsumption extends CreateRecord
{
    protected static string $resource = FuelConsumptionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {

        $formattedDateTime = Carbon::now('Asia/Manila')->format('F j, Y, g:i a');

        return Notification::make()
            ->title('Fuel Consumption Added')
            ->success()
            ->body('Success! The fuel consumption has been successfully added on ' . $formattedDateTime . '.')
            ->icon('heroicon-o-funnel')
            ->send()
            ->color('success')
            ->duration(5000);
    }

    protected function getCreateFormAction(): Actions\Action
    {
        return parent::getCreateFormAction()
            ->submit(null)
            ->requiresConfirmation()
            ->action(function () {
                $this->closeActionModal();
                $this->create();
            });
    }
}
