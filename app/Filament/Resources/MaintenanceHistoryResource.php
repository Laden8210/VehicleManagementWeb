<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceHistoryResource\Pages;
use App\Models\MaintenanceHistory;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class MaintenanceHistoryResource extends Resource
{
    protected static ?string $model = MaintenanceHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

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
            'index' => Pages\ListMaintenanceHistories::route('/'),
        ];
    }
}
