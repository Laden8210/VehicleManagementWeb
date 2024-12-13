<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ReminderResource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestReminders extends BaseWidget
{
    protected static ?int $sort = 7;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(ReminderResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('ReminderDate')
                    ->label('Reminder Date')
                    ->date()
                    ->searchable(),
                TextColumn::make('Remarks')
                    ->label('Expiring Document')
                    ->searchable(),
                TextColumn::make('ReminderStatus')
                    ->label('Reminder Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Sent' => 'warning',
                        'Acknowledged' => 'warning',
                        'Action Taken' => 'info',
                        'Done' => 'success',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'Pending' => 'heroicon-o-clock', // Example icon for Pending
                        'Sent' => 'heroicon-o-check-circle', // Example icon for Sent
                        'Acknowledged' => 'heroicon-o-check-circle', // Example icon for Acknowledged
                        'Action Taken' => 'heroicon-o-pencil', // Example icon for Action Taken
                        'Done' => 'heroicon-o-check-badge', // Example icon for Overdue
                        default => 'heroicon-o-x-circle', // Default icon if none match
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
