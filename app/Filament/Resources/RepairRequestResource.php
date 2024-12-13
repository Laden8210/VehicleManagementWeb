<?php

namespace App\Filament\Resources;

use App\Models\Personnel;
use App\Models\RepairHistory;
use App\Models\Vehicle;
use App\Models\RepairRequest;
use App\Filament\Resources\RepairRequestResource\Pages;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\ActionGroup;
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
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Filament\Tables\Enums\FiltersLayout;
use Spatie\Permission\Models\Role;



class RepairRequestResource extends Resource
{
    protected static ?string $model = RepairRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench';

    protected static ?int $navigationSort = 9;

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
                    Step::make('Repair Request')
                        ->icon('heroicon-o-wrench-screwdriver')
                        ->schema([
                            Section::make('Repair Request')
                                ->description('Request for repair of vehicles')
                                ->schema([
                                    TextInput::make('RRNumber')
                                        ->label('R-R Number')
                                        ->disabled()
                                        ->default(fn() => 'RR-' . now()->year . '-' . str_pad(now()->month, 2, '0', STR_PAD_LEFT) . '-' . str_pad(\App\Models\RepairRequest::max('id') + 1, 3, '0', STR_PAD_LEFT))
                                        ->readonly()
                                        ->columnSpanFull(),
                                    Grid::make(2)
                                        ->schema([
                                            Select::make('vehicles_id')
                                                ->label('Vehicle Name')
                                                ->placeholder('Select Vehicle')
                                                ->options(Vehicle::all()->pluck('VehicleName', 'id'))
                                                ->afterStateUpdated(function ($state, callable $set) {
                                                    $set('RequestStatus', 'Pending'); // Set RequestStatus to Pending when vehicle is selected
                                                }),

                                            TextInput::make('user_id')
                                                ->label('Driver ID')
                                                ->placeholder('Driver Name')
                                                ->default(auth()->id()) // Automatically fills with the logged-in user's name
                                                ->readonly() // Make it readonly to prevent changes
                                                ->reactive(),
                                        ]),

                                    Grid::make(2)
                                        ->schema([
                                            DatePicker::make('RequestDate')->required()
                                                ->label('Request Date')
                                                ->minDate(Carbon::today('Asia/Manila'))
                                                ->maxDate(Carbon::today('Asia/Manila')),

                                            Select::make('ReportedIssue')->required()
                                                ->placeholder('Reported Issue')
                                                ->options([
                                                    'Engine' => 'Engine',
                                                    'Transmission' => 'Transmission',
                                                    'Suspension' => 'Suspension',
                                                    'Air Conditioner' => 'Air Conditioner',
                                                    'Exhaust' => 'Exhaust',
                                                    'Windshield' => 'Windshield',
                                                    'Electrical Issues' => 'Electrical Issues',
                                                    'Fuel' => 'Fuel',
                                                    'Overheating' => 'Overheating',
                                                    'Steering' => 'Steering',
                                                    'Clutch' => 'Clutch',
                                                    'Other - Specify to Description' => 'Other - Specify to Description',
                                                ])->native(false),
                                        ]),
                                    Repeater::make('Issues')
                                        ->label('Issues')
                                        ->schema([
                                            TextInput::make('IssueDescription')
                                                ->required()->columnSpanFull()
                                                ->label('Issue Description')
                                                ->placeholder('Full description of the reported issue'),
                                        ])
                                        ->minItems(1)
                                        ->maxItems(10),

                                    Grid::make(2)
                                        ->schema([
                                            Select::make('PriorityLevel')->required()
                                                ->label('Priority Level')
                                                ->placeholder('Priority Level')
                                                ->options([
                                                    'High Priority' => 'High Priority',
                                                    'Medium Priority' => 'Medium Priority',
                                                    'Low Priority' => 'Low Priority',
                                                ])->native(false)->columnSpanFull(),
                                        ]),
                                ]),
                        ]),
                ])->columnSpanFull()
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
                ? RepairRequest::query()
                : RepairRequest::where('user_id', $user->id)
            )
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('RRNumber')
                    ->label('Repair Request Number')
                    ->searchable(),
                TextColumn::make('vehicle.VehicleName')
                    ->label('Vehicle')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Requested by')
                    ->searchable(),
                TextColumn::make('ReportedIssue')
                    ->label('Reported Issue')
                    ->searchable(),
                TextColumn::make('PriorityLevel')
                    ->label('Priority Level')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'High Priority' => 'danger',
                        'Medium Priority' => 'warning',
                        'Low Priority' => 'success',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'High Priority' => 'heroicon-o-exclamation-circle',
                        'Medium Priority' => 'heroicon-o-information-circle',
                        'Low Priority' => 'heroicon-o-check-circle',
                    })
                    ->searchable(),
                TextColumn::make('RequestStatus')
                    ->label('Request Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Approved' => 'success',
                        'Disapproved' => 'danger',
                        'Pending' => 'warning', // Handle any other unexpected values
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'Approved' => 'heroicon-o-check',
                        'Disapproved' => 'heroicon-o-x-circle',
                        'Pending' => 'heroicon-o-clock',
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
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Driver Name')
                    ->options(function () {
                        return User::whereHas('roles', function ($query) {
                            $query->where('name', 'Driver'); // Filters users who have the 'Driver' role
                        })->pluck('name', 'id')->toArray(); // Plucks 'name' and 'id' for the options
                    })
                    ->multiple() // Allows multiple selection if needed
                    ->searchable(), // Makes the options searchable,
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

                Tables\Actions\Action::make('print')
                    ->icon('heroicon-m-printer')
                    ->color('primary')
                    ->visible(fn($record) => $record->RequestStatus === 'Approved' && Auth::user()->hasRole('Admin')) // Only visible if approved and user is admin
                    ->requiresConfirmation('Are you sure you want to print this repair request form?') // Confirmation before the action
                    ->url(fn($record) => route('repair_requests.print', $record->id)) // Direct URL without action
                    ->openUrlInNewTab()
                    ->action(function ($record) {
                        // This part will not be executed since we handle the confirmation in the frontend
                        return '<script>window.open("' . route('repair_requests.print', $record->id) . '", "_blank");</script>';
                    }),

                ActionGroup::make([
                    Tables\Actions\Action::make('approve')
                        ->icon('heroicon-m-check-badge')
                        ->label('Approve')
                        ->color('success')
                        ->requiresConfirmation('Are you sure you want to approve this request?')
                        ->action(function ($record) {
                            // First, update the repair request status
                            $record->update([
                                'RequestStatus' => 'Approved',
                                'DisapprovalComments' => null,
                            ]);


                            Notification::make()
                                ->title('Request Approved')
                                ->body('The request for repair has been approved successfully.')
                                ->success()
                                ->send();

                        })
                        ->visible(fn() => Auth::user()->hasRole('Admin')), // Visible only for admin

                    Tables\Actions\Action::make('disapprove')
                        ->icon('heroicon-m-x-circle')
                        ->label('Disapprove')
                        ->color('danger')
                        ->requiresConfirmation('Are you sure you want to disapprove this request?') // Requires confirmation before action
                        ->form([
                            Textarea::make('disapprovalComments')
                                ->label('Disapproval Comments')
                                ->required(), // Make disapproval comments mandatory
                        ])
                        ->action(function ($record, array $data) {
                            $record->update([
                                'RequestStatus' => 'Disapproved',
                                'DisapprovalComments' => $data['disapprovalComments'], // Save the comment
                            ]);

                            Notification::make()
                                ->title('Disapproval Comments Posted')
                                ->body('The disapproval comments have been successfully posted.')
                                ->success() // or ->danger() if you want a danger notification
                                ->send();
                        })
                        ->visible(fn() => Auth::user()->hasRole('Admin')) // Visible only for admin
                ])->icon('heroicon-s-ellipsis-horizontal-circle')
            ]);
    }

    public static function getRelations(): array
    {
        return [ /* Add relations here if needed */];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRepairRequests::route('/'),
            'create' => Pages\CreateRepairRequest::route('/create'),
            'edit' => Pages\EditRepairRequest::route('/{record}/edit'),
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
            'RequestDate.before_or_equal' => 'The Request Date cannot be a future or past date.',
        ];
    }
}
