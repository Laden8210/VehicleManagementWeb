<?php

namespace App\Filament\Resources\ExpensesResource\Pages;

use App\Filament\Resources\ExpensesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Carbon\Carbon;
use Filament\Notifications\Notification;

class EditExpenses extends EditRecord
{
    protected static string $resource = ExpensesResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {

        $formattedDateTime = Carbon::now('Asia/Manila')->format('F j, Y, g:i a');

        return Notification::make()
            ->title('Expenses Updated')
            ->info()
            ->body('Success! The expenses has been successfully updated on ' . $formattedDateTime . '.')
            ->icon('heroicon-o-currency-dollar')
            ->send()
            ->color('info')
            ->duration(5000);
    }
}
