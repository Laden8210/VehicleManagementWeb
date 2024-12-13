<?php

namespace App\Filament\Resources;

use App\Models\Personnel;

use App\Filament\Resources\PersonnelResource\Pages;
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
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\FiltersLayout;

class PersonnelResource extends Resource
{
    protected static ?string $model = Personnel::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Employees';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttribute(): array
    {
        return [];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Wizard::make([
                    Step::make('Employee Information')
                        ->icon('heroicon-o-user')
                        ->schema([

                            Section::make('Employee Complete Information')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('Name')
                                                ->required()
                                                ->label('Employee Name')
                                                ->helperText('First Name, Middle Initial, Last Name')
                                                ->columns(1),

                                            Select::make('Suffix')
                                                ->placeholder('Select Suffix')
                                                ->options([
                                                    'Not Applicable' => 'Not Applicable',
                                                    'Jr.' => 'Jr.',
                                                    'Sr.' => 'Sr.',
                                                    'III' => 'III',
                                                    'IV' => 'IV',
                                                    'V' => 'V',
                                                    'VI' => 'VI',
                                                    'VII' => 'VII',
                                                    'VIII' => 'VIII',
                                                ])
                                                ->native(false)
                                                ->helperText('Leave blank if not applicable.')
                                                ->columns(1), // Keep the Suffix field to span 1 column
                                        ]),

                                    Grid::make(4)
                                        ->schema([
                                            DatePicker::make('DateOfBirth')
                                                ->required() // Mark the field as required
                                                ->label('Date Of Birth') // Add a user-friendly label
                                                ->reactive() // Listen for changes
                                                ->afterStateUpdated(
                                                    fn($state, callable $set) =>
                                                    $set('Age', Carbon::parse($state)->age) // Automatically set Age
                                                )
                                                ->maxDate(Carbon::today('Asia/Manila')) // Prevent selecting dates beyond today
                                                ->columns(4),
                                            TextInput::make('Age')
                                                ->maxLength(2)
                                                ->label('Age')
                                                ->disabled()
                                                ->reactive()
                                                ->columns(4),

                                            Select::make('Gender')->required()
                                                ->placeholder('Select Gender')
                                                ->options([
                                                    'Male' => 'Male',
                                                    'Female' => 'Female',
                                                    ' LGBTQIA+' => ' LGBTQIA+',
                                                ])->native(false)
                                                ->columns(1),

                                            Select::make('CivilStatus')->required()
                                                ->label('Civil Status')
                                                ->placeholder('Select Civil Status')
                                                ->options([
                                                    'Single' => 'Single',
                                                    'Married' => 'Married',
                                                    'Separated' => 'Separated',
                                                    'Widowed' => 'Widowed',
                                                ])->native(false)
                                                ->columns(1),
                                        ]),
                                    Grid::make(2)
                                    ->schema([
                                    TextInput::make('MobileNumber')->required()
                                        ->label('Mobile Number')
                                        ->maxLength(11) // Limits the input to a maximum of 11 characters
                                        ->minLength(11)
                                        ->numeric()
                                        ->extraAttributes(['maxlength' => 11])
                                        ->helperText('09xxxxxxxxx format only')
                                        ->reactive()
                                        ->columns(1),
                                    TextInput::make('EmailAddress')->email()->required()
                                        ->label('Email Address')
                                        ->columns(1),
                                         ]),

                                    Textarea::make('Address')->required()
                                        ->label('Complete Address')
                                        ->helperText('Block #, Lot #, Street #, Purok (Sitio), Brgy., City/Municipality, Province, Zip Code'),
                                       
                                ])->columns(2), //Employee Info Schema
                        ]),

                    Step::make('Employment Information')
                        ->icon('heroicon-o-calendar-days')
                        ->schema([
                            Section::make('Employee Employment Information')
                                ->schema([
                                    TextInput::make('EmployeeID')
                                        ->required()
                                        ->label('Employee ID'),
                                    Select::make('Status')
                                        ->required()
                                        ->placeholder('Select Employment Status')
                                        ->options([
                                            'Permanent' => 'Permanent',
                                            'Casual' => 'Casual',
                                            'Job Order' => 'Job Order',
                                        ])
                                        ->native(false),

                                    Select::make('Designation')
                                        ->required()
                                        ->searchable()
                                        ->placeholder('Select Position')
                                        ->options(function (callable $get) {
                                            $status = $get('Status'); // Get the selected 'Status'
                                
                                            switch ($status) {
                                                case 'Permanent':
                                                    return [
                                                        'LDRRMO' => 'LDRRMO',
                                                        'LDRRMO III' => 'LDRRMO III',
                                                        'LDRRMO II' => 'LDRRMO II',
                                                        'Administrative Officer IV (Administrative Officer II)' => 'Administrative Officer IV (Administrative Officer II)',
                                                        'Administrative Assistant V (Data Controller III)' => 'Administrative Assistant V (Data Controller III)',
                                                        'Administrative Assistant III (Storekeeper)' => 'Administrative Assistant III (Storekeeper)',
                                                        'Administrative Assistant II (Administrative Assistant)' => 'Administrative Assistant II (Administrative Assistant)',
                                                        'LDRRM Assistant' => 'LDRRM Assistant',
                                                        'Administrative Aide VI (Clerk III)' => 'Administrative Aide VI (Clerk III)',
                                                        'Administrative Aide VI (Data Controller I)' => 'Administrative Aide VI (Data Controller I)',
                                                        'Administrative Aide IV (Clerk II)' => 'Administrative Aide IV (Clerk II)',
                                                        'Administrative Aide IV (Communications Equipment Operator I)' => 'Administrative Aide IV (Communications Equipment Operator I)',
                                                        'Administrative Aide III (Driver I)' => 'Administrative Aide III (Driver I)',
                                                        'Administrative Aide I (Utility Worker I)' => 'Administrative Aide I (Utility Worker I)',
                                                    ];

                                                case 'Casual':
                                                    return [
                                                        'Administrative Aide VI (Clerk III)' => 'Administrative Aide VI (Clerk III)',
                                                        'Administrative Aide VI (Data Controller I)' => 'Administrative Aide VI (Data Controller I)',
                                                        'Administrative Aide IV (Clerk II)' => 'Administrative Aide IV (Clerk II)',
                                                        'Administrative Aide IV (Communications Equipment Operator I)' => 'Administrative Aide IV (Communications Equipment Operator I)',
                                                        'Administrative Aide III (Driver I)' => 'Administrative Aide III (Driver I)',
                                                        'Administrative Aide I (Utility Worker I)' => 'Administrative Aide I (Utility Worker I)',
                                                    ];

                                                case 'Job Order':
                                                    return [
                                                        'Research Assistant' => 'Research Assistant',
                                                        'Program Assistant' => 'Program Assistant',
                                                        'Documenter' => 'Documenter',
                                                        'Driver' => 'Driver',
                                                        'Utility Worker' => 'Utility Worker',
                                                    ];

                                                default:
                                                    return []; // Default empty options when no status is selected
                                            }
                                        })
                                        ->native(false)
                                        ->reactive(),

                                    Select::make('Section')
                                        ->required()
                                        ->placeholder('Select Section')
                                        ->options([
                                            'Administrative Support Services' => 'Administrative Support Services',
                                            'Administration and Training Section' => 'Administration and Training Section',
                                            'Planning and Research Section' => 'Planning and Research Section',
                                            'Operations and Warning Section' => 'Operations and Warning Section',
                                            'PDRRMO' => 'PDRRMO',
                                        ])->native(false),
                                ])->columns(2),
                        ]),
                ])->columnSpanFull(),
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
                TextColumn::make('Name')
                    ->searchable(),
                TextColumn::make('EmployeeID')
                    ->label('Employee ID')
                    ->searchable(),
                TextColumn::make('Designation')
                    ->searchable(),
                TextColumn::make('Status')
                    ->searchable(),
                TextColumn::make('Section')
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
                // Filter for Status
                Tables\Filters\SelectFilter::make('Status')
                    ->label('Status')
                    ->options([
                        'Permanent' => 'Permanent',
                        'Job Order' => 'Job Order',
                        'Casual' => 'Casual',
                    ])
                    ->placeholder('Select Status'),

                // Filter for Section
                Tables\Filters\SelectFilter::make('Section')
                    ->label('Section')
                    ->options([
                        'Administrative Support Services' => 'Administrative Support Services',
                        'Administration and Training Section' => 'Administration and Training Section',
                        'Planning and Research Section' => 'Planning and Research Section',
                        'Operations and Warning Section' => 'Operations and Warning Section',
                    ])
                    ->placeholder('Select Section'),
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
            'index' => Pages\ListPersonnels::route('/'),
            'create' => Pages\CreatePersonnel::route('/create'),
            'edit' => Pages\EditPersonnel::route('/{record}/edit'),
        ];
    }

    protected function rules(): array
    {
        return [
            'DateOfBirth' => ['required', 'date', 'before_or_equal:today'],
        ];
    }

    protected function messages(): array
    {
        return [
            'DateOfBirth.before_or_equal' => 'The Date of Birth cannot be a future date.',
        ];
    }
}
