<?php

namespace App\Filament\Resources\MaintenanceHistoryResource\Pages;

use App\Filament\Resources\MaintenanceHistoryResource;
use App\Filament\Resources\MaintenanceHistoryResource\Widgets\MaintenanceHistory;
use Filament\Resources\Pages\ListRecords;

class ListMaintenanceHistories extends ListRecords
{
    protected static string $resource = MaintenanceHistoryResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            MaintenanceHistory::class
        ];
    }

}
