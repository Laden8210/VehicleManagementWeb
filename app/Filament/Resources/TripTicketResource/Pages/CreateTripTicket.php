<?php

namespace App\Filament\Resources\TripTicketResource\Pages;

use App\Filament\Resources\TripTicketResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Carbon\Carbon;
use Filament\Resources\Actions\ActionGroup;
use Filament\Resources\Actions\Action;

class CreateTripTicket extends CreateRecord
{
    protected static string $resource = TripTicketResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Remove any keys with empty values to avoid validation errors
        return array_filter($data, function ($value) {
            return $value !== null && $value !== '';
        });
    }

    protected function beforeWizardComplete(): void
    {
        $this->save();
    }

    protected function afterSave()
    {
        // After the trip ticket is created, save the responders
        $this->saveResponders();
    }

    protected function saveResponders()
    {
        // Get the created trip ticket
        $tripTicket = $this->record;

        // Save the responders
        if ($this->responders) {
            foreach ($this->responders as $responder) {
                $tripTicket->responders()->attach($responder['responder_id']);
            }
        }
    }

    protected function getCreatedNotification(): ?Notification
    {
        $formattedDateTime = Carbon::now('Asia/Manila')->format('F j, Y, g:i a');

        return Notification::make()
            ->title('Trip Ticket Added')
            ->success()
            ->body('Success! The trip ticket has been successfully added on ' . $formattedDateTime . '.')
            ->icon('heroicon-o-ticket')
            ->color('success')
            ->duration(5000);
    }
}
