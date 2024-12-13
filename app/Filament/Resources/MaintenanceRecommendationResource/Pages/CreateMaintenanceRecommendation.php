<?php

namespace App\Filament\Resources\MaintenanceRecommendationResource\Pages;

use App\Filament\Resources\MaintenanceRecommendationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Carbon\Carbon;
use App\Models\User;
use Filament\Notifications\Actions\Action;

class CreateMaintenanceRecommendation extends CreateRecord
{
    protected static string $resource = MaintenanceRecommendationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {

        $formattedDateTime = Carbon::now('Asia/Manila')->format('F j, Y, g:i a');

        return Notification::make()
            ->title('Maintenance Recommendation Added')
            ->success()
            ->body('Success! The maintenance recommendation has been successfully added on ' . $formattedDateTime . '.')
            ->icon('heroicon-o-exclamation-triangle')
            ->send()
            ->color('success')
            ->duration(5000);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['RequestStatus'] = 'Pending';
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

        $adminUsers = User::role('Admin')->get();

        foreach ($adminUsers as $admin) {
            Notification::make()
                ->title('New Maintenance Recommendation Created')
                ->body('A new maintenance recommendation has been created. Click here to view it.')
                ->success()
                ->actions([
                    Action::make('view')
                        ->button()
                        ->url(MaintenanceRecommendationResource::getUrl('index', ['record' => $this->record]))
                        ->markAsRead(),
                ])
                ->sendToDatabase($admin)
                ->send();
        }

    }

    protected function afterAction($record)
    {

        $driver = User::find($record->user_id);

        if ($driver) {
            Notification::make()
                ->title('Maintenance Recommendation Update')
                ->body('Your maintenance recommendation has been updated. Check for details.')
                ->success()
                ->actions([
                    Action::make('view')
                        ->button()
                        ->url(route('maintenance-recommendations.view', ['record' => $record->id]))
                        ->markAsRead(),
                ])
                ->sendToDatabase($driver);
        }

    }
}
