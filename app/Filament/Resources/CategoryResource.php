<?php

namespace App\Filament\Resources;

use App\Models\Inventory;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 12;

    protected static ?string $navigationParentItem = 'Inventories';

    protected static ?string $navigationGroup = 'Inventory';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Inventory Items')
                    ->description('Select Inventory Items')
                    ->schema([
                        Select::make('inventories_id')
                            ->label('Inventory Item')
                            ->placeholder('Select Inventory Item')
                            ->options(Inventory::whereNotIn(
                                'id',
                                Category::pluck('inventories_id')->toArray()
                            )->pluck('ItemName', 'id')),
                    ]),

                Section::make('Category')
                    ->description('Item Category')
                    ->schema([
                        Select::make('CategoryName')->required()
                            ->placeholder('Select Category Name')
                            ->options([
                                'Ambulance Medical Supplies' => 'Ambulance Medical Supplies',
                                'Ambulance Medical Equipment' => 'Ambulance Medical Equipment',
                                'Oxygen and Respiratory Equipment' => 'Oxygen and Respiratory Equipment',
                                'Emergency Lighting and Sirens' => 'Emergency Lighting and Sirens',
                                'Communication Equipment' => 'Communication Equipment',
                                'Protective Gear' => 'Protective Gear',
                                'Fire Extinguishers' => 'Fire Extinguishers',
                                'First Aid Kits' => 'First Aid Kits',
                                'Personal Protective Equipment' => 'Personal Protective Equipment',
                                'Disaster Response Equipment' => 'Disaster Response Equipment',
                                'Rescue and Recovery Tools' => 'Rescue and Recovery Tools',
                                'Temporary Shelter' => 'Temporary Shelter',
                                'Search and Rescue Gear' => 'Search and Rescue Gear',
                                'Survival Kits' => 'Survival Kits'
                            ])->native(false),
                    ]),
            ]); //base schema
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
                TextColumn::make('inventory.ItemName')
                    ->label('Item Name')
                    ->searchable(),
                TextColumn::make('CategoryName')
                    ->label('Category Name')
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
                Tables\Filters\SelectFilter::make('CategoryName')
                    ->label('Category Name')
                    ->options([
                        'Ambulance Medical Supplies' => 'Ambulance Medical Supplies',
                        'Ambulance Medical Equipment' => 'Ambulance Medical Equipment',
                        'Oxygen and Respiratory Equipment' => 'Oxygen and Respiratory Equipment',
                        'Emergency Lighting and Sirens' => 'Emergency Lighting and Sirens',
                        'Communication Equipment' => 'Communication Equipment',
                        'Protective Gear' => 'Protective Gear',
                        'Fire Extinguishers' => 'Fire Extinguishers',
                        'First Aid Kits' => 'First Aid Kits',
                        'Personal Protective Equipment' => 'Personal Protective Equipment',
                        'Disaster Response Equipment' => 'Disaster Response Equipment',
                        'Rescue and Recovery Tools' => 'Rescue and Recovery Tools',
                        'Temporary Shelter' => 'Temporary Shelter',
                        'Search and Rescue Gear' => 'Search and Rescue Gear',
                        'Survival Kits' => 'Survival Kits',
                    ])
                    ->multiple() // Use multiple if you want to allow selecting multiple types
                    ->placeholder('Select Document Type'),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
