<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\InventoryResource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestInventories extends BaseWidget
{
    protected static ?int $sort = 8;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(InventoryResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('ItemCode')
                    ->label('Item Code')
                    ->searchable(),
                TextColumn::make('ItemName')
                    ->label('Item Name')
                    ->searchable(),
                TextColumn::make('ItemUnit')
                    ->label('Item Unit')
                    ->searchable(),
                TextColumn::make('ItemQuantity')
                    ->label('Item Quantity')
                    ->searchable(),
                TextColumn::make('ItemDescription')
                    ->label('Item Description')
                    ->searchable(),
                TextColumn::make('ItemStatus')
                    ->label('Item Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Available' => 'success',
                        'Not Available' => 'danger',
                    })
                    ->searchable(),
                ImageColumn::make('ItemImage')
                    ->label('Item Image')
                    ->square()
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
        return auth()->user()->hasAnyRole(['Admin', 'Storekeeper']);
    }
}
