<?php

namespace App\Filament\Resources\RepairRequestResource\Pages;

use App\Filament\Resources\RepairRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class EditRepairRequest extends EditRecord
{
    protected static string $resource = RepairRequestResource::class;

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
        ->title('Repair Request Updated')
        ->info()
        ->body('Success! The repair request has been successfully updated on ' . $formattedDateTime . '.')
        ->icon('heroicon-o-wrench-screwdriver')
        ->send()
        ->color('info')
        ->duration(5000);   
    }
}
