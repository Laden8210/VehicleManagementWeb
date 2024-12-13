<?php

namespace App\Filament\Resources;

use App\Models\RepairRequest;

use App\Filament\Resources\RepairHistoryResource\Pages;
use App\Models\RepairHistory;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class RepairHistoryResource extends Resource
{
    protected static ?string $model = RepairHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?int $navigationSort = 18;

    protected static ?string $navigationGroup = 'History';

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('')
            ->emptyStateDescription('')
            ->columns([
                // Your columns here...
            ])
            ->filters([
                // Your filters here...
            ]);
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRepairHistories::route('/'),
        ];
    }
}
