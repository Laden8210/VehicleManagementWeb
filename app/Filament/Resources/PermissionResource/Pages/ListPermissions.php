<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Card;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Permission')
            ->icon('heroicon-o-folder-plus') 
            ->tooltip('Create a new permission'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
            Section::make('Permission Information')
            ->icon('heroicon-o-information-circle')
            ->iconColor('primary') 
                ->schema([
                    TextEntry::make('name')->label('Permission'),
                    ])->columns(3),

            Section::make([
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('updated_at')->dateTime(),
                    ])->columns(2)->grow(false),
            ]);
    }
}
