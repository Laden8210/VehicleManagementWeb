<?php

namespace App\Filament\Resources;

use App\Models\RepairRequest;
use App\Models\MaintenanceRecommendation;

use App\Filament\Resources\ExpensesResource\Pages;
use App\Models\Expenses;
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
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\FiltersLayout;
use Carbon\Carbon;

class ExpensesResource extends Resource
{
    protected static ?string $model = Expenses::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?int $navigationSort = 12;

    protected static ?string $navigationGroup = 'Expenses and Suppliers';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Expenses')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Section::make('Expenses')
                                ->description('Expenses Details')
                                ->schema([
                                    Grid::make(3)
                                        ->schema([
                                            Select::make('repair_requests_id')
                                                ->label('Repair Request Number')
                                                ->placeholder('Select Repair Request Number')
                                                ->options(function () {
                                                    $usedRepairRequestIds = Expenses::pluck('repair_requests_id')->filter()->toArray();
                                                    return RepairRequest::where('RequestStatus', 'Approved')
                                                        ->whereNotIn('id', $usedRepairRequestIds)
                                                        ->pluck('RRNumber', 'id');
                                                })
                                                ->reactive()
                                                ->disabled(function (callable $get) {
                                                    // Disable the Repair Request field if Maintenance Recommendation is selected
                                                    return $get('maintenance_recommendations_id') !== null;
                                                })
                                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                    $serviceCosts = RepairRequest::find($state)?->serviceDetails?->ServiceCosts ?? 0;
                                                    $set('TotalCost', $serviceCosts);

                                                    $lastBalance = Expenses::orderBy('id', 'desc')
                                                        ->whereNotNull('AppropriationBalance')
                                                        ->value('AppropriationBalance');
                                                    $set('AppropriationBudget', $lastBalance !== null ? $lastBalance : 0);

                                                    // Update appropriation balance
                                                    $appropriationBudget = $get('AppropriationBudget') ?? 0;
                                                    $totalCost = $get('TotalCost') ?? 0;
                                                    $set('AppropriationBalance', max($appropriationBudget - $totalCost, 0));
                                                }),

                                            Select::make('maintenance_recommendations_id')
                                                ->label('Maintenance Recommendation Number')
                                                ->placeholder('Select Maintenance Recommendation Number')
                                                ->options(function () {
                                                    $usedMaintenanceRecommendationIds = Expenses::pluck('maintenance_recommendations_id')->filter()->toArray();
                                                    return MaintenanceRecommendation::whereNotIn('id', $usedMaintenanceRecommendationIds)
                                                        ->pluck('MRNumber', 'id');
                                                })
                                                ->reactive()
                                                ->disabled(function (callable $get) {
                                                    // Disable the Maintenance Recommendation field if Repair Request is selected
                                                    return $get('repair_requests_id') !== null;
                                                })
                                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                    $serviceCosts = MaintenanceRecommendation::find($state)?->serviceRecords?->ServiceCosts ?? 0;
                                                    $set('TotalCost', $serviceCosts);

                                                    $lastBalance = Expenses::orderBy('id', 'desc')
                                                        ->whereNotNull('AppropriationBalance')
                                                        ->value('AppropriationBalance');
                                                    $set('AppropriationBudget', $lastBalance !== null ? $lastBalance : 0);

                                                    // Update appropriation balance
                                                    $appropriationBudget = $get('AppropriationBudget') ?? 0;
                                                    $totalCost = $get('TotalCost') ?? 0;
                                                    $set('AppropriationBalance', max($appropriationBudget - $totalCost, 0));
                                                }),

