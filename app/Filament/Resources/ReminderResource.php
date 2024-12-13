<?php

namespace App\Filament\Resources;

use App\Models\User;
use App\Models\Vehicle;

use App\Filament\Resources\ReminderResource\Pages;
use App\Models\Reminder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class ReminderResource extends Resource
{
    protected static ?string $model = Reminder::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationGroup = 'Vehicle Management';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Tabs::make('Reminder Information')->tabs([
                    Tab::make('Reminder Information')
                        ->icon('heroicon-o-bell-alert')
                        ->schema([

                            Section::make('Reminder Date')
                                ->description('Reminder Date')
                                ->schema([

                                    Select::make('user_id')
                                        ->label('Driver Name')
                                        ->placeholder('Select Driver')
                                        ->options(
                                            User::role('Driver') // Include only users with the "Driver" role
                                                ->whereDoesntHave('roles', function ($query) {
                                                    $query->whereIn('name', ['Admin', 'Storekeeper']);
                                                }) // Exclude users with "Admin" or "Storekeeper" roles
                                                ->pluck('name', 'id')
                                        ),

                                    Select::make('vehicles_id')
                                        ->label('Vehicle Name')
                                        ->placeholder('Select Vehicle')
                                        ->options(
                                            Vehicle::whereHas('remarks', function ($query) {
                                                $query->where('VehicleRemarks', 'Serviceable');
                                            })->pluck('VehicleName', 'id')
                                        ),

                                    DatePicker::make('ReminderDate')->required()
                                        ->maxDate(Carbon::today('Asia/Manila'))
                                        ->label('Reminder Date'),

                                    DatePicker::make('DueDate')->required()
                                        ->label('Due Date'),

                                    Select::make('Remarks')->required()
                                        ->label('Expiring Documents')
                                        ->placeholder('Select Expiring Documents')
                                        ->options(function () {
                                            // Fetch options for Expiring Documents excluding already selected ones
                                            return [
                                                'ORCR' => 'ORCR',
                                                'Smoke Emission' => 'Smoke Emission',
                                                'Insurance' => 'Insurance',
                                            ];
                                        })
                                        ->native(false)->columnSpanFull(),

                                ])->columns(2), //Employee Info Schema
                        ]),
                ])->columnSpanFull(),
            ]);

    }
    public static function getTableQuery()
    {
        return parent::getTableQuery()->orderBy('created_at', 'desc');
    }
    public static function table(Table $table): Table
    {
        $user = Auth::user();
        return $table
            ->query(
                fn() => $user->hasRole('Admin')
                ? Reminder::query()
                : Reminder::where('user_id', $user->id)
            )
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Driver Name')
                    ->searchable(),
                TextColumn::make('ReminderDate')
                    ->label('Reminder Date')
                    ->date()
                    ->searchable(),
                TextColumn::make('DueDate')
                    ->label('Due Date')
                    ->date()
                    ->searchable(),
                TextColumn::make('vehicle.VehicleName')
                    ->label('Vehicle')
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
            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('ReminderStatus')
                    ->label('Reminder Status')
                    ->options([
                        'Pending' => 'Pending',
                        'Sent' => 'Sent',
                        'Acknowledged' => 'Acknowledged',
                        'Action Taken' => 'Action Taken',
                        'Done' => 'Done',
                    ])
                    ->multiple(), // Use multiple if you want to allow selecting multiple statuses
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-m-eye')
                    ->iconButton()
                    ->color('danger'),
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-m-pencil-square')
                    ->iconButton()
                    ->color('info'),
                // ActionGroup for Driver Actions
                ActionGroup::make([
                    Tables\Actions\Action::make('acknowledge')
                        ->icon('heroicon-o-check-circle')
                        ->label('Acknowledge')
                        ->color('warning')
                        ->requiresConfirmation('Are you sure you want to acknowledge this reminder?')
                        ->action(function ($record) {
                            $record->update([
                                'ReminderStatus' => 'Acknowledged',
                            ]);

                            Notification::make()
                                ->title('Reminder Acknowledged')
                                ->body('The reminder has been acknowledged successfully.')
                                ->warning()
                                ->send();
                        })
                        ->visible(fn() => Auth::user()->hasRole('Driver')), // Visible only for drivers

                    Tables\Actions\Action::make('takeAction')
                        ->icon('heroicon-o-pencil')
                        ->label('Action Taken')
                        ->color('info')
                        ->requiresConfirmation('Are you sure you want to mark this reminder as action taken?')
                        ->action(function ($record) {
                            $record->update([
                                'ReminderStatus' => 'Action Taken',
                            ]);

                            Notification::make()
                                ->title('Action Taken')
                                ->body('The reminder has been marked as action taken successfully.')
                                ->success()
                                ->send();
                        })
                        ->visible(fn() => Auth::user()->hasRole('Driver')), // Visible only for drivers

                    Tables\Actions\Action::make('done')
                        ->icon('heroicon-o-check-badge')
                        ->label('Done')
                        ->color('success')
                        ->requiresConfirmation('Are you sure you want to mark this reminder as action taken?')
                        ->action(function ($record) {
                            $record->update([
                                'ReminderStatus' => 'Done',
                            ]);

                            Notification::make()
                                ->title('Done')
                                ->body('The reminder has been marked as done successfully.')
                                ->success()
                                ->send();
                        })
                        ->visible(fn() => Auth::user()->hasRole('Driver')), // Visible only for drivers
                ])
                    ->icon('heroicon-s-ellipsis-horizontal-circle'),
            ])
            ->bulkActions([

            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReminders::route('/'),
            'create' => Pages\CreateReminder::route('/create'),
            'edit' => Pages\EditReminder::route('/{record}/edit'),
        ];
    }

    protected function rules(): array
    {
        return [
            'ReminderDate' => ['required', 'date', 'before_or_equal:today'],
        ];
    }

    protected function messages(): array
    {
        return [
            'ReminderDate.before_or_equal' => 'The Date of Reminder cannot be a future date.',
        ];
    }
}
