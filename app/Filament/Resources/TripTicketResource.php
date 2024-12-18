<?php

namespace App\Filament\Resources;

use App\Models\Personnel;
use App\Models\Vehicle;

use App\Filament\Resources\TripTicketResource\Pages;
use App\Models\TripTicket;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Repeater;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Enums\FiltersLayout;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Commands\Show;

class TripTicketResource extends Resource
{
    protected static ?string $model = TripTicket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?int $navigationSort = 7;

    protected static ?string $navigationGroup = 'Vehicle Management';

    protected static ?string $label = 'Trip Ticket';
    protected static ?string $pluralLabel = 'Trip Tickets';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([

                Wizard::make([
                    Step::make('Trip Ticket Information (Arrival)')
                        ->icon('heroicon-o-arrow-right-start-on-rectangle')
                        ->schema([

                            Section::make('Trip Ticket')
                                ->description('Trip Ticket Details')
                                ->schema([

                                    Grid::make(3)
                                        ->schema([
                                            TextInput::make('TripTicketNumber')
                                                ->label('Trip Ticket Number')
                                                ->disabled()
                                                ->default(fn() => 'TT-' . now()->year . '-' . str_pad(now()->month, 2, '0', STR_PAD_LEFT) . '-' . str_pad(TripTicket::max('id') + 1, 3, '0', STR_PAD_LEFT))
                                                ->readonly(),

                                            DatePicker::make('ArrivalDate')->required()
                                                ->minDate(Carbon::today('Asia/Manila'))
                                                ->maxDate(Carbon::today('Asia/Manila'))
                                                ->label('Arrival Date'),

                                            DatePicker::make('ReturnDate')->required()
                                                ->minDate(Carbon::today('Asia/Manila'))
                                                ->label('Return Date'),
                                        ]),

                                    Select::make('vehicles_id')
                                        ->label('Vehicle Name')
                                        ->placeholder('Select Vehicle')
                                        ->options(
                                            Vehicle::whereHas('remarks', function ($query) {
                                                $query->where('VehicleRemarks', 'Serviceable');
                                            })->pluck('VehicleName', 'id')
                                        )
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            $lastTrip = TripTicket::where('vehicles_id', $state)
                                                ->orderByDesc('id')
                                                ->first();
                                            $set('KmBeforeTravel', $lastTrip ? $lastTrip->KmAfterTravel : 0);

                                            $set('BalanceStart', $lastTrip ? $lastTrip->BalanceEnd : 0);
                                        }),

                                    TextInput::make('user_id')
                                        ->label('Driver ID')
                                        ->placeholder('Driver Name')
                                        ->default(auth()->id()) // Automatically fills with the logged-in user's name
                                        ->readonly() // Make it readonly to prevent changes
                                        ->reactive(),

                                    Repeater::make('responders')
                                        ->label('Responder Names')
                                        ->schema([
                                            Select::make('responder_id')
                                                ->label('Responder Name')
                                                ->placeholder('Select Responder')
                                                ->options(
                                                    Personnel::whereHas('roles', function ($query) {
                                                        $query->where('RoleName', 'Responder');
                                                    })->pluck('Name', 'id')
                                                )
                                        ])->columnSpanFull(),

                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('Origin'),
                                            TextInput::make('Destination'),
                                        ]),

                                    TextArea::make('Purpose')->columnSpanFull(),

                                    Grid::make(4)
                                        ->schema([
                                            TextInput::make('KmBeforeTravel')
                                                ->label('Kilometer Before Travel')
                                                ->numeric()
                                                ->reactive()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    $kmAfter = $get('KmAfterTravel') ?? 0;
                                                    $kmBefore = $get('KmBeforeTravel') ?? 0;
                                                    $set('DistanceTravelled', $kmAfter - $kmBefore);
                                                }),

                                            TextInput::make('BalanceStart')
                                                ->label('Balance Start')
                                                ->numeric()
                                                ->reactive()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    $balanceStart = floatval($get('BalanceStart')) ?? 0;
                                                    $issuedFromOffice = floatval($get('IssuedFromOffice')) ?? 0;
                                                    $addedDuringTrip = floatval($get('AddedDuringTrip')) ?? 0;
                                                    $totalFuelTank = round($balanceStart + $issuedFromOffice + $addedDuringTrip, 2);

                                                    $set('TotalFuelTank', $totalFuelTank);
                                                }),


                                            TextInput::make('IssuedFromOffice')
                                                ->label('Issued From Office')
                                                ->numeric()
                                                ->reactive()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    $balanceStart = $get('BalanceStart') ?? 0;
                                                    $addedDuringTrip = $get('AddedDuringTrip') ?? 0;
                                                    $set('TotalFuelTank', $balanceStart + $get('IssuedFromOffice') + $addedDuringTrip);
                                                }),

