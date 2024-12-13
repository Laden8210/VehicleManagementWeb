<?php

namespace App\Filament\Resources;

use App\Models\Personnel;
use App\Models\PersonnelRole;
use App\Filament\Resources\PersonnelRoleResource\Pages;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;

class PersonnelRoleResource extends Resource
{
    protected static ?string $model = PersonnelRole::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationParentItem = 'Personnels';
    protected static ?string $navigationGroup = 'Employees';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Personnel Roles')->tabs([
                Tab::make('Personnel Roles')
                    ->icon('heroicon-o-key')
                    ->schema([
                        Section::make('Personnel')
                            ->description('Select Personnel')
                            ->schema([
                                Select::make('personnels_id')
                                    ->label('Personnel Name')
                                    ->placeholder('Select Employee')
                                    ->options(
                                        // Only include personnel who do not have an existing role
                                        Personnel::whereDoesntHave('roles')->pluck('Name', 'id')
                                    )
                                    ->disabled(fn($get) => !is_null($get('record.id'))) // Disable if editing an existing role
                                    ->afterStateUpdated(function ($set, $state) {
                                        // Check if the selected personnel already has a role
                                        $role = PersonnelRole::where('personnels_id', $state)->first();
                                        if ($role) {
                                            // Notify that this personnel cannot have more than one role
                                            Notification::make()
                                                ->title('Warning')
                                                ->body('This personnel cannot have 2 roles assigned.')
                                                ->danger()
                                                ->send();

                                            // Set the RoleName to the existing role
                                            $set('RoleName', $role->RoleName);
                                        } else {
                                            // Clear the role if no role is assigned
                                            $set('RoleName', null);
                                        }
                                    }),
                            ]),
                        Section::make('Personnel Role')
                            ->description('Role of a Personnel in the PDRRMO')
                            ->schema([
                                Select::make('RoleName')
                                    ->required()
                                    ->placeholder('Select Role')
                                    ->options([
                                        'Driver' => 'Driver',
                                        'Responder' => 'Responder',
                                    ])
                                    ->disabled(
                                        fn($get) =>
                                        !is_null($get('personnels_id')) &&
                                        PersonnelRole::where('personnels_id', $get('personnels_id'))->exists() // Disable if role is already assigned
                                    )
                                    ->native(false),
                            ]),
                    ]),
            ])->columnSpanFull(),
        ]); // base schema
    }
    public static function getTableQuery()
    {
        return parent::getTableQuery()->orderBy('created_at', 'desc');
    }
    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')
                ->label('ID')
                ->searchable()
                ->sortable(),
            TextColumn::make('personnel.Name')
                ->label('Personnel Name')
                ->searchable(),
            TextColumn::make('personnel.Designation')
                ->label('Designation')
                ->searchable(),
            TextColumn::make('RoleName')
                ->label('Role Name')
                ->searchable(),
            TextColumn::make('created_at')
                ->label('Added On')
                ->date()
                ->searchable()
                ->toggleable(),
            TextColumn::make('updated_at')
                ->label('Last Updated On')
                ->date()
                ->searchable()
                ->toggleable(),
        ])->defaultSort('created_at', 'desc')
            ->filters([
                // Filter for RoleName
                Tables\Filters\SelectFilter::make('RoleName')
                    ->label('Role')
                    ->options([
                        'Driver' => 'Driver',
                        'Responder' => 'Responder',
                    ])
                    ->placeholder('Select Role'),

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
                // Define any bulk actions here
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Define any relations here
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPersonnelRoles::route('/'),
            'create' => Pages\CreatePersonnelRole::route('/create'),
            'edit' => Pages\EditPersonnelRole::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('personnel');
    }
}
