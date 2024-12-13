<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BorrowerResource\Pages;
use App\Models\Borrower;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;

class BorrowerResource extends Resource
{
    protected static ?string $model = Borrower::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 13;

    protected static ?string $navigationParentItem = 'Inventories';

    protected static ?string $navigationGroup = 'Inventory';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Borrower Information')
                        ->icon('heroicon-o-information-circle')
                        ->schema([

                            Section::make('Borrower Information')
                                ->description('Borrower Complete Information')
                                ->schema([
                                    TextInput::make('BorrowerName')
                                        ->required()
                                        ->columns(2)
                                        ->label('Borrower Name'),

                                    TextInput::make('BorrowerAddress')
                                        ->required()
                                        ->columns(2)
                                        ->label('Borrower Address'),

                                    TextInput::make('BorrowerNumber')
                                        ->required()
                                        ->label('Borrower Mobile Number')
                                        ->maxLength(11) // Limits the input to a maximum of 11 characters
                                        ->minLength(11)
                                        ->numeric()
                                        ->extraAttributes(['maxlength' => 11])
                                        ->reactive(),

                                    TextInput::make('BorrowerEmail')
                                        ->label('Borrower Email')
                                        ->email()
                                        ->required()
                                        ->maxLength(255),
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
                TextColumn::make('BorrowerName')
                    ->label('Borrower Name')
                    ->searchable(),
                TextColumn::make('BorrowerAddress')
                    ->label('Borrower Address')
                    ->searchable(),
                TextColumn::make('BorrowerNumber')
                    ->label('Borrower Number')
                    ->searchable(),
                TextColumn::make('BorrowerEmail')
                    ->label('Borrower Email')
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
            'index' => Pages\ListBorrowers::route('/'),
            'create' => Pages\CreateBorrower::route('/create'),
            'edit' => Pages\EditBorrower::route('/{record}/edit'),
        ];
    }
}
