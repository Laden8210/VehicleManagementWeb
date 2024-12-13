<?php

namespace App\Filament\Resources\ServiceDetailsResource\Pages;

use App\Filament\Resources\ServiceDetailsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class CreateServiceDetails extends CreateRecord
{
    protected static string $resource = ServiceDetailsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        $formattedDateTime = Carbon::now('Asia/Manila')->format('F j, Y, g:i a');

        return Notification::make()
            ->title('Service Details Added')
            ->success()
            ->body('Success! The service details has been successfully added on ' . $formattedDateTime . '.')
            ->icon('heroicon-o-wrench')
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