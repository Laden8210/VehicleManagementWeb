<?php

namespace App\Filament\Resources;

use App\Models\Vehicle;

use App\Filament\Resources\VehicleRemarksResource\Pages;
use App\Models\VehicleRemarks;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class VehicleRemarksResource extends Resource
{
    protected static ?string $model = VehicleRemarks::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationParentItem = 'Vehicles';

    protected static ?string $navigationGroup = 'Vehicle Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Vehicle Remarks')->tabs([
                    Tab::make('Vehicle Remarks')
                        ->icon('heroicon-o-cog')
                        ->schema([
                            Section::make()
                                ->schema([
                                    Select::make('vehicles_id')
                                        ->label('Vehicle Name')
                                        ->placeholder('Select Vehicle')
                                        ->options(
                                            Vehicle::whereNotIn(
                                                'id',
                                                VehicleRemarks::pluck('vehicles_id')->toArray()
                                            )->pluck('VehicleName', 'id')
                                        ),
                                ]),

                            Section::make()
                                ->schema([
                                    Select::make('VehicleRemarks')->required()
                                        ->label('Vehicle Remarks')
                                        ->placeholder('Select Remarks')
                                        ->options([
                                            'Serviceable' => 'Serviceable',
                                            'Unserviceable' => 'Unserviceable',
                                            'Under Repair' => 'Under Repair',
                                            'For PRS' => 'For PRS',
                                        ])->native(false),
                                ]),
                        ]) //base schema
                ])->columnSpanFull(), //Vehicle Information Tabs
            ]); //Base Schema
    }
    public static function getTableQuery()
    {
        return parent::getTableQuery()->orderBy('created_at', 'desc');
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('vehicle.VehicleName')
                    ->label('Vehicle Name')
                    ->searchable(),
                TextColumn::make('vehicle.PlateNumber')
                    ->label('Plate Number')
                    ->searchable(),
                TextColumn::make('VehicleRemarks')
                    ->label('Vehicle Remarks')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Serviceable' => 'success',
                        'Unserviceable' => 'danger',
                        'Under Repair' => 'warning',
                        'For PRS' => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'Serviceable' => 'heroicon-o-check-circle', // Icon for serviceable
                        'Unserviceable' => 'heroicon-o-x-circle', // Icon for unserviceable
                        'Under Repair' => 'heroicon-o-cog', // Icon for under maintenance
                        'For PRS' => 'heroicon-o-bolt-slash', // Icon for for PRS
                        default => 'heroicon-o-question-mark-circle', // Default icon
                    })
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Added On')
                    ->date()
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->label('Last Updated On')
                    ->date()
                    ->searchable()
            ])->defaultSort('created_at', 'desc')
            ->filters([
                // Filter for Vehicle Remarks
                Tables\Filters\SelectFilter::make('VehicleRemarks')
                    ->label('Vehicle Remarks')
                    ->options([
                        'Serviceable' => 'Serviceable',
                        'Unserviceable' => 'Unserviceable',
                        'Under Repair' => 'Under Repair',
                        'For PRS' => 'For PRS',
                    ])
                    ->placeholder('Select Remark'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-m-eye')
                    ->iconButton()
                    ->color('danger'),
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-m-pencil-square')
                    ->iconButton()
                    ->color('info')
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
            'index' => Pages\ListVehicleRemarks::route('/'),
            'create' => Pages\CreateVehicleRemarks::route('/create'),
            'edit' => Pages\EditVehicleRemarks::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('vehicle');
    }
}
