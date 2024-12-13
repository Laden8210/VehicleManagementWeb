<?php

namespace App\Filament\Resources\BorrowerResource\Pages;

use App\Filament\Resources\BorrowerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class EditBorrower extends EditRecord
{
    protected static string $resource = BorrowerResource::class;

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
        ->title('Borrower Updated')
        ->info()
        ->body('Success! The borrower has been successfully updated on ' . $formattedDateTime . '.')
        ->icon('heroicon-o-archive-box-arrow-down')
        ->send()
        ->color('info')
        ->duration(5000);
    }
}
