<?php

namespace App\Filament\Resources\PersonnelRoleResource\Pages;

use App\Filament\Resources\PersonnelRoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class EditPersonnelRole extends EditRecord
{
    protected static string $resource = PersonnelRoleResource::class;

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
        ->title('Personnel Role Updated')
        ->info()
        ->body('Success! The personnel role has been successfully updated on ' . $formattedDateTime . '.')
        ->icon('heroicon-o-user-plus')
        ->send()
        ->color('info')
        ->duration(5000);
    }
}
