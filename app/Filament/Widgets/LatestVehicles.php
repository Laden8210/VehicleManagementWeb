<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\VehicleResource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestVehicles extends BaseWidget
{

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(VehicleResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('VehicleName')
                    ->label('Vehicle Name')
                    ->searchable(),
                TextColumn::make('PlateNumber')
                    ->label('Plate Number')
                    ->searchable(),
                TextColumn::make('Make')
                    ->searchable(),
                TextColumn::make('Series')
                    ->searchable(),
                TextColumn::make('PropertyNumber')
                    ->label('Property Number')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Added On')
                    ->date()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Last Updated On')
                    ->date()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
            ]);
    }
    public static function canView(): bool
    {
        return auth()->user()->hasRole('Admin');
    }
}
