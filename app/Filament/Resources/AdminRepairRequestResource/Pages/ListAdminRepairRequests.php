<?php

namespace App\Filament\Resources\AdminRepairRequestResource\Pages;

use App\Filament\Resources\AdminRepairRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdminRepairRequests extends ListRecords
{
    protected static string $resource = AdminRepairRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
