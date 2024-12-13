<?php

namespace App\Filament\Resources\RepairAndMaintenanceHistoryResource\Pages;

use App\Filament\Resources\RepairAndMaintenanceHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRepairAndMaintenanceHistory extends EditRecord
{
    protected static string $resource = RepairAndMaintenanceHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
