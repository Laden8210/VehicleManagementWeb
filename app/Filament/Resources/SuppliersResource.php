<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuppliersResource\Pages;
use App\Models\Suppliers;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class SuppliersResource extends Resource
{
    protected static ?string $model = Suppliers::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?int $navigationSort = 13;

    protected static ?string $navigationGroup = 'Expenses and Suppliers';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Supplier Information')->tabs([
                    Tab::make('Supplier Information')
                        ->icon('heroicon-o-information-circle')
                        ->schema([

                            Section::make('Supplier Information')
                                ->description('Supplier Complete Information')
                                ->schema([
                                    TextInput::make('SupplierName')->required()
                                        ->label('Supplier Name')
                                        ->columns(2),
                                    TextInput::make('ContactPerson')->required()
                                        ->label('Contact Person')
                                        ->columns(2),
                                    TextInput::make('Designation')->required()->columns(2),
                                    TextInput::make('MobileNumber')->required()
                                        ->label('Mobile Number')
                                        ->maxLength(11) // Limits the input to a maximum of 11 characters
                                        ->minLength(11)
                                        ->numeric()
                                        ->extraAttributes(['maxlength' => 11])
                                        ->reactive()
                                        ->columns(2),
                                    TextArea::make('CompleteAddress')
                                        ->required()
                                        ->helperText('Block #, Lot #, Street #, Purok (Sitio), Brgy., City/Municipality, Province, Zip Code')
                                        ->columnSpanFull()
                                        ->label('Complete Address'),
                                    TextInput::make('EmailAddress')->required()
                                        ->label('Email Address'),

                                    Select::make('YearEstablished')
                                        ->placeholder('Select Established Year')
                                        ->label('Year Established')
                                        ->options(
                                            collect(range(date('Y'), date('Y') - 100)) // Generates an array of years from the current year to 100 years ago
                                                ->mapWithKeys(fn($year) => [$year => $year])
                                                ->toArray()
                                        )->required()->native(false),

                                    Select::make('PhilgepsMembership')
                                        ->label('PhilGEPS Membership')
                                        ->placeholder('Select PhilGEPS Membership')
                                        ->options([
                                            'Red' => 'Red',
                                            'Platinum' => 'Platinum',
                                        ])->native(false)
                                        ->columnSpanFull(),
                                ])->columns(2), //Employee Info Schema
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
                TextColumn::make('SupplierName')
                    ->label('Supplier Name')
                    ->searchable(),
                TextColumn::make('ContactPerson')
                    ->label('Contact Person')
                    ->searchable(),
                TextColumn::make('CompleteAddress')
                    ->label('Complete Address')
                    ->searchable(),
                TextColumn::make('PhilgepsMembership')
                    ->label('PhilGEPS Membership')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Red' => 'danger',
                        'Platinum' => 'gray',
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
                Tables\Filters\SelectFilter::make('PhilgepsMembership')
                    ->label('PhilGEPS Membership')
                    ->options([
                        'Red' => 'Red',
                        'Platinum' => 'Platinum'
                    ])
                    ->multiple()
                    ->placeholder('Select PhilGEPS Membership'),
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSuppliers::route('/create'),
            'edit' => Pages\EditSuppliers::route('/{record}/edit'),
        ];
    }
}
