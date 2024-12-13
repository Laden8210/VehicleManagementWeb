<?php

namespace App\Filament\Resources;

use App\Models\Personnel;
use App\Models\Vehicle;

use App\Filament\Resources\MaintenanceRecommendationResource\Pages;
use App\Models\MaintenanceRecommendation;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Form;
use App\Models\User;
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
use Filament\Notifications\Notification;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Enums\FiltersLayout;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class MaintenanceRecommendationResource extends Resource
{
    protected static ?string $model = MaintenanceRecommendation::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationGroup = 'Vehicle Management';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Tabs::make('Maintenance Recommendations')->tabs([
                    Tab::make('Maintenance Recommendations')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Section::make('Maintenance Recommendations')
                                ->description('For Change Oil, Tire, Brake Pads, Fluid, Battery, Air filter, or Scheduled Inspection.')
                                ->schema([

                                    TextInput::make('MRNumber')
                                        ->label('Maintenance Recommendation Number')
                                        ->disabled()
                                        ->default(fn() => 'MR-' . now()->year . '-' . str_pad(now()->month, 2, '0', STR_PAD_LEFT) . '-' . str_pad(\App\Models\MaintenanceRecommendation::max('id') + 1, 3, '0', STR_PAD_LEFT))
                                        ->readonly() // Use readonly instead of disabled
                                        ->columnSpanFull(), // Disable manual input since it's auto-generated

                                    Grid::make(2)
                                        ->schema([
                                            Select::make('vehicles_id')
                                                ->label('Vehicle Name')
                                                ->placeholder('Select Vehicle')
                                                ->options(Vehicle::all()->pluck('VehicleName', 'id')),

                                            TextInput::make('user_id')
                                                ->label('Driver ID')
                                                ->placeholder('Driver Name')
                                                ->default(auth()->id()) // Automatically fills with the logged-in user's name
                                                ->readonly() // Make it readonly to prevent changes
                                                ->reactive(),
                                        ]), //grid 2 schema 

                                    Select::make('RecommendationType')->required()->columnSpanFull()
                                        ->label('Recommendation Type')
                                        ->placeholder('Select Recommendation Type')
                                        ->options([
                                            'Oil' => 'Oil',
                                            'Tire' => 'Tire',
                                            'Brake' => 'Brake',
                                            'Battery' => 'Battery',
                                            'Fluid' => 'Fluid',
                                            'Air Filter' => 'Air Filter',
                                            'Lighting' => 'Lighting',
                                            'Scheduled Inspections' => 'Scheduled Inspections',
                                            'Cleaning and Detailing' => 'Cleaning and Detailing',
                                        ])->native(false),

                                    Repeater::make('Issues')
                                        ->label('Issues')
                                        ->schema([
                                            TextInput::make('IssueDescription')
                                                ->required()->columnSpanFull()
                                                ->label('Issue Description')
                                                ->placeholder('Full description of the reported issue'),
                                        ])
                                        ->minItems(1)
                                        ->maxItems(10)
                                        ->columnSpanFull(),

                                    Grid::make(3)
                                        ->schema([
                                            DatePicker::make('RecommendationDate')
                                                ->label('Recommendation Date')
                                                ->required()
                                                ->minDate(Carbon::today('Asia/Manila'))
                                                ->maxDate(Carbon::today('Asia/Manila')),

                                            DatePicker::make('DueDate')
                                                ->label('Due Date')
                                                ->minDate(Carbon::today('Asia/Manila'))
                                                ->required(),

                                            Select::make('PriorityLevel')->required()
                                                ->label('Priority Level')
                                                ->options([
                                                    'High Priority' => 'High Priority',
                                                    'Medium Priority' => 'Medium Priority',
                                                    'Low Priority' => 'Low Priority',
                                                ])->native(false),
                                        ]),

                                ])->columns(2), //dispatch details schema
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
        $user = Auth::user();
        return $table
            ->query(
                fn() => $user->hasRole('Admin')
                ? MaintenanceRecommendation::query()
                : MaintenanceRecommendation::where('user_id', $user->id)
            )
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('MRNumber')
                    ->label('M-R Number')
                    ->searchable(),
                TextColumn::make('vehicle.VehicleName')
                    ->label('Vehicle Name')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Requested by')
                    ->searchable(),
                TextColumn::make('RecommendationType')
                    ->label('Rec. Type')
                    ->searchable(),
                TextColumn::make('RecommendationDate')
                    ->label('Rec. Date')
                    ->date()
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
                        'Approved' => 'heroicon-o-check-circle',
                        'Disapproved' => 'heroicon-o-x-circle',
                        'Pending' => 'heroicon-o-clock',
                    })
                    ->searchable(),
                TextColumn::make('DisapprovalComments')
                    ->label('Disapproval Comments')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Added On')
                    ->date()
                    ->toggleable(),
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
                    ->url(fn($record) => route('maintenance_recommendations.print', $record->id)) // Direct URL without action
                    ->openUrlInNewTab()
                    ->action(function ($record) {
                        // This part will not be executed since we handle the confirmation in the frontend
                        return '<script>window.open("' . route('maintenance_recommendations.print', $record->id) . '", "_blank");</script>';
                    }),

                ActionGroup::make([
                    Tables\Actions\Action::make('approve')
                        ->icon('heroicon-m-check-badge')
                        ->label('Approve')
                        ->color('success')
                        ->requiresConfirmation('Are you sure you want to approve this request?')
                        ->action(function ($record) {
                            $record->update([
                                'RequestStatus' => 'Approved',
                                'DisapprovalComments' => null, // Clear disapproval comments on approval
                            ]);

                            Notification::make()
                                ->title('Request Approved')
                                ->body('The maintenance recommendation has been approved successfully.')
                                ->success() // or ->danger() if you want a danger notification
                                ->send();
                        })
                        ->visible(fn() => Auth::user()->hasRole('Admin')), // Visible only for admin

                    Tables\Actions\Action::make('disapprove')
                        ->icon('heroicon-m-x-circle')
                        ->label('Disapprove')
                        ->color('danger')
                        ->requiresConfirmation() // Requires confirmation before action
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaintenanceRecommendations::route('/'),
            'create' => Pages\CreateMaintenanceRecommendation::route('/create'),
            'edit' => Pages\EditMaintenanceRecommendation::route('/{record}/edit'),
        ];
    }
    protected function rules(): array
    {
        return [
            'RecommendationDate' => ['required', 'date', 'before_or_equal:today'],
            'DueDate' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    protected function messages(): array
    {
        return [
            'RecommendationDate.before_or_equal' => 'The Recommendation Date cannot be a future or past date.',
            'DueDate.before_or_equal' => 'The Due Date cannot be a past date.',
        ];
    }
}
