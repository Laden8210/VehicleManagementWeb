<?php

namespace App\Filament\Resources;

use App\Models\RepairRequest;
use App\Models\Suppliers;
use App\Models\ServiceDetails;

use App\Filament\Resources\ServiceDetailsResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class ServiceDetailsResource extends Resource
{
    protected static ?string $model = ServiceDetails::class;

    protected static ?int $navigationSort = 9;

    protected static ?string $navigationParentItem = 'Repair Requests';

    protected static ?string $navigationGroup = 'Vehicle Management';

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Service Details')
                        ->icon('heroicon-o-information-circle')
                        ->schema([

                            Section::make('Service Details')
                                ->description('Service details of vehicles repair')
                                ->schema([

                                    Grid::make(2)
                                        ->schema([
                                            Select::make('repair_requests_id')
                                                ->label('Repair Requests Number')
                                                ->placeholder('Select Repair Requests Number')
                                                ->options(function () {
                                                    $usedIds = ServiceDetails::pluck('repair_requests_id')->toArray();
                                                    return RepairRequest::where('RequestStatus', 'Approved')
                                                        ->whereNotIn('id', $usedIds)
                                                        ->pluck('RRNumber', 'id');
                                                }),

                                            Select::make('suppliers_id')
                                                ->label('Supplier')
                                                ->placeholder('Select Supplier')
                                                ->options(Suppliers::all()->pluck('SupplierName', 'id')),

                                        ]), //grid 2 schema 

                                    Grid::make(2)
                                        ->schema([
                                            DatePicker::make('RepairDate')
                                                ->required()
                                                ->label('Repair Date')
                                                ->maxDate(Carbon::today('Asia/Manila')),

                                            Select::make('RepairType')->required()
                                                ->label('Repair Type')
                                                ->placeholder('Select Repair Type')
                                                ->options([
                                                    'Engine Repair' => 'Engine Repair',
                                                    'Transmission Repair' => 'Transmission Repair',
                                                    'Suspension Repair' => 'Suspension Repair',
                                                    'Exhaust System Repair' => 'Exhaust System Repair',
                                                    'Windshield Replacement' => 'Windshield Replacement',
                                                    'Electrical System Repair' => 'Electrical System Repair',
                                                    'Air Conditioning Repair' => 'Air Conditioning Repair',
                                                    'Fuel System Repair' => 'Fuel System Repair',
                                                    'Cooling System Repair' => 'Cooling System Repair',
                                                    'Steering Repair' => 'Steering Repair',
                                                    'Clutch Replacement' => 'Clutch Replacement',
                                                    'Other - Specify to Description' => 'Other - Specify to Description',
                                                ])->native(false),

                                        ]), //grid 2 schema 
                                    Textarea::make('ServiceDescription')->required()->columnSpanFull()
                                        ->label('Service Description')
                                        ->placeholder('State all the repairs done to the vehicle.'),

                                    Textarea::make('ChangedParts')->required()->columnSpanFull()
                                        ->label('Changed Parts')
                                        ->placeholder('Breakdown all the parts changed during this repair.'),

                                    TextInput::make('ServiceCosts')
                                        ->required()
                                        ->columnSpanFull()
                                        ->label('Service Cost')
                                        ->helperText('Overall total cost of a repair (Labor and Parts)'),
                                ])->columns(2),
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
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('repairrequests.RRNumber')
                    ->label('R-R Number')
                    ->searchable(),
                TextColumn::make('repairrequests.ReportedIssue')
                    ->label('Reported Issue')
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
            'index' => Pages\ListServiceDetails::route('/'),
            'create' => Pages\CreateServiceDetails::route('/create'),
            'edit' => Pages\EditServiceDetails::route('/{record}/edit'),
        ];
    }

    protected function rules(): array
    {
        return [
            'RepairDate' => ['required', 'date', 'before_or_equal:today'],
        ];
    }

    protected function messages(): array
    {
        return [
            'RepairDate.before_or_equal' => 'The Repair Date cannot be a future date.',
        ];
    }
}
