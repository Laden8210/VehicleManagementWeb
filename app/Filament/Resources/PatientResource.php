<?php

namespace App\Filament\Resources;

use App\Models\TripTicket;
use App\Models\Dispatch;

use App\Filament\Resources\PatientResource\Pages;
use App\Models\Patient;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?int $navigationSort = 7;

    protected static ?string $navigationParentItem = 'Dispatches';

    protected static ?string $navigationGroup = 'Vehicle Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Wizard::make([
                    Step::make('Patient Information')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Section::make('Patient Details')
                                ->description('Complete Information of a Patient')
                                ->schema([
                                    Select::make('dispatches_id')
                                        ->label('Requestor Name')
                                        ->placeholder('Select Requestor')
                                        ->options(Dispatch::where('RequestStatus', '!=', 'Pending')->pluck('RequestorName', 'id')),

                                    TextInput::make('PatientName')->required()
                                        ->label('Patient Name'),
                                    Grid::make(3)
                                        ->schema([
                                            Select::make('Gender')->required()
                                                ->placeholder('Select Gender')
                                                ->options([
                                                    'Male' => 'Male',
                                                    'Female' => 'Female',
                                                    ' LGBTQIA+' => ' LGBTQIA+',
                                                ])->native(false),
                                            TextInput::make('Age')->required(),
                                            TextInput::make('PatientNumber')->required()
                                                ->label('Mobile Number')
                                                ->maxLength(11) // Limits the input to a maximum of 11 characters
                                                ->minLength(11)
                                                ->numeric()
                                                ->extraAttributes(['maxlength' => 11])
                                                ->reactive(),
                                        ]), //Grid schema
                                    Textarea::make('PatientAddress')
                                        ->required()
                                        ->helperText('Block #, Lot #, Street #, Purok (Sitio), Brgy., City/Municipality, Province, Zip Code')
                                        ->label('Patient Complete Address'),
                                    Textarea::make('PatientDiagnosis')
                                        ->required()
                                        ->label('Patient Diagnosis'),
                                ]), // patient details schema
                        ]),
                    Step::make('Assigned Personnel')
                        ->icon('heroicon-o-user-group')
                        ->schema([
                            Section::make('Trip Ticket Data')
                                ->description('Trip Ticket Number')
                                ->schema([
                                    Select::make('trip_tickets_id')
                                        ->label('Trip Ticket Number')
                                        ->placeholder('Select Trip Ticket')
                                        ->options(TripTicket::all()->pluck('TripTicketNumber', 'id')),
                                ]),
                        ]) //step 2 schema
                ])->columnSpanFull(), //base Wizard
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
                TextColumn::make('dispatch.RequestorName')
                    ->label('Requestor Name')
                    ->searchable(),
                TextColumn::make('PatientName')
                    ->label('Patient Name')
                    ->searchable(),
                TextColumn::make('tripticket.TripTicketNumber')
                    ->label('Trip Ticket Number')
                    ->searchable(),
                TextColumn::make('dispatch.RequestStatus') // New column for Request Status
                    ->label('Request Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Approved' => 'success',
                        'Disapproved' => 'danger',
                        'Cancelled' => 'gray',
                        default => 'default',
                    })
                    ->searchable(), // This makes the status searchable
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
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}
