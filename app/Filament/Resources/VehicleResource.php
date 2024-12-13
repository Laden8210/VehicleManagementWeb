<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Models\Vehicle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Vehicle Management';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Wizard::make([
                    Step::make('Vehicle Information')
                        ->icon('heroicon-o-truck')
                        ->schema([
                            Section::make('Vehicle Complete Information')
                                ->schema([
                                    TextInput::make('VehicleName')
                                        ->required()
                                        ->label('Vehicle Name'),
                                    TextInput::make('MvfileNo')
                                        ->required()
                                        ->label('MV File No'),
                                    Grid::make(3)
                                        ->schema([
                                            TextInput::make('PlateNumber')
                                                ->required()
                                                ->label('Plate Number'),
                                            TextInput::make('EngineNumber')
                                                ->required()
                                                ->label('Engine Number'),
                                            TextInput::make('ChassisNumber')
                                                ->required()
                                                ->label('Chassis Number'),
                                        ]), //grid schema

                                    Grid::make(3)
                                        ->schema([
                                            Select::make('Fuel')
                                                ->required()
                                                ->placeholder('Select Fuel')
                                                ->options([
                                                    'Diesel' => 'Diesel',
                                                    'Gasoline' => 'Gasoline',
                                                ])->native(false),

                                            TextInput::make('Make')
                                                ->required(),
                                            TextInput::make('Series')
                                                ->required(),
                                        ]), //grid schema

                                    Grid::make(3)
                                        ->schema([
                                            Select::make('BodyType')
                                                ->required()
                                                ->label('Vehicle Type')
                                                ->options([
                                                    'Rescue Vehicle' => 'Rescue Vehicle',
                                                    'Ambulance' => 'Ambulance',
                                                    'PTV' => 'PTV',
                                                ])->native(false),

                                            Select::make('YearModel')
                                                ->placeholder('Select Year Model')
                                                ->label('Year Model')
                                                ->options(
                                                    collect(range(date('Y'), date('Y') - 100)) // Generates an array of years from the current year to 100 years ago
                                                        ->mapWithKeys(fn($year) => [$year => $year])
                                                        ->toArray()
                                                )->required()->native(false),
                                            TextInput::make('Color')->required()
                                        ]), //grid schema,
                                ])->columns(2), //Vehicle Info Schema
                        ]), // tab schema

                    Step::make('Vehicle Registration')
                        ->icon('heroicon-o-calendar-days')
                        ->schema([
                            Section::make('Vehicle Registration')
                                ->schema([
                                    DatePicker::make('PurchasedDate')
                                        ->label('Purchased Date')
                                        ->helperText('Refer to the vehicle Certificate of Registration. Leave blank if not available.'),
                                    DatePicker::make('RegistrationDate')
                                        ->label('Registration Date')
                                        ->helperText('Refer to the vehicle Certificate of Registration. Leave blank if not available..'),
                                    TextInput::make('OrcrNo')
                                        ->label('OR CR No')
                                        ->helperText('Refer to the vehicle Certificate of Registration. Leave blank if not available.'),
                                    TextInput::make('PurchasedCost')
                                        ->label('Purchased Cost')
                                        ->helperText('Contact PGSO for proper costing. Leave blank if not available.'),
                                    TextInput::make('PropertyNumber')
                                        ->label('Property Number')
                                        ->helperText('Leave blank if not available. Contact PGSO for the Property Number.')
                                        ->columnSpan(2),
                                ])->columns(2), //Vehicle Registration Schema
                        ]), //Vehicle Registation step
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
                TextColumn::make('VehicleName')
                    ->label('Vehicle Name')
                    ->searchable(),
                TextColumn::make('MvfileNo')
                    ->label('Plate Number')
                    ->searchable(),
                TextColumn::make('Make')
                    ->searchable(),
                TextColumn::make('Series')
                    ->searchable(),
                TextColumn::make('YearModel')
                    ->label('Year Model')
                    ->searchable(),
                TextColumn::make('PropertyNumber')
                    ->label('Property Number')
                    ->searchable(),
                TextColumn::make('BodyType')
                    ->label('Vehicle Type')
                    ->searchable(),
                ImageColumn::make('Image')
                    ->label('Vehicle Image')
                    ->circular()
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
                // Adding the filter for vehicle type (BodyType)
                Tables\Filters\SelectFilter::make('BodyType')
                    ->label('Vehicle Type')
                    ->options([
                        'Rescue Vehicle' => 'Rescue Vehicle',
                        'Ambulance' => 'Ambulance',
                        'PTV' => 'PTV',
                    ])
                    ->placeholder('Select Vehicle Type'),
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
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}
