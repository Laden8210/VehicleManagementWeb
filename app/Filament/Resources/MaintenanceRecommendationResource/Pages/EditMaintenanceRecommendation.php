<?php

namespace App\Filament\Resources\MaintenanceRecommendationResource\Pages;

use App\Filament\Resources\MaintenanceRecommendationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class EditMaintenanceRecommendation extends EditRecord
{
    protected static string $resource = MaintenanceRecommendationResource::class;

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
        ->title('Maintenance Recommendation Updated')
        ->info()
        ->body('Success! The maintenance recommendation has been successfully updated on ' . $formattedDateTime . '.')
        ->icon('heroicon-o-exclamation-triangle')
        ->send()
        ->color('info')
        ->duration(5000);
    }
}
