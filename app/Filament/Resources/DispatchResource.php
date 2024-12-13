<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DispatchResource\Pages;
use App\Models\Dispatch;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class DispatchResource extends Resource
{
    protected static ?string $model = Dispatch::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';

    protected static ?int $navigationSort = 7;

    protected static ?string $navigationGroup = 'Vehicle Management';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Dispatch Details')->tabs([
                    Tab::make('Dispatch Details')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Section::make('Dispatch Details')
                                ->description('For Patient Transport Services')
                                ->schema([
                                    DatePicker::make('RequestDate')
                                        ->label('Request Date')
                                        ->minDate(Carbon::today('Asia/Manila'))
                                        ->maxDate(Carbon::today('Asia/Manila'))
                                        ->required(),

                                    TextInput::make('RequestorName')
                                        ->label('Requestor Name')
                                        ->required(),

                                    DatePicker::make('TravelDate')
                                        ->label('Travel Date')
                                        ->minDate(Carbon::today('Asia/Manila'))
                                        ->required(),

                                    TimePicker::make('PickupTime')
                                        ->label('Pick-up Time')
                                        ->required(),

                                    Textarea::make('Destination')->columnSpanFull()
                                        ->helperText('Destination Format - From(Brgy-City/Municipality) - To(Name of Hospital or Specific Address)')
                                        ->required(),

                                    Select::make('RequestStatus')->required()
                                        ->placeholder('Request Status')
                                        ->options([
                                            'Pending' => 'Pending',
                                            'Approved' => 'Approved',
                                            'Disapproved' => 'Disapproved',
                                            'Cancelled' => 'Cancelled',
                                        ])->native(false),

                                    TextInput::make('Remarks')
                                        ->helperText('Refer to your Division Head')
                                        ->required(),
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
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('RequestDate')
                    ->label('Request Date')
                    ->date()
                    ->searchable(),
                TextColumn::make('RequestorName')
                    ->label('Requestor Name')
                    ->searchable(),
                TextColumn::make('TravelDate')
                    ->label('Travel Date')
                    ->date()
                    ->searchable(),
                TextColumn::make('RequestStatus')
                    ->label('Request Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Approved' => 'success',
                        'Disapproved' => 'danger',
                        'Cancelled' => 'gray',
                        default => 'default',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'Pending' => 'heroicon-o-clock',
                        'Approved' => 'heroicon-o-check-circle',
                        'Disapproved' => 'heroicon-o-x-circle',
                        'Cancelled' => 'heroicon-o-ban',
                        default => 'heroicon-o-question-circle',
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
                        'Pending' => 'Pending',
                        'Approved' => 'Approved',
                        'Disapproved' => 'Disapproved',
                        'Cancelled' => 'Cancelled',
                    ])
                    ->placeholder('Select Status'),

                Tables\Filters\SelectFilter::make('travel_date')
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
                            ? $query->whereMonth('TravelDate', $data['value'])
                            : $query;
                    }),

                Tables\Filters\SelectFilter::make('arrival_year')
                    ->label('Year')
                    ->options([
                        '2024' => '2024',
                        '2025' => '2025',
                        '2026' => '2026',
                        '2027' => '2027',
                        '2028' => '2028',
                        '2029' => '2029',
                        '2030' => '2030',
                        // Add more years as needed
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return isset($data['value'])
                            ? $query->whereYear('TravelDate', $data['value'])
                            : $query;
                    }),

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
            'index' => Pages\ListDispatches::route('/'),
            'create' => Pages\CreateDispatch::route('/create'),
            'edit' => Pages\EditDispatch::route('/{record}/edit'),
        ];
    }

    protected function rules(): array
    {
        return [
            'RequestDate' => ['required', 'date', 'before_or_equal:today'],
            'TravelDate' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    protected function messages(): array
    {
        return [
            'RequestDate.before_or_equal' => 'The Request Date cannot be a future date.',
            'TravelDate.after_or_equal' => 'The Travel Date cannot be a past date.',
        ];
    }


}