                                            DatePicker::make('RepairMaintenanceDate')
                                                ->required()
                                                ->label('Repair / Maintenance Date')
                                                ->maxDate(Carbon::today('Asia/Manila')),
                                        ]),
                                    TextArea::make('Description')
                                        ->required()
                                        ->helperText('Full description of repair done in the vehicle')
                                        ->columnSpanFull(),

                                    Grid::make(3)
                                        ->schema([
                                            TextInput::make('AppropriationBudget')->required()
                                                ->label('Appropriation Budget')
                                                ->numeric()
                                                ->reactive()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    $totalCost = $get('TotalCost') ?? 0;
                                                    $appropriationBudget = $get('AppropriationBudget') ?? 0;
                                                    $appropriationBalance = $appropriationBudget - $totalCost;
                                                    if ($appropriationBalance < 0) {
                                                        $appropriationBalance = 0;
                                                        Notification::make()
                                                            ->title('Budget Warning')
                                                            ->body('Appropriation balance cannot be less than zero. Please check the total cost.')
                                                            ->warning()
                                                            ->send();
                                                    }
                                                    $set('AppropriationBalance', $appropriationBalance);
                                                }),
                                            TextInput::make('TotalCost')->required()
                                                ->label('Total Cost')
                                                ->numeric()
                                                ->reactive()
                                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                    $appropriationBudget = $get('AppropriationBudget');
                                                    $appropriationBalance = ($appropriationBudget ?? 0) - $state;
                                                    if ($appropriationBalance < 0) {
                                                        $appropriationBalance = 0;
                                                        Notification::make()
                                                            ->title('Budget Warning')
                                                            ->body('Appropriation balance cannot be less than zero. Please check the total cost.')
                                                            ->warning()
                                                            ->send();
                                                    }
                                                    $set('AppropriationBalance', $appropriationBalance);
                                                }),

                                            TextInput::make('AppropriationBalance')->required()
                                                ->label('Appropriation Balance')
                                                ->disabled()
                                                ->numeric()
                                                ->reactive()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    $appropriationBudget = $get('AppropriationBudget') ?? 0;
                                                    $totalCost = $get('TotalCost') ?? 0;
                                                    $newBalance = $appropriationBudget - $totalCost;
                                                    $set('AppropriationBalance', max($newBalance, 0));
                                                }),
                                        ]), // grid schema

                                    Grid::make(3)
                                        ->schema([
                                            Select::make('PaymentType')->required()
                                                ->label('Payment Type')
                                                ->placeholder('Select Payment Type')
                                                ->options([
                                                    'Purchased Order' => 'Purchased Order',
                                                    'Reimbursement' => 'Reimbursement',
                                                    'Replenishment' => 'Replenishment',
                                                ])->native(false),

                                            Select::make('PaymentStatus')->required()
                                                ->label('Payment Status')
                                                ->placeholder('Select Payment Status')
                                                ->options([
                                                    'Pending' => 'Pending',
                                                    'In Progress' => 'In Progress',
                                                    'Paid' => 'Paid',
                                                ])->native(false),

                                            TextInput::make('DvNumber')
                                                ->label('Disbursement Voucher Number')
                                                ->helperText('e.g DV-24-1234'),
                                        ]),
                                ])->columns(2), // first section schema
                        ]), // first tab schema
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
                TextColumn::make('maintenancerecommendations.MRNumber')
                    ->label('M-R Number')
                    ->searchable(),
                TextColumn::make('RepairMaintenanceDate')
                    ->label('R/M Date')
                    ->date()
                    ->searchable(),
                TextColumn::make('AppropriationBudget')
                    ->label('Appro Bud.')
                    ->formatStateUsing(fn($state) => '₱' . number_format($state, 2, '.', ','))
                    ->searchable(),
                TextColumn::make('TotalCost')
                    ->label('Total Cost')
                    ->formatStateUsing(fn($state) => '₱' . number_format($state, 2))
                    ->searchable(),
                TextColumn::make('AppropriationBalance')
                    ->label('Appro Bal.')
                    ->numeric()
                    ->formatStateUsing(fn($state) => '₱' . number_format($state, 2))
                    ->searchable(),
                TextColumn::make('PaymentType')
                    ->label('Payment Type')
                    ->searchable(),
                TextColumn::make('PaymentStatus')
                    ->label('Payment Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'In Progress' => 'info',
                        'Paid' => 'success'
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'Pending' => 'heroicon-o-clock',       // Icon for Pending
                        'In Progress' => 'heroicon-o-arrow-path',  // Icon for In Progress
                        'Paid' => 'heroicon-o-check-circle',    // Icon for Paid
                        default => 'heroicon-o-question-mark',  // Default icon
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
                Tables\Filters\SelectFilter::make('PaymentType')
                    ->label('Payment Type')
                    ->options([
                        'Purchased Order' => 'Purchased Order',
                        'Reimbursement' => 'Reimbursement',
                        'Replenishment' => 'Replenishment',
                    ])
                    ->multiple()
                    ->placeholder('Select Payment Type'),
                Tables\Filters\SelectFilter::make('PaymentStatus')
                    ->label('Payment Status')
                    ->options([
                        'Pending' => 'Pending',
                        'In Progress' => 'In Progress',
                        'Paid' => 'Paid',
                    ])
                    ->multiple()
                    ->placeholder('Select Payment Status'),
                Tables\Filters\SelectFilter::make('repair_maintenance_month')
                    ->label('Month')
                    ->options([
                        '01' => 'January',
                        '02' => 'February',
                        '03' => 'March',
                        '04' => 'April',
                        '05' => 'May',
                        '06' => 'June',
                        '07' => 'July',
                        '08' => 'August',
                        '09' => 'September',
                        '10' => 'October',
                        '11' => 'November',
                        '12' => 'December',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return isset($data['value'])
                            ? $query->whereMonth('RepairMaintenanceDate', $data['value'])
                            : $query;
                    })
                    ->multiple()
                    ->placeholder('Select Month'),
            ], layout: FiltersLayout::AboveContent)
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
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpenses::route('/create'),
            'edit' => Pages\EditExpenses::route('/{record}/edit'),
        ];
    }

    protected function rules(): array
    {
        return [
            'RepairMaintenanceDate' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    protected function messages(): array
    {
        return [
            'RepairMaintenanceDate.before_or_equal' => 'The Repair Maintenance Date cannot be a future date.',
        ];
    }
}
