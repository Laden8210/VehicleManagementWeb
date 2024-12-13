<?php

namespace App\Filament\Resources\RepairHistoryResource\Pages;

use App\Filament\Resources\RepairHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRepairHistory extends EditRecord
{
    protected static string $resource = RepairHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
