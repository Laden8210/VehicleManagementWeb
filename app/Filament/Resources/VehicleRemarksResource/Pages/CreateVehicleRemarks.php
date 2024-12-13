<?php

namespace App\Filament\Resources\VehicleRemarksResource\Pages;

use App\Filament\Resources\VehicleRemarksResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class CreateVehicleRemarks extends CreateRecord
{
    protected static string $resource = VehicleRemarksResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {

        $formattedDateTime = Carbon::now('Asia/Manila')->format('F j, Y, g:i a');

        return Notification::make()
            ->title('Remark Added')
            ->success()
            ->body('Success! The remark has been successfully added on ' . $formattedDateTime . '.')
            ->icon('heroicon-o-truck')
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
