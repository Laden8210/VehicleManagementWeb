<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\DispatchResource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestDispatches extends BaseWidget
{
    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(DispatchResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('RequestDate')
                    ->label('Request Date')
                    ->date()
                    ->searchable(),
                TextColumn::make('RequestorName')
                    ->label('Requestor Name')
                    ->searchable(),
                TextColumn::make('TravelDate')
                    ->label('Travel Date')
                    ->date()
                    ->searchable(),
                TextColumn::make('RequestStatus')
                    ->label('Request Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Approved' => 'success',
                        'Disapproved' => 'danger',
                        'Cancelled' => 'gray',
                    })
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
        return auth()->user()->hasAnyRole(['Admin', 'Driver']);
    }
}
