<?php

namespace App\Filament\Resources;

use App\Models\TripTicket;

use App\Filament\Resources\FuelConsumptionResource\Pages;
use App\Models\FuelConsumption;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class FuelConsumptionResource extends Resource
{
    protected static ?string $model = FuelConsumption::class;

    protected static ?string $navigationIcon = 'heroicon-o-funnel';

    protected static ?int $navigationSort = 8;

    protected static ?string $navigationGroup = 'Vehicle Management';

    public static function form(Form $form): Form
    {
        $requestCount = session('request_count', 0) + 1;
        session(['request_count' => $requestCount]);
        return $form
            ->schema([
                Tabs::make('Fuel Consumption Details')->tabs([
                    Tab::make('Fuel Consumption Details')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Section::make('Fuel Consumption Details')
                                ->description('To utilize fuel allocated budget')
                                ->schema([
                                    Grid::make(3)
                                        ->schema([
                                            TextInput::make('WithdrawalSlipNo')
                                                ->label('Withdrawal Slip No')
                                                ->disabled()
                                                ->default(fn() => 'WS-' . now()->year . '-' . str_pad(now()->month, 2, '0', STR_PAD_LEFT) . '-' . str_pad(\App\Models\FuelConsumption::max('id') + 1, 3, '0', STR_PAD_LEFT))
                                                ->readonly(),
                                            TextInput::make('PONum')
                                                ->label('Purchased Order Number'),
                                            DatePicker::make('RequestDate')
                                                ->label('Request Date')
                                                ->required()
                                                ->minDate(Carbon::today('Asia/Manila'))
                                                ->maxDate(Carbon::today('Asia/Manila')),
                                        ]),
                                    Select::make('trip_tickets_id')
                                        ->label('Trip Ticket Number')
                                        ->placeholder('Select Trip Ticket')
                                        ->options(TripTicket::whereDoesntHave('fuelConsumptions')->pluck('TripTicketNumber', 'id'))
                                        ->reactive()
                                        ->afterStateUpdated(function (callable $set, $state) {
                                            $vehicle = TripTicket::find($state)?->vehicle;
                                            if ($vehicle) {
                                                $set('Fuel', $vehicle->Fuel);
                                            }
                                        }),
                                    TextInput::make('Fuel')
                                        ->label('Fuel Type')
                                        ->disabled(),
                                    Grid::make(3)
                                        ->schema([
                                            TextInput::make('Quantity')
                                                ->required()
                                                ->numeric()
                                                ->reactive()
                                                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                                                    $price = $get('Price') ?? 0;
                                                    $amount = $state * $price;
                                                    $set('Amount', $amount);

                                                    $previousBalance = $get('PreviousBalance') ?? 0;
                                                    $remainingBalance = $previousBalance - $amount;
                                                    $set('RemainingBalance', $remainingBalance);
                                                }),
                                            TextInput::make('Price')
                                                ->required()
                                                ->numeric()
                                                ->reactive()
                                                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                                                    $quantity = $get('Quantity') ?? 0;
                                                    $amount = $quantity * $state;
                                                    $set('Amount', $amount);

                                                    $previousBalance = $get('PreviousBalance') ?? 0;
                                                    $remainingBalance = $previousBalance - $amount;
                                                    $set('RemainingBalance', $remainingBalance);
                                                }),
                                            TextInput::make('Amount')
                                                ->reactive()
                                                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                                                    Log::info('Amount updated:', ['Amount' => $state]);
                                                    $previousBalance = $get('PreviousBalance') ?? 0;
                                                    $remainingBalance = $previousBalance - $state;
                                                    $set('RemainingBalance', $remainingBalance);
                                                    Log::info('Remaining Balance calculated:', ['RemainingBalance' => $remainingBalance]);
                                                }),

                                            TextInput::make('PreviousBalance')
                                                ->label('Previous Balance')
                                                ->required()
                                                ->numeric()
                                                ->default(function () {
                                                    // Fetch the latest RemainingBalance from the last FuelConsumption record
                                                    $lastFuelConsumption = FuelConsumption::latest()->first();
                                                    return $lastFuelConsumption ? $lastFuelConsumption->RemainingBalance : 0;
                                                })
                                                ->reactive()
                                                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                                                    // Recalculate the remaining balance after updating the previous balance
                                                    $amount = $get('Amount') ?? 0;  // Get the calculated amount
                                                    $set('RemainingBalance', $state - $amount); // Subtract amount from previous balance
                                                }),
                                            TextInput::make('RemainingBalance')
                                                ->label('Remaining Balance')
                                                ->numeric()
                                                ->disabled(true),
                                            TextInput::make('ReferenceNumber')
                                                ->label('Reference Number')
                                        ])
                                ])
                                ->columns(2),
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
                TextColumn::make('WithdrawalSlipNo')
                    ->label('WS Number')
                    ->searchable(),

                TextColumn::make('RequestDate')
                    ->label('Request Date')
                    ->date()
                    ->searchable(),

                TextColumn::make('tripticket.TripTicketNumber')
                    ->label('TT Number')
                    ->searchable(),

                TextColumn::make('tripTicket.vehicle.Fuel')
                    ->label('Fuel Type')
                    ->formatStateUsing(function ($record) {
                        return $record->tripTicket->vehicle->Fuel ?? '-';
                    })
                    ->searchable(),

                TextColumn::make('PreviousBalance')
                    ->label('Prev. Bal.')
                    ->formatStateUsing(fn($state) => '₱' . number_format($state, 2, '.', ','))
                    ->color('success'),

                TextColumn::make('Amount')
                    ->formatStateUsing(fn($state) => '₱' . number_format($state, 2, '.', ',')) // Formatting the amount
                    ->color('warning')
                    ->searchable(),

                TextColumn::make('RemainingBalance')
                    ->label('Rem. Bal.')
                    ->formatStateUsing(fn($state) => '₱' . number_format($state, 2, '.', ','))
                    ->color('danger'),

                TextColumn::make('ReferenceNumber')
                    ->label('Ref. No.')
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
                    ->color('info'),
                Tables\Actions\Action::make('Print')
                    ->icon('heroicon-m-printer')
                    ->iconButton()
                    ->requiresConfirmation('Are you sure you want to print this fuel slip form?')
                    ->url(fn($record) => route('fuel_slips.print', $record->id))
                    ->openUrlInNewTab()
                    ->action(function ($record) {
                        return '<script>window.open("' . route('fuel_slips.print', $record->id) . '", "_blank");</script>';

                    }),
            ])
            ->headerActions([
                Action::make('print')
                    ->label('Print Report')
                    ->icon('heroicon-m-printer')
                    ->color('info')
                    ->button()
                    ->form([
                        Select::make('year')
                            ->options([
                                '2024' => '2024',
                                '2023' => '2023',
                                // Add more years as needed
                            ])
                            ->label('Year')
                            ->required(),
                        Select::make('month')
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
                            ->label('Month')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        // Use redirect instead of script
                        $url = route('fuel_consumptions.print_monthly', [
                            'year' => $data['year'],
                            'month' => $data['month'],
                        ]);

                        // Return the redirect response
                        return redirect()->away($url);
                    })
                    ->requiresConfirmation('Are you sure you want to print the monthly report?'),
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
            'index' => Pages\ListFuelConsumptions::route('/'),
            'create' => Pages\CreateFuelConsumption::route('/create'),
            'edit' => Pages\EditFuelConsumption::route('/{record}/edit'),
        ];
    }

    protected function rules(): array
    {
        return [
            'RequestDate' => ['required', 'date', 'before_or_equal:today'],
        ];
    }

    protected function messages(): array
    {
        return [
            'RequestDate.before_or_equal' => 'The Arrival Date cannot be a future or past date.',
        ];
    }
}