                                            TimePicker::make('TimeDeparture_A')
                                                ->label('Departure from Office')
                                                ->reactive()
                                                ->displayFormat('h:i A')
                                                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                                                    $arrivalTime = $get('TimeArrival_A');
                                                    if ($arrivalTime && $state > $arrivalTime) {
                                                        $set('TimeArrival_A', null);
                                                    }
                                                }),

                                        ]), //grid schema
                                ])->columns(2), //first section schema
                        ]), //first tab schema

                    Step::make('Trip Ticket Information (Departure)')
                        ->icon('heroicon-o-arrow-left-start-on-rectangle')
                        ->schema([

                            Section::make('Fuel Management')
                                ->description('Fuel consumed during the trip')
                                ->schema([
                                    TextInput::make('KmAfterTravel')
                                        ->label('Kilometer After Travel')
                                        ->numeric()
                                        ->reactive()
                                        ->afterStateUpdated(function (callable $set, callable $get) {
                                            $kmBefore = $get('KmBeforeTravel') ?? 0;
                                            $kmAfter = $get('KmAfterTravel') ?? 0;
                                            $set('DistanceTravelled', $kmAfter - $kmBefore);
                                        }),
                                    TextInput::make('DistanceTravelled')
                                        ->label('Distance Travelled')
                                        ->numeric()
                                        ->reactive()
                                        ->afterStateUpdated(function (callable $set, callable $get) {
                                            $kmBefore = $get('KmBeforeTravel') ?? 0;
                                            $kmAfter = $get('KmAfterTravel') ?? 0;
                                            $set('DistanceTravelled', $kmAfter - $kmBefore);
                                        }),
                                    Grid::make(3)
                                        ->schema([
                                            TimePicker::make('TimeArrival_A')
                                                ->label('Arrival at Destination')
                                                ->reactive()
                                                ->displayFormat('h:i A')
                                                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                                                    $departureTime = $get('TimeDeparture_A');
                                                    if ($departureTime && $state < $departureTime) {
                                                        $set('TimeArrival_A', null);
                                                        Notification::make()
                                                            ->title('Invalid Time')
                                                            ->body('TimeArrival_A cannot be earlier than TimeDeparture_A.')
                                                            ->danger()
                                                            ->send();
                                                    }
                                                }),

                                            TimePicker::make('TimeDeparture_B')
                                                ->label('Departure from Destination')
                                                ->reactive()
                                                ->displayFormat('h:i A')
                                                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                                                    $arrivalTimeA = $get('TimeArrival_A');
                                                    $arrivalTimeB = $get('TimeArrival_B');
                                                    if ($arrivalTimeA && $state < $arrivalTimeA) {
                                                        $set('TimeDeparture_B', null);
                                                        Notification::make()
                                                            ->title('Invalid Time')
                                                            ->body('TimeDeparture_B cannot be earlier than TimeArrival_A.')
                                                            ->danger()
                                                            ->send();
                                                    }
                                                    if ($arrivalTimeB && $state > $arrivalTimeB) {
                                                        $set('TimeArrival_B', null);
                                                        Notification::make()
                                                            ->title('Invalid Time')
                                                            ->body('TimeArrival_B cannot be earlier than TimeDeparture_B.')
                                                            ->danger()
                                                            ->send();
                                                    }
                                                }),

                                            TimePicker::make('TimeArrival_B')
                                                ->label('Arrival at Office')
                                                ->reactive()
                                                ->displayFormat('h:i A')
                                                ->afterStateUpdated(function (callable $set, $state, callable $get) {
                                                    $departureTimeB = $get('TimeDeparture_B');
                                                    if ($departureTimeB && $state < $departureTimeB) {
                                                        $set('TimeArrival_B', null);
                                                        Notification::make()
                                                            ->title('Invalid Time')
                                                            ->body('TimeArrival_B cannot be earlier than TimeDeparture_B.')
                                                            ->danger()
                                                            ->send();
                                                    }
                                                }),
                                        ]),
                                    Grid::make(4)
                                        ->schema(components: [

                                            TextInput::make('AddedDuringTrip')
                                                ->label('Added During Trip')
                                                ->numeric()
                                                ->reactive()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    $balanceStart = $get('BalanceStart') ?? 0;
                                                    $issuedFromOffice = $get('IssuedFromOffice') ?? 0;
                                                    $set('TotalFuelTank', $balanceStart + $issuedFromOffice + $get('AddedDuringTrip'));
                                                }),

                                            TextInput::make('TotalFuelTank')
                                                ->label('Total Fuel Tank')
                                                ->numeric()
                                                ->reactive()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    $balanceStart = $get('BalanceStart') ?? 0;
                                                    $issuedFromOffice = $get('IssuedFromOffice') ?? 0;
                                                    $addedDuringTrip = $get('AddedDuringTrip') ?? 0;
                                                    $set('TotalFuelTank', $balanceStart + $issuedFromOffice + $addedDuringTrip);
                                                }),

                                            TextInput::make('FuelConsumption')
                                                ->label('Fuel Consumed')
                                                ->numeric()
                                                ->disabled()
                                                ->reactive()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    $distanceTravelled = $get('DistanceTravelled') ?? 0;
                                                    $set('FuelConsumption', $distanceTravelled / 10);
                                                }),

                                            TextInput::make('BalanceEnd')
                                                ->label('Balance End')
                                                ->numeric()
                                                ->disabled()
                                                ->reactive()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    $totalFuelTank = $get('TotalFuelTank') ?? 0;
                                                    $fuelConsumption = $get('FuelConsumption') ?? 0;
                                                    $balanceEnd = $totalFuelTank - $fuelConsumption;
                                                    if ($balanceEnd < 0) {
                                                        $balanceEnd = 0;
                                                        Notification::make()
                                                            ->title('Fuel Warning')
                                                            ->body('Fuel must not be lower than zero. Please check the fuel consumption or total fuel tank.')
                                                            ->warning()
                                                            ->send();
                                                    }

                                                    $set('BalanceEnd', $balanceEnd);
                                                }),
                                        ]),
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('Others')
                                                ->placeholder('If necessary'),
                                            TextInput::make('Remarks')
                                                ->placeholder('If necessary'),
                                        ]),
                                ])->columns(2), //first section schema
                        ]), //first tab schema
                ])->columnSpanFull(),
            ]); //Base Schema
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
                ? TripTicket::query()
                : TripTicket::where('user_id', $user->id)
            )
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('TripTicketNumber')
                    ->label('Trip Ticket Number')
                    ->searchable(),
                TextColumn::make('vehicle.VehicleName')
                    ->label('Vehicle')
                    ->searchable(),
                TextColumn::make('vehicle.MvfileNo')
                    ->label('Plate Number')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Driver Name')
                    ->searchable(),
                TextColumn::make('ArrivalDate')
                    ->label('Arrival Date')
                    ->searchable()
                    ->date(),
                TextColumn::make('ReturnDate')
                    ->label('Return Date')
                    ->searchable()
                    ->date(),
                TextColumn::make('Destination')
                    ->searchable(),
                TextColumn::make('KmBeforeTravel')
                    ->label('KmRBT')
                    ->searchable()
                    ->color('success'),
                TextColumn::make('KmAfterTravel')
                    ->label('KmRAT')
                    ->searchable()
                    ->color('danger'),
                TextColumn::make('BalanceStart')
                    ->label('BtSOT')
                    ->color('success'),
                TextColumn::make('BalanceEnd')
                    ->label('BtEOT')
                    ->color('danger')
                    ->formatStateUsing(function ($state) {
                        return number_format($state, 2);  // Format to 2 decimal places
                    }),
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
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Driver Name')
                    ->options(function () {
                        return User::whereHas('roles', function ($query) {
                            $query->where('name', 'Driver'); // Filters users who have the 'Driver' role
                        })->pluck('name', 'id')->toArray(); // Plucks 'name' and 'id' for the options
                    })
                    ->multiple() // Allows multiple selection if needed
                    ->searchable(), // Makes the options searchable,

                Tables\Filters\SelectFilter::make('arrival_month')
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
                            ? $query->whereMonth('ArrivalDate', $data['value'])
                            : $query;
                    }),

                Tables\Filters\SelectFilter::make('arrival_year')
                    ->label('Year')
                    ->options([
                        '2024' => '2024',
                        // Add more years as needed
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return isset($data['value'])
                            ? $query->whereYear('ArrivalDate', $data['value'])
                            : $query;
                    }),

                Tables\Filters\SelectFilter::make('vehicles_id')
                    ->label('Vehicle Name')
                    ->options(function () {
                        return Vehicle::whereHas('remarks', function ($query) {
                            $query->where('VehicleRemarks', 'Serviceable');
                        })->pluck('VehicleName', 'id')->toArray();
                    })
                    ->searchable(),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                ViewAction::make()
                    ->icon('heroicon-m-eye')
                    ->iconButton(),
                EditAction::make()
                    ->icon('heroicon-m-pencil-square')
                    ->iconButton(),

                Tables\Actions\Action::make('print')
                    ->icon('heroicon-m-printer')
                    ->color('primary')
                    ->iconButton()
                    ->requiresConfirmation('Are you sure you want to print this repair request form?')
                    ->url(fn($record) => route('trip_tickets.print', $record->id))
                    ->openUrlInNewTab()
                    ->action(function ($record) {
                        return '<script>window.open("' . route('trip_tickets.print', $record->id) . '", "_blank");</script>';
                    }),
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListTripTickets::route('/'),
            'create' => Pages\CreateTripTicket::route('/create'),
            'view' => Pages\ViewTripTicket::route('/{record}'),
            'edit' => Pages\EditTripTicket::route('/{record}/edit'),
        ];
    }

    protected function rules(): array
    {
        return [
            'ArrivalDate' => ['required', 'date', 'before_or_equal:today'],
            'ReturnDate' => ['required', 'date', 'before_or_equal:today'],
        ];
    }

    protected function messages(): array
    {
        return [
            'ArrivalDate.before_or_equal' => 'The Arrival Date cannot be a future date.',
            'ReturnDate.before_or_equal' => 'The Return Date cannot be a past date.',
        ];
    }
}
