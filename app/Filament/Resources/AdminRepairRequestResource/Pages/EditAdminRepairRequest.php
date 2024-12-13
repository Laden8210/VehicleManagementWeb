<?php

namespace App\Filament\Resources\AdminRepairRequestResource\Pages;

use App\Filament\Resources\AdminRepairRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminRepairRequest extends EditRecord
{
    protected static string $resource = AdminRepairRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
