<?php

namespace App\Filament\Resources;


use App\Models\Reminder;
use App\Models\Vehicle;

use App\Filament\Resources\DocumentsResource\Pages;
use App\Models\Documents;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class DocumentsResource extends Resource
{
    protected static ?string $model = Documents::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationParentItem = 'Reminders';

    protected static ?string $navigationGroup = 'Vehicle Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Document Information')->tabs([
                    Tab::make('Documents Information')
                        ->icon('heroicon-o-clipboard-document-list')
                        ->schema([
                            Section::make('Reminders')
                                ->description('Reminders about expiring documents')
                                ->schema([
                                    Select::make('vehicles_id')
                                        ->label('Vehicle Name')
                                        ->placeholder('Select Vehicle')
                                        ->options(
                                            Vehicle::whereHas('remarks', function ($query) {
                                                $query->where('VehicleRemarks', 'Serviceable');
                                            })->pluck('VehicleName', 'id')
                                        ),

                                    Grid::make(3)
                                        ->schema([
                                            TextInput::make('DocumentNumber')
                                                ->label('Document Number')
                                                ->default(fn() => 'DOC-' . now()->year . '-' . str_pad(now()->month, 2, '0', STR_PAD_LEFT) . '-' . str_pad(\App\Models\Documents::max('id') + 1, 3, '0', STR_PAD_LEFT))
                                                ->disabled(),

                                            Select::make('reminders_id')
                                                ->label('Renewed Documents')
                                                ->placeholder('Select Documents')
                                                ->options(function () {
                                                    // Fetch reminders where ReminderStatus is 'Done' and not already associated with documents
                                                    return Reminder::select('id', 'Remarks')
                                                        ->whereNotIn('id', Documents::pluck('reminders_id'))
                                                        ->where('ReminderStatus', 'Done') // Ensure the status is 'Done'
                                                        ->distinct('Remarks') // Ensure unique remarks
                                                        ->pluck('Remarks', 'id'); // Adjust pluck to get both id and remarks
                                                }),

                                            Select::make('DocumentType')->required()
                                                ->label('Document Type')
                                                ->placeholder('Select Document')
                                                ->options([
                                                    'Vehicle Registration' => 'Vehicle Registration',
                                                    'Smoke Emission Test' => 'Smoke Emission Test',
                                                    'Vehicle Insurance' => 'Vehicle Insurance',
                                                ])->native(false),
                                        ]),

                                    Grid::make(2)
                                        ->schema([
                                            DatePicker::make('IssueDate')
                                                ->maxDate(Carbon::today('Asia/Manila'))
                                                ->label('Issued Date'),

                                            DatePicker::make('ExpirationDate')
                                                ->minDate(Carbon::today('Asia/Manila'))
                                                ->label('Expiration Date'),
                                        ]),

                                ]), //schema before select
                        ]), //schema before section
                ])->columnSpanFull() //tab schema
            ]); //base schema
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
                TextColumn::make('vehicle.VehicleName')
                    ->label('Vehicle Name')
                    ->searchable(),
                TextColumn::make('DocumentNumber')
                    ->label('Document Number')
                    ->searchable(),
                TextColumn::make('reminder.Remarks')
                    ->label('Renewed Documents')
                    ->searchable(),
                TextColumn::make('IssueDate')
                    ->label('Issued Date')
                    ->date()
                    ->searchable(),
                TextColumn::make('ExpirationDate')
                    ->date()
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
                Tables\Filters\SelectFilter::make('DocumentType')
                    ->label('Document Type')
                    ->options([
                        'Vehicle Registration' => 'Vehicle Registration',
                        'Smoke Emission Test' => 'Smoke Emission Test',
                        'Vehicle Insurance' => 'Vehicle Insurance',
                    ])
                    ->multiple() // Use multiple if you want to allow selecting multiple types
                    ->placeholder('Select Document Type'),
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
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocuments::route('/create'),
            'edit' => Pages\EditDocuments::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('vehicle', 'reminder');
    }

    protected function rules(): array
    {
        return [
            'IssueDate' => ['required', 'date', 'after_or_equal:today'],
            'ExpirationDate' => ['required', 'date', 'before_or_equal:today'],
        ];
    }

    protected function messages(): array
    {
        return [
            'IssueDate.before_or_equal' => 'The Issue Date cannot be a future date.',
            'ExpirationDate.before_or_equal' => 'The Expiration Date cannot be a past date.',
        ];
    }



}
