<?php

namespace App\Filament\Resources\PersonnelRoleResource\Pages;

use App\Filament\Resources\PersonnelRoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Card;

class ListPersonnelRoles extends ListRecords
{
    protected static string $resource = PersonnelRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Personnel Roles')
                ->icon('heroicon-o-folder-plus')
                ->tooltip('Create a new personnel roles'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Personnel Role Information')
                    ->icon('heroicon-o-key')
                    ->iconColor('primary')
                    ->schema([
                        TextEntry::make('personnel.Name')->label('Personnel Name'),
                        TextEntry::make('RoleName')->label('Role Name'),
                    ])->columns(2),

                Section::make([
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('updated_at')->dateTime(),
                ])->columns(2)->grow(false),
            ]);
    }
}
