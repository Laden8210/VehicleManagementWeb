<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

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
        ->title('Category Updated')
        ->info()
        ->body('Success! The category has been successfully updated on ' . $formattedDateTime . '.')
        ->icon('heroicon-o-chart-bar-square')
        ->send()
        ->color('info')
        ->duration(5000);
    }
}
