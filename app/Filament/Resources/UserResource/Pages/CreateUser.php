<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {

        $formattedDateTime = Carbon::now('Asia/Manila')->format('F j, Y, g:i a');

        return Notification::make()
            ->title('User Added')
            ->success()
            ->body('Success! The user has been successfully added on ' . $formattedDateTime . '.')
            ->icon('heroicon-o-user-plus')
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
