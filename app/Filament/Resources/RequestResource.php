<?php

namespace App\Filament\Resources;

use App\Models\Borrower;
use App\Models\Inventory;

use App\Filament\Resources\RequestResource\Pages;
use App\Models\Request;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class RequestResource extends Resource
{
    protected static ?string $model = Request::class;

    protected static ?string $navigationIcon = 'heroicon-o-cloud-arrow-up';

    protected static ?int $navigationSort = 13;

    protected static ?string $navigationParentItem = 'Inventories';

    protected static ?string $navigationGroup = 'Inventory';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Borrower Information')
                        ->icon('heroicon-o-magnifying-glass-plus')
                        ->schema([

                            TextInput::make('GTNumber')
                                ->label('Gate Pass Number')
                                ->default(function () {
                                    $year = now()->year;
                                    $month = str_pad(now()->month, 2, '0', STR_PAD_LEFT);
                                    $lastId = \App\Models\Request::max('id') + 1;

                                    // Format the GTNumber as 'GT-YYYY-MM-001'
                                    return 'GP-' . $year . '-' . $month . '-' . str_pad($lastId, 3, '0', STR_PAD_LEFT);
                                })
                                ->readonly()
                                ->disabled(),

                            Select::make('borrowers_id')
                                ->label('Borrower Name')
                                ->placeholder('Select Borrower')
                                ->options(Borrower::pluck('BorrowerName', 'id')->toArray()),

                            Grid::make(3)
                                ->schema([

                                    Select::make('inventories_id')
                                        ->label('Item Name')
                                        ->placeholder('Select Item')
                                        ->options(Inventory::all()->pluck('ItemName', 'id'))
                                        ->reactive()
                                        ->afterStateUpdated(fn(callable $set, $state) => $set('Quantity', Inventory::find($state)?->ItemQuantity ?? 0)),

                                    TextInput::make('Quantity')
                                        ->label('Quantity of current item')
                                        ->disabled(),
                                    TextInput::make('NumberOfItems')
                                        ->required()
                                        ->numeric()
                                        ->label('Number of Items to be Requested')
                                        ->reactive()
                                        ->rule(function (callable $get) {
                                            $status = $get('RequestStatus');
                                            if ($status === 'Returned') {
                                                return 'gte:0'; // Ensure a valid number for returned status
                                            }

                                            $inventoryId = $get('inventories_id');
                                            $inventory = Inventory::find($inventoryId);

                                            if ($inventory) {
                                                return 'lte:' . $inventory->ItemQuantity; // Less than or equal to available quantity
                                            }
                                            return 'nullable';
                                        })
                                        ->afterStateUpdated(function (callable $get, callable $set, $state) {
                                            $inventoryId = $get('inventories_id');
                                            $inventory = Inventory::find($inventoryId);

                                            if ($inventory && $state > $inventory->ItemQuantity) {
                                                $set('NumberOfItems', $inventory->ItemQuantity); // Reset to max allowed
                                            }
                                        })
                                        ->default(function (callable $get) {
                                            $inventoryId = $get('inventories_id');
                                            $inventory = Inventory::find($inventoryId);
                                            return $inventory ? $inventory->ItemQuantity : null;
                                        })

                                ]),

                            Grid::make(2)
                                ->schema([
                                    DatePicker::make('RequestDate')
                                        ->label('Request Date')
                                        ->minDate(Carbon::today('Asia/Manila'))
                                        ->maxDate(Carbon::today('Asia/Manila')),

                                    DatePicker::make('ReturnDate')
                                        ->minDate(Carbon::today('Asia/Manila'))
                                        ->label('Return Date'),

                                    Textarea::make('Purpose')
                                        ->columnSpanFull()
                                ]),
                            Select::make('RequestStatus')->required()
                                ->placeholder('Request Status')
                                ->visible(fn() => !Auth::user()->is_admin)
                                ->options([
                                    'Approved' => 'Approved',
                                    'Returned' => 'Returned',
                                ])->native(false)
                                ->helperText('If the item was returnable and it was returned, update the status to "Returned."'),

                        ])
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
                TextColumn::make('GTNumber')
                    ->label('Gate Pass #')
                    ->searchable(),
                TextColumn::make('borrower.BorrowerName')
                    ->label('Borrower Name')
                    ->searchable(),
                TextColumn::make('inventory.ItemName')
                    ->label('Item Name')
                    ->searchable(),
                TextColumn::make('NumberOfItems')
                    ->label('Quantity')
                    ->searchable(),
                TextColumn::make('RequestDate')
                    ->label('Request Date')
                    ->date()
                    ->searchable(),
                TextColumn::make('ReturnDate')
                    ->label('Return Date')
                    ->date()
                    ->searchable(),
                TextColumn::make('RequestStatus')
                    ->label('Request Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Approved' => 'success',
                        'Returned' => 'info',
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
                Tables\Filters\SelectFilter::make('RequestStatus')
                    ->label('Request Status')
                    ->options([
                        'Approved' => 'Approved',
                        'Returned' => 'Returned',
                    ])
                    ->placeholder('Select Request Status'),
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
                    ->visible(fn($record) => in_array($record->RequestStatus, ['Approved', 'Returned']))
                    ->requiresConfirmation('Are you sure you want to print this repair request form?')
                    ->url(fn($record) => route('borrower_requests.print', $record->id))
                    ->openUrlInNewTab()
                    ->action(function ($record) {
                        return '<script>window.open("' . route('borrower_requests.print', $record->id) . '", "_blank");</script>';

                    }),

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
            'index' => Pages\ListRequests::route('/'),
            'create' => Pages\CreateRequest::route('/create'),
            'edit' => Pages\EditRequest::route('/{record}/edit'),
        ];
    }

    protected function rules(): array
    {
        return [
            'RequestDate' => ['required', 'date', 'before_or_equal:today'],
            'ReturnDate' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    protected function messages(): array
    {
        return [
            'RequestDate.before_or_equal' => 'The Request Date must be a todays date.',
            'ReturnDate.before_or_equal' => 'The Return Date cannot be a past date.',
        ];
    }
}
