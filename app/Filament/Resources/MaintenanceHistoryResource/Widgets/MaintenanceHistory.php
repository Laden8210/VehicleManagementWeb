<?php

namespace App\Filament\Resources\MaintenanceHistoryResource\Widgets;

use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\ServiceRecordsResource;

class MaintenanceHistory extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(ServiceRecordsResource::getEloquentQuery()) // Update to your model
            ->defaultPaginationPageOption(25)
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('maintenancerecommendation.MRNumber')
                    ->label('Main. Rec. #')
                    ->searchable(),
                TextColumn::make('MaintenanceType')
                    ->label('Maintenance Type')
                    ->searchable(),
                TextColumn::make('maintenancerecommendation.Issues')
                    ->label('Issue Description')
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        // Decode the JSON string into an array
                        $issuesArray = json_decode($state, true); // The second argument true converts it to an associative array
            
                        // Check if decoding was successful and the result is an array
                        if (is_array($issuesArray)) {
                            // Map through the issues and get the descriptions
                            return implode(', ', array_map(fn($issue) => $issue['IssueDescription'], $issuesArray));
                        }

                        // If not an array, return the state as is or handle accordingly
                        return $state; // Adjust this return value based on what you want to show if decoding fails
                    }),
                TextColumn::make('maintenancerecommendation.vehicle.VehicleName')
                    ->label('Vehicle')
                    ->searchable(),
                TextColumn::make('maintenancerecommendation.vehicle.MvfileNo')
                    ->label('Plate #')
                    ->searchable(),
                TextColumn::make('maintenancerecommendation.user.name')
                    ->label('Requested By')
                    ->searchable(),
                TextColumn::make('suppliers.SupplierName')
                    ->label('Supplier Name')
                    ->searchable(),
                TextColumn::make('MaintenanceDate')
                    ->label('Maint. Date')
                    ->date()
                    ->searchable(),
                TextColumn::make('ServiceCosts')
                    ->label('Service Cost')
                    ->formatStateUsing(fn($state) => '₱' . number_format($state, 2, '.', ','))
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
}
