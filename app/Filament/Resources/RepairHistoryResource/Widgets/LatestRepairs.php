<?php

namespace App\Filament\Resources\RepairHistoryResource\Widgets;

use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\ServiceDetailsResource;

class LatestRepairs extends BaseWidget
{
    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'full';

    // Explicitly setting the title of the widget
    public static function getTitle(): string
    {
        return 'Repair Histories'; // Desired title
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ServiceDetailsResource::getEloquentQuery()) // Update to your model
            ->defaultPaginationPageOption(25)
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('repairrequests.RRNumber')
                    ->label('Repair Req. #')
                    ->searchable(),
                TextColumn::make('repairrequests.ReportedIssue')
                    ->label('Reported Issue')
                    ->searchable(),
                TextColumn::make('repairrequests.Issues')
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
                TextColumn::make('repairrequests.vehicle.VehicleName')
                    ->label('Vehicle')
                    ->searchable(),
                TextColumn::make('repairrequests.vehicle.MvfileNo')
                    ->label('Plate #')
                    ->searchable(),
                TextColumn::make('repairrequests.user.name')
                    ->label('Requested By')
                    ->searchable(),
                TextColumn::make('suppliers.SupplierName')
                    ->label('Supplier Name')
                    ->searchable(),
                TextColumn::make('RepairDate')
                    ->label('Repair Date')
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
