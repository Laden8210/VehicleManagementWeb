<?php

namespace App\Filament\Resources\RepairRequestResource\Pages;

use App\Filament\Resources\RepairRequestResource;
use Filament\Actions;
use App\Models\RepairRequest;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Carbon\Carbon;
use App\Models\User;
use Filament\Notifications\Actions\Action;

class CreateRepairRequest extends CreateRecord
{
    protected static string $resource = RepairRequestResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        $formattedDateTime = Carbon::now('Asia/Manila')->format('F j, Y, g:i a');

        return Notification::make()
            ->title('Repair Request Added')
            ->success()
            ->body('Success! The repair request has been successfully added on ' . $formattedDateTime . '.')
            ->icon('heroicon-o-wrench-screwdriver')
            ->send()
            ->color('success')
            ->duration(5000);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['RequestStatus'] = 'Pending'; // Set default status
        return $data;
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

    protected function afterCreate()
    {
        // Retrieve all users with the 'Admin' role
        $adminUsers = User::role('Admin')->get(); // Requires Spatie's Laravel Permission

        foreach ($adminUsers as $admin) {
            Notification::make()
                ->title('New Repair Request Created')
                ->body('A new repair request has been created. Click here to view it.')
                ->success()
                ->actions([
                    Action::make('view')
                        ->button()
                        ->url(RepairRequestResource::getUrl('index', ['record' => $this->record]))
                        ->markAsRead(),
                ])
                ->sendToDatabase($admin)
                ->send();
        }

    }


}
