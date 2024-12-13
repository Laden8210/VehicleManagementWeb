<?php

namespace App\Filament\Resources\ReminderResource\Pages;

use App\Filament\Resources\ReminderResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Reminder;
use App\Notifications\ReminderCreated;
use Filament\Pages\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Actions\Action;


class CreateReminder extends CreateRecord
{
    protected static string $resource = ReminderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate()
    {

        $selectedUser = User::find($this->record->user_id);

        if ($selectedUser) {
            Notification::make()
                ->title('New Reminder Created')
                ->body('You have a new reminder. Click here to view it.')
                ->success()
                ->actions([
                    Action::make('view')
                        ->button()
                        ->url(ReminderResource::getUrl('index', ['record' => $this->record]))
                        ->markAsRead(),
                ])
                ->sendToDatabase($selectedUser)
                ->send();
        }

    }

    protected function getCreatedNotification(): ?Notification
    {
        $formattedDateTime = Carbon::now('Asia/Manila')->format('F j, Y, g:i a');

        return Notification::make()
            ->title('Reminder Added')
            ->success()
            ->body('Success! The reminder has been successfully added on ' . $formattedDateTime . '.')
            ->icon('heroicon-o-tag')
            ->send()
            ->color('success')
            ->duration(5000);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = $data['user_id'] ?? Auth::id();
        return $data;
    }



}
