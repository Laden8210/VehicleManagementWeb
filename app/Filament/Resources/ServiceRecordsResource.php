<?php

namespace App\Filament\Resources;

use App\Models\MaintenanceRecommendation;
use App\Models\ServiceRecords;
use App\Models\Suppliers;

use App\Filament\Resources\ServiceRecordsResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class ServiceRecordsResource extends Resource
{
    protected static ?string $model = ServiceRecords::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationParentItem = 'Maintenance Recommendations';

    protected static ?string $navigationGroup = 'Vehicle Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Tabs::make('Service Records')->tabs([
                    Tab::make('Service Records')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Section::make('Service Records')
                                ->description('Maintenance Recommendations records.')
                                ->schema([

                                    Grid::make(2)
                                        ->schema([

                                            Select::make('maintenance_recommendations_id')
                                                ->label('Maintenance Recommendation Number')
                                                ->placeholder('Select Maintenance Recommendation Number')
                                                ->options(function () {
                                                    // Get all used maintenance recommendation IDs
                                                    $usedIds = ServiceRecords::pluck('maintenance_recommendations_id')->toArray();

                                                    // Return available options excluding the used IDs
                                                    return MaintenanceRecommendation::where('RequestStatus', 'Approved')
                                                        ->whereNotIn('id', $usedIds) // Exclude used IDs
                                                        ->pluck('MRNumber', 'id');
                                                }),

                                            Select::make('suppliers_id')
                                                ->label('Supplier')
                                                ->placeholder('Select Supplier')
                                                ->options(Suppliers::all()->pluck('SupplierName', 'id')),
                                        ]), //grid schema

                                    Grid::make(2)
                                        ->schema([
                                            DatePicker::make('MaintenanceDate')
                                                ->required()
                                                ->label('Maintenance Date')
                                                ->maxDate(Carbon::today('Asia/Manila')),

                                            Select::make('MaintenanceType')
                                                ->required()
                                                ->label('Maintenance Type')
                                                ->placeholder('Select Maintenance Type')
                                                ->options([
                                                    'Oil Change' => 'Oil Change',
                                                    'Tire Maintenance' => 'Tire Maintenance',
                                                    'Brake System Inspection' => 'Brake System Inspection',
                                                    'Battery Maintenance' => 'Battery Maintenance',
                                                    'Fluid Checks' => 'Fluid Checks',
                                                    'Air Filter Replacement' => 'Air Filter Replacement',
                                                    'Lighting Checks' => 'Lighting Checks',
                                                    'Scheduled Inspections' => 'Scheduled Inspections',
                                                    'Cleaning and Detailing' => 'Cleaning and Detailing',
                                                    'Others - Specify to Description' => 'Others - Specify to Description'
                                                ])->native(false),
                                        ]), //grid schema

                                    TextArea::make('ServiceDescription')
                                        ->required()
                                        ->label('Service Description'),

                                    TextArea::make('ChangedParts')
                                        ->required()
                                        ->columnSpanFull()
                                        ->label('Changed Parts')
                                        ->placeholder('Breakdown all the parts changed during this maintenance.'),

                                    TextInput::make('ServiceCosts')
                                        ->required()
                                        ->columnSpanFull()
                                        ->label('Service Cost')
                                        ->helperText('Overall total cost of a repair (Labor and Parts)'),

                                ]),
                        ]) //base schema
                ])->columnSpanFull(), //Vehicle Information Tabs
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
                TextColumn::make('maintenancerecommendation.MRNumber')
                    ->label('M-R Number')
                    ->searchable(),
                TextColumn::make('suppliers.SupplierName')
                    ->label('Supplier Name')
                    ->searchable(),
                TextColumn::make('MaintenanceType')
                    ->label('Maintenance Type')
                    ->searchable(),
                TextColumn::make('ChangedParts')
                    ->label('Changed Parts')
                    ->searchable(),
                TextColumn::make('ServiceCosts')
                    ->label('Service Costs')
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
            ])->defaultSort('created_at', 'desc')
            ->filters([
                //
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
            'index' => Pages\ListServiceRecords::route('/'),
            'create' => Pages\CreateServiceRecords::route('/create'),
            'edit' => Pages\EditServiceRecords::route('/{record}/edit'),
        ];
    }

    protected function rules(): array
    {
        return [
            'MaintenanceDate' => ['required', 'date', 'before_or_equal:today'],
        ];
    }

    protected function messages(): array
    {
        return [
            'MaintenanceDate.before_or_equal' => 'The Maintenance Date cannot be a future date.',
        ];
    }
}
