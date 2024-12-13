<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryResource\Pages;
use App\Models\Inventory;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?int $navigationSort = 11;

    protected static ?string $navigationGroup = 'Inventory';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Wizard::make([
                    Step::make('Inventory Item Information')
                        ->icon('heroicon-o-information-circle')
                        ->schema([

                            Section::make('Item Details')
                                ->description('Details of all inventory items')
                                ->schema([

                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('ItemName')
                                                ->required()
                                                ->label('Item Name'),

                                            TextInput::make('ItemCode')
                                                ->required()
                                                ->label('Item Code')
                                                ->default(fn() => 'ITEM-' . strtoupper(uniqid())) // Auto-generate item code
                                                ->disabled() // Make it read-only to prevent manual input
                                                ->required(),
                                        ]),


                                    Textarea::make('ItemDescription')
                                        ->required()
                                        ->columnSpanFull()
                                        ->label('Item Description'),

                                    Grid::make(2)
                                        ->schema([
                                            Select::make('ItemUnit')
                                                ->label('Item Unit')
                                                ->placeholder('Select Item unit')
                                                ->required()
                                                ->searchable()
                                                ->options([
                                                    'Barrel' => 'Barrel',
                                                    'Box' => 'Box',
                                                    'Bundle' => 'Bundle',
                                                    'Carton' => 'Carton',
                                                    'Centimeter (cm)' => 'Centimeter (cm)',
                                                    'Crate' => 'Crate',
                                                    'Cubic Meter (m³)' => 'Cubic Meter (m³)',
                                                    'Dozen (doz)' => 'Dozen (doz)',
                                                    'Foot (ft)' => 'Foot (ft)',
                                                    'Gallon (gal)' => 'Gallon (gal)',
                                                    'Gram (g)' => 'Gram (g)',
                                                    'Inch (in)' => 'Inch (in)',
                                                    'Kilogram (kg)' => 'Kilogram (kg)',
                                                    'Liter (L)' => 'Liter (L)',
                                                    'Meter (m)' => 'Meter (m)',
                                                    'Milligram (mg)' => 'Milligram (mg)',
                                                    'Milliliter (mL)' => 'Milliliter (mL)',
                                                    'Millimeter (mm)' => 'Millimeter (mm)',
                                                    'Ounce (oz)' => 'Ounce (oz)',
                                                    'Pair' => 'Pair',
                                                    'Packet' => 'Packet',
                                                    'Piece (pc)' => 'Piece (pc)',
                                                    'Pint (pt)' => 'Pint (pt)',
                                                    'Pound (lb)' => 'Pound (lb)',
                                                    'Quart (qt)' => 'Quart (qt)',
                                                    'Roll' => 'Roll',
                                                    'Sack' => 'Sack',
                                                    'Set' => 'Set',
                                                    'Tank' => 'Tank',
                                                    'Tonne (t)' => 'Tonne (t)',
                                                    'Yard (yd)' => 'Yard (yd)'
                                                ])->native(false),

                                            TextInput::make('ItemQuantity')
                                                ->required()
                                                ->label('Item Quantity'),
                                        ]),

                                    Grid::make(2)
                                        ->schema([
                                            DatePicker::make('ExpirationDate')
                                                ->label('Expiration Date (If Applicable)'),

                                            Select::make('ItemStatus')
                                                ->label('Item Status')
                                                ->placeholder('Select Item Status')
                                                ->required()
                                                ->options([
                                                    'Available' => 'Available',
                                                    'Not Available' => 'Not Available',
                                                ])
                                                ->default(function ($get) {
                                                    // Get the initial ItemQuantity value
                                                    $itemQuantity = $get('ItemQuantity');
                                                    // Set 'Available' if the quantity is greater than 0, otherwise 'Not Available'
                                                    return $itemQuantity > 0 ? 'Available' : 'Not Available';
                                                })
                                                ->native(false)
                                                ->afterStateUpdated(function ($state, $set, $get) {
                                                    // Whenever the ItemQuantity changes, update ItemStatus accordingly
                                                    $itemQuantity = $get('ItemQuantity');
                                                    // If quantity is greater than 0, set status to 'Available', otherwise 'Not Available'
                                                    $set('ItemStatus', $itemQuantity > 0 ? 'Available' : 'Not Available');
                                                }),
                                        ]),
                                ]), //first section schema
                        ]), //first tab schema
                ])->columnSpanFull(),
            ]);
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
            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('ItemStatus')
                    ->label('Item Status')
                    ->options([
                        'Available' => 'Available',
                        'Not Available' => 'Not Available',
                    ])
                    ->multiple() // Use multiple if you want to allow selecting multiple types
                    ->placeholder('Select Item Status'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-m-eye')
                    ->iconButton()
                    ->color('danger'),
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-m-pencil-square')
                    ->iconButton()
                    ->color('info'),
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
            'index' => Pages\ListInventories::route('/'),
            'create' => Pages\CreateInventory::route('/create'),
            'edit' => Pages\EditInventory::route('/{record}/edit'),
        ];
    }

}
