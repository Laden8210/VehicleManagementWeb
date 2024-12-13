<?php

namespace App\Filament\Resources\PersonnelRoleResource\Pages;

use App\Filament\Resources\PersonnelRoleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class CreatePersonnelRole extends CreateRecord
{
    protected static string $resource = PersonnelRoleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        $formattedDateTime = Carbon::now('Asia/Manila')->format('F j, Y, g:i a');

        return Notification::make()
            ->title('Personnel Role Added')
            ->success()
            ->body('Success! The personnel role has been successfully added on ' . $formattedDateTime . '.')
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
