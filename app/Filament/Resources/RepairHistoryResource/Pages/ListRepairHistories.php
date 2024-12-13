<?php

namespace App\Filament\Resources\RepairHistoryResource\Pages;

use App\Filament\Resources\RepairHistoryResource;
use App\Filament\Resources\RepairHistoryResource\Widgets\LatestRepairs;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRepairHistories extends ListRecords
{
    protected static string $resource = RepairHistoryResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            LatestRepairs::class
        ];
    }
    protected function getFooterWidgets(): array
    {
        return [];
    }
}
