<?php

namespace App\Filament\Resources\PersonnelResource\Pages;

use App\Filament\Resources\PersonnelResource;
use App\Filament\Resources\PersonnelResource\Widgets\StatsOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;

class ListPersonnels extends ListRecords
{
    protected static string $resource = PersonnelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Personnel')
                ->icon('heroicon-o-folder-plus')
                ->tooltip('Create a new personnels')
                ->createAnother(false),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class
        ];
    }
    protected function getFooterWidgets(): array
    {
        return [];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Employee Information')
                    ->icon('heroicon-o-information-circle')
                    ->iconColor('primary')
                    ->schema([
                        TextEntry::make('Name')->label('Personnel Name'),
                        TextEntry::make('Suffix'),
                        TextEntry::make('DateOfBirth')->label('Date Of Birth')->date(),
                        TextEntry::make('Age'),
                        TextEntry::make('Gender'),
                        TextEntry::make('CivilStatus')->label('Civil Status'),
                        TextEntry::make('MobileNumber')->label('Mobile Number'),
                        TextEntry::make('EmailAddress')->label('Email Address'),
                        TextEntry::make('Address'),
                    ])->columns(3),

                Section::make('Employment Information')
                    ->icon('heroicon-o-calendar-days')
                    ->iconColor('primary')
                    ->schema([
                        TextEntry::make('EmployeeID')->label('Employee ID'),
                        TextEntry::make('Designation'),
                        TextEntry::make('Status'),
                        TextEntry::make('Section'),
                    ])->columns(3),

                Section::make([
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('updated_at')->dateTime(),
                ])->columns(2)->grow(false),
            ]);
    }

}
